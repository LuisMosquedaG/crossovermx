<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameAction;
use Illuminate\Http\Request;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\LiveGameService;

class GameController extends Controller
{
    protected $liveGameService;

    public function __construct(LiveGameService $liveGameService)
    {
        $this->middleware('auth');
        $this->liveGameService = $liveGameService;
    }

    /**
     * Método privado auxiliar para descontar suspensiones de equipo y jugadores
     */
    private function decrementSuspensions($team, $game)
    {
        // Descuento de Equipo
        if ($team->suspension_games > 0) {
            $team->suspension_games--;
            if ($team->suspension_games == 0) {
                $team->status = 'active';
            }
            $team->save();
        }

        // Descuento de Jugadores
        $players = Player::where('team_id', $team->id)->get();
        foreach ($players as $player) {
            if ($player->suspension_games > 0) {
                $player->suspension_games--;
                if ($player->suspension_games == 0) {
                    $player->status = 'active';
                }
                $player->save();
            }
        }
    }

public function showLiveGame(Game $game)
{
    
    $this->authorize('view', $game);
    // Delegamos TODA la lógica pesada al Servicio
    // El controlador solo se encarga de recibir la petición y devolver la vista
    $data = $this->liveGameService->getGameData($game);

    return view('games.live', $data);
}

