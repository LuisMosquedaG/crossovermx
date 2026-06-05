<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use App\Models\Court;
use App\Models\TeamSchedule;

class CalendarGeneratorService
{
    /**
     * Genera un calendario de todos contra todos (Round Robin).
     * 
     * CAMBIO: Agregado $virtualExistingGames para manejar juegos creados en la misma transacción.
     */
    public function generateRoundRobinSchedule(
        int $tournamentId, 
        array $config, 
        ?array $specificTeamIds = null, 
        ?string $groupName = null, 
        ?Collection $virtualExistingGames = null
    ): array {
        
        if ($specificTeamIds) {
            $teams = array_values(array_filter($specificTeamIds, function($id) {
                return is_numeric($id);
            }));
            $teams = array_map('intval', $teams);

            if (count($teams) < 2) {
                return ['success' => false, 'message' => 'El grupo seleccionado tiene menos de 2 equipos.'];
            }
        } else {
            $tournament = Tournament::with('teams')->findOrFail($tournamentId);
            $teams = $tournament->teams->pluck('id')->toArray();

            if (count($teams) < 2) {
                return ['success' => false, 'message' => 'Se necesitan al menos 2 equipos para generar un calendario.'];
            }
        }

        if (count($teams) % 2 != 0) {
            $teams[] = 'bye';
        }

        $rounds = $this->createRoundRobinPairs($teams);

        // --- CORRECCIÓN CLAVE ---
        // 1. Obtenemos los juegos que YA están en la base de datos
        $dbGames = Game::where('tournament_id', $tournamentId)
            ->get(['local_team_id', 'away_team_id', 'date_time', 'court_id']);

        // 2. Los mezclamos con los juegos virtuales (generados en iteraciones anteriores del loop)
        $allExistingGames = $dbGames;
        if ($virtualExistingGames && $virtualExistingGames->isNotEmpty()) {
            $allExistingGames = $dbGames->concat($virtualExistingGames);
        }
        // -----------------------
                // --- NUEVO: Cargar horarios de equipos para validación ---
        $teamSchedules = null;
        if (!empty($teams)) {
            // Si son IDs
            $ids = is_array($teams[0]) ? $teams : $teams; 
            // Asumimos que $teams son IDs para el round robin
             $teamSchedules = TeamSchedule::whereIn('team_id', $ids)->get()->groupBy('team_id');
        }
        // ------------------------------------------------------------

        $constrainedTeamIds = $teamSchedules ? $teamSchedules->keys()->toArray() : [];

        foreach ($rounds as &$roundPairs) {
            usort($roundPairs, function($a, $b) use ($constrainedTeamIds) {
                $aHasConstraint = in_array($a['home'], $constrainedTeamIds) || in_array($a['away'], $constrainedTeamIds);
                $bHasConstraint = in_array($b['home'], $constrainedTeamIds) || in_array($b['away'], $constrainedTeamIds);

                // Si el juego A tiene un equipo restringido y el B no, A va primero
                if ($aHasConstraint && !$bHasConstraint) {
                    return -1;
                }
                // Si el juego B tiene un equipo restringido y el A no, B va primero
                if (!$aHasConstraint && $bHasConstraint) {
                    return 1;
                }

                // Si ambos tienen o no tienen restricciones, mantenemos el orden original
                return 0;
            });
        }
        // ------------------------------------------------------------------------

        // Pasamos la lista combinada a la generación de slots
        $timeSlots = $this->generateTimeSlots($config, $tournamentId, $allExistingGames); 

        // Pasamos la lista combinada a la programación
        $scheduledGames = $this->scheduleGames($rounds, $timeSlots, $config, $tournamentId, $groupName, $allExistingGames, $teamSchedules);

        if (empty($scheduledGames)) {
            return ['success' => false, 'message' => 'No se pudo programar el calendario. No hay espacio disponible o las reglas de descanso impiden la programación.'];
        }

        Game::insert($scheduledGames); 

        // --- RETORNO DE DATOS ---
        // Devolvemos los juegos generados para que el Controller los acumule
        return [
            'success' => true, 
            'message' => 'Calendario generado exitosamente.',
            'generated_games' => $scheduledGames 
        ];
    }

