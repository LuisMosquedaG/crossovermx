<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoubleEliminationService
{
    protected $calendarService;

    public function __construct(CalendarGeneratorService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function generateInitialBracket(Tournament $tournament, array &$config)
    {
        $teamIds = isset($config['specific_team_ids']) 
            ? collect($config['specific_team_ids'])->shuffle()->toArray() 
            : $tournament->teams->pluck('id')->shuffle()->toArray();

        if (count($teamIds) < 2) {
            throw new \Exception("Se requieren al menos 2 equipos.");
        }

        $targetSize = $this->getNextPowerOfTwo(count($teamIds));
        $byesNeeded = $targetSize - count($teamIds);

        for ($b = 0; $b < $byesNeeded; $b++) {
            $teamIds[] = null;
        }

        $config['wb_current_round'] = 1;
        $config['wb_total_rounds'] = log($targetSize, 2);
        $config['lb_pending_pool'] = []; 
        $config['wb_byes'] = []; 
        $config['tournament_type'] = 'double_elimination';
        $categoryGroup = $config['group_name'] ?? null;

        $matchups = [];
        for ($i = 0; $i < count($teamIds); $i += 2) {
            if (!isset($teamIds[$i + 1])) continue;

            $local = $teamIds[$i];
            $away = $teamIds[$i + 1];

            if ($local !== null && $away !== null) {
                $matchups[] = [
                    'local' => $local, 
                    'away' => $away, 
                    'group_name' => 'WB_R1', 
                    'category_group' => $categoryGroup
                ];
            } else {
                $teamWithBye = ($local !== null) ? $local : $away;
                if ($teamWithBye !== null) {
                    $config['wb_byes'][] = $teamWithBye;
                }
            }
        }

        $startDate = Carbon::parse($tournament->start_date);
        $endDate = $tournament->end_date ? Carbon::parse($tournament->end_date) : $startDate->copy()->addWeek();

        if (!empty($matchups)) {
            $this->calendarService->schedulePlayoffRound($tournament, $matchups, $startDate, $endDate, $config, collect(), true);

            // --- NUEVO: Forzar que la Ronda 1 se guarde como 1 ---
            // Aunque el servicio de calendario ponga 1 por defecto, lo aseguramos explícitamente
            \App\Models\Game::where('tournament_id', $tournament->id)
                ->where('group_name', 'WB_R1')
                ->where('category_group', $categoryGroup)
                ->update(['round_number' => 1]);
            // ----------------------------------------------------
        }
    }

    public function processBracketProgression(Tournament $tournament, array &$config)
    {
        $categoryGroup = $config['group_name'] ?? null;
        $wbRound = $config['wb_current_round'] ?? 1;
        
        // --- PASO 0: Reconstruir Piscina (Lógica Mejorada) ---
        $config['lb_pending_pool'] = $this->rebuildLoserPool($tournament, $config, $categoryGroup);
        $this->saveConfig($tournament, $config);
        // -------------------------------------------------

        // --- PASO 1: Avanzar Winner Bracket (WB) ---
        $currentWbRoundName = 'WB_R' . $wbRound;
        $wbGames = $this->getGamesByGroup($tournament, $currentWbRoundName, $categoryGroup);

        if ($wbGames->isNotEmpty() && $wbGames->every('status', 'finished')) {
            $this->advanceWinnerBracket($tournament, $config, $wbGames, $wbRound);
            $this->feedLoserPool($tournament, $config, $wbGames);
        }

        // --- PASO 2: Procesar Piscina de Perdedores (LB) ---
        $this->processLoserPool($tournament, $config);

        // --- PASO 3: Verificar Gran Final (GF) ---
        $this->checkAndCreateFinal($tournament, $config);
    }

    /**
     * Reconstruye la piscina de perdedores evitando duplicados y manejando la fase final.
     * INCLUYE CORRECCIÓN PARA EVITAR QUE EL GANADOR DEL WB ENTRE AL LB.
     */
    private function rebuildLoserPool($tournament, $config, $categoryGroup)
    {
        $pool = [];

        // 1. Identificar al GANADOR del Winner Bracket (si existe)
        $wbChampionId = null;
        $finalWbRoundName = 'WB_R' . ($config['wb_total_rounds']);
        $finalWbGame = $tournament->games()
            ->where('is_playoff', true)
            ->where('group_name', $finalWbRoundName)
            ->when($categoryGroup, fn($q) => $q->where('category_group', $categoryGroup))
            ->where('status', 'finished')
            ->first();

        if ($finalWbGame) {
            $wbChampionId = ($finalWbGame->local_team_score > $finalWbGame->away_team_score) 
                ? $finalWbGame->local_team_id 
                : $finalWbGame->away_team_id;
        }

        // 2. Obtener perdedores de todas las rondas WB finalizadas
        $wbGames = $tournament->games()
            ->where('is_playoff', true)
            ->where('group_name', 'like', 'WB_%')
            ->when($categoryGroup, fn($q) => $q->where('category_group', $categoryGroup))
            ->where('status', 'finished')
            ->get();

        foreach ($wbGames as $game) {
            $loserId = ($game->local_team_score > $game->away_team_score) ? $game->away_team_id : $game->local_team_id;
            // Si el perdedor es el CAMPEÓN del WB, es un error de datos, no lo agregamos
            if ($loserId != $wbChampionId) {
                $pool[] = $loserId;
            }
        }

        // 3. Obtener juegos LB finalizadas y procesarlos cronológicamente
        $lbGames = $tournament->games()
            ->where('is_playoff', true)
            ->where('group_name', 'like', 'LB_%')
            ->when($categoryGroup, fn($q) => $q->where('category_group', $categoryGroup))
            ->where('status', 'finished')
            ->orderBy('created_at', 'asc')
            ->get();

        // Verificamos si el WB ya terminó completamente
        $isWbFinished = ($config['wb_current_round'] > $config['wb_total_rounds']);

        foreach ($lbGames as $game) {
            $winnerId = ($game->local_team_score > $game->away_team_score) ? $game->local_team_id : $game->away_team_id;
            $loserId = ($game->local_team_score > $game->away_team_score) ? $game->away_team_id : $game->local_team_id;
            
            // Eliminamos a ambos equipos de la piscina (estaban "ocupados" jugando)
            $pool = array_diff($pool, [$game->local_team_id, $game->away_team_id]);

            // --- CORRECCIÓN AQUÍ ---
            // El ganador SIEMPRE vuelve a la piscina (a menos que sea el campeón WB).
            // Esto permite que el bracket de perdedores siga avanzando aunque el WB ya haya terminado.
            if ($winnerId != $wbChampionId) {
                $pool[] = $winnerId;
            }
            // -------------------------
        }

        // LIMPIEZA FINAL DE SEGURIDAD:
        // Asegurarnos de que el campeón del WB no esté en la piscina por algún error anterior
        if ($wbChampionId) {
            $pool = array_diff($pool, [$wbChampionId]);
        }

        return array_values(array_unique($pool));
    }

    private function advanceWinnerBracket($tournament, &$config, $wbGames, $currentRoundNum)
    {
        $nextRoundName = 'WB_R' . ($currentRoundNum + 1);
        if ($this->existsGames($tournament, $nextRoundName, $config['group_name'] ?? null)) {
            return;
        }

        $winners = $wbGames->map(function($g) {
            return ($g->local_team_score > $g->away_team_score) ? $g->local_team_id : $g->away_team_id;
        })->toArray();

        $currentByes = $config['wb_byes'] ?? [];
        $survivorPool = array_merge($currentByes, $winners);
        shuffle($survivorPool);

        $nextMatchups = [];
        $newByes = [];
        
        for ($i = 0; $i < count($survivorPool); $i += 2) {
            if (isset($survivorPool[$i + 1])) {
                $nextMatchups[] = [
                    'local' => $survivorPool[$i],
                    'away' => $survivorPool[$i + 1],
                    'group_name' => $nextRoundName,
                    'category_group' => $config['group_name'] ?? null
                ];
            } else {
                $newByes[] = $survivorPool[$i];
            }
        }

        $config['wb_byes'] = $newByes;
        $config['wb_current_round'] = $currentRoundNum + 1;
        $this->saveConfig($tournament, $config);

        if (!empty($nextMatchups)) {
            $this->scheduleNextRound($tournament, $nextMatchups, $wbGames, $config);
        }
    }

    private function feedLoserPool($tournament, &$config, $wbGames)
    {
        $losers = $wbGames->map(function($g) {
            return ($g->local_team_score > $g->away_team_score) ? $g->away_team_id : $g->local_team_id;
        })->toArray();

        if (!isset($config['lb_pending_pool'])) {
            $config['lb_pending_pool'] = [];
        }

        // Usamos array_unique para evitar duplicados si ya estaban ahí por el rebuild
        $config['lb_pending_pool'] = array_values(array_unique(array_merge($config['lb_pending_pool'], $losers)));
        
        $this->saveConfig($tournament, $config);
    }

    private function processLoserPool($tournament, &$config)
    {
        // Si hay menos de 2 equipos, no se puede jugar
        if (!isset($config['lb_pending_pool']) || count($config['lb_pending_pool']) < 2) {
            return; 
        }

        // 1. Obtenemos el último juego existente como referencia de fecha inicial
        $lastGame = $tournament->games()->orderBy('created_at', 'desc')->first();
        // Usamos este juego como "base" para calcular el inicio del primero de la tanda
        $currentReferenceGames = $lastGame ? collect([$lastGame]) : collect();

        // 2. Contamos cuántos juegos de LB existen YA para no repetir números de ronda
        $existingLbCount = $tournament->games()
            ->where('is_playoff', true)
            ->where('group_name', 'like', 'LB_%')
            ->when(isset($config['group_name']), fn($q) => $q->where('category_group', $config['group_name']))
            ->count();

        // 3. CICLO PRINCIPAL: Mientras tengamos al menos 2 equipos, seguimos creando juegos
        // Esto soluciona tu problema: si hay 4 equipos, crea 2 juegos seguidos.
        while (count($config['lb_pending_pool']) >= 2) {
            
            $team1 = array_shift($config['lb_pending_pool']);
            $team2 = array_shift($config['lb_pending_pool']);

            // Incrementamos el contador para el nombre de la ronda (LB_R3, LB_R4, etc.)
            $existingLbCount++;
            $roundName = 'LB_R' . $existingLbCount;

            $matchups = [[
                'local' => $team1,
                'away' => $team2,
                'group_name' => $roundName,
                'category_group' => $config['group_name'] ?? null
            ]];

            // Programamos el juego.
            // Nota: Pasamos $currentReferenceGames que contiene el juego anterior.
            // scheduleNextRound calculará la fecha sumando el buffer al juego anterior.
            $this->scheduleNextRound($tournament, $matchups, $currentReferenceGames, $config);

            // 4. ACTUALIZACIÓN CRÍTICA DE REFERENCIA
            // Buscamos el juego que acabamos de crear para que el SIGUIENTE bucle
            // use la fecha de ESTE juego como punto de partida.
            $newlyCreatedGame = $tournament->games()
                ->where('group_name', $roundName)
                ->where('category_group', $config['group_name'] ?? null)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($newlyCreatedGame) {
                $currentReferenceGames = collect([$newlyCreatedGame]);
            }
        }
        
        // Guardamos el estado final de la piscina (que podría estar vacía o con 1 equipo sobrante)
        $this->saveConfig($tournament, $config);
    }

    private function checkAndCreateFinal($tournament, &$config)
    {
        $wbCurrent = $config['wb_current_round'];
        $wbTotal = $config['wb_total_rounds'];
        
        $finalWbRoundName = 'WB_R' . $wbTotal;
        $finalWbGames = $this->getGamesByGroup($tournament, $finalWbRoundName, $config['group_name'] ?? null);

        // Si el WB no ha terminado su ronda final, no hacemos nada
        if ($finalWbGames->isEmpty() || !$finalWbGames->every('status', 'finished')) {
            return; 
        }

        // --- NUEVA BARRERA DE SEGURIDAD ---
        // Si hay partidos de LB pendientes de jugar, no podemos crear la Final.
        // Debemos esperar a que se resuelvan para saber quién entra realmente.
        $pendingLbGames = $tournament->games()
            ->where('is_playoff', true)
            ->where('group_name', 'like', 'LB_%')
            ->where('status', 'pending')
            ->when(isset($config['group_name']), fn($q) => $q->where('category_group', $config['group_name']))
            ->exists();
            
        if ($pendingLbGames) {
            return; // Esperar a que se jueguen los partidos pendientes
        }
        // ---------------------------------

        // Determinar campeón WB
        $wbChampionId = ($finalWbGames->first()->local_team_score > $finalWbGames->first()->away_team_score) 
            ? $finalWbGames->first()->local_team_id 
            : $finalWbGames->first()->away_team_id;

        // Si ya hay Final, revisamos Reset
        if ($this->existsGames($tournament, 'GF', $config['group_name'] ?? null)) {
            $this->checkResetGame($tournament, $config, $wbChampionId);
            return;
        }

        // --- LÓGICA FINAL (Con Pool) ---
        $isWbFinished = ($config['wb_current_round'] > $config['wb_total_rounds']);
        $poolCount = count($config['lb_pending_pool'] ?? []);

        // Si el WB terminó y solo queda 1 equipo en la piscina (y no hay pendientes), ese es el Campeón LB.
        if ($isWbFinished && $poolCount === 1) {
            $lbChampionId = $config['lb_pending_pool'][0];
            
            $matchups = [[
                'local' => $wbChampionId, 
                'away' => $lbChampionId, 
                'group_name' => 'GF', 
                'category_group' => $config['group_name'] ?? null
            ]];

            $lastLbGame = $tournament->games()
                ->where('is_playoff', true)
                ->where('group_name', 'like', 'LB_%')
                ->when(isset($config['group_name']), fn($q) => $q->where('category_group', $config['group_name']))
                ->orderBy('created_at', 'desc')
                ->first();

            $this->scheduleNextRound($tournament, $matchups, $lastLbGame ? collect([$lastLbGame]) : collect(), $config);
            return;
        }

        // Lógica estándar si no se cumple lo anterior (Pool vacío)
        if (!empty($config['lb_pending_pool'])) {
            return; 
        }

        $lastLbGame = $tournament->games()
            ->where('is_playoff', true)
            ->where('group_name', 'like', 'LB_%')
            ->when(isset($config['group_name']), fn($q) => $q->where('category_group', $config['group_name']))
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastLbGame || $lastLbGame->status === 'finished') {
             $lbChampionId = $lastLbGame 
                ? (($lastLbGame->local_team_score > $lastLbGame->away_team_score) ? $lastLbGame->local_team_id : $lastLbGame->away_team_id)
                : null; 
             
             if (!$lbChampionId) return;

            $matchups = [[
                'local' => $wbChampionId, 
                'away' => $lbChampionId, 
                'group_name' => 'GF', 
                'category_group' => $config['group_name'] ?? null
            ]];

            $this->scheduleNextRound($tournament, $matchups, collect([$lastLbGame]), $config);
        }
    }

    private function checkResetGame($tournament, &$config, $wbChampionId)
    {
        $gfGame = $this->getGamesByGroup($tournament, 'GF', $config['group_name'] ?? null)->first();
        
        if ($gfGame && $gfGame->status === 'finished' && !$this->existsGames($tournament, 'GR', $config['group_name'] ?? null)) {
            $gfWinnerId = ($gfGame->local_team_score > $gfGame->away_team_score) ? $gfGame->local_team_id : $gfGame->away_team_id;

            // Si el ganador no es el invicto, hay revancha
            if ($wbChampionId !== $gfWinnerId) {
                $matchups = [[
                    'local' => $wbChampionId, 
                    'away' => $gfWinnerId, 
                    'group_name' => 'GR', 
                    'category_group' => $config['group_name'] ?? null
                ]];
                $this->scheduleNextRound($tournament, $matchups, collect([$gfGame]), $config);
            }
        }
    }

    // --- HELPERS ---

    private function getGamesByGroup($tournament, $groupName, $categoryGroup)
    {
        $query = $tournament->games()->where('is_playoff', true)->where('group_name', $groupName);
        if ($categoryGroup) {
            $query->where('category_group', $categoryGroup);
        }
        return $query->get();
    }

    private function existsGames($tournament, $groupName, $categoryGroup)
    {
        return $this->getGamesByGroup($tournament, $groupName, $categoryGroup)->isNotEmpty();
    }

    private function saveConfig($tournament, $config)
    {
        $setting = \App\Models\TournamentSetting::where('tournament_id', $tournament->id)->first();
        if ($setting) {
            $currentSettings = $setting->settings;
            $groupName = $config['group_name'] ?? null;

            $mergedSettings = array_merge($currentSettings, $config);
            
            if (isset($currentSettings['brackets_data'])) {
                $mergedSettings['brackets_data'] = $currentSettings['brackets_data'];
                
                if ($groupName && isset($mergedSettings['brackets_data'][$groupName])) {
                    if (isset($config['lb_pending_pool'])) {
                        $mergedSettings['brackets_data'][$groupName]['lb_pending_pool'] = $config['lb_pending_pool'];
                    }
                    if (isset($config['wb_current_round'])) {
                        $mergedSettings['brackets_data'][$groupName]['wb_current_round'] = $config['wb_current_round'];
                    }
                    if (isset($config['wb_byes'])) {
                        $mergedSettings['brackets_data'][$groupName]['wb_byes'] = $config['wb_byes'];
                    }
                }
            }
            
            $setting->update(['settings' => $mergedSettings]);
        }
    }

    private function scheduleNextRound($tournament, $matchups, $currentGames, $config)
    {
        // 1. Lógica existente para calcular fechas (se mantiene igual)
        $lastDate = collect($currentGames)->max('date_time') ?: $tournament->start_date;
        
        $gameDuration = ($config['game_duration'] ?? 10) * ($config['periods_per_game'] ?? 1);
        $restBetweenGames = $config['rest_between_games'] ?? 10;
        $totalBuffer = $gameDuration + $restBetweenGames;
        
        $startDate = Carbon::parse($lastDate)->addMinutes($totalBuffer);
        $endDate = $tournament->end_date ? Carbon::parse($tournament->end_date) : $startDate->copy()->addWeek();

        if ($startDate->gt($endDate)) {
            $endDate = $startDate->copy()->addWeek();
            $tournament->end_date = $endDate;
            $tournament->save();
        }

        // 2. Creamos los juegos
        $this->calendarService->schedulePlayoffRound($tournament, $matchups, $startDate, $endDate, $config, collect(), true);

        // --- NUEVO: CORRECCIÓN DEL NÚMERO DE RONDA ---
        if (!empty($matchups)) {
            // Tomamos el primer partido para saber qué ronda estamos generando (ej. WB_R2 o LB_R3)
            $firstMatchup = $matchups[0];
            $groupName = $firstMatchup['group_name'];
            
            $roundNumber = 1; // Valor por defecto
            
            // Intentamos extraer el número del nombre (ej. "WB_R3" -> 3, "LB_R5" -> 5)
            if (preg_match('/\d+/', $groupName, $matches)) {
                $roundNumber = (int)$matches[0];
            } 
            // Caso especial para Gran Final (GF) o Reset Game (GR)
            // Si no hay número, le asignamos un número alto para que aparezca al final
            elseif (in_array($groupName, ['GF', 'GR'])) {
                // Buscamos la ronda más alta actual y le sumamos 1
                $maxRound = \App\Models\Game::where('tournament_id', $tournament->id)->max('round_number');
                $roundNumber = ($maxRound ? $maxRound + 1 : 99);
            }

            // Actualizamos en base de datos los juegos que acabamos de crear
            // Filtramos por group_name para no afectar a otros juegos
            \App\Models\Game::where('tournament_id', $tournament->id)
                ->where('group_name', $groupName)
                ->where('category_group', $config['group_name'] ?? null) // Aseguramos que sea del grupo correcto
                ->update(['round_number' => $roundNumber]);
        }
        // ------------------------------------------------
    }

    private function getNextPowerOfTwo($number) {
        $value = 1;
        while ($value < $number) {
            $value *= 2;
        }
        return $value;
    }

        /**
     * Calcula la estructura inicial del bracket (sin guardar en BD).
     * Útil para generar múltiples brackets a la vez e intercalarlos.
     */
    public function calculateBracketStructure(array $teamIds, string $categoryGroup): array
    {
        // 1. Lógica de equipos y Byes (Igual que en generateInitialBracket)
        if (count($teamIds) < 2) {
            return ['matchups' => [], 'config' => []];
        }

        // Barajamos equipos
        $shuffledTeams = collect($teamIds)->shuffle()->toArray();
        
        $targetSize = $this->getNextPowerOfTwo(count($shuffledTeams));
        $byesNeeded = $targetSize - count($shuffledTeams);

        // Rellenamos con null para representar Byes
        for ($b = 0; $b < $byesNeeded; $b++) {
            $shuffledTeams[] = null;
        }

        $configData = [];
        $configData['wb_current_round'] = 1;
        $configData['wb_total_rounds'] = log($targetSize, 2);
        $configData['lb_pending_pool'] = []; 
        $configData['wb_byes'] = []; 
        
        $matchups = [];

        // 2. Generar los enfrentamientos de la Ronda 1 del Winner Bracket
        for ($i = 0; $i < count($shuffledTeams); $i += 2) {
            if (!isset($shuffledTeams[$i + 1])) continue;

            $local = $shuffledTeams[$i];
            $away = $shuffledTeams[$i + 1];

            if ($local !== null && $away !== null) {
                $matchups[] = [
                    'local' => $local, 
                    'away' => $away, 
                    'group_name' => 'WB_R1', 
                    'category_group' => $categoryGroup
                ];
            } else {
                // Identificar quién tuvo el Bye
                $teamWithBye = ($local !== null) ? $local : $away;
                if ($teamWithBye !== null) {
                    $configData['wb_byes'][] = $teamWithBye;
                }
            }
        }

        return [
            'matchups' => $matchups,
            'config' => $configData
        ];
    }

}