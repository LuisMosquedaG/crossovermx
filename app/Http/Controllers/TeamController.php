<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Services\TenantStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtenemos todos los parámetros de búsqueda
        $search = $request->input('search');
        $category = $request->input('category');
        $strength = $request->input('strength');
        $tournamentId = $request->input('tournament_id');

        $teamsQuery = Team::with('tournament', 'coach', 'tournament.client');

        // --- Filtrar equipos según el rol ---
        if (auth()->check() && auth()->user()->client_id) {
            $teamsQuery->where('client_id', auth()->user()->client_id);
        }

        if (auth()->user()->hasRole('Coach')) {
            // El coach solo ve su propio equipo
            $teamsQuery->where('coach_id', auth()->id());
        }
        // ------------------------------------------------

        // --- Lógica de Búsqueda de Texto ---
        if ($search) {
            $teamsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('coach', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('tournament', function($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // --- Lógica de Filtro por Categoría ---
        if ($category) {
            $teamsQuery->where('category', $category);
        }

        // --- Lógica de Filtro por Fuerza ---
        if ($strength) {
            $teamsQuery->where('strength', $strength);
        }

        // --- Lógica de Filtro por Torneo ---
        if ($tournamentId) {
            $teamsQuery->where('tournament_id', $tournamentId);
        }

        // Paginamos conservando todos los parámetros
        $teams = $teamsQuery->orderBy('name')
            ->paginate(15)
            ->appends([
                'search' => $search, 
                'category' => $category,
                'strength' => $strength,
                'tournament_id' => $tournamentId
            ]);

        // --- PREPARAR DATOS PARA EL MODAL ---
        $tournaments = Tournament::orderBy('name')->get();
        
        if (auth()->check() && auth()->user()->client_id) {
            $tournaments = $tournaments->where('client_id', auth()->user()->client_id);
        }

        $coachesQuery = \App\Models\User::whereHas('role', function($q){
            $q->where('name', 'Coach');
        });
        
        if (auth()->check() && auth()->user()->client_id) {
            $coachesQuery->where('client_id', auth()->user()->client_id);
        }
        
        $coaches = $coachesQuery->get();
        // ----------------------------------------

        // ==========================================
        // AQUÍ ESTABA EL FALTANTE O EL ERROR
        // ==========================================
        
        // 1. Obtenemos las fuerzas del cliente actual
        $strengths = \App\Models\Strength::where('client_id', auth()->user()->client_id)
            ->orderBy('name')
            ->get();

        // 2. IMPORTANTE: Pasamos 'strengths' al compact
        return view('teams.index', compact(
            'teams', 
            'tournaments', 
            'coaches', 
            'search', 
            'category', 
            'strength',  // Este es el filtro seleccionado
            'strengths'  // ESTE ES EL QUE FALTABA (La lista de opciones)
        ));

        // --- NUEVO: Cargar Fuerzas del Cliente ---
        $strengths = \App\Models\Strength::where('client_id', auth()->user()->client_id)
            ->orderBy('name')
            ->get();
        // ----------------------------------------
        
        // Pasamos todas las variables a la vista (incluyendo los nuevos filtros)
        return view('teams.index', compact('teams', 'tournaments', 'coaches', 'search', 'category', 'strength'));
    }

    /**
     * Show form for creating a new resource.
     */
    public function create()
    {
        // Obtenemos usuarios con rol Coach para el select
        $coaches = \App\Models\User::whereHas('role', function($q){
            $q->where('name', 'Coach');
        })->get();

        return view('teams.create', compact('coaches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // ... tus reglas anteriores ...
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teams')->where(function ($query) use ($request) {
                    return $query->where('tournament_id', $request->tournament_id)
                                 ->where('category', $request->category)
                                 ->where('strength', $request->strength);
                })
            ],
            'coach_name' => 'nullable|string|max:255',
            'coach_id' => 'nullable|exists:users,id',
            'tournament_id' => 'nullable|exists:tournaments,id',
            'status' => 'required|string|in:active,pending,suspended',
            'category' => 'nullable|in:Varonil,Femenil,Mixto,Infantil',
            'strength' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], 
        // AGREGA ESTE SEGUNDO ARGUMENTO:
        [
            'name.unique' => 'Ya existe un equipo con ese nombre en este torneo, categoría y fuerza.',
        ]);

        $data = $request->all();

        // --- ASIGNAR CLIENTE Y TORNEO ---
        $tournamentId = $request->input('tournament_id');
        $clientId = auth()->check() ? auth()->user()->client_id : null;
        if (!empty($tournamentId)) {
            $tournament = Tournament::find($tournamentId);
            if ($tournament) {
                $clientId = $tournament->client_id ?? $clientId;
                $data['client_id'] = $clientId;
                $data['tournament_id'] = $tournament->id;
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => 'El torneo seleccionado no es válido.'], 422);
                }
                return back()->withErrors(['tournament_id' => 'El torneo seleccionado no es válido.']);
            }
        } else {
            $data['client_id'] = $clientId;
            $data['tournament_id'] = null;
        }

        // Manejo de imagen del equipo aislada por cliente
        if ($request->hasFile('image')) {
            $targetPath = TenantStorageService::path('teams', $clientId);
            $imagePath = $request->file('image')->store($targetPath, 'public');
            $data['image_path'] = $imagePath;
        }
        // ------------------------------------------

        $nuevoEquipo = Team::create($data);

        $this->actualizarFuerzaTorneo($nuevoEquipo);

        // --- INTEGRACIÓN AUTOMÁTICA DE EQUIPO TARDÍO EN DOBLE ELIMINATORIA ---
        if ($nuevoEquipo->tournament_id && !$request->has('skip_double_elim_late')) {
            $tournament = Tournament::find($nuevoEquipo->tournament_id);
            if ($tournament && in_array($tournament->status, ['active', 'in_progress'])) {
                $settings = $tournament->settings ? $tournament->settings->settings : [];
                $tType = $settings['tournament_type'] ?? ($tournament->tournament_settings['tournament_type'] ?? null);
                $hasDE = $tournament->games()->where('is_playoff', true)->whereIn('group_name', ['WB_R1', 'LB_R1'])->exists();

                if ($tType === 'double_elimination' || $hasDE) {
                    try {
                        $cat = trim($nuevoEquipo->category ?? 'General');
                        $str = trim($nuevoEquipo->strength ?? 'General');
                        $categoryGroup = $cat . ' - ' . $str;
                        app(\App\Services\DoubleEliminationService::class)->addLateTeam($tournament, $nuevoEquipo->id, $categoryGroup);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Error al integrar equipo tardío en Doble Eliminatoria: " . $e->getMessage());
                    }
                }
            }
        }
        // -----------------------------------------------------------------------------

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Equipo creado exitosamente.',
                'team' => $nuevoEquipo
            ]);
        }

        return redirect()->route('teams.index')->with('message', 'Equipo creado exitosamente.');
    }

    /**
     * Display specified resource.
     */
    public function show(string $id)
    {
        //
    }

     public function edit(Team $team)
    {
        $tournaments = Tournament::orderBy('name')->get();

        // --- Filtro de TORNEOS ---
        if (auth()->check() && auth()->user()->client_id) {
            $tournaments = $tournaments->where('client_id', auth()->user()->client_id);
        }

        // --- Filtro de COACHES ---
        $coachesQuery = \App\Models\User::whereHas('role', function($q){
            $q->where('name', 'Coach');
        });

        if (auth()->check() && auth()->user()->client_id) {
            $coachesQuery->where('client_id', auth()->user()->client_id);
        }

        // --- IMPORTANTE: Faltaba el ->get() ---
        $coaches = $coachesQuery->get(); 
        // ----------------------------------------

        return view('teams.edit', compact('team', 'tournaments', 'coaches'));
    }
    