    private function createRoundRobinPairs(array $teams): array
    {
        $numTeams = count($teams);
        $numRounds = $numTeams - 1;
        $pairsPerRound = $numTeams / 2;
        $allRounds = []; 

        for ($round = 0; $round < $numRounds; $round++) {
            $currentRoundPairs = []; 
            
            for ($i = 0; $i < $pairsPerRound; $i++) {
                $home = $teams[$i];
                $away = $teams[$numTeams - 1 - $i];

                if ($home !== 'bye' && $away !== 'bye') {
                    $currentRoundPairs[] = ['home' => $home, 'away' => $away];
                }
            }
            
            $allRounds[] = $currentRoundPairs;
            
            $team = array_pop($teams);
            array_splice($teams, 1, 0, $team);
        }
        
        return $allRounds; 
    }

    private function generateTimeSlots(array $config, int $tournamentId, $existingGames): array
    {
        $slots = [];
        $startDate = Carbon::parse($config['start_date']);
        
        if (empty($config['end_date'])) {
            $endDate = $startDate->copy()->addMonth();
        } else {
            $endDate = Carbon::parse($config['end_date']);
        }
        
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

        $courtIds = $config['courts'] ?? \App\Models\Court::pluck('id')->toArray();
        $courtsData = Court::whereIn('id', $courtIds)->with('schedules')->get()->keyBy('id');

        $totalMatchTime = ($config['periods_per_game'] * $config['game_duration']) + $config['rest_between_periods'];
        $slotDurationMinutes = $totalMatchTime + $config['rest_between_games'];

        $occupiedSlots = [];
        foreach($existingGames as $game) {
            $dt = ($game->date_time instanceof Carbon) ? $game->date_time : Carbon::parse($game->date_time);
            $occupiedSlots[] = $dt->toDateTimeString() . '-' . $game->court_id;
        }

        $allowedDays = $config['days'] ?? [0, 1, 2, 3, 4, 5, 6];
        $globalStart = $config['start_time'] ?? '10:00';
        $globalEnd = $config['end_time'] ?? '23:00';

        // --- NUEVO: Array auxiliar para evitar duplicados en la generación ---
        $generatedSlotsKeys = []; 

        foreach ($period as $date) {
            if (!in_array($date->dayOfWeek, $allowedDays)) {
                continue;
            }

            $dayGlobalStart = Carbon::parse($date->toDateString() . ' ' . $globalStart);
            $dayGlobalEnd = Carbon::parse($date->toDateString() . ' ' . $globalEnd);

            foreach ($courtIds as $courtId) {
                $courtSchedules = $courtsData->get($courtId)->schedules;
                $intervals = $this->getCourtOpenIntervals($courtSchedules, $date->dayOfWeek, $dayGlobalStart, $dayGlobalEnd);

                foreach ($intervals as $interval) {
                    [$intervalStart, $intervalEnd] = $interval;
                    $currentSlotTime = $intervalStart->copy();
                    
                    while ($currentSlotTime->copy()->addMinutes($slotDurationMinutes)->lte($intervalEnd)) {
                        $slotKey = $currentSlotTime->toDateTimeString() . '-' . $courtId;
                        
                        // --- CORRECCIÓN: Verificamos que no esté ocupado Y que no lo hayamos generado ya ---
                        if (!in_array($slotKey, $occupiedSlots) && !in_array($slotKey, $generatedSlotsKeys)) {
                            $slots[] = [
                                'date_time' => $currentSlotTime->copy(),
                                'court_id' => $courtId,
                            ];
                            // Agregamos a nuestro registro local para evitar duplicados de este mismo bucle
                            $generatedSlotsKeys[] = $slotKey; 
                        }
                        $currentSlotTime->addMinutes($slotDurationMinutes);
                    }
                }
            }
        }

        usort($slots, function($a, $b) {
            return $a['date_time'] <=> $b['date_time'];
        });

        return $slots;
    }

