<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtenemos todos los parámetros de búsqueda
        $search = $request->input('search');
        $category = $request->input('category'); // <--- AGREGADO
        $strength = $request->input('strength'); // <--- AGREGADO

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
        // -------------------------------------------

        // --- NUEVO: Lógica de Filtro por Categoría ---
        if ($category) {
            $teamsQuery->where('category', $category);
        }
        // -------------------------------------------

        // --- NUEVO: Lógica de Filtro por Fuerza ---
        if ($strength) {
            $teamsQuery->where('strength', $strength);
        }
        // -------------------------------------------

        // Paginamos (15 registros) y mantenemos los parámetros en la paginación
        $teams = $teamsQuery->orderBy('name')
            ->paginate(15)
            ->appends([
                'search' => $search, 
                'category' => $category, // <--- AGREGADO para que paginación 2, 3... conserve el filtro
                'strength' => $strength   // <--- AGREGADO
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

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('teams', 'public');
            $data['image_path'] = $imagePath;
        }

        // --- NUEVO: ASIGNAR CLIENTE DEL TORNEO ---
        if ($request->filled('tournament_id')) {
            $tournament = Tournament::find($request->tournament_id);
            if ($tournament) {
                $data['client_id'] = $tournament->client_id;
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => 'El torneo seleccionado no es válido.'], 422);
                }
                return back()->withErrors(['tournament_id' => 'El torneo seleccionado no es válido.']);
            }
        } else {
            $data['client_id'] = auth()->user()->client_id;
            $data['tournament_id'] = null;
        }
        // ------------------------------------------

        $nuevoEquipo = Team::create($data);

        $this->actualizarFuerzaTorneo($nuevoEquipo);

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
    if ($currentTournament && $currentTournament->status === 'finished') {
        // Si el torneo actual está terminado y el usuario asigna uno nuevo diferente
        if ($request->filled('tournament_id') && $request->tournament_id != $team->tournament_id) {
            // 1. Replicamos el equipo en el nuevo torneo
            $newTeam = $team->replicate();
            $newTeam->tournament_id = $request->tournament_id;
            
            $newTournamentObj = Tournament::find($request->tournament_id);
            if ($newTournamentObj) {
                $newTeam->client_id = $newTournamentObj->client_id;
            }
            
            $newTeam->status = 'pending';
            $newTeam->contract_accepted_at = null; // Nueva firma requerida
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('teams', 'public');
                $newTeam->image_path = $imagePath;
            }
            
            $newTeam->save();

            // 2. Replicamos los jugadores
            $players = \App\Models\Player::where('team_id', $team->id)->get();
            foreach ($players as $player) {
                $newPlayer = $player->replicate();
                $newPlayer->team_id = $newTeam->id;
                $newPlayer->save();
            }

            // Recalcular el nuevo torneo
            $nuevoTorneo = $newTeam->tournament;
            if ($nuevoTorneo) {
                $this->recalcularDatosTorneo($nuevoTorneo);
            }
        }

        // El equipo original se queda en el torneo terminado.
        // Si hay cambios en los datos (nombre, entrenador, etc.), los actualizamos,
        // pero FORZAMOS que su torneo siga siendo el terminado (no se borra ni se altera).
        $data['tournament_id'] = $team->tournament_id;
        $data['client_id'] = $team->client_id;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('teams', 'public');
            $data['image_path'] = $imagePath;
        }
        
        $team->update($data);

    } else {
        // Lógica normal para equipos que no están en torneos terminados
        if ($request->hasFile('image')) {
            if ($team->image_path) {
                Storage::disk('public')->delete($team->image_path);
            }
            $imagePath = $request->file('image')->store('teams', 'public');
            $data['image_path'] = $imagePath;
        }

        // Actualizar client_id y tournament_id
        if ($request->filled('tournament_id')) {
            $tournament = Tournament::find($request->tournament_id);
            if ($tournament) {
                $data['client_id'] = $tournament->client_id;
            }
        } else {
            $data['tournament_id'] = null;
            $data['client_id'] = auth()->user()->client_id;
        }

        // Actualizar equipo
        $team->update($data);

        // Recalcular torneos
        $nuevoTorneo = $team->fresh()->tournament;
        if ($nuevoTorneo) {
            $this->recalcularDatosTorneo($nuevoTorneo);
        }

        if ($viejoTorneoId != $nuevoTorneoId) {
            $viejoTorneo = Tournament::find($viejoTorneoId);
            if ($viejoTorneo) {
                $this->recalcularDatosTorneo($viejoTorneo);
            }
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

        return redirect()->route('teams.index')->with('message', 'Equipo eliminado exitosamente.');
    }

    public function stats(Team $team)
    {
        // Agregamos ->whereNotNull('tournament_id') para excluir partidos manuales (independientes)
        $games = \App\Models\Game::where(function($query) use ($team) {
            $query->where('local_team_id', $team->id)
                  ->orWhere('away_team_id', $team->id);
        })->where('status', 'finished')
          ->whereNotNull('tournament_id') // <--- LÍNEA CLAVE: Solo partidos con torneo
          ->with('actions')->get();

        $accumulatedStats = [];

        foreach ($games as $game) {
            $teamSide = ($game->local_team_id == $team->id) ? 'local' : 'away';

            foreach ($game->actions as $action) {
                if ($action->team_side !== $teamSide) continue;
                $playerId = $action->player_id;
                if (!$playerId) continue;

                if (!isset($accumulatedStats[$playerId])) {
                    $accumulatedStats[$playerId] = [
                        'points1' => 0, 'points2' => 0, 'points3' => 0, 'fouls' => 0
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
        
        $playerIds = array_keys($accumulatedStats);
        $players = \App\Models\Player::find($playerIds);

        $finalStats = []; 
        
        foreach ($accumulatedStats as $pid => $s) {
            $p = $players->find($pid); 
            
            $finalStats[] = [
                'id' => $pid,
                'name' => $p ? $p->name : 'Desconocido',
                'number' => $p ? $p->number : '-', 
                'stats' => $s
            ];
        }

        return response()->json($finalStats);
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