 /**
     * Registra una acción del juego (punto, falta, etc.)
     * VERSIÓN CORREGIDA: Define $game antes de autorizar.
     */
    public function recordAction(Request $request)
    {
        // 1. Obtenemos el juego primero para poder autorizar
        $game = Game::findOrFail($request->game_id);

        // 2. Autorizamos la acción (ahora $game existe)
        $this->authorize('view', $game);

        // 3. Validamos los datos
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_id' => 'nullable|exists:players,id',
            'team_side' => 'required|in:local,away',
            'action_type' => 'required|in:point_scored,foul_personal,foul_technical,foul_unsportsmanlike,foul_disqualifying,timeout_called',
            'value' => 'nullable|integer',
            'period' => 'required|integer|min:1',
        ]);

        // Creamos la acción
        $action = GameAction::create($request->all());
        
        $gameId = $request->game_id;
        $teamSide = $request->team_side;
        $value = (int) $request->value; // Nos aseguramos que sea entero

        $responseScore = ['localScore' => 0, 'awayScore' => 0];
        $knockOutOccurred = false;

        // --- CORRECCIÓN: Usar Lock For Update ---
        // Esto bloquea la fila del juego para que nadie la toque mientras nosotros sumamos
        if ($request->action_type === 'point_scored') {
            
            DB::transaction(function () use ($gameId, $teamSide, $value, &$responseScore, &$knockOutOccurred) {
                $game = Game::where('id', $gameId)->lockForUpdate()->first();

                if ($game) {
                    if ($teamSide === 'local') {
                        // Si es null, tomamos 0, si no sumamos al actual
                        $current = $game->local_team_score ?? 0;
                        $game->local_team_score = $current + $value;
                        $responseScore['localScore'] = $game->local_team_score;
                    } else {
                        $current = $game->away_team_score ?? 0;
                        $game->away_team_score = $current + $value;
                        $responseScore['awayScore'] = $game->away_team_score;
                    }
                    $game->save();

                    // --- NUEVO: DETECTAR KNOCK-OUT ---
                    $gameSettings = $game->settings ?? [];
                    $knockOutLimit = $gameSettings['knock_out'] ?? ($game->tournament->settings->settings['knock_out'] ?? null);
                    
                    if ($knockOutLimit && $knockOutLimit > 0) {
                        if ($game->local_team_score >= $knockOutLimit || $game->away_team_score >= $knockOutLimit) {
                            $knockOutOccurred = true;
                            
                            $game->status = 'finished';
                            $game->timer_status = 'finished';
                            $game->save();

                            // Lógica de torneo (Protegida para partidos manuales)
                            if ($game->tournament) {
                                try {
                                    $game->tournament->checkCompletionStatus();
                                } catch (\Exception $e) {
                                    \Log::error('Error al actualizar torneo en knock-out: ' . $e->getMessage());
                                }
                            }

                            // Descuento de suspensiones
                            try {
                                $this->decrementSuspensions($game->localTeam, $game);
                                $this->decrementSuspensions($game->awayTeam, $game);
                            } catch (\Exception $e) {
                                \Log::error('Error al decrementar suspensiones en knock-out: ' . $e->getMessage());
                            }
                        }
                    }
                }
            });
        } else {
            // Si no fue punto, devolvemos los scores actuales (necesario para que el JS no rompa)
            $game = Game::find($gameId);
            $responseScore['localScore'] = $game->local_team_score ?? 0;
            $responseScore['awayScore'] = $game->away_team_score ?? 0;
        }

        return response()->json([
            'success' => true,
            'action' => $action->load('player'),
            'localScore' => $responseScore['localScore'],
            'awayScore' => $responseScore['awayScore'],
            'knockOut' => $knockOutOccurred,
        ]);
    }

    /**
     * Actualiza el periodo actual del juego
     * VERSIÓN CORREGIDA
     */
    public function updatePeriod(Request $request)
    {
        // 1. Obtenemos el juego primero
        $game = Game::findOrFail($request->game_id);

        // 2. Autorizamos (ahora $game existe)
        $this->authorize('view', $game);

        // 3. Validamos
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'period' => 'required|integer|min:1',
        ]);

        // 4. Actualizamos y guardamos
        $game->period = $request->period;
        $game->save();
        
        return response()->json([
            'success' => true,
            'period' => $request->period,
        ]);
    }

    /**
     * Actualiza el estado del cronómetro (Iniciar/Pausar)
     * CORREGIDO: Ahora respeta los minutos configurados en el torneo.
     */
    public function updateTimer(Request $request)
    {
        // 1. Obtenemos el juego primero
        $game = Game::findOrFail($request->game_id);

        // 2. Autorizamos
        $this->authorize('view', $game);

        // 3. Validamos
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'status' => 'required|in:running,stopped',
            'seconds' => 'required|integer|min:0'
        ]);

        // --- NUEVO: Obtenemos la configuración del torneo ---
        $tournamentSettings = $game->tournament->settings->settings ?? [];
        // Usamos 'game_duration' de la config, por defecto 10 si no existe
        $configuredMinutes = $tournamentSettings['game_duration'] ?? 10; 
        // -----------------------------------------------

        // 4. Actualizamos lógica
        $game->timer_status = $request->status;
        $game->seconds_remaining = $request->seconds;
        
        // CORRECCIÓN AQUÍ: Usamos $configuredMinutes en lugar de 600
        if ($request->status === 'running' && $game->seconds_remaining === null) {
            $game->seconds_remaining = $configuredMinutes * 60; 
        }

        $game->save();

        return response()->json(['success' => true]);
    }

    public function finishGame(Request $request)
    {
        // WRAPPER PRINCIPAL PARA CAPTURAR CUALQUIER ERROR
        try {
            // 1. Cargar juego y relaciones necesarias (tournament es clave para evitar errores de null)
            $game = Game::with(['localTeam', 'awayTeam', 'tournament'])->findOrFail($request->input('game_id'));
            
            // 2. Autorizar
            $this->authorize('finish', $game);

            // 3. Validar que los equipos existan (Por si se borraron después de crear el partido)
            if (!$game->localTeam || !$game->awayTeam) {
                return response()->json(['success' => false, 'message' => 'Error Crítico: El partido no tiene equipos asociados correctamente.'], 400);
            }

            DB::transaction(function () use ($request, $game) {
                $localTeam = $game->localTeam;
                $awayTeam = $game->awayTeam;
                
                // Asegurar que sean objetos y no IDs
                if (!is_object($localTeam) || !is_object($awayTeam)) {
                    throw new \Exception('Error: Los datos de los equipos no se cargaron correctamente.');
                }

                // (Lógica de puntuación por suspensión si la tenías antes, mantenla aquí)

                $game->status = 'finished';
                $game->timer_status = 'finished';
                $game->save();

                // Lógica de torneo (Protegida para partidos manuales)
                if ($game->tournament) {
                    try {
                        $game->tournament->checkCompletionStatus();
                    } catch (\Exception $e) {
                        // Si el torneo falla, logueamos pero no rompemos el finalizado del partido
                        \Log::error('Error al actualizar torneo: ' . $e->getMessage());
                    }
                }

                // Descuento de suspensiones
                $this->decrementSuspensions($localTeam, $game);
                $this->decrementSuspensions($awayTeam, $game);
            });

            // --- REDIRECCIÓN INTELIGENTE ---
            if ($game->tournament_id) {
                $targetUrl = route('tournaments.schedule', $game->tournament_id);
            } else {
                $targetUrl = route('games.index');
            }

            // Si es AJAX (Fetch), devolvemos JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Partido finalizado correctamente.',
                    'redirect_url' => $targetUrl
                ]);
            }

            // Si es web normal, redirigimos
            return redirect($targetUrl);

        } catch (\Exception $e) {
            // REGISTRAR EL ERROR EN EL ARCHIVO DE LOG
            \Log::error('ERROR EN finishGame: ' . $e->getMessage());
            
            // DEVOLVER EL ERROR AL JAVASCRIPT PARA QUE LO MUESTRE EN ALERT
            return response()->json([
                'success' => false, 
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Avanza al siguiente periodo.
     * ACTUALIZADO: Detecta empates para lanzar Tiempo Extra.
     */
    public function nextPeriod(Request $request)
    {
        try {
            // 1. Recargamos el juego desde BD (Fresh) para evitar desincronización
            $game = Game::lockForUpdate()->findOrFail($request->game_id);

            // 2. Autorizamos
            $this->authorize('view', $game);

            // 3. Validamos
            $request->validate([
                'game_id' => 'required|exists:games,id',
                'current_period' => 'required|integer'
            ]);
            
            $currentPeriodInDb = (int) $game->period;
            $localScore = (int) $game->local_team_score;
            $awayScore = (int) $game->away_team_score;

            // --- CORRECCIÓN: Priorizar Configuración del Juego sobre la del Torneo ---
            // 1. Intentamos leer la configuración individual del juego (si existe)
            $gameSettings = $game->settings ?? [];

            // 2. Definimos los valores por defecto (si el juego no tiene config, usamos la del torneo)
            $totalPeriods = $gameSettings['periods_per_game'] ?? ($game->tournament->settings->settings['periods_per_game'] ?? 4);
            $configuredMinutes = $gameSettings['game_duration'] ?? ($game->tournament->settings->settings['game_duration'] ?? 10);
            // -----------------------------------------------------------------------

            // --- LÓGICA DE FINALIZACIÓN O TIEMPO EXTRA ---
            if ($currentPeriodInDb >= $totalPeriods) {
                
                // VERIFICAR EMPATE
                if ($localScore === $awayScore) {
                    // Retornamos estado 'tied' para que el Frontend lance el modal
                    return response()->json([
                        'success' => true, 
                        'status' => 'tied',
                        'message' => 'El partido está empatado. Se requiere Tiempo Extra.'
                    ]);
                }

                // SI NO HAY EMPATE, FINALIZA EL JUEGO
                $game->status = 'finished';
                $game->timer_status = 'finished';
                
                // --- CORRECCIÓN: Solo actualizar torneo si existe ---
                if ($game->tournament) {
                    try {
                        $game->tournament->checkCompletionStatus();
                    } catch (\Exception $e) {
                        \Log::error('Error en torneo al finalizar: ' . $e->getMessage());
                    }
                }
                // --------------------------------------------------
                
                \Log::info('JUEGO FINALIZADO CORRECTAMENTE', ['game_id' => $game->id, 'periodo' => $currentPeriodInDb]);
                
            } else {
                // PASA AL SIGUIENTE PERIODO NORMAL
                $nextPeriod = $currentPeriodInDb + 1;
                $game->period = $nextPeriod;
                $game->seconds_remaining = $configuredMinutes * 60; 
                $game->timer_status = 'stopped'; 
                
                \Log::info('AVANZANDO PERIODO', ['game_id' => $game->id, 'de' => $currentPeriodInDb, 'a' => $nextPeriod]);
            }

            $game->save();

            return response()->json([
                'success' => true, 
                'status' => $game->status, // 'finished', 'playing' o el valor que tenga
                'period' => $game->period,
                'seconds' => $game->seconds_remaining
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en nextPeriod: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Inicia el periodo de tiempo extra
     * CORREGIDO: Mismo principio, guardar el tiempo exacto del evento.
     */
    public function startOvertime(Request $request)
    {
        try {
            $game = Game::lockForUpdate()->findOrFail($request->game_id);
            $this->authorize('view', $game);

            $request->validate([
                'game_id' => 'required|exists:games,id',
                'minutes' => 'required|integer|min:1|max:20'
            ]);

            $currentSeconds = $game->seconds_remaining; // Guardar tiempo actual

            $game->period = $game->period + 1;
            $game->seconds_remaining = $request->minutes * 60;
            $game->timer_status = 'stopped';
            $game->save();

            \App\Models\GameAction::create([
                'game_id' => $game->id,
                'player_id' => null,
                'team_side' => 'system',
                'action_type' => 'overtime_started',
                'value' => $request->minutes,
                'period' => $game->period,
                'seconds' => $currentSeconds // <-- GUARDAMOS EL TIEMPO ACTUAL
            ]);

            return response()->json([
                'success' => true,
                'status' => 'overtime',
                'period' => $game->period,
                'seconds' => $game->seconds_remaining,
                'action' => \App\Models\GameAction::latest()->first()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en startOvertime: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Agrega tiempo de compensación
     * CORREGIDO: Guarda el tiempo ANTES de agregar para mostrar el momento exacto en el log.
     */
    public function addCompensationTime(Request $request)
    {
        try {
            $game = Game::lockForUpdate()->findOrFail($request->game_id);
            $this->authorize('view', $game);

            $request->validate([
                'game_id' => 'required|exists:games,id',
                'minutes' => 'required|integer|min:1|max:10'
            ]);

            // 1. Capturamos el tiempo ACTUAL (ej. 00:00) para el historial
            $currentSeconds = $game->seconds_remaining;

            // 2. Modificamos el tiempo del juego
            $game->seconds_remaining += ($request->minutes * 60);
            $game->save();

            // 3. Registramos la acción usando el tiempo ANTES de la modificación
            \App\Models\GameAction::create([
                'game_id' => $game->id,
                'player_id' => null,
                'team_side' => 'system',
                'action_type' => 'compensation_added',
                'value' => $request->minutes,
                'period' => $game->period,
                'seconds' => $currentSeconds // <-- GUARDAMOS EL TIEMPO ANTES (00:00)
            ]);

            return response()->json([
                'success' => true,
                'seconds' => $game->seconds_remaining,
                'action' => \App\Models\GameAction::latest()->first()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en addCompensationTime: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra las estadísticas finales de un partido.
     * Mejora: Toda la lógica de cálculo está aquí, no en la vista. Se cargan jugadores en lote.
     */
    public function showStats(Game $game)
    {
        $game->load(['localTeam', 'awayTeam', 'tournament.settings']);

        // --- PREPARACIÓN DE DATOS ---
        
        // 1. Agrupar acciones por tipo para calcular totales por jugador
        $actions = $game->actions;
        
        $playerIds = $actions->pluck('player_id')->unique()->filter()->toArray();
        
        // Cargar jugadores de una sola vez (Evita N+1)
        $players = Player::whereIn('id', $playerIds)->get()->keyBy('id');

        // Arrays de estadísticas
        $localStats = []; 
        $awayStats = [];

        // Calcular Estadísticas por Jugador
        foreach ($actions as $action) {
            if (!$action->player_id || !isset($players[$action->player_id])) continue;

            $side = $action->team_side;
            $pid = $action->player_id;

            // Usar arrays normales en lugar de variables variables (${...})
            if ($side === 'local') {
                if (!isset($localStats[$pid])) {
                    $localStats[$pid] = ['points1' => 0, 'points2' => 0, 'points3' => 0, 'fouls' => 0];
                }
                $targetStats = &$localStats[$pid];
            } else {
                if (!isset($awayStats[$pid])) {
                    $awayStats[$pid] = ['points1' => 0, 'points2' => 0, 'points3' => 0, 'fouls' => 0];
                }
                $targetStats = &$awayStats[$pid];
            }

            if ($action->action_type === 'point_scored') {
                $targetStats['points' . $action->value]++;
            } elseif (strpos($action->action_type, 'foul') !== false) {
                $targetStats['fouls'] += 1;
            }
        }

        // --- CALCULAR ESTADÍSTICAS POR PERIODO (Antes en la vista) ---
        $localPeriodStats = [];
        $awayPeriodStats = [];

        for($i = 1; $i <= ($game->period ?? 1); $i++) {
            $periodActions = $actions->where('period', $i);

            // Local
            $localP = $periodActions->where('team_side', 'local')->where('action_type', 'point_scored')->sum('value');
            $localF = $periodActions->where('team_side', 'local')->filter(function($a){ return strpos($a->action_type, 'foul') !== false; })->count();
            $localPeriodStats[$i] = ['points' => $localP, 'fouls' => $localF];

            // Away
            $awayP = $periodActions->where('team_side', 'away')->where('action_type', 'point_scored')->sum('value');
            $awayF = $periodActions->where('team_side', 'away')->filter(function($a){ return strpos($a->action_type, 'foul') !== false; })->count();
            $awayPeriodStats[$i] = ['points' => $awayP, 'fouls' => $awayF];
        }

        return view('games.stats', compact('game', 'localStats', 'awayStats', 'players', 'localPeriodStats', 'awayPeriodStats'));
    }

    public function getBenchPlayers(Request $request, Game $game)
    {
        try {
            $teamSide = $request->query('team_side'); 
            $teamId = $teamSide === 'local' ? $game->local_team_id : $game->away_team_id;

            $activePlayerIds = $game->players()
                                    ->wherePivot('team_side', $teamSide)
                                    ->wherePivot('is_active', true)
                                    ->pluck('players.id')
                                    ->toArray();

            $query = Player::where('team_id', $teamId);

            if (!empty($activePlayerIds)) {
                $query->whereNotIn('id', $activePlayerIds);
            }

            $benchPlayers = $query->get();

            return response()->json($benchPlayers);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Procesa el cambio de un jugador por otro.
     */
    public function substitutePlayer(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_out_id' => 'nullable|exists:players,id',
            'player_in_id' => 'nullable|exists:players,id',
            'team_side' => 'required|in:local,away',
        ]);

        try {
            $game = Game::find($request->game_id);

            if ($request->player_out_id) {
                $game->players()->updateExistingPivot($request->player_out_id, ['is_active' => false]);
            }

            if ($request->player_in_id) {
                $playerInRelation = $game->players()->where('player_id', $request->player_in_id)->first();
                if ($playerInRelation) {
                    $game->players()->updateExistingPivot($request->player_in_id, ['is_active' => true]);
                } else {
                    $game->players()->attach($request->player_in_id, [
                        'team_side' => $request->team_side,
                        'is_starter' => false,
                        'is_active' => true
                    ]);
                }
            } 

            $action = GameAction::create([
                'game_id' => $game->id,
                'player_id' => $request->player_in_id,
                'team_side' => $request->team_side,
                'action_type' => 'substitution',
                'value' => $request->player_out_id,
                'period' => $game->period ?? 1,
                'seconds' => $game->seconds_remaining ?? 0,
            ]);

            $playerIn = $request->player_in_id ? Player::find($request->player_in_id) : null; 
            $playerOut = $request->player_out_id ? Player::find($request->player_out_id) : null;
            
            $action->load('player');

            return response()->json([
                'success' => true,
                'playerIn' => $playerIn,
                'playerOut' => $playerOut,
                'action' => $action
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

     public function cancelGame(Request $request, Game $game)
    {
        $this->authorize('update', $game);
        $request->validate([
            'reason' => 'required|in:weather,local_no_show,away_no_show,double_no_show',
        ]);

        // --- PUNTO B: ENVOLVER EN TRANSACCIÓN ---
        DB::transaction(function () use ($request, $game) {
            
            // 1. Manejo de Condiciones Meteorológicas (Reagendar)
            if ($request->reason === 'weather') {
                $newDateTime = $this->findNextAvailableSlot($game);
                
                if (!$newDateTime) {
                    // Lanzamos excepción para hacer rollback de la transacción si falla
                    throw new \Exception('No se encontró un hueco disponible para reagendar.');
                }

                $game->date_time = $newDateTime;
                $game->status = 'pending'; 
                $game->save();
                return; // Retornamos temprano, no se descontan suspensiones si se reagenda
            }

            // 2. Manejo de Inasistencias
            if ($request->reason === 'local_no_show') {
                $game->local_team_score = 0;
                $game->away_team_score = 1;
            } elseif ($request->reason === 'away_no_show') {
                $game->local_team_score = 1;
                $game->away_team_score = 0;
            } elseif ($request->reason === 'double_no_show') {
                $game->local_team_score = 0;
                $game->away_team_score = 0;
            }

            $game->status = 'finished'; 
            $game->save();

            $game->tournament->checkCompletionStatus();

            // LÓGICA DE DESCUENTO DE SUSPENSIONES
            $localTeam = $game->localTeam;
            $awayTeam = $game->awayTeam;

            $this->decrementSuspensions($localTeam, $game);
            $this->decrementSuspensions($awayTeam, $game);
        });

        // Preparamos mensaje basado en si fue reagendado o cancelado
        $message = ($request->reason === 'weather') 
            ? 'Partido reagendado correctamente.' 
            : 'Partido cancelado. Resultado registrado y suspensiones de jugadores descontadas.';

        return response()->json(['success' => true, 'message' => $message]);
    }

    private function findNextAvailableSlot(Game $game)
    {
        $tournament = $game->load('tournament.settings')->tournament;
        $settings = $tournament->settings->settings ?? [];
        
        if (empty($settings)) throw new \Exception('El torneo no tiene configuración de calendario.');

        $allowedDays = $settings['days'] ?? [];
        $startTime = $settings['start_time'] ?? '10:00';
        $courts = $settings['courts'] ?? [];
        $tournamentEnd = Carbon::parse($tournament->end_date)->endOfDay();

        // OPTIMIZACIÓN: Obtener todos los partidos futuros del torneo en UNA sola consulta
        // Mapeamos los resultados a una colección indexada por fecha y cancha para acceso rápido en memoria
        $occupiedSlots = Game::where('tournament_id', $tournament->id)
            ->whereIn('status', ['pending', 'scheduled', 'live']) // Solo partios relevantes
            ->where('date_time', '>=', Carbon::parse($game->date_time)->addDay()->startOfDay())
            ->where('date_time', '<=', $tournamentEnd)
            ->get(['court_id', 'date_time'])
            ->map(function ($item) {
                return $item->court_id;
            });

        $searchDate = Carbon::parse($game->date_time)->addDay()->startOfDay();
        
        // Bucle máximo 365 días
        for ($i = 0; $i < 365; $i++) {
            if ($searchDate->gt($tournamentEnd)) return null;
            
            // Si el día está permitido
            if (in_array($searchDate->dayOfWeek, $allowedDays)) {
                $potentialDateTime = $searchDate->copy()->setTimeFromTimeString($startTime);
                $dateStr = $potentialDateTime->toDateTimeString(); // '2023-10-25 10:00:00'

                // Verificar disponibilidad en memoria (mucho más rápido que query)
                if ($occupiedSlots->has($dateStr)) {
                    // La cancha original está ocupada en este horario
                    $currentCourtId = $game->court_id;
                    
                    // Intentar buscar otra cancha libre
                    if (in_array($currentCourtId, $courts)) {
                        // Filtrar las canchas configuradas para ver cuál está libre
                        foreach ($courts as $courtId) {
                            // Si esta canca NO está en el mapa de ocupados para esta hora
                            if ($occupiedSlots->get($dateStr) != $courtId) {
                                // Verificamos específicamente si esta fecha + esta canca existe en la coleccion
                                // Nota: $occupiedSlots->has($dateStr) nos da el ID de la canca ocupada.
                                // Si es diferente, está libre.
                                
                                // Verificación doble robusta:
                                $isThisCourtBusy = false;
                                // Como nuestro map es 'fecha' => 'id_cancha', simplemente verificamos si coincide
                                if ($occupiedSlots->get($dateStr) == $courtId) {
                                    $isThisCourtBusy = true;
                                }

                                if (!$isThisCourtBusy) {
                                    // Hemos encontrado una canca libre
                                    $game->court_id = $courtId;
                                    return $potentialDateTime;
                                }
                            }
                        }
                    }
                } else {
                    // La canca original está libre
                    return $potentialDateTime;
                }
            }
            $searchDate->addDay();
        }
        return null;
    }

    public function assignReferee(Request $request, Game $game)
    {
        $this->authorize('assignReferee', $game);
        $request->validate([
            'referee_id' => 'required|exists:users,id'
        ]);
        $this->authorize('assignReferee', $game);
        $game->referee_id = $request->referee_id;
        $game->save();

        return response()->json(['success' => true, 'message' => 'Árbitro asignado correctamente.']);
    }

    /**
     * Guardar un comentario del partido
     */
    public function storeComment(Request $request, Game $game)
    {
        $this->authorize('view', $game);
        $request->validate([
            'player_id' => 'nullable|exists:players,id',
            'team_id'   => 'nullable|exists:teams,id',
            'content'   => 'required|string|max:1000',
        ]);

        $game->comments()->create([
            'user_id'    => auth()->id(),
            'player_id'  => $request->player_id,
            'team_id'    => $request->team_id,
            'content'    => $request->content
        ]);

        return response()->json(['success' => true, 'message' => 'Comentario agregado.']);
    }

    /**
     * Obtener comentarios del partido
     */
    public function getComments(Game $game)
    {
        $comments = $game->comments()->with('user', 'player.team', 'team')->get();
        return response()->json($comments);
    }

    public function autoFinishSuspended(Request $request, $gameId)
    {
        $this->authorize('update', $game);
        // --- PUNTO B: ENVOLVER EN TRANSACCIÓN ---
        DB::transaction(function () use ($gameId) {
            $game = Game::with(['localTeam', 'awayTeam'])->findOrFail($gameId);

            if ($game->status !== 'pending') {
                throw new \Exception('Este partido ya inició o terminó.');
            }

            $localTeam = $game->localTeam;
            $awayTeam = $game->awayTeam;

            $localSuspended = ($localTeam->status === 'suspended');
            $awaySuspended = ($awayTeam->status === 'suspended');

            if (!$localSuspended && !$awaySuspended) {
                throw new \Exception('Ningún equipo está suspendido.');
            }

            if ($localSuspended && $awaySuspended) {
                $game->local_team_score = 0;
                $game->away_team_score = 0;
            } elseif ($localSuspended) {
                $game->local_team_score = 0;
                $game->away_team_score = 1;
            } elseif ($awaySuspended) {
                $game->local_team_score = 1;
                $game->away_team_score = 0;
            }

            $game->status = 'finished';
            $game->save();

            $game->tournament->checkCompletionStatus();

            $this->decrementSuspensions($localTeam, $game);
            $this->decrementSuspensions($awayTeam, $game);
        });

        return response()->json(['success' => true, 'message' => 'Partido finalizado automáticamente por suspensión.']);
    }
        /**
     * Devuelve el estado actual del juego para sincronización (Polling)
     */
    public function getGameStatus(Game $game)
    {
        return response()->json([
            'localScore' => $game->local_team_score ?? 0,
            'awayScore' => $game->away_team_score ?? 0,
            'period' => $game->period ?? 1,
            'timerStatus' => $game->timer_status, // 'running', 'stopped', 'finished'
            'secondsRemaining' => $game->seconds_remaining,
            'status' => $game->status // 'live', 'finished', etc.
        ]);
    }
        /**
     * Obtiene los detalles del partido para el modal de inicio
     */
    public function getGameDetails(Game $game)
    {
        // Cargar el partido con los equipos y sus jugadores
        $game->load([
            'localTeam.players',
            'awayTeam.players'
        ]);

        return response()->json($game);
    }

    /**
     * Guarda la alineación inicial para iniciar el partido.
     * Lógica movida desde TournamentController para cumplir SRP.
     */
    public function saveStartingPlayers(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'players.local' => 'required|array|min:1|max:5',
            'players.local.*' => 'exists:players,id',
            'players.away' => 'required|array|min:1|max:5',
            'players.away.*' => 'exists:players,id',
        ]);

        $game = Game::find($request->game_id);
        $localTeamId = $game->local_team_id;
        $awayTeamId = $game->away_team_id;

        // 1. VALIDACIÓN DE SUSPENSIÓN (Local)
        $localPlayers = Player::whereIn('id', $request->input('players.local'))->where('team_id', $localTeamId)->get();
        $suspendedLocal = $localPlayers->where('status', 'suspended');
        
        if ($suspendedLocal->isNotEmpty()) {
            $names = $suspendedLocal->pluck('name')->implode(', ');
            return response()->json(['success' => false, 'message' => "Error: Los siguientes jugadores locales están suspendidos: {$names}"], 403);
        }

        // 2. VALIDACIÓN DE SUSPENSIÓN (Visitante)
        $awayPlayers = Player::whereIn('id', $request->input('players.away'))->where('team_id', $awayTeamId)->get();
        $suspendedAway = $awayPlayers->where('status', 'suspended');

        if ($suspendedAway->isNotEmpty()) {
            $names = $suspendedAway->pluck('name')->implode(', ');
            return response()->json(['success' => false, 'message' => "Error: Los siguientes jugadores visitantes están suspendidos: {$names}"], 403);
        }

        // 3. Validar que los jugadores pertenecen a los equipos correctos
        if ($localPlayers->count() !== count($request->input('players.local')) || $awayPlayers->count() !== count($request->input('players.away'))) {
            return response()->json(['success' => false, 'message' => 'Uno o más jugadores seleccionados no pertenecen al equipo correspondiente.'], 403);
        }
        
        // 4. Guardar en Base de Datos
        DB::transaction(function () use ($game, $localPlayers, $awayPlayers) {
            $game->players()->sync([]);
            $game->players()->attach($localPlayers, ['is_starter' => true, 'team_side' => 'local', 'is_active' => true]);
            $game->players()->attach($awayPlayers, ['is_starter' => true, 'team_side' => 'away', 'is_active' => true]);
            $game->status = 'playing';
            $game->save();
        });

        return response()->json([
            'success' => true, 
            'message' => 'Alineación guardada. Redirigiendo al partido...',
            'redirect_url' => route('games.live', $game->id)
        ]);    
    }

    /**
     * Suspende manualmente a un jugador por X partidos.
     * Lógica movida desde TournamentController.
     */
    public function suspendPlayer(Request $request)
    {
        
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'game_id' => 'required|exists:games,id',
            'games' => 'required|integer|min:1|max:99',
            'content' => 'nullable|string', 
        ]);

        $player = Player::find($request->player_id);
        $game = Game::find($request->game_id);
        $this->authorize('suspend', $game);

        if ($player && $game) {
            // Actualizar estatus del jugador
            $player->status = 'suspended';
            $player->suspension_games = $request->games;
            $player->save();

            // Crear comentario de la sanción
            $commentText = $request->content ?? "SANCION: Jugador suspendido por {$request->games} partido(s).";

            \App\Models\GameComment::create([
                'game_id' => $game->id,
                'user_id' => auth()->id(),
                'player_id' => $player->id,
                'content' => $commentText,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Jugador suspendido y registro guardado."
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Error en los datos.'], 404);
    }

    /**
     * Suspende manualmente a un equipo por X partidos.
     * Lógica movida desde TournamentController.
     */
    public function suspendTeam(Request $request)    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'game_id' => 'required|exists:games,id',
            'games' => 'required|integer|min:1|max:99',
            'content' => 'nullable|string',
        ]);

        $team = Team::find($request->team_id);
        $game = Game::find($request->game_id);
        $this->authorize('suspend', $game);

        if ($team && $game) {
            // Actualizar estatus del equipo
            $team->status = 'suspended';
            $team->suspension_games = $request->games;
            $team->save();

            // Crear comentario de la sanción
            $commentText = $request->content ?? "SANCION EQUIPO: Suspendido por {$request->games} partido(s).";

            \App\Models\GameComment::create([
                'game_id' => $game->id,
                'user_id' => auth()->id(),
                'player_id' => null, 
                'team_id' => $team->id, 
                'content' => $commentText,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Equipo suspendido correctamente."
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Error en los datos.'], 404);
    }

        /**
     * Deshace la última acción registrada
     */
    public function undoLastAction(Request $request)
    {
        $game = Game::findOrFail($request->game_id);
        $this->authorize('view', $game);

        // 1. Buscar la última acción
        $lastAction = \App\Models\GameAction::where('game_id', $game->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastAction) {
            return response()->json(['success' => false, 'message' => 'No hay acciones para deshacer.']);
        }

        // 2. Borrarla de la BD
        $lastAction->delete();

        // 3. Recalcular totales desde 0 usando las acciones RESTANTES
        $remainingActions = \App\Models\GameAction::where('game_id', $game->id)->get();

        // Recalcular Puntajes
        $localScore = $remainingActions->where('team_side', 'local')->where('action_type', 'point_scored')->sum('value');
        $awayScore = $remainingActions->where('team_side', 'away')->where('action_type', 'point_scored')->sum('value');

        // Actualizar Tabla Game
        $game->local_team_score = $localScore;
        $game->away_team_score = $awayScore;
        $game->save();

        // Recalcular Tiempos Muertos
        $localTimeoutsCalled = $remainingActions->where('team_side', 'local')->where('action_type', 'timeout_called')->count();
        $awayTimeoutsCalled = $remainingActions->where('team_side', 'away')->where('action_type', 'timeout_called')->count();

        // Recalcular Faltas de Equipo (Cuenta todas las faltas para simplificar)
        $localTeamFouls = $remainingActions->where('team_side', 'local')->filter(function($a) {
            return strpos($a->action_type, 'foul') !== false;
        })->count();

        $awayTeamFouls = $remainingActions->where('team_side', 'away')->filter(function($a) {
            return strpos($a->action_type, 'foul') !== false;
        })->count();

        // Recalcular Estadísticas de Jugadores
        $playerStatsResponse = [];

        foreach ($remainingActions as $action) {
            if ($action->player_id) {
                $pid = $action->player_id;
                $side = $action->side ?? $action->team_side; // Asegurar compatibilidad
                $key = "{$side}-{$pid}";

                if (!isset($playerStatsResponse[$key])) {
                    $playerStatsResponse[$key] = [
                        'points' => 0,
                        'fouls' => [
                            'personal' => 0,
                            'technical' => 0,
                            'unsportsmanlike' => 0,
                            'disqualifying' => 0
                        ]
                    ];
                }

                if ($action->action_type === 'point_scored') {
                    $playerStatsResponse[$key]['points'] += $action->value;
                } else {
                    // Sumar faltas específicas
                    $typeKey = 'personal';
                    if ($action->action_type === 'foul_technical') $typeKey = 'technical';
                    if ($action->action_type === 'foul_unsportsmanlike') $typeKey = 'unsportsmanlike';
                    if ($action->action_type === 'foul_disqualifying') $typeKey = 'disqualifying';
                    
                    if (isset($playerStatsResponse[$key]['fouls'][$typeKey])) {
                        $playerStatsResponse[$key]['fouls'][$typeKey]++;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'localScore' => $localScore,
            'awayScore' => $awayScore,
            'localTimeoutsUsed' => $localTimeoutsCalled,
            'awayTimeoutsUsed' => $awayTimeoutsCalled,
            'localTeamFouls' => $localTeamFouls,
            'awayTeamFouls' => $awayTeamFouls,
            'playerStats' => $playerStatsResponse
        ]);
    }

    /**
     * Muestra el listado de todos los partidos (Módulo Manual)
     */
    public function index()
    {
        $clientId = auth()->user()->client_id;

        // FILTRO: Solo partidos manuales (sin torneo) Y de este cliente
        $query = Game::with(['localTeam', 'awayTeam', 'court'])
            ->whereNull('tournament_id') 
            ->where('client_id', $clientId)
            ->orderBy('date_time', 'desc');

        // Si el usuario es Árbitro, solo ve los partidos donde esté asignado
        if (auth()->user()->hasRole('Arbitro')) {
            $query->where('referee_id', auth()->id());
        }

        // Si el usuario es Coach, solo ve los partidos donde su equipo participe
        if (auth()->user()->hasRole('Coach')) {
            $coachId = auth()->id();
            $query->where(function($q) use ($coachId) {
                $q->whereHas('localTeam', function($t) use ($coachId) {
                    $t->where('coach_id', $coachId);
                })->orWhereHas('awayTeam', function($t) use ($coachId) {
                    $t->where('coach_id', $coachId);
                });
            });
        }

        // Lógica de búsqueda
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->whereHas('localTeam', function($t) use ($search) {
                    $t->where('name', 'like', "%{$search}%");
                })->orWhereHas('awayTeam', function($t) use ($search) {
                    $t->where('name', 'like', "%{$search}%");
                });
            });
        }

        $games = $query->paginate(20);
        
        // CORRECCIÓN: Filtrar equipos y canchas para el select del modal
        // Si no haces esto, el usuario podría asignar un equipo de otro cliente a un partido manual
        $teams = \App\Models\Team::where('client_id', $clientId)->get();
        $courts = \App\Models\Court::where('client_id', $clientId)->get();

        return view('games.index', compact('games', 'teams', 'courts'));
    }

    /**
     * Muestra el formulario para crear un partido manual
     */
    public function create()
    {
        // Necesitamos enviar torneos, equipos y canchas al formulario
        $tournaments = \App\Models\Tournament::all(); // O filtrar por usuario
        $teams = \App\Models\Team::all(); // O filtrar por usuario
        $courts = \App\Models\Court::all();

        return view('games.create', compact('tournaments', 'teams', 'courts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'local_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:local_team_id',
            'court_id'     => 'required|exists:courts,id',
            'date_time'    => 'required|date',
            'periods'      => 'required|integer|min:1',
            'duration'     => 'required|integer|min:1',
            'timeouts'     => 'required|integer|min:0',
            'fouls'        => 'sometimes|array',
        ]);

        $settings = [
            'periods_per_game' => $request->periods,
            'game_duration'    => $request->duration,
            'timeouts_per_game' => $request->timeouts,
            'fouls_per_game'   => [
                'personal'        => $request->fouls['personal'] ?? 5,
                'technical'       => $request->fouls['technical'] ?? 2,
                'unsportsmanlike'=> $request->fouls['unsportsmanlike'] ?? 2,
                'disqualifying'  => $request->fouls['disqualifying'] ?? 1,
            ]
        ];

        $game = Game::create([
            'local_team_id'  => $request->local_team_id,
            'away_team_id'  => $request->away_team_id,
            'court_id'       => $request->court_id,
            'date_time'      => $request->date_time,
            'tournament_id'  => null, // Partido independiente
            'status'         => 'pending',
            'settings'       => $settings,
            'local_team_score' => 0,
            'away_team_score' => 0,
            'client_id'      => auth()->user()->client_id, // <--- ASIGNAR EL CLIENTE
        ]);

        return response()->json(['success' => true, 'message' => 'Partido creado correctamente.']);
    }

        public function edit(Game $game)
    {
        // Reutilizamos la lógica de create pasando el juego existente
        $tournaments = \App\Models\Tournament::all();
        $teams = \App\Models\Team::all();
        $courts = \App\Models\Court::all();

        return view('games.edit', compact('game', 'tournaments', 'teams', 'courts'));
    }

    public function update(Request $request, Game $game)
    {
        // SEGURIDAD: Verificar que el partido pertenece al cliente
        if ($game->client_id !== auth()->user()->client_id) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'local_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:local_team_id',
            'court_id'     => 'required|exists:courts,id',
            'date_time'    => 'required|date',
        ]);

        $game->update($request->all());

        return response()->json(['success' => true, 'message' => 'Partido actualizado.']);
    }

    public function destroy(Game $game)
    {
        // SEGURIDAD: Verificar que el partido pertenece al cliente
        if ($game->client_id !== auth()->user()->client_id) {
            abort(403, 'No autorizado');
        }

        $game->delete();
        return redirect()->route('games.index')->with('success', 'Partido eliminado.');
    }

}