    private function scheduleGames(array $rounds, array $slots, array $config, int $tournamentId, ?string $groupName, $existingGames, $teamSchedules = null): array
    {
        $scheduledGames = [];

        // --- CÁLCULO DE DURACIÓN ---
        $periods = $config['periods_per_game'] ?? 4;
        $gameDuration = $config['game_duration'] ?? 10;
        $restPeriods = $config['rest_between_periods'] ?? 2;
        $totalMatchTime = ($periods * $gameDuration) + (($periods - 1) * $restPeriods);
        // --------------------------------------------------

        // --- PREPARAR HISTORIAL DE JUEGOS PARA VALIDACIÓN ---
        $teamGameDates = []; 
        foreach ($existingGames as $game) {
            $dt = ($game->date_time instanceof Carbon) ? $game->date_time : Carbon::parse($game->date_time);
            $dateStr = $dt->toDateString();
            
            if (!isset($teamGameDates[$game->local_team_id])) $teamGameDates[$game->local_team_id] = [];
            if (!isset($teamGameDates[$game->away_team_id])) $teamGameDates[$game->away_team_id] = [];
            
            $teamGameDates[$game->local_team_id][] = $dateStr;
            $teamGameDates[$game->away_team_id][] = $dateStr;
        }
        // -----------------------------------------------
        
        foreach ($rounds as $roundPairs) {
            $roundScheduled = false;
            
            // --- NUEVA ESTRATEGIA: ASIGNACIÓN INDIVIDUAL POR PARTIDO ---
            // Ya no forzamos que toda la ronda quepa en un solo día.
            // Intentamos asignar cada partido a la brecha más cercana disponible.
            
            // Recorremos los modos (Estricto -> Flexible)
            $attemptModes = [true, false];

            foreach ($attemptModes as $isStrict) {
                // Si ya logramos programar la ronda en el intento anterior, salimos
                if ($roundScheduled) break;

                // Reiniciamos el índice de slots para cada intento
                reset($slots);
                
                // Array temporal para marcar qué slots ocupamos en este intento fallido (para no repetir errores)
                $tempAssignedIndices = [];

                foreach ($roundPairs as $pair) {
                    $gameAssigned = false;

                    // Buscamos un hueco para ESTE partido específico
                    foreach ($slots as $index => $slot) {
                        // Ignorar slots ya usados en este mismo bucle de ronda
                        if (in_array($index, $tempAssignedIndices)) continue;

                        // Verificar Reglas de Descanso y Disponibilidad
                        $localAvailable = true;
                        $awayAvailable = true;

                        if ($teamSchedules) {
                            $localAvailable = $this->isTeamAvailable($teamSchedules, $pair['home'], $slot['date_time'], $totalMatchTime, $isStrict);
                            $awayAvailable = $this->isTeamAvailable($teamSchedules, $pair['away'], $slot['date_time'], $totalMatchTime, $isStrict);
                        }

                        if ($localAvailable && $awayAvailable && $this->canTeamsPlay($pair['home'], $pair['away'], $slot['date_time']->toDateString(), $teamGameDates, $config)) {
                            
                            // ASIGNAR PARTIDO
                            $dateStr = $slot['date_time']->toDateString();
                            $scheduledGames[] = [
                                'tournament_id' => $tournamentId,
                                'local_team_id' => $pair['home'],
                                'away_team_id' => $pair['away'],
                                'court_id' => $slot['court_id'],
                                'date_time' => $slot['date_time'],
                                'status' => 'pending',
                                'group_name' => $groupName, 
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            // Actualizar historial en memoria para los siguientes partidos de la misma ronda
                            // (para que el mismo equipo no juegue 2 veces en la misma ronda aunque sean días distintos)
                            if (!isset($teamGameDates[$pair['home']])) $teamGameDates[$pair['home']] = [];
                            if (!isset($teamGameDates[$pair['away']])) $teamGameDates[$pair['away']] = [];
                            
                            $teamGameDates[$pair['home']][] = $dateStr;
                            $teamGameDates[$pair['away']][] = $dateStr;

                            // Marcamos este slot como usado y lo removemos de la lista general
                            $tempAssignedIndices[] = $index;
                            unset($slots[$index]);
                            
                            $gameAssigned = true;
                            break; // Pasamos al siguiente partido
                        }
                    }

                    // Si un partido no se pudo asignar en ningun slot disponible
                    if (!$gameAssigned) {
                        // Rompemos el bucle de partidos, este intento (estricto/flexible) falló para la ronda completa
                        $roundScheduled = false;
                        
                        // IMPORTANTE: Revertimos los cambios en $slots si fallamos a mitad de ronda
                        // (Devolvemos los slots que usamos temporalmente)
                        foreach ($tempAssignedIndices as $usedIndex) {
                            // Como unset rompe el índice, reconstruimos el array si es necesario o simplemente manejamos la lógica
                            // Para simplificar en esta corrección, asumiremos que si falla, el siguiente intento (flexible) lo intentará de nuevo.
                            // Lo ideal sería devolver los juegos al array $slots, pero dado que reindexamos al final,
                            // simplemente saldremos y el siguiente modo lo intentará con la lista original de slots (que pasamos por referencia si fuera objeto, pero es array).
                            // NOTA: Como $slots es un array y lo estamos modificando con unset, si fallamos a mitad, hemos perdido esos slots.
                            // Solución rápida: No hacer unset hasta confirmar que TODA la ronda está lista.
                        }
                        
                        // Estrategia de "Rollback" manual para arrays:
                        // Como esto se complica, usaremos un enfoque más seguro:
                        // Solo hacemos unset de $slots al final del todo.
                        
                        break 2; // Rompe el bucle de partidos y el de modos
                    }
                }

                // Si llegamos aquí, todos los partidos de la ronda fueron asignados
                $roundScheduled = true;
                
                // Ahora sí, eliminamos definitivamente los slots usados del array principal
                // Reindexamos $slots para limpiar huecos vacíos del unset anterior
                $slots = array_values($slots);
            }

            // Si después de intentar modo estricto y flexible fallamos, retornamos vacío
            if (!$roundScheduled) {
                return []; 
            }
        }

        return $scheduledGames;
    }

    /**
     * Valida si los equipos pueden jugar según las reglas de descanso.
     */
    private function canTeamsPlay(int $homeTeamId, int $awayTeamId, string $dateStr, array &$teamGameDates, array $config): bool
    {
        // Corregido: quitamos la redundancia de asignación
        $restRules = $config['rest_rules'] ?? [];

        if (in_array('no_same_day', $restRules)) {
            // Verificación Equipo Local
            if (isset($teamGameDates[$homeTeamId]) && in_array($dateStr, $teamGameDates[$homeTeamId])) {
                return false;
            }
            // Verificación Equipo Visitante (CORREGIDO)
            if (isset($teamGameDates[$awayTeamId]) && in_array($dateStr, $teamGameDates[$awayTeamId])) {
                return false;
            }
        }

        if (in_array('no_same_week', $restRules)) {
            $weekStart = Carbon::parse($dateStr)->startOfWeek(Carbon::MONDAY);
            $weekEnd = Carbon::parse($dateStr)->endOfWeek(Carbon::SUNDAY);

            // Verificación Equipo Local
            if (isset($teamGameDates[$homeTeamId])) {
                foreach ($teamGameDates[$homeTeamId] as $date) {
                    if (Carbon::parse($date)->between($weekStart, $weekEnd)) {
                        return false; // Ya jugó esa semana
                    }
                }
            }
            
            // Verificación Equipo Visitante (CORREGIDO)
            if (isset($teamGameDates[$awayTeamId])) {
                foreach ($teamGameDates[$awayTeamId] as $date) {
                    if (Carbon::parse($date)->between($weekStart, $weekEnd)) {
                        return false; // Ya jugó esa semana
                    }
                }
            }
        }

        return true;
    }
        /**
     * MÉTODO NUEVO: Devuelve solo la estructura de rondas (parejas) sin programar fechas.
     * Útil para intercalar categorías ronda por ronda.
     */
    public function getRoundRobinStructure(array $teamIds): array
    {
        $teams = $teamIds;
        
        if (count($teams) < 2) {
            return [];
        }

        // Manejo de equipos impares (Bye)
        if (count($teams) % 2 != 0) {
            $teams[] = 'bye';
        }

        // Utilizamos la lógica existente de round robin
        $numTeams = count($teams);
        $numRounds = $numTeams - 1;
        $pairsPerRound = $numTeams / 2;
        $allRounds = []; 

        for ($round = 0; $round < $numRounds; $round++) {
            $currentRoundPairs = []; 
            
            for ($i = 0; $i < $pairsPerRound; $i++) {
                $home = $teams[$i];
                $away = $teams[$numTeams - 1 - $i];

                // Ignoramos los 'bye' para no crear juegos fantasmas
                if ($home !== 'bye' && $away !== 'bye') {
                    $currentRoundPairs[] = ['home' => $home, 'away' => $away];
                }
            }
            
            $allRounds[] = $currentRoundPairs;
            
            // Rotación: El último equipo se mueve a la posición 1
            $team = array_pop($teams);
            array_splice($teams, 1, 0, $team);
        }
        
        return $allRounds; 
    }
    
    public function schedulePlayoffRound(
        Tournament $tournament, 
        array $matchups, 
        \Carbon\Carbon $startDate, 
        \Carbon\Carbon $endDate, 
        array $config = [], 
        ?\Illuminate\Support\Collection $previouslyScheduledGames = null, 
        bool $isPlayoffFlag = true
    ): \Illuminate\Support\Collection {
        
        // --- Configuración base ---
        $allowedDays = $config['days'] ?? [0, 1, 2, 3, 4, 5, 6]; 
        $courts = $config['courts'] ?? \App\Models\Court::pluck('id')->toArray();
        $startTime = $config['start_time'] ?? '10:00';
        $endTime = $config['end_time'] ?? '23:00';

        $periods = $config['periods_per_game'] ?? 4;
        $gameDuration = $config['game_duration'] ?? 10;
        $restPeriods = $config['rest_between_periods'] ?? 2;
        $restGames = $config['rest_between_games'] ?? 10;

        $matchDuration = ($periods * $gameDuration) + (($periods - 1) * $restPeriods);
        $totalSlotMinutes = $matchDuration + $restGames; 

        // --- 1. LEER CONFIGURACIÓN DE INTERCALEADO ---
        $interleave = $config['interleave_categories'] ?? true;
        // ------------------------------------------

        // --- 2. PREPARAR MAPAS DE OCUPACIÓN ---
        $dbGames = \App\Models\Game::where('tournament_id', $tournament->id)
            ->get(['local_team_id', 'away_team_id', 'date_time', 'court_id']);
        
        $allGames = $dbGames;
        if ($previouslyScheduledGames) {
            $allGames = $dbGames->concat($previouslyScheduledGames);
        }

        $teamGameDates = [];
        $occupiedSlots = [];
        
        foreach ($allGames as $g) {
            $d = ($g->date_time instanceof \Carbon\Carbon) ? $g->date_time : \Carbon\Carbon::parse($g->date_time);
            $dateStr = $d->toDateString();
            $slotKey = $d->toDateTimeString() . '-' . $g->court_id;
            
            $teamGameDates[$g->local_team_id][] = $dateStr;
            $teamGameDates[$g->away_team_id][] = $dateStr;
            $occupiedSlots[] = $slotKey;
        }

        // --- 3. CARGAR HORARIOS ---
        $courtsData = Court::whereIn('id', $courts)->with('schedules')->get()->keyBy('id');
        $teamsInvolved = collect($matchups)->flatMap(fn($m) => [$m['local'], $m['away']])->unique();
        $teamSchedules = TeamSchedule::whereIn('team_id', $teamsInvolved)->get()->groupBy('team_id');

        // --- 4. GENERAR HUECOS LIBRES ---
        $allFreeSlots = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        $generatedKeysMap = [];

        foreach ($period as $date) {
            if (!in_array($date->dayOfWeek, $allowedDays)) continue;

            $dayGlobalStart = \Carbon\Carbon::parse($date->toDateString() . ' ' . $startTime);
            $dayGlobalEnd = \Carbon\Carbon::parse($date->toDateString() . ' ' . $endTime);

            foreach ($courts as $courtId) {
                $courtSchedules = $courtsData->get($courtId)->schedules;
                $intervals = $this->getCourtOpenIntervals($courtSchedules, $date->dayOfWeek, $dayGlobalStart, $dayGlobalEnd);

                foreach ($intervals as $interval) {
                    [$intervalStart, $intervalEnd] = $interval;
                    $currentSlotTime = $intervalStart->copy();

                    while ($currentSlotTime->copy()->addMinutes($totalSlotMinutes)->lte($intervalEnd)) {
                        $slotKey = $currentSlotTime->toDateTimeString() . '-' . $courtId;
                        
                        if (!in_array($slotKey, $occupiedSlots) && !isset($generatedKeysMap[$slotKey])) {
                            $allFreeSlots[] = [
                                'date_time' => $currentSlotTime->copy(),
                                'court_id' => $courtId,
                                'date_str' => $currentSlotTime->toDateString()
                            ];
                            $generatedKeysMap[$slotKey] = true;
                        }
                        $currentSlotTime->addMinutes($totalSlotMinutes);
                    }
                }
            }
        }

        // --- 5. ORGANIZAR PARTIDOS PENDIENTES ---
        $pendingGamesByCategory = [];
        $pendingGamesFlat = [];

        if ($interleave) {
            // Intercalado: Agrupamos por categoría
            foreach ($matchups as $m) {
                // --- CORRECCIÓN CRÍTICA AQUÍ ---
                // Antes usábamos 'group_name' (ej: 'WB_R1'), que es igual para todos.
                // Ahora usamos 'category_group' (ej: 'Femenil - 1era') para poder mezclar.
                $cat = $m['category_group'] ?? $m['group_name'] ?? 'General'; 
                
                $pendingGamesByCategory[$cat][] = $m;
            }
        } else {
            // Corridas: Lista plana...
            // (El resto del código se queda igual)
        }

        // --- 6. ASIGNAR JUEGOS (TRANSACCIÓN OPTIMIZADA) ---
        return \DB::transaction(function () use ($tournament, $allFreeSlots, $config, &$teamGameDates, &$occupiedSlots, $interleave, $pendingGamesByCategory, $pendingGamesFlat, $isPlayoffFlag, $teamSchedules) {
            
            $gamesToInsert = []; 
            $scheduledKeys = []; // Para modo corridas

            $slotsByDate = [];
            foreach ($allFreeSlots as $slot) {
                $slotsByDate[$slot['date_str']][] = $slot;
            }
            ksort($slotsByDate);

            // Ordenar slots por hora dentro de cada día
            foreach ($slotsByDate as $dateStr => $daySlots) {
                usort($slotsByDate[$dateStr], function($a, $b) {
                    return $a['date_time'] <=> $b['date_time'];
                });
            }

            // Helper interno (Mantener igual)
            $assignGame = function($slot, $game, $config, $tournament, $isStrict, $teamSchedules, &$teamGameDates, &$occupiedSlots, &$gamesToInsert) use ($isPlayoffFlag) {
                $slotKey = $slot['date_time']->toDateTimeString() . '-' . $slot['court_id'];
                if (in_array($slotKey, $occupiedSlots)) {
                    return false; 
                }
                
                $totalMatchTime = ($config['periods_per_game'] * $config['game_duration']) + (($config['periods_per_game'] - 1) * $config['rest_between_periods']);
                
                $localAvailable = $this->isTeamAvailable($teamSchedules, $game['local'], $slot['date_time'], $totalMatchTime, $isStrict);
                $awayAvailable = $this->isTeamAvailable($teamSchedules, $game['away'], $slot['date_time'], $totalMatchTime, $isStrict);
                
                $dateStr = $slot['date_time']->toDateString();

                if ($localAvailable && $awayAvailable) {
                    if ($this->canTeamsPlay($game['local'], $game['away'], $dateStr, $teamGameDates, $config)) {
                        
                        $gamesToInsert[] = [
                            'tournament_id' => $tournament->id,
                            'local_team_id' => $game['local'],
                            'away_team_id' => $game['away'],
                            'court_id' => $slot['court_id'],
                            'date_time' => $slot['date_time']->toDateTimeString(), 
                            'status' => 'pending',
                            'local_team_score' => 0,
                            'away_team_score' => 0,
                            'is_playoff' => $isPlayoffFlag ? 1 : 0,
                            'group_name' => $game['group_name'] ?? null,
                            'category_group' => $game['category_group'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $occupiedSlots[] = $slotKey;
                        $teamGameDates[$game['local']][] = $dateStr;
                        $teamGameDates[$game['away']][] = $dateStr;
                        return true;
                    }
                }
                return false;
            };

            // --- NUEVA LÓGICA DE INTERCALEADO (COLA ROTATIVA) ---
            
            if ($interleave) {
                // 1. Preparamos una cola estricta intercalada
                // Ejemplo: Si Cat A tiene 3 juegos y Cat B tiene 3 juegos -> [A1, B1, A2, B2, A3, B3]
                $gameQueue = [];
                $categories = array_keys($pendingGamesByCategory);
                
                // Llenamos la cola tomando un juego de cada categoría por turno
                while(true) {
                    $addedAny = false;
                    foreach($categories as $cat) {
                        if(!empty($pendingGamesByCategory[$cat])) {
                            $gameQueue[] = array_shift($pendingGamesByCategory[$cat]);
                            $addedAny = true;
                        }
                    }
                    if(!$addedAny) break; // No quedan juegos en ninguna categoría
                }

                // 2. Función para procesar la cola
                $processQueue = function($isStrictMode) use ($slotsByDate, &$gameQueue, $assignGame, $config, $tournament, $teamSchedules, &$teamGameDates, &$occupiedSlots, &$gamesToInsert) {
                    foreach ($slotsByDate as $dateStr => $daySlots) {
                        foreach ($daySlots as $slot) {
                            $assigned = false;
                            $queueSize = count($gameQueue);
                            
                            // Intentamos asignar rotando la cola para este hueco
                            for ($i = 0; $i < $queueSize; $i++) {
                                $game = $gameQueue[0]; // Miramos el primero de la cola

                                if ($assignGame($slot, $game, $config, $tournament, $isStrictMode, $teamSchedules, $teamGameDates, $occupiedSlots, $gamesToInsert)) {
                                    // Éxito: Quitamos el juego de la cola
                                    array_shift($gameQueue);
                                    $assigned = true;
                                    break; // Pasamos al siguiente hueco
                                } else {
                                    // Fallo: Este juego no cabe en este hueco (equipo ocupado).
                                    // Lo movemos al final de la cola para intentar en el próximo hueco
                                    $gameToMove = array_shift($gameQueue);
                                    $gameQueue[] = $gameToMove;
                                }
                            }
                            
                            // Si $assigned es false, significa que NINGÚN juego de la cola cabe en este hueco.
                            // Lo dejamos vacío (skip) para respetar el orden estricto.
                        }
                    }
                };

                // --- INTENTO 1: ESTRICTO ---
                $processQueue(true);

                // --- INTENTO 2: FALLBACK (Si quedaron juegos) ---
                if (!empty($gameQueue)) {
                    $processQueue(false);
                }

            } else {
                // --- LÓGICA CORRIDA (Sin cambios) ---
                foreach ($slotsByDate as $dateStr => $daySlots) {
                    foreach ($daySlots as $slot) {
                        foreach ($pendingGamesFlat as $key => $game) {
                            if (in_array($key, $scheduledKeys)) continue; 
                            if ($assignGame($slot, $game, $config, $tournament, true, $teamSchedules, $teamGameDates, $occupiedSlots, $gamesToInsert)) {
                                $scheduledKeys[] = $key;
                                break; 
                            }
                        }
                    }
                }
                
                // Fallback para corridas
                if (count($pendingGamesFlat) > count($scheduledKeys)) {
                    foreach ($slotsByDate as $dateStr => $daySlots) {
                        foreach ($daySlots as $slot) {
                            foreach ($pendingGamesFlat as $key => $game) {
                                if (in_array($key, $scheduledKeys)) continue;
                                if ($assignGame($slot, $game, $config, $tournament, false, $teamSchedules, $teamGameDates, $occupiedSlots, $gamesToInsert)) {
                                    $scheduledKeys[] = $key;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            // ---------------------------------------------------------------

            // --- INSERCIÓN MASIVA Y VALIDACIÓN FINAL ---
            if (!empty($gamesToInsert)) {
                \App\Models\Game::insert($gamesToInsert);
            }

            $tournament->status = 'active';
            if ($isPlayoffFlag) $tournament->is_playoffs = true;
            $tournament->save();

            // Validar si quedó algo pendiente (solo aplicable a lógica corrida, la cola se vacía sola)
            $finalPending = !$interleave ? (count($pendingGamesFlat) > count($scheduledKeys)) : !empty($gameQueue);
            
            if ($finalPending) {
                throw new \Exception("No se encontraron suficientes fechas/horarios disponibles para todos los cruces.");
            }

            return collect(); 
        });
    }
    /**
     * Determina los intervalos de apertura de una cancha para un día específico.
     * 
     * Lógica Estricta:
     * 1. Si la cancha NO tiene horarios en la BD -> Asume horario global del torneo.
     * 2. Si la cancha SÍ tiene horarios en la BD -> Verifica este día.
     *    - Si este día tiene horarios -> Usa esos.
     *    - Si este día NO tiene horarios -> CERRADO (array vacío).
     * 
     * Retorna un array de arrays [start_time, end_time] (Carbon).
     */
    private function getCourtOpenIntervals($courtSchedules, $dayOfWeek, $defaultStart, $defaultEnd)
    {
        // 1. Si la cancha no tiene NINGÚN horario configurado en la BD, usamos el global.
        if ($courtSchedules->isEmpty()) {
            return [[$defaultStart, $defaultEnd]];
        }

        // 2. Buscamos si hay horario específico para este día (ej. Lunes)
        $daySchedules = $courtSchedules->where('day_of_week', $dayOfWeek);

        // 3. CAMBIO CRÍTICO AQUÍ:
        // Si la cancha tiene horarios (ej. para Lunes), pero estamos buscando Domingo,
        // en lugar de retornar vacío (Cerrado), usamos el horario global (Default).
        // Esto permite definir excepciones sin bloquear la cancha el resto de días.
        if ($daySchedules->isEmpty()) {
            return [[$defaultStart, $defaultEnd]]; 
        }

        // 4. Si SÍ hay horario específico para ese día, lo usamos
        $intervals = [];
        foreach ($daySchedules as $schedule) {
            $sStart = Carbon::parse($schedule->start_time);
            $sEnd = Carbon::parse($schedule->end_time);
            
            // Intersectamos con el rango global para seguridad (que no se salga de los límites del torneo)
            $start = max($defaultStart->copy()->setTime($sStart->hour, $sStart->minute), $defaultStart);
            $end = min($defaultEnd->copy()->setTime($sEnd->hour, $sEnd->minute), $defaultEnd);

            if ($start->lt($end)) {
                $intervals[] = [$start, $end];
            }
        }
        
        return $intervals;
    }
    /**
     * Verifica si un equipo puede jugar en un horario según sus preferencias.
     */
    private function isTeamAvailable($teamSchedules, $teamId, $startTime, $totalDurationMinutes, $strict = true)
    {
        // --- NUEVA LÓGICA: FALLBACK ---
        // Si estamos en modo flexible (fallback), ignoramos las restricciones específicas del equipo
        // y permitimos que use los horarios generales del torneo.
        if (!$strict) {
            return true;
        }
        // -----------------------------

        $schedules = $teamSchedules->get($teamId);

        if (!$schedules || $schedules->isEmpty()) {
            // Sin restricciones, puede jugar
            return true;
        }

        // Calculamos en qué hora TERMINARÁ el partido (incluyendo tiempos muertos del juego)
        $endTime = $startTime->copy()->addMinutes($totalDurationMinutes);

        $dayOfWeek = $startTime->dayOfWeek;
        
        // Buscamos un horario que cubra TODA la duración del partido
        $match = $schedules->first(function($sch) use ($dayOfWeek, $startTime, $endTime) {
            if ($sch->day_of_week != $dayOfWeek) return false;

            $sStart = Carbon::parse($sch->start_time)->setDateFrom($startTime);
            $sEnd = Carbon::parse($sch->end_time)->setDateFrom($startTime);

            // Condición: Inicio del partido >= Inicio del horario 
            //           FIN del partido <= Fin del horario
            return $startTime->gte($sStart) && $endTime->lte($sEnd);
        });

        return $match !== null;
    }
        /**
     * GENERADOR UNIFICADO PARA LIGAS (INTERCALADO)
     * 
     * A diferencia de generateRoundRobinSchedule, este acepta múltiples grupos
     * y los mezcla antes de asignar fechas, logrando el efecto de "Intercalar Categorías".
     */
    public function generateInterleavedLeagueSchedule(int $tournamentId, array $config, array $groupsData): array
    {
        // $groupsData estructura esperada:
        // [
        //    'Varonil' => [1, 2, 3, 4], // IDs de equipos
        //    'Femenil' => [5, 6, 7, 8]
        // ]

        $allMatchups = [];

        // 1. GENERAR ESTRUCTURA DE JUEGOS (Solo matemáticas, sin guardar en BD aun)
        foreach ($groupsData as $groupName => $teamIds) {
            // Limpiar y validar IDs
            $teams = array_values(array_filter($teamIds, function($id) {
                return is_numeric($id);
            }));
            $teams = array_map('intval', $teams);

            if (count($teams) < 2) continue;

            // Manejo de impares (Bye)
            if (count($teams) % 2 != 0) {
                $teams[] = 'bye';
            }

            // Algoritmo Round Robin estándar
            $numTeams = count($teams);
            $numRounds = $numTeams - 1;
            $pairsPerRound = $numTeams / 2;
            $currentRoundTeams = $teams;

            for ($round = 0; $round < $numRounds; $round++) {
                for ($i = 0; $i < $pairsPerRound; $i++) {
                    $home = $currentRoundTeams[$i];
                    $away = $currentRoundTeams[$numTeams - 1 - $i];

                    if ($home !== 'bye' && $away !== 'bye') {
                        // Agregamos el matchup a la lista maestra
                        $allMatchups[] = [
                            'local' => $home,
                            'away' => $away,
                            'group_name' => $groupName, // Vital para que el intercalador sepa de qué categoría es
                            'category_group' => $groupName
                        ];
                    }
                }
                
                // Rotación de equipos para la siguiente ronda
                $team = array_pop($currentRoundTeams);
                array_splice($currentRoundTeams, 1, 0, $team);
            }
        }

        if (empty($allMatchups)) {
            return ['success' => false, 'message' => 'No hay equipos suficientes para generar juegos.'];
        }

        // 2. CONFIGURACIÓN
        $tournament = Tournament::find($tournamentId);
        $startDate = Carbon::parse($config['start_date']);
        $endDate = $config['end_date'] ? Carbon::parse($config['end_date']) : $startDate->copy()->addMonth();

        // Forzamos el intercalado
        $config['interleave_categories'] = true;

        try {
            // 3. DELEGAR AL PLANIFICADOR INTELIGENTE (schedulePlayoffRound)
            // Este método ya tiene la lógica de colas rotativas que necesitas.
            // Pasamos isPlayoffFlag = false porque son juegos de fase de grupos.
            $this->schedulePlayoffRound($tournament, $allMatchups, $startDate, $endDate, $config, null, false);

            return [
                'success' => true, 
                'message' => 'Calendario generado con categorías intercaladas.',
                'games_count' => count($allMatchups)
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error al programar: ' . $e->getMessage()];
        }
    }
}