public function update(Request $request, Team $team)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('teams')->where(function ($query) use ($request) {
                return $query->where('tournament_id', $request->tournament_id)
                            ->where('category', $request->category)
                            ->where('strength', $request->strength);
            })->ignore($team->id)
        ],
        'coach_name' => 'nullable|string|max:255',
        'coach_id' => 'nullable|exists:users,id',
        'tournament_id' => 'nullable|exists:tournaments,id',
        'status' => 'required|string|in:active,pending,suspended',
        'category' => 'nullable|in:Varonil,Femenil,Mixto,Infantil',
        'strength' => 'nullable|string|max:100', 
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], 
    [
        'name.unique' => 'Ya existe un equipo con ese nombre en este torneo, categoría y fuerza.',
    ]);

    $viejoTorneoId = $team->tournament_id;
    $nuevoTorneoId = $request->tournament_id;

    // --- CAMBIO 1: RESPUESTA JSON PARA ERROR DE CONTRATO ---
    if ($request->status == 'active' && is_null($team->contract_accepted_at)) {
        // En lugar de redirect(), devolvemos un error JSON (código 400)
        return response()->json([
            'message' => 'No se puede activar el equipo. El entrenador debe aceptar el contrato primero.'
        ], 400);
    }
    // -----------------------------------------------------

    $data = $request->all();
    unset($data['image']); // Eliminar de data para evitar errores en update

    $currentTournament = $team->tournament;

    // Regla 3.2: Los equipos solo pueden estar activos en un torneo a la vez.
    // Si el torneo actual no ha finalizado, no se puede cambiar a otro torneo.
    if ($request->filled('tournament_id') && $request->tournament_id != $team->tournament_id) {
        if ($currentTournament && in_array($currentTournament->status, ['active', 'in_progress', 'paused'])) {
            return response()->json([
                'message' => 'El equipo no puede cambiar de torneo mientras su torneo actual esté activo. Debe finalizar el torneo primero para liberarlo.'
            ], 400);
        }

        // Si el equipo se transfiere a un nuevo torneo (estando liberado),
        // reinicia su estatus para requerir nueva aceptación de contrato en el nuevo torneo (Regla 3.1 y 3.2)
        $data['status'] = 'pending';
        $data['contract_accepted_at'] = null;
        $data['suspension_games'] = 0;
    }

    // Actualizar client_id y tournament_id
    $clientId = $team->client_id ?? (auth()->check() ? auth()->user()->client_id : null);
    if ($request->filled('tournament_id')) {
        $tournament = Tournament::find($request->tournament_id);
        if ($tournament) {
            $data['client_id'] = $tournament->client_id;
            $clientId = $tournament->client_id ?? $clientId;
        }
    } else {
        $data['tournament_id'] = null;
        $data['client_id'] = auth()->user()->client_id ?? $team->client_id;
        $clientId = $data['client_id'] ?? $clientId;
    }

    // Manejo de imagen del equipo aislada por cliente
    if ($request->hasFile('image')) {
        if ($team->image_path && Storage::disk('public')->exists($team->image_path)) {
            Storage::disk('public')->delete($team->image_path);
        }
        $targetPath = TenantStorageService::path('teams', $clientId);
        $imagePath = $request->file('image')->store($targetPath, 'public');
        $data['image_path'] = $imagePath;
    }

    // Actualizar equipo sin duplicar registros de equipos ni jugadores
    $team->update($data);

    // Recalcular torneos
    $nuevoTorneo = $team->fresh()->tournament;
    if ($nuevoTorneo) {
        $this->recalcularDatosTorneo($nuevoTorneo);
    }

    if ($viejoTorneoId && $viejoTorneoId != $nuevoTorneoId) {
        $viejoTorneo = Tournament::find($viejoTorneoId);
        if ($viejoTorneo) {
            $this->recalcularDatosTorneo($viejoTorneo);
        }
    }

    // --- CAMBIO 2: RESPUESTA JSON PARA ÉXITO ---
    // En lugar de redirect(), devolvemos éxito JSON
    return response()->json([
        'success' => true,
        'message' => 'Equipo actualizado exitosamente.'
    ]);
    // -------------------------------------------
}

    public function destroy(Team $team)
    {
        // 1. Guardamos el ID del torneo ANTES de borrar el equipo
        $torneoId = $team->tournament_id;
        
        // 2. Borramos el equipo
        $team->delete();

        // 3. Recalculamos los datos del torneo que quedó vacío
        if ($torneoId) {
            $torneo = Tournament::find($torneoId);
            if ($torneo) {
                $this->recalcularDatosTorneo($torneo);
            }
        }

        return redirect()->back()->with('message', 'Equipo eliminado exitosamente.');
    }

    public function stats(Request $request, Team $team)
    {
        // 1. Obtener todos los torneos en los que ha jugado el equipo o que tiene asignado
        $tournamentIdsFromGames = \App\Models\Game::where(function($query) use ($team) {
            $query->where('local_team_id', $team->id)
                  ->orWhere('away_team_id', $team->id);
        })->whereNotNull('tournament_id')->pluck('tournament_id');

        $allTournamentIds = $tournamentIdsFromGames;
        if ($team->tournament_id) {
            $allTournamentIds = $allTournamentIds->push($team->tournament_id);
        }
        $allTournamentIds = $allTournamentIds->unique()->filter()->values();

        $tournaments = \App\Models\Tournament::whereIn('id', $allTournamentIds)
            ->orderByDesc('id')
            ->get();

        // 2. Determinar el torneo activo por defecto (Regla 4)
        $activeTournament = null;
        if ($team->tournament && in_array($team->tournament->status, ['active', 'in_progress', 'pending'])) {
            $activeTournament = $team->tournament;
        } else {
            $activeTournament = $tournaments->firstWhere(fn($t) => in_array($t->status, ['active', 'in_progress']));
            if (!$activeTournament && $team->tournament) {
                $activeTournament = $team->tournament;
            } elseif (!$activeTournament && $tournaments->isNotEmpty()) {
                $activeTournament = $tournaments->first();
            }
        }

        // 3. Determinar torneo seleccionado
        $requestedTournamentId = $request->query('tournament_id');
        if ($requestedTournamentId !== null) {
            if ($requestedTournamentId === 'all' || $requestedTournamentId === '') {
                $selectedTournamentId = 'all';
            } else {
                $selectedTournamentId = (int)$requestedTournamentId;
            }
        } else {
            if ($activeTournament) {
                $selectedTournamentId = $activeTournament->id;
            } elseif ($tournaments->isNotEmpty()) {
                $selectedTournamentId = $tournaments->first()->id;
            } else {
                $selectedTournamentId = 'all';
            }
        }

        // 4. Filtrar partidos terminados según el torneo seleccionado
        $gamesQuery = \App\Models\Game::where(function($query) use ($team) {
            $query->where('local_team_id', $team->id)
                  ->orWhere('away_team_id', $team->id);
        })->where('status', 'finished');

        if ($selectedTournamentId !== 'all' && $selectedTournamentId !== null) {
            $gamesQuery->where('tournament_id', $selectedTournamentId);
        }

        $games = $gamesQuery->with('actions')->get();

        // 5. Acumular estadísticas por jugador
        $accumulatedStats = [];

        foreach ($games as $game) {
            $teamSide = ($game->local_team_id == $team->id) ? 'local' : 'away';

            foreach ($game->actions as $action) {
                if ($action->team_side !== $teamSide) continue;
                $playerId = $action->player_id;
                if (!$playerId) continue;

                if (!isset($accumulatedStats[$playerId])) {
                    $accumulatedStats[$playerId] = [
                        'points1' => 0, 'points2' => 0, 'points3' => 0, 'total_points' => 0, 'fouls' => 0
                    ];
                }

                if ($action->action_type == 'point_scored') {
                    if ($action->value == 1) $accumulatedStats[$playerId]['points1']++;
                    if ($action->value == 2) $accumulatedStats[$playerId]['points2']++;
                    if ($action->value == 3) $accumulatedStats[$playerId]['points3']++;
                }
                if (strpos($action->action_type, 'foul') !== false) {
                    $accumulatedStats[$playerId]['fouls']++;
                }
            }
        }

        foreach ($accumulatedStats as $pid => &$s) {
            $s['total_points'] = ($s['points1'] * 1) + ($s['points2'] * 2) + ($s['points3'] * 3);
        }
        unset($s);

        // 6. Obtener jugadores del equipo + jugadores históricos que tengan acciones
        $hasAnyStats = !empty($accumulatedStats);
        $finalStats = [];

        $rosterPlayers = \App\Models\Player::where('team_id', $team->id)->get();
        $historicalPlayerIds = array_diff(array_keys($accumulatedStats), $rosterPlayers->pluck('id')->toArray());
        $historicalPlayers = !empty($historicalPlayerIds) ? \App\Models\Player::whereIn('id', $historicalPlayerIds)->get() : collect();
        $allPlayers = $rosterPlayers->concat($historicalPlayers);

        foreach ($allPlayers as $player) {
            $s = $accumulatedStats[$player->id] ?? [
                'points1' => 0, 'points2' => 0, 'points3' => 0, 'total_points' => 0, 'fouls' => 0
            ];

            if ($hasAnyStats || ($s['total_points'] > 0 || $s['fouls'] > 0)) {
                $finalStats[] = [
                    'id' => $player->id,
                    'name' => $player->name,
                    'number' => $player->number ?? '-',
                    'stats' => $s
                ];
            }
        }

        usort($finalStats, function($a, $b) {
            if ($b['stats']['total_points'] !== $a['stats']['total_points']) {
                return $b['stats']['total_points'] <=> $a['stats']['total_points'];
            }
            return strcmp($a['name'], $b['name']);
        });

        $statusLabels = [
            'pending' => 'Pendiente',
            'active' => 'Activo',
            'in_progress' => 'Activo',
            'finished' => 'Terminado',
            'paused' => 'Pausado',
            'cancelled' => 'Cancelado',
        ];

        $tournamentsData = $tournaments->map(function($t) use ($activeTournament, $statusLabels) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'status' => $t->status,
                'status_label' => $statusLabels[$t->status] ?? ucfirst($t->status),
                'is_active' => in_array($t->status, ['active', 'in_progress']),
                'is_default' => $activeTournament && $activeTournament->id == $t->id,
            ];
        })->values();

        return response()->json([
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
                'current_tournament_id' => $team->tournament_id,
            ],
            'tournaments' => $tournamentsData,
            'selected_tournament_id' => $selectedTournamentId,
            'has_activity' => $hasAnyStats,
            'stats' => $finalStats
        ]);
    }
    /**
     * NUEVO: Método para que el Coach acepte el contrato
     */
    public function acceptContract(Team $team)
    {
        if ($team->coach_id != auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        // LÓGICA NUEVA: Al aceptar, el equipo pasa a "active" automáticamente
        $team->contract_accepted_at = now();
        $team->status = 'active'; 
        $team->save();

        return response()->json([
            'success' => true,
            'message' => '¡Contrato firmado y equipo activado!'
        ]);
    }
        /**
     * Actualiza la fuerza del torneo basándose en los equipos inscritos.
     * - Si todos los equipos tienen la misma fuerza, coloca esa.
     * - Si hay 2 o más fuerzas diferentes, coloca "Varios".
     */
        /**
     * Actualiza la FUERZA y CATEGORÍA del torneo basándose en los equipos inscritos.
     * Lógica:
     * - Si todos los datos son iguales, coloca ese dato.
     * - Si hay 2 o más datos diferentes, coloca "Varios".
     * - Si no hay datos, coloca null.
     */
    private function actualizarFuerzaTorneo(Team $team)
    {
        $tournament = $team->tournament;

        if (!$tournament) {
            return;
        }

        // --- 1. Lógica para FUERZA ---
        $fuerzas = $tournament->teams()->pluck('strength')->filter()->unique();

        if ($fuerzas->isEmpty()) {
            $tournament->fuerza = null;
        } elseif ($fuerzas->count() > 1) {
            $tournament->fuerza = 'Varios';
        } else {
            $tournament->fuerza = $fuerzas->first();
        }

        // --- 2. Lógica para CATEGORÍA (NUEVO) ---
        $categorias = $tournament->teams()->pluck('category')->filter()->unique();

        if ($categorias->isEmpty()) {
            $tournament->category = null;
        } elseif ($categorias->count() > 1) {
            // Si hay mezcla de géneros (ej: Varonil y Femenil), marcamos "Varios"
            $tournament->category = 'Varios';
        } else {
            // Si todos son iguales, hereda la categoría
            $tournament->category = $categorias->first();
        }

        $tournament->save();
    }
    /**
    * Función auxiliar para recalcular categoría y fuerza.
    */
    private function recalcularDatosTorneo(Tournament $tournament)
    {
        // Cargamos los equipos
        $teams = $tournament->teams; 

        // --- 1. Lógica para FUERZA ---
        $fuerzas = $teams->pluck('strength')->filter()->unique();

        if ($fuerzas->isEmpty()) {
            $tournament->fuerza = null; // Si está vacío, es NULL
        } elseif ($fuerzas->count() > 1) {
            $tournament->fuerza = 'Varios';
        } else {
            $tournament->fuerza = $fuerzas->first();
        }

        // --- 2. Lógica para CATEGORÍA ---
        $categorias = $teams->pluck('category')->filter()->unique();

        if ($categorias->isEmpty()) {
            $tournament->category = null; // Si está vacío, es NULL
        } elseif ($categorias->count() > 1) {
            $tournament->category = 'Varios';
        } else {
            $tournament->category = $categorias->first();
        }

        $tournament->save();
    }
    /**
     * Obtiene los horarios de un equipo en formato JSON.
     */
    public function getSchedules(Team $team)
    {
        return response()->json($team->schedules);
    }

    /**
     * Actualiza los horarios de un equipo.
     */
    /**
     * Actualiza los horarios de un equipo.
     */
    public function updateSchedules(Request $request, Team $team)
    {
        // --- CORRECCIÓN PARA EVITAR ERROR DE VALIDACIÓN ---
        // Si el input viene vacío o como string vacío "", forzamos que sea un array vacío []
        if (!$request->has('schedules') || $request->input('schedules') === "") {
            $request->merge(['schedules' => []]);
        }
        // -----------------------------------------------------

        $request->validate([
            'schedules' => 'sometimes|array', // Usamos 'sometimes' para que sea más permisivo si falta la llave
            'schedules.*.day' => 'required|integer|min:0|max:6',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        // Eliminamos horarios anteriores (esto limpia todo si schedules está vacío)
        $team->schedules()->delete();

        // Creamos los nuevos solo si el array no está vacío
        if (!empty($request->schedules)) {
            foreach ($request->schedules as $schedule) {
                $team->schedules()->create([
                    'day_of_week' => $schedule['day'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]);
            }
        }

        return response()->json(['message' => 'Disponibilidad del equipo actualizada exitosamente.']);
    }
    /**
     * Guarda una nueva fuerza para el cliente actual (AJAX).
     */
    public function storeStrength(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:strengths,name,NULL,id,client_id,' . auth()->user()->client_id,
        ]);

        $strength = \App\Models\Strength::create([
            'name' => $request->name,
            'client_id' => auth()->user()->client_id,
        ]);

        return response()->json([
            'success' => true,
            'strength' => $strength
        ]);
    }
        /**
     * Devuelve los jugadores de un equipo en formato JSON para el modal.
     */
    public function getPlayersByTeamJson(Team $team)
    {
        // Usamos 'get()' para traer TODOS los campos (incluyendo image_path y gender)
        // Usamos 'with' para traer también los datos del Equipo y Torneo (necesarios para la credencial)
        return response()->json($team->players()->orderBy('name')->with(['team.tournament'])->get());
    }
}