<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Court;
use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use App\Models\TournamentSetting;
use App\Services\CalendarGeneratorService;
use App\Services\DoubleEliminationService;
use App\Strategies\Tournament\TournamentGenerationStrategyInterface;
use App\Strategies\Tournament\LeagueStrategy;
use App\Strategies\Tournament\EliminationStrategy;
use App\Strategies\Tournament\DoubleEliminationStrategy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TournamentController extends Controller
{
    protected $calendarService;
    protected $doubleElimService;

    public function __construct(CalendarGeneratorService $calendarService, DoubleEliminationService $doubleElimService)
    {
        $this->calendarService = $calendarService;
        $this->doubleElimService = $doubleElimService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $tournamentType = $request->input('tournament_type');
        $category = $request->input('category');
        $fuerza = $request->input('fuerza');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $tournamentsQuery = Tournament::query();

        // --- NUEVA LÓGICA: FILTRO POR CLIENTE ---
        if (auth()->check() && auth()->user()->client_id) {
            $tournamentsQuery->where('client_id', auth()->user()->client_id);
        }

        // --- LÓGICA ÁRBITRO ---
        if (auth()->user()->hasRole('Arbitro')) {
            $tournamentIds = Game::where('referee_id', auth()->id())->pluck('tournament_id')->unique();
            $tournamentsQuery->whereIn('id', $tournamentIds);
        }
        
        // --- LÓGICA COACH ---
        if (auth()->user()->hasRole('Coach')) {
            $tournamentIds = Team::where('coach_id', auth()->id())->pluck('tournament_id')->unique();
            $tournamentsQuery->whereIn('id', $tournamentIds);
        }

        // --- BÚSQUEDA GENERAL DE TEXTO ---
        if ($search) {
            $tournamentsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('fuerza', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('start_date', 'like', "%{$search}%")
                  ->orWhere('end_date', 'like', "%{$search}%");
            });
        }

        // --- FILTRO POR TIPO DE TORNEO ---
        if ($tournamentType) {
            $tournamentsQuery->whereHas('settings', function($q) use ($tournamentType) {
                $q->where('settings->tournament_type', $tournamentType);
            });
        }

        // --- FILTRO POR CATEGORÍA ---
        if ($category) {
            $tournamentsQuery->where('category', $category);
        }

        // --- FILTRO POR FUERZA ---
        if ($fuerza) {
            $tournamentsQuery->where('fuerza', $fuerza);
        }

        // --- FILTRO POR ESTADO ---
        if ($status) {
            if ($status === 'active') {
                $tournamentsQuery->whereIn('status', ['active', 'in_progress']);
            } else {
                $tournamentsQuery->where('status', $status);
            }
        }

        // --- FILTRO POR FECHA INICIO ---
        if ($startDate) {
            $tournamentsQuery->whereDate('start_date', '>=', $startDate);
        }

        // --- FILTRO POR FECHA FIN ---
        if ($endDate) {
            $tournamentsQuery->whereDate('end_date', '<=', $endDate);
        }

        // Paginamos conservando todos los parámetros de filtrado
        $tournaments = $tournamentsQuery->orderBy('name')
            ->paginate(15)
            ->appends($request->only(['search', 'tournament_type', 'category', 'fuerza', 'status', 'start_date', 'end_date']));
        
         // --- NUEVO: FILTRAR CANCHAS PARA EL MODAL DE CALENDARIO ---
        $courtsQuery = Court::orderBy('name');
        
        // Si el usuario tiene un cliente asignado, filtrar por él
        if (auth()->check() && auth()->user()->client_id) {
            $courtsQuery->where('client_id', auth()->user()->client_id);
        }
        
        $courts = $courtsQuery->get();
        // ------------------------------------------------------------

        // --- NUEVO: OBTENER ENTRENADORES ---
        $coachesQuery = \App\Models\User::whereHas('role', function($q){
            $q->where('name', 'Coach');
        });
        if (auth()->check() && auth()->user()->client_id) {
            $coachesQuery->where('client_id', auth()->user()->client_id);
        }
        $coaches = $coachesQuery->get();
        // -----------------------------------

        // --- NUEVO: OBTENER FUERZAS ---
        $strengths = \App\Models\Strength::where('client_id', auth()->user()->client_id ?? null)
            ->orderBy('name')
            ->get();
        // ------------------------------

        return view('tournaments.index', compact('tournaments', 'courts', 'coaches', 'strengths'));
    }

    /**
    * Show the form for creating a new resource.
    */
    public function create()
        {
            // Simplemente devuelve la vista del formulario que acabamos de crear
            return view('tournaments.create');
        }

    // Archivo: app/Http/Controllers/TournamentController.php

public function store(Request $request)
{
    // 1. Validar datos
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'location' => 'nullable|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // 2. Preparar datos
    $data = $request->all();
    $data['status'] = 'pending'; 

    // --- CORRECCIÓN AQUÍ ---
    // Forzamos que estos campos sean null al crear
    $data['category'] = null;
    $data['fuerza'] = null;
    // -----------------------

    // --- NUEVO: ASIGNAR CLIENTE AUTOMÁTICAMENTE ---
    $data['client_id'] = auth()->user()->client_id;
    // -------------------------------------------

    // 3. Procesar Logo
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('tournaments', 'public');
        $data['logo_path'] = $logoPath;
    }

    // 4. Crear
    Tournament::create($data);

    return redirect()->back()->with('message', 'Torneo creado exitosamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
        {
            //
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tournament $tournament)
        {
            $this->authorize('update', $tournament);
            return view('tournaments.edit', compact('tournament'));
        }

    public function update(Request $request, Tournament $tournament)
    {
        $this->authorize('update', $tournament);    
        // 1. Validar (Eliminamos is_active)
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|in:varonil,femenil,mixto,infantil',
            'fuerza' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Preparar datos
        $data = $request->all();

        // 3. Procesar Logo
        if ($request->hasFile('logo')) {
            if ($tournament->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($tournament->logo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tournament->logo_path);
            }
            $logoPath = $request->file('logo')->store('tournaments', 'public');
            $data['logo_path'] = $logoPath;
        }

        // 4. Actualizar
        $tournament->update($data);

        return redirect()->back()->with('message', 'Torneo actualizado exitosamente.');
    }

    public function destroy(Tournament $tournament)
        {
            $this->authorize('delete', $tournament); // <--- AGREGAR
            if ($tournament->teams()->exists()) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el torneo porque tiene equipos asignados.');
            }

            // Si no tiene equipos, procedemos a eliminar
            // Nota: Si tienes 'onDelete cascade' en tu base de datos, esto borrará automáticamente
            // los equipos y jugadores, pero al no entrar aquí si hay equipos, estamos seguros.
            $tournament->delete();

            return redirect()->back()
                ->with('message', 'Torneo eliminado exitosamente.');
        }

    public function getTeamsByTournamentJson(Tournament $tournament)
    {
        if (auth()->user()->client_id && $tournament->client_id !== auth()->user()->client_id) {
            abort(403, 'Acción no autorizada.');
        }

        if (auth()->user()->hasRole('Arbitro')) {
            $hasRefereed = Game::where('tournament_id', $tournament->id)->where('referee_id', auth()->id())->exists();
            if (!$hasRefereed) {
                abort(403, 'Acción no autorizada.');
            }
        }

        if (auth()->user()->hasRole('Coach')) {
            $hasTeam = Team::where('tournament_id', $tournament->id)->where('coach_id', auth()->id())->exists();
            if (!$hasTeam) {
                abort(403, 'Acción no autorizada.');
            }
        }

        $teams = $tournament->teams()->get();
        return response()->json($teams);
    }
    
    public function generateCalendar(Request $request, CalendarGeneratorService $calendarService)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'tournament_type' => 'required|string',
            // Eliminadas validaciones de group_a_name y group_b_name
            'days' => 'required|array',
            'days.*' => 'integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'rest_rules' => 'nullable|array', 
            'rest_rules.*' => 'string',
            'periods_per_game' => 'required|integer|min:1',
            'game_duration' => 'required|integer|min:1',
            'rest_between_periods' => 'required|integer|min:0',
            'rest_between_games' => 'required|integer|min:0',
            'courts' => 'required|array|exists:courts,id',
            'timeouts_per_game' => 'required|integer|min:0',
            'limit_foul_personal' => 'required|integer|min:1',
            'limit_foul_technical' => 'required|integer|min:1',
            'limit_foul_unsportsmanlike' => 'required|integer|min:1',
            'limit_foul_disqualifying' => 'required|integer|min:1',
            'interleave_categories' => 'nullable|boolean',
        ]);

        // Seguridad de Canchas
        $requestedCourts = $request->input('courts', []);
        if (!empty($requestedCourts) && auth()->user()->client_id) {
            $validCourts = Court::where('client_id', auth()->user()->client_id)
                                ->whereIn('id', $requestedCourts)
                                ->count();
            if ($validCourts !== count($requestedCourts)) {
                return response()->json(['success' => false, 'message' => 'Error: Estás intentando generar calendario en canchas no autorizadas.'], 403);
            }
        }

        $tournament = Tournament::findOrFail($request->tournament_id);
        $this->authorize('update', $tournament);

        $config = $request->all();
        $config['rest_rules'] = $config['rest_rules'] ?? []; 
        $config['days'] = $config['days'] ?? [0, 1, 2, 3, 4, 5, 6]; 
        $config['start_date'] = $tournament->start_date;
        $config['end_date'] = $tournament->end_date;
        $config['interleave_categories'] = isset($config['interleave_categories']) ? (bool)$config['interleave_categories'] : true;

        try {
            DB::transaction(function () use ($tournament, $config, $calendarService) {
                $tournament->games()->delete();
                $tournament->settings()->delete();

                // --- NUEVA VARIABLE PARA ACUMULAR JUEGOS ---
                $cumulativeGames = collect(); 

                // --- NUEVA LÓGICA DE ESTRATEGIAS ---
                $strategy = $this->getStrategy($config['tournament_type']);
                
                // Ejecutamos la generación
                $result = $strategy->generate($tournament, $config, $cumulativeGames);
                
                // Actualizamos variables con el resultado de la estrategia
                $cumulativeGames = $result['games'];
                $config = $result['config'];
                // -----------------------------------

                TournamentSetting::create([
                    'tournament_id' => $tournament->id,
                    'settings' => $config,
                ]);

                $tournament->status = 'active';
                $tournament->save();

                $totalGames = $tournament->games()->count();
                if ($totalGames === 0) {
                    throw new \Exception("Error crítico: El proceso finalizó pero no se crearon partidos en la base de datos. Verifica que hay fechas disponibles y suficientes equipos.");
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Calendario generado exitosamente.',
                'redirect_url' => route('tournaments.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getStrategy(string $type): TournamentGenerationStrategyInterface
    {
        return match($type) {
            'elimination' => new EliminationStrategy($this->calendarService),
            'double_elimination' => new DoubleEliminationStrategy($this->doubleElimService, $this->calendarService),
            default => new LeagueStrategy($this->calendarService),
        };
    }

    public function getCalendarSettings(Tournament $tournament)
    {
        if (!$tournament->settings) {
            return response()->json(['message' => 'No se encontró configuración para este torneo.'], 404);
        }
        $settings = $tournament->settings->settings;
        $settings['location'] = $tournament->location;
        return response()->json($settings);
    }

    public function deleteCalendar(Tournament $tournament)
    {
        $this->authorize('update', $tournament);
        DB::transaction(function () use ($tournament) {
            // Eliminar juegos y configuración
            $tournament->games()->delete();
            $tournament->settings()->delete();

            // --- NUEVO: REGRESAR EL ESTADO A PENDIENTE ---
            $tournament->status = 'pending';
            $tournament->is_playoffs = false;
            $tournament->save();
            // -----------------------------------------------

        });

        return response()->json([
            'success' => true,
            'message' => 'Calendario eliminado exitosamente. El torneo ahora está pendiente.',
            'redirect_url' => route('tournaments.index')
        ]);
    }
    
    public function showSchedule(Request $request, Tournament $tournament)
    {
        $this->authorize('view', $tournament);
        
        // Base de la consulta con relaciones eager loading
        $gamesQuery = $tournament->games()->with(['localTeam', 'awayTeam', 'court', 'referee']);

        // --- LÓGICA ÁRBITRO ---
        if (auth()->user()->hasRole('Arbitro')) {
            $gamesQuery->where('referee_id', auth()->id());
        }

        // --- LÓGICA COACH ---
        if (auth()->user()->hasRole('Coach')) {
            $gamesQuery->where(function($q) {
                $q->whereHas('localTeam', fn($subQ) => $subQ->where('coach_id', auth()->id()))
                ->orWhereHas('awayTeam', fn($subQ) => $subQ->where('coach_id', auth()->id()));
            });
        }

        // --- FILTRO: TEXTO LIBRE ---
        $search = $request->input('search');
        if ($search) {
            $gamesQuery->where(function($q) use ($search) {
                $q->whereHas('localTeam', fn($t) => $t->where('name', 'like', "%{$search}%"))
                ->orWhereHas('awayTeam', fn($t) => $t->where('name', 'like', "%{$search}%"))
                ->orWhereHas('court', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        // --- FILTROS (CATEGORÍA, FUERZA, GRUPO) ---
        if ($request->filled('category')) {
            $gamesQuery->where(function($q) use ($request) {
                $q->whereHas('localTeam', fn($t) => $t->where('category', $request->category))
                ->orWhereHas('awayTeam', fn($t) => $t->where('category', $request->category));
            });
        }

        if ($request->filled('strength')) {
            $gamesQuery->where(function($q) use ($request) {
                $q->whereHas('localTeam', fn($t) => $t->where('strength', $request->strength))
                ->orWhereHas('awayTeam', fn($t) => $t->where('strength', $request->strength));
            });
        }

        if ($request->filled('group')) {
            $groupValue = $request->group;
            $gamesQuery->where(function ($query) use ($groupValue) {
                $query->where('group_name', $groupValue)
                      ->orWhere('category_group', $groupValue);
            });
        }

        // --- FECHAS ---
        if ($request->filled('start_date')) {
            $gamesQuery->whereDate('date_time', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $gamesQuery->whereDate('date_time', '<=', $request->input('end_date'));
        }

        // --- PREPARAR DATOS PARA LOS SELECTS ---
        $groups = Game::where('tournament_id', $tournament->id)
            ->whereNotNull('group_name')
            ->distinct()
            ->pluck('group_name')
            ->sort()
            ->values();

        $categories = Team::where('tournament_id', $tournament->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        $strengths = Team::where('tournament_id', $tournament->id)
            ->whereNotNull('strength')
            ->distinct()
            ->pluck('strength')
            ->sort()
            ->values();

        $games = $gamesQuery->orderBy('date_time')->paginate(15)->appends($request->all());
        
        // --- AQUÍ ESTÁ LA CLAVE: PREPARAR EQUIPOS CON EL GROUP_NAME CORRECTO ---
        $teamsCollection = $tournament->teams()->orderBy('name')->get();
        
        // 1. Obtenemos todos los juegos del torneo para crear un mapa ID -> Grupo
        // Esto asume que un equipo juega siempre en el mismo grupo en este torneo
        $tournamentGames = Game::where('tournament_id', $tournament->id)
            ->select('local_team_id', 'away_team_id', 'group_name')
            ->get();

                    // Ejecutamos una consulta rápida para saber qué equipos ya tienen estatus != pending
        $playedTeamIds = Game::where('tournament_id', $tournament->id)
            ->where('status', '!=', 'pending') // playing, finished, cancelled, etc.
            ->select('local_team_id', 'away_team_id') 
            ->get()
            ->map(function($g) { return [$g->local_team_id, $g->away_team_id]; })
            ->flatten()
            ->unique()
            ->values();
        // --------------------------------------------------

        $teamGroupMap = [];
        foreach ($tournamentGames as $g) {
            if ($g->group_name) {
                // Asignamos el grupo al equipo local si no lo tiene
                if (!isset($teamGroupMap[$g->local_team_id])) {
                    $teamGroupMap[$g->local_team_id] = $g->group_name;
                }
                // Asignamos el grupo al equipo visitante si no lo tiene
                if (!isset($teamGroupMap[$g->away_team_id])) {
                    $teamGroupMap[$g->away_team_id] = $g->group_name;
                }
            }
        }

        // 2. Mapeamos la colección de equipos para inyectar el group_name
        $teams = $teamsCollection->map(function ($team) use ($teamGroupMap, $playedTeamIds) {
            // Prioridad 1: Usar el grupo encontrado en los partidos (Mapa)
            $team->group_name = $teamGroupMap[$team->id] ?? null;

            // Prioridad 2: Si es null (no jugó partidos), construir desde la tabla teams
            if (empty($team->group_name)) {
                $cat = $team->category ?? 'General';
                $str = $team->strength ?? 'General';
                $team->group_name = trim($cat . ' - ' . $str);
            }
            // --- NUEVO: Marcar si el equipo ya jugó ---
            // Si el ID del equipo está en la lista de 'played', marcamos true
            $team->has_played = $playedTeamIds->contains($team->id);
            // -------------------------------------------

            return $team;
        });
        // ----------------------------------------------------------------------

        return view('tournaments.schedule', compact('games', 'tournament', 'groups', 'categories', 'strengths', 'teams'));
    }
   
    /**
     * Muestra la tabla de posiciones agrupada (Solo lectura, sin auto-generación).
     */
    public function showStandings(Tournament $tournament)
    {
        $this->authorize('view', $tournament);
        
        // 1. Cargar Configuración y Detectar Tipo
        $settings = $tournament->settings ? $tournament->settings->settings : [];
        $tournamentType = $settings['tournament_type'] ?? 'round_robin';
        
        // Eliminada variable $isGroups

        $groupsStructure = $settings['groups_structure'] ?? null;

        // Agrupamiento estándar para vista de tabla
        $groups = $tournament->teams->groupBy(function ($item) {
            $cat = $item->category ?? 'Sin Categoria';
            $strength = $item->strength ?? 'General';
            return $cat . ' - ' . $strength;
        });

        // Filtro Coach
        if (auth()->user()->hasRole('Coach')) {
             $myTeams = $tournament->teams->where('coach_id', auth()->id());
             if ($myTeams->isNotEmpty()) {
                $allowedGroupKeys = $myTeams->map(function ($team) {
                    $cat = $team->category ?? 'Sin Categoria';
                    $strength = $team->strength ?? 'General';
                    return $cat . ' - ' . $strength;
                })->unique();
                $groups = $groups->filter(function ($value, $key) use ($allowedGroupKeys) {
                    return $allowedGroupKeys->contains($key);
                });
             } else { $groups = collect(); }
        }

        $standingsData = [];

        // --- NUEVA LÓGICA DINÁMICA: DOBLE ELIMINATORIA (V2) ---
        $hasDoubleElimGames = $tournament->games()
            ->where('is_playoff', true)
            ->whereIn('group_name', ['WB_R1', 'LB_R1', 'GF']) // Solo verificamos semillas clave para detectar el modo
            ->exists();

        if ($tournamentType === 'double_elimination' || $hasDoubleElimGames) {
            if ($hasDoubleElimGames && $tournamentType !== 'double_elimination') {
                $tournamentType = 'double_elimination';
            }

            $doubleElimGroups = Game::where('tournament_id', $tournament->id)
                ->where('is_playoff', true)
                ->whereNotNull('category_group')
                ->distinct()
                ->pluck('category_group');

            $standingsData = [];

            foreach ($doubleElimGroups as $groupName) {
                // Obtenemos todos los juegos de esta categoría
                $allCategoryGames = $tournament->games()
                    ->where('is_playoff', true)
                    ->where('category_group', $groupName)
                    ->with(['localTeam', 'awayTeam'])
                    ->orderBy('created_at') // Importante para el orden de creación
                    ->get();

                $wbRounds = [];
                $lbRounds = [];
                $grandFinal = null;
                $resetGame = null;

                // Clasificamos los juegos en arrays por ronda
                foreach ($allCategoryGames as $game) {
                    if (strpos($game->group_name, 'WB_R') === 0) {
                        // Extrae el número de Ronda (ej: WB_R3 -> 3)
                        $roundNum = (int) str_replace('WB_R', '', $game->group_name);
                        $wbRounds[$roundNum][] = $game;
                    } elseif (strpos($game->group_name, 'LB_R') === 0) {
                        $roundNum = (int) str_replace('LB_R', '', $game->group_name);
                        $lbRounds[$roundNum][] = $game;
                    } elseif ($game->group_name === 'GF') {
                        $grandFinal = $game;
                    } elseif ($game->group_name === 'GR') {
                        $resetGame = $game;
                    }
                }

                // Ordenamos las rondas por clave numérica
                ksort($wbRounds);
                ksort($lbRounds);

                $standingsData[$groupName] = [
                    'mode' => 'double_elimination_grouped',
                    'bracket' => [
                        'winner_bracket' => array_values($wbRounds), // Re-indexa array para que sea 0, 1, 2...
                        'loser_bracket'  => array_values($lbRounds),
                        'grand_final'    => $grandFinal,
                        'reset_game'     => $resetGame
                    ]
                ];
            }
        }
        // -----------------------------------------

        // --- LÓGICA ESTÁNDAR (LIGA O ELIMINACIÓN SIMPLE) ---
        else {
            foreach ($groups as $groupName => $teamsInGroup) {
                $teamIdsInGroup = $teamsInGroup->pluck('id')->toArray();
                $isElimination = ($tournamentType === 'elimination');

                $allGroupGames = $tournament->games()
                    ->where(function($query) use ($teamIdsInGroup) {
                        $query->whereIn('local_team_id', $teamIdsInGroup)
                              ->orWhereIn('away_team_id', $teamIdsInGroup);
                    })->get();
                
                $teamsCount = $teamsInGroup->count();
                $gamesPerRound = ($teamsCount * ($teamsCount - 1)) / 2;
                $finishedGamesCount = $allGroupGames->where('status', 'finished')->count();
                $roundsPlayed = floor($finishedGamesCount / $gamesPerRound);
                $nextRoundNumber = $roundsPlayed + 1;
                $roundOrdinal = '1ra';
                if($nextRoundNumber == 2) $roundOrdinal = '2da';
                if($nextRoundNumber == 3) $roundOrdinal = '3ra';

                $groupPlayoffGames = $tournament->games()
                    ->where('is_playoff', true)
                    ->where(function($query) use ($teamIdsInGroup) {
                        $query->whereIn('local_team_id', $teamIdsInGroup)
                              ->orWhereIn('away_team_id', $teamIdsInGroup);
                    })
                    ->orderBy('created_at')
                    ->with(['localTeam', 'awayTeam'])
                    ->get();

                $hasPlayoffs = $groupPlayoffGames->count() > 0;
                $playoffChampion = null; 
                $playoffRoundName = '';
                
                $playoffRounds = [];
                if ($hasPlayoffs) {
                    $rawRounds = $this->groupPlayoffRoundsByTeamOverlap($groupPlayoffGames);
                    $playoffRounds = $rawRounds->map(function($round) {
                        return $this->formatRoundData($round);
                    })->values()->all();
                }

                // --- ELIMINADA: Lógica de avance automático (Estándar) ---
                // Ya no se chequea si la ronda terminó para generar la siguiente aquí.

                // Detección de Campeón (Estándar)
                if (!empty($playoffRounds)) {
                    $playoffRoundName = end($playoffRounds)['name'];
                    $lastRoundFinal = end($playoffRounds)['games'];
                    if ($lastRoundFinal->count() === 1 && $lastRoundFinal->first()->status === 'finished') {
                        $finalGame = $lastRoundFinal->first();
                        $wTeam = ($finalGame->local_team_score > $finalGame->away_team_score) ? $finalGame->localTeam : $finalGame->awayTeam;
                        $playoffChampion = $wTeam->name;
                    }
                }

                $standings = [];
                if (!$isElimination) {
                    $groupGames = $allGroupGames->where('status', 'finished');
                    foreach ($teamsInGroup as $team) {
                        $standings[$team->id] = [
                            'team_id' => $team->id, 'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'points' => 0
                        ];
                    }
                    foreach ($groupGames as $game) {
                        $localId = $game->local_team_id;
                        $awayId = $game->away_team_id;
                        if (!isset($standings[$localId]) || !isset($standings[$awayId])) continue;
                        $standings[$localId]['played']++;
                        $standings[$awayId]['played']++;
                        if ($game->local_team_score > $game->away_team_score) {
                            $standings[$localId]['won']++; $standings[$localId]['points'] += 2; $standings[$awayId]['lost']++;
                        } elseif ($game->local_team_score < $game->away_team_score) {
                            $standings[$awayId]['won']++; $standings[$awayId]['points'] += 2; $standings[$localId]['lost']++;
                        } else {
                            if ($game->local_team_score === 0 && $game->away_team_score === 0) {
                                $standings[$localId]['lost']++; $standings[$awayId]['lost']++;
                            } else {
                                $standings[$localId]['drawn']++; $standings[$localId]['points'] += 1;
                                $standings[$awayId]['drawn']++; $standings[$awayId]['points'] += 1; //
                            }
                        }
                    }
                    uasort($standings, function($a, $b) { return $b['points'] <=> $a['points']; });
                }

                $standingsData[$groupName] = [
                    'standings' => $standings,
                    'teams' => $teamsInGroup->keyBy('id'),
                    'is_finished' => ($allGroupGames->count() > 0 && $allGroupGames->count() === $allGroupGames->where('status', 'finished')->count()),
                    'team_ids' => $teamIdsInGroup,
                    'next_round_number' => $nextRoundNumber,
                    'round_ordinal' => $roundOrdinal,
                    'has_playoffs' => $hasPlayoffs,
                    'playoff_games' => $groupPlayoffGames, 
                    'playoff_rounds' => $playoffRounds, 
                    'playoff_champion' => $playoffChampion,
                    'playoff_round_name' => $playoffRoundName ?? '',
                    'is_groups' => false
                ];
            }
        }

        return view('tournaments.standings', compact('tournament', 'standingsData'));
    }

    public function publicStandings(\Illuminate\Http\Request $request)
    {
        $tournaments = \App\Models\Tournament::whereIn('status', ['active', 'finished'])->orderBy('name')->get();
        
        $selectedTournamentId = $request->input('tournament_id');
        
        $tournament = $selectedTournamentId ? \App\Models\Tournament::find($selectedTournamentId) : null;
        $standingsData = [];
        $dashboardData = [];

        if (!$selectedTournamentId || $selectedTournamentId === '') {
            // General Dashboard Mode (No tournament selected yet)
            foreach ($tournaments as $t) {
                // 1. Los 5 jugadores con más puntos
                $topScorers = \App\Models\GameAction::whereIn('game_id', $t->games()->pluck('id'))
                    ->where('action_type', 'point_scored')
                    ->selectRaw('player_id, SUM(value) as total_points')
                    ->groupBy('player_id')
                    ->orderByDesc('total_points')
                    ->limit(5)
                    ->with('player.team')
                    ->get()
                    ->map(function($action) {
                        return [
                            'player_name' => $action->player->name ?? 'Jugador',
                            'player_logo' => $action->player->image_path ?? null,
                            'player_gender' => $action->player->gender ?? null,
                            'team_name' => $action->player->team->name ?? 'Equipo',
                            'team_logo' => $action->player->team->image_path ?? null,
                            'points' => $action->total_points
                        ];
                    });

                // Get tournament type from settings
                $tSettings = $t->settings ? $t->settings->settings : [];
                $tType = $tSettings['tournament_type'] ?? 'round_robin';

                $topTeams = [];

                if ($tType === 'round_robin') {
                    // 2. Los 3 equipos con más puntos (todos contra todos)
                    $groups = $t->teams->groupBy(function ($item) {
                        $cat = $item->category ?? 'Sin Categoria';
                        $strength = $item->strength ?? 'General';
                        return $cat . ' - ' . $strength;
                    });

                    $allStandings = [];
                    foreach ($groups as $groupName => $teamsInGroup) {
                        $teamIdsInGroup = $teamsInGroup->pluck('id')->toArray();
                        $groupGames = $t->games()
                            ->where('status', 'finished')
                            ->where('is_playoff', false)
                            ->where(function($query) use ($teamIdsInGroup) {
                                $query->whereIn('local_team_id', $teamIdsInGroup)
                                      ->orWhereIn('away_team_id', $teamIdsInGroup);
                            })->get();

                        $standings = [];
                        foreach ($teamsInGroup as $team) {
                            $standings[$team->id] = [
                                'team' => $team,
                                'points' => 0
                            ];
                        }
                        foreach ($groupGames as $game) {
                            $localId = $game->local_team_id;
                            $awayId = $game->away_team_id;
                            if (!isset($standings[$localId]) || !isset($standings[$awayId])) continue;
                            if ($game->local_team_score > $game->away_team_score) {
                                $standings[$localId]['points'] += 2;
                            } elseif ($game->local_team_score < $game->away_team_score) {
                                $standings[$awayId]['points'] += 2;
                            } else {
                                if ($game->local_team_score > 0 || $game->away_team_score > 0) {
                                    $standings[$localId]['points'] += 1;
                                    $standings[$awayId]['points'] += 1;
                                }
                            }
                        }
                        foreach ($standings as $tid => $sData) {
                            $allStandings[] = $sData;
                        }
                    }
                    
                    usort($allStandings, function($a, $b) {
                        return $b['points'] <=> $a['points'];
                    });
                    
                    $top3Teams = array_slice($allStandings, 0, 5);
                    foreach ($top3Teams as $item) {
                        $topTeams[] = [
                            'team_name' => $item['team']->name,
                            'team_logo' => $item['team']->image_path,
                            'score' => $item['points'] . ' pts'
                        ];
                    }
                } else {
                    // 3. Los 3 equipos con más victorias (eliminatoria o doble eliminatoria)
                    $finishedGames = $t->games()->where('status', 'finished')->get();
                    $wins = [];
                    foreach ($finishedGames as $game) {
                        $wId = $game->getWinnerId();
                        if ($wId) {
                            $wins[$wId] = ($wins[$wId] ?? 0) + 1;
                        }
                    }
                    arsort($wins);
                    $top3Ids = array_slice(array_keys($wins), 0, 5, true);
                    $teams = \App\Models\Team::whereIn('id', $top3Ids)->get()->keyBy('id');
                    
                    foreach ($top3Ids as $tid) {
                        if (isset($teams[$tid])) {
                            $topTeams[] = [
                                'team_name' => $teams[$tid]->name,
                                'team_logo' => $teams[$tid]->image_path,
                                'score' => $wins[$tid] . ' victorias'
                            ];
                        }
                    }
                }

                // 4. Proximos 3 partidos del torneo
                $upcoming = $t->games()
                    ->where('status', 'pending')
                    ->where('date_time', '>=', now())
                    ->orderBy('date_time', 'asc')
                    ->limit(3)
                    ->with(['localTeam', 'awayTeam', 'court'])
                    ->get()
                    ->map(function($game) {
                        $category = $game->localTeam->category ?? ($game->awayTeam->category ?? null);
                        $strength = $game->localTeam->strength ?? ($game->awayTeam->strength ?? null);
                        $catStr = $category ? ($strength ? "$category - $strength" : $category) : 'General';
                        
                        return [
                            'local_name' => $game->localTeam->name ?? 'Pendiente',
                            'local_logo' => $game->localTeam->image_path ?? null,
                            'away_name' => $game->awayTeam->name ?? 'Pendiente',
                            'away_logo' => $game->awayTeam->image_path ?? null,
                            'court_name' => $game->court->name ?? 'Cancha',
                            'category_strength' => $catStr,
                            'date_time' => $game->date_time ? $game->date_time->format('d/m H:i') : '-'
                        ];
                    });

                $dashboardData[] = [
                    'tournament_name' => $t->name,
                    'tournament_type' => $tType,
                    'tournament_status' => $t->status,
                    'top_scorers' => $topScorers,
                    'top_teams' => $topTeams,
                    'upcoming_games' => $upcoming
                ];
            }
        }

        if ($tournament) {
            // 1. Cargar Configuración y Detectar Tipo
            $settings = $tournament->settings ? $tournament->settings->settings : [];
            $tournamentType = $settings['tournament_type'] ?? 'round_robin';
            
            // Agrupamiento estándar para vista de tabla
            $groups = $tournament->teams->groupBy(function ($item) {
                $cat = $item->category ?? 'Sin Categoria';
                $strength = $item->strength ?? 'General';
                return $cat . ' - ' . $strength;
            });

            // --- NUEVA LÓGICA DINÁMICA: DOBLE ELIMINATORIA (V2) ---
            $hasDoubleElimGames = $tournament->games()
                ->where('is_playoff', true)
                ->whereIn('group_name', ['WB_R1', 'LB_R1', 'GF']) // Solo verificamos semillas clave para detectar el modo
                ->exists();

            if ($tournamentType === 'double_elimination' || $hasDoubleElimGames) {
                if ($hasDoubleElimGames && $tournamentType !== 'double_elimination') {
                    $tournamentType = 'double_elimination';
                }

                $doubleElimGroups = \App\Models\Game::where('tournament_id', $tournament->id)
                    ->where('is_playoff', true)
                    ->whereNotNull('category_group')
                    ->distinct()
                    ->pluck('category_group');

                foreach ($doubleElimGroups as $groupName) {
                    // Obtenemos todos los juegos de esta categoría
                    $allCategoryGames = $tournament->games()
                        ->where('is_playoff', true)
                        ->where('category_group', $groupName)
                        ->with(['localTeam', 'awayTeam'])
                        ->orderBy('created_at')
                        ->get();

                    $wbRounds = [];
                    $lbRounds = [];
                    $grandFinal = null;
                    $resetGame = null;

                    // Clasificamos los juegos en arrays por ronda
                    foreach ($allCategoryGames as $game) {
                        if (strpos($game->group_name, 'WB_R') === 0) {
                            $roundNum = (int) str_replace('WB_R', '', $game->group_name);
                            $wbRounds[$roundNum][] = $game;
                        } elseif (strpos($game->group_name, 'LB_R') === 0) {
                            $roundNum = (int) str_replace('LB_R', '', $game->group_name);
                            $lbRounds[$roundNum][] = $game;
                        } elseif ($game->group_name === 'GF') {
                            $grandFinal = $game;
                        } elseif ($game->group_name === 'GR') {
                            $resetGame = $game;
                        }
                    }

                    // Ordenamos las rondas por clave numérica
                    ksort($wbRounds);
                    ksort($lbRounds);

                    $standingsData[$groupName] = [
                        'mode' => 'double_elimination_grouped',
                        'bracket' => [
                            'winner_bracket' => array_values($wbRounds),
                            'loser_bracket'  => array_values($lbRounds),
                            'grand_final'    => $grandFinal,
                            'reset_game'     => $resetGame
                        ]
                    ];
                }
            }
            // --- LÓGICA ESTÁNDAR (LIGA O ELIMINACIÓN SIMPLE) ---
            else {
                foreach ($groups as $groupName => $teamsInGroup) {
                    $teamIdsInGroup = $teamsInGroup->pluck('id')->toArray();
                    $isElimination = ($tournamentType === 'elimination');

                    $allGroupGames = $tournament->games()
                        ->where(function($query) use ($teamIdsInGroup) {
                            $query->whereIn('local_team_id', $teamIdsInGroup)
                                  ->orWhereIn('away_team_id', $teamIdsInGroup);
                        })->get();
                    
                    $teamsCount = $teamsInGroup->count();
                    $gamesPerRound = $teamsCount > 1 ? ($teamsCount * ($teamsCount - 1)) / 2 : 1;
                    $finishedGamesCount = $allGroupGames->where('status', 'finished')->count();
                    $roundsPlayed = floor($finishedGamesCount / $gamesPerRound);
                    $nextRoundNumber = $roundsPlayed + 1;
                    $roundOrdinal = '1ra';
                    if($nextRoundNumber == 2) $roundOrdinal = '2da';
                    if($nextRoundNumber == 3) $roundOrdinal = '3ra';

                    $groupPlayoffGames = $tournament->games()
                        ->where('is_playoff', true)
                        ->where(function($query) use ($teamIdsInGroup) {
                            $query->whereIn('local_team_id', $teamIdsInGroup)
                                  ->orWhereIn('away_team_id', $teamIdsInGroup);
                        })
                        ->orderBy('created_at')
                        ->with(['localTeam', 'awayTeam'])
                        ->get();

                    $hasPlayoffs = $groupPlayoffGames->count() > 0;
                    $playoffChampion = null; 
                    $playoffRoundName = '';
                    
                    $playoffRounds = [];
                    if ($hasPlayoffs) {
                        $rawRounds = $this->groupPlayoffRoundsByTeamOverlap($groupPlayoffGames);
                        $playoffRounds = $rawRounds->map(function($round) {
                            return $this->formatRoundData($round);
                        })->values()->all();
                    }

                    // Detección de Campeón (Estándar)
                    if (!empty($playoffRounds)) {
                        $playoffRoundName = end($playoffRounds)['name'];
                        $lastRoundFinal = end($playoffRounds)['games'];
                        if ($lastRoundFinal->count() === 1 && $lastRoundFinal->first()->status === 'finished') {
                            $finalGame = $lastRoundFinal->first();
                            $wTeam = ($finalGame->local_team_score > $finalGame->away_team_score) ? $finalGame->localTeam : $finalGame->awayTeam;
                            $playoffChampion = $wTeam->name;
                        }
                    }

                    $standings = [];
                    if (!$isElimination) {
                        $groupGames = $allGroupGames->where('status', 'finished');
                        foreach ($teamsInGroup as $team) {
                            $standings[$team->id] = [
                                'team_id' => $team->id, 'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'points' => 0
                            ];
                        }
                        foreach ($groupGames as $game) {
                            $localId = $game->local_team_id;
                            $awayId = $game->away_team_id;
                            if (!isset($standings[$localId]) || !isset($standings[$awayId])) continue;
                            $standings[$localId]['played']++;
                            $standings[$awayId]['played']++;
                            if ($game->local_team_score > $game->away_team_score) {
                                $standings[$localId]['won']++; $standings[$localId]['points'] += 2; $standings[$awayId]['lost']++;
                            } elseif ($game->local_team_score < $game->away_team_score) {
                                $standings[$awayId]['won']++; $standings[$awayId]['points'] += 2; $standings[$localId]['lost']++;
                            } else {
                                if ($game->local_team_score === 0 && $game->away_team_score === 0) {
                                    $standings[$localId]['lost']++; $standings[$awayId]['lost']++;
                                } else {
                                    $standings[$localId]['drawn']++; $standings[$localId]['points'] += 1;
                                    $standings[$awayId]['drawn']++; $standings[$awayId]['points'] += 1;
                                }
                            }
                        }
                        uasort($standings, function($a, $b) { return $b['points'] <=> $a['points']; });
                    }

                    $standingsData[$groupName] = [
                        'standings' => $standings,
                        'teams' => $teamsInGroup->keyBy('id'),
                        'is_finished' => ($allGroupGames->count() > 0 && $allGroupGames->count() === $allGroupGames->where('status', 'finished')->count()),
                        'team_ids' => $teamIdsInGroup,
                        'next_round_number' => $nextRoundNumber,
                        'round_ordinal' => $roundOrdinal,
                        'has_playoffs' => $hasPlayoffs,
                        'playoff_games' => $groupPlayoffGames, 
                        'playoff_rounds' => $playoffRounds, 
                        'playoff_champion' => $playoffChampion,
                        'playoff_round_name' => $playoffRoundName ?? '',
                        'is_groups' => false
                    ];
                }
            }
        }

        return view('public.standings', compact('tournaments', 'selectedTournamentId', 'tournament', 'standingsData', 'dashboardData'));
    }
    
    private function determineGroupWinner($games, &$winnerId)
    {
        if ($games->isEmpty()) return;

        // 1. Obtener TODOS los equipos que ganaron sus partidos finalizados
        $winners = $games->where('status', 'finished')->map(function($g) {
            return ($g->local_team_score > $g->away_team_score) ? $g->local_team_id : $g->away_team_id;
        })->unique()->values();

        if ($winners->isEmpty()) return;

        // 2. Buscar cuáles de estos ganadores tienen un partido futuro programado
        $championCandidates = [];

        foreach ($winners as $teamId) {
            // ¿Este equipo tiene un partido pendiente/futuro en ESTE grupo?
            $hasFutureGame = $games->contains(function($g) use ($teamId) {
                return ($g->status !== 'finished') && 
                       ($g->local_team_id === $teamId || $g->away_team_id === $teamId);
            });

            // Si NO tiene futuro, es candidato a campeón
            if (!$hasFutureGame) {
                $championCandidates[] = $teamId;
            }
        }

        // 3. Lógica:
        // - Si hay 0 candidatos (todos juegan), no hay campeón.
        // - Si hay MÁS de 1 candidato (ej: Ganador A y Ganador C aún no se enfrentaron), no hay campeón definitivo.
        // - Si hay EXACTAMENTE 1 candidato, ese es el ganador del grupo.
        if (count($championCandidates) === 1) {
            $winnerId = $championCandidates[0];
        }
    }

    // Auxiliar para procesar un lado del bracket
    private function processSideBracket($games, $name) {
        $rounds = [];
        if ($games->isNotEmpty()) {
            $rawRounds = $this->groupPlayoffRoundsByTeamOverlap($games);
            $rounds = $rawRounds->map(function($round) use ($name) {
                $r = $this->formatRoundData($round);
                // Renombramos rondas para que se vean bonitas: "Final Zona Norte"
                $r['name'] = $name . " - " . $r['name'];
                return $r;
            })->values()->all();
        }
        return [
            'rounds' => $rounds,
            'name' => $name,
            'last_game' => $games->last() // Último juego jugado/creado
        ];
    }
    /**
     * Agrupa los juegos en rondas detectando repetición de equipos.
     * Si un equipo aparece en un juego nuevo y ya estaba en la ronda actual, 
     * significa que ese juego pertenece a una nueva ronda (eliminatoria directa).
     */
    private function groupPlayoffRoundsByTeamOverlap($games)
    {
        $rounds = collect();
        $currentRound = collect();
        $currentRoundTeams = []; // Array plano de IDs de equipos en la ronda actual

        foreach ($games as $game) {
            $gameTeams = [$game->local_team_id, $game->away_team_id];
            
            // Verificar si hay intersección entre los equipos de este juego y la ronda actual
            $overlap = array_intersect($gameTeams, $currentRoundTeams);
            
            if (!empty($overlap) && $currentRound->isNotEmpty()) {
                // HAY REPETICIÓN: Este equipo ya jugó en esta iteración -> Nueva Ronda
                $rounds->push($currentRound);
                
                // Reiniciar para la nueva ronda
                $currentRound = collect([$game]);
                $currentRoundTeams = $gameTeams;
            } else {
                // SIN REPETICIÓN: Misma ronda
                $currentRound->push($game);
                $currentRoundTeams = array_merge($currentRoundTeams, $gameTeams);
            }
        }

        // Agregar la última ronda acumulada
        if ($currentRound->isNotEmpty()) {
            $rounds->push($currentRound);
        }

        return $rounds;
    }

    /**
     * Método auxiliar para dar formato al nombre de la ronda según cantidad de partidos
     */
    private function formatRoundData($games)
    {
        $count = $games->count();
        $name = "Ronda " . $count;
        
        if ($count == 1) $name = 'Gran Final';
        elseif ($count == 2) $name = 'Semifinales';
        elseif ($count == 4) $name = 'Cuartos de Final';
        elseif ($count == 8) $name = 'Octavos de Final';
        elseif ($count == 16) $name = 'Dieciseisavos de Final';

        return [
            'name' => $name,
            'games' => $games
        ];
    }

    /**
     * Obtener el reglamento de un torneo específico (para leerlo)
     */
    public function getRules(Tournament $tournament)
    {
        return response()->json([
            'reglamento' => $tournament->reglamento,
            'logo_url' => $tournament->logo_path ? asset('storage/' . $tournament->logo_path) : null
        ]);
    }

    public function updateRules(Request $request, Tournament $tournament)
    {
        $request->validate([
            'reglamento' => 'nullable|string'
        ]);

        $tournament->reglamento = $request->reglamento;
        $tournament->save();

        return response()->json([
            'success' => true,
            'message' => 'Reglamento actualizado exitosamente.'
        ]);
    }

    public function generateSecondRound(Request $request, CalendarGeneratorService $calendarService, Tournament $tournament)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_ids' => 'nullable|string',
        ]);

        if (!$tournament->settings) {
            return response()->json(['success' => false, 'message' => 'No se encontró configuración del torneo original.']);
        }

        $config = $tournament->settings->settings;
        $config['start_date'] = $request->start_date;
        $config['end_date'] = $request->end_date;

        $specificTeamIds = $request->input('team_ids');
        if ($specificTeamIds) {
            $specificTeamIds = json_decode($specificTeamIds, true);
            // Invertir para la vuelta (A vs B se convierte en B vs A)
            $specificTeamIds = array_reverse($specificTeamIds);
        }

        DB::transaction(function () use ($tournament, $config, $calendarService, $request, $specificTeamIds) {
            
            // 1. El servicio genera los juegos. 
            // IMPORTANTE: En este punto, se crean con round_number = 1 (o defecto de BD).
            $result = $calendarService->generateRoundRobinSchedule(
                $tournament->id, 
                $config, 
                $specificTeamIds, 
                null 
            );

            if (!$result['success']) {
                throw new \Exception($result['message']);
            }

            // 2. CORRECCIÓN: Calcular y asignar la Ronda Correcta
            // Buscamos cuál es la ronda más alta existente actualmente
            $maxRound = Game::where('tournament_id', $tournament->id)->max('round_number');
            
            // Si no hay juegos o es 0, empezamos en 1. Si hay, sumamos 1.
            $nextRoundNumber = ($maxRound) ? $maxRound + 1 : 1;

            // Actualizamos los juegos que acabamos de crear.
            // Criterio de seguridad: Actualizar solo los juegos que tengan la fecha de inicio de la nueva ronda.
            Game::where('tournament_id', $tournament->id)
                ->where('date_time', '>=', $request->start_date)
                ->update(['round_number' => $nextRoundNumber]);
            
            // ------------------------------------------------

            $tournament->settings->update(['settings' => $config]);

            $tournament->status = 'active';
            $tournament->start_date = $request->start_date;
            $tournament->end_date = $request->end_date;
            $tournament->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Vuelta generada exitosamente. Se ha asignado la Ronda ' . ($tournament->games()->max('round_number')) . '.',
            'redirect_url' => route('tournaments.standings', $tournament)
        ]);
    }

    /**
     * Genera una fase eliminatoria (Playoffs) basada en la tabla actual.
     * MEJORADO: Ahora respeta la configuración de horarios/días original.
     */
    public function generateElimination(Request $request, Tournament $tournament)
    {
        // 1. Validación
        $request->validate([
            'teams_count' => 'required|integer|min:2|max:32',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'team_ids' => 'nullable|string', 
        ]);

        $teamsCount = $request->teams_count;
        
        // Decodificar los IDs del grupo específico
        $specificTeamIds = $request->input('team_ids');
        if ($specificTeamIds) {
            $specificTeamIds = json_decode($specificTeamIds, true);
        }
        
        // 2. Verificar potencia de 2
        if (($teamsCount & ($teamsCount - 1)) != 0) {
            return response()->json(['success' => false, 'message' => 'El número de equipos debe ser una potencia de 2 (Ej: 4, 8, 16).']);
        }

        // 3. Calcular la tabla de posiciones actual
        $query = $tournament->games()->where('status', 'finished');
        if ($specificTeamIds) {
            $query->where(function($q) use ($specificTeamIds) {
                $q->whereIn('local_team_id', $specificTeamIds)
                  ->orWhereIn('away_team_id', $specificTeamIds);
            });
        }

        $finishedGames = $query->get();
        $standings = [];

        foreach ($finishedGames as $game) {
            $localId = $game->local_team_id;
            $awayId = $game->away_team_id;

            if (!isset($standings[$localId])) $standings[$localId] = ['team_id' => $localId, 'points' => 0];
            if (!isset($standings[$awayId])) $standings[$awayId] = ['team_id' => $awayId, 'points' => 0];

            if ($game->local_team_score > $game->away_team_score) {
                $standings[$localId]['points'] += 2;
            } elseif ($game->local_team_score < $game->away_team_score) {
                $standings[$awayId]['points'] += 2;
            } else {
                if ($game->local_team_score > 0) { // Empate real
                    $standings[$localId]['points'] += 1;
                    $standings[$awayId]['points'] += 1;
                }
            }
        }

        uasort($standings, function($a, $b) { return $b['points'] <=> $a['points']; });

        $qualifiedTeams = array_slice($standings, 0, $teamsCount, true);
        
        // --- FILTRO FINAL DE SEGURIDAD ---
        if ($specificTeamIds) {
            $qualifiedTeamIds = array_intersect(array_keys($qualifiedTeams), $specificTeamIds);
        } else {
            $qualifiedTeamIds = array_keys($qualifiedTeams);
        }

        if (count($qualifiedTeamIds) < $teamsCount) {
            return response()->json(['success' => false, 'message' => 'No hay suficientes equipos clasificados.']);
        }
        
        // 4. Generar Cruces
        $matchups = [];
        for ($i = 0; $i < $teamsCount / 2; $i++) {
            $matchups[] = [
                'local' => $qualifiedTeamIds[$i],
                'away' => $qualifiedTeamIds[count($qualifiedTeamIds) - 1 - $i]
            ];
        }

        // 5. Usar el método auxiliar para crear los juegos
        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : $startDate->copy()->addDays(30);

        // --- CORRECCIÓN CLAVE: Cargar y pasar la configuración original ---
        $settings = $tournament->settings ? $tournament->settings->settings : [];
        
        // Pasamos $settings para que use Domingos/Sábados, 19:00, Canchas, etc.
        $this->calendarService->schedulePlayoffRound($tournament, $matchups, $startDate, $endDate, $settings);
        // --------------------------------------------------------------

        return response()->json([
            'success' => true,
            'message' => "Fase eliminatoria generada respetando la configuración del torneo.",
            'redirect_url' => route('tournaments.standings', $tournament)
        ]);
    }
        /**
     * Actualiza el torneo y genera la siguiente ronda automáticamente (sin recargar vista completa)
     */
    public function updateProgression(Request $request, Tournament $tournament)
    {
        $this->authorize('update', $tournament);
        
        $settings = $tournament->settings ? $tournament->settings->settings : [];
        $tournamentType = $settings['tournament_type'] ?? 'round_robin';
        
        // Eliminada variable $isGroups

        $groupsStructure = $settings['groups_structure'] ?? null;

        $gamesCreated = 0;
        $message = "No hay rondas listas para generar aún.";

        try {
            // --- 1. LÓGICA ESTÁNDAR (LIGA / ELIMINACIÓN SIMPLE) ---
            if ($tournamentType !== 'double_elimination') {
                $groups = $tournament->teams->groupBy(function ($item) {
                    $cat = $item->category ?? 'Sin Categoria';
                    $strength = $item->strength ?? 'General';
                    return $cat . ' - ' . $strength;
                });

                foreach ($groups as $groupName => $teamsInGroup) {
                    $teamIdsInGroup = $teamsInGroup->pluck('id')->toArray();
                    
                    // Solo procesar si tiene playoffs activos
                    $groupPlayoffGames = $tournament->games()
                        ->where('is_playoff', true)
                        ->where(function($query) use ($teamIdsInGroup) {
                            $query->whereIn('local_team_id', $teamIdsInGroup)
                                  ->orWhereIn('away_team_id', $teamIdsInGroup);
                        })
                        ->orderBy('created_at')
                        ->with(['localTeam', 'awayTeam'])
                        ->get();

                    if ($groupPlayoffGames->isEmpty()) continue;

                    // Agrupar por rondas
                    $rawRounds = $this->groupPlayoffRoundsByTeamOverlap($groupPlayoffGames);
                    $playoffRounds = $rawRounds->map(function($round) {
                        return $this->formatRoundData($round);
                    })->values()->all();

                    if (empty($playoffRounds)) continue;

                    // Verificar última ronda
                    $lastRoundData = end($playoffRounds);
                    $lastRoundGames = $lastRoundData['games']; 

                    // Verificamos si hay partidos pendientes en la última ronda generada
                    $pendingInLastRound = $lastRoundGames->where('status', '!=', 'finished');

                    // --- AVANZAR SI NO HAY PENDIENTES ---
                    if ($pendingInLastRound->count() === 0) {
                        
                        $winners = [];

                        // 1. Obtener ganadores de los partidos JUGADOS
                        foreach ($lastRoundGames as $game) {
                            $winners[] = ($game->local_team_score > $game->away_team_score) 
                                ? $game->local_team_id 
                                : $game->away_team_id;
                        }

                        // 2. --- RECUPERAR BYES (SISTEMA SEGURO) ---
                        $currentRoundByes = [];
                        
                        // A. Intentamos leer de la configuración guardada
                        if (isset($settings['current_byes'][$groupName])) {
                            $currentRoundByes = $settings['current_byes'][$groupName];
                        } 
                        // B. FALLBACK SOLO PARA RONDA 1 (Evita revivir muertos)
                        elseif ($tournament->games()->where('is_playoff', true)->count() === count($lastRoundGames)) {
                            // Verificación: Solo usamos el cálculo automático si estos son los ÚNICOS juegos del torneo.
                            // Esto confirma que estamos en Ronda 1. Si ya hay rondas anteriores, es inseguro.
                            
                            $teamsThatPlayed = $lastRoundGames->flatMap(function($g) {
                                return [$g->local_team_id, $g->away_team_id];
                            })->unique()->values()->toArray();

                            $missingTeams = array_diff($teamIdsInGroup, $teamsThatPlayed);
                            
                            if (!empty($missingTeams)) {
                                $currentRoundByes = array_values($missingTeams);
                            }
                        }
                        // ----------------------------------------

                        // Fusionamos ganadores de partidos + equipos descansantes
                        $allSurvivors = array_merge($winners, $currentRoundByes);

                        // 3. --- VERIFICAR SI HAY SUFICIENTES EQUIPOS ---
                        if (count($allSurvivors) > 1) {
                            
                            // 4. --- CREAR POOL DE JUGADORES (EVITAR DESCANSOS DOBLES) ---
                            // Separamos a los que descansaron de los que jugaron
                            $teamsThatPlayed = $winners; // Ganadores de partidos
                            $teamsThatRested = $currentRoundByes; // Equipos que tuvieron bye

                            if (!empty($teamsThatRested)) {
                                // LÓGICA: Priorizar que los equipos que descansaron JUEN ahora.
                                // 1. Barajamos solo a los que jugaron
                                shuffle($teamsThatPlayed);
                                
                                // 2. Ponemos a los equipos que descansaron AL PRINCIPIO de la lista.
                                $survivorPool = array_merge($teamsThatRested, $teamsThatPlayed);
                            } else {
                                // Si nadie descansó, barajamos normal
                                $survivorPool = $allSurvivors;
                                shuffle($survivorPool);
                            }
                            // ----------------------------------------

                                // 5. --- GENERAR EMPAREJAMIENTOS ---
                                $nextMatchups = [];
                                $totalSurvivors = count($survivorPool);
                                $newByesForNextRound = [];

                                for ($i = 0; $i < $totalSurvivors; $i += 2) {
                                    if (isset($survivorPool[$i + 1])) {
                                        // Tenemos par -> JUEGO
                                        $nextMatchups[] = [
                                            'local' => $survivorPool[$i], 
                                            'away' => $survivorPool[$i + 1],
                                            'group_name' => $groupName,         // <--- NUEVO LÍNEA 1
                                            'category_group' => $groupName      // <--- NUEVO LÍNEA 2
                                        ];
                                    } else {
                                        // No hay par -> BYE para este equipo en la siguiente ronda
                                        $newByesForNextRound[] = $survivorPool[$i];
                                    }
                                }
                            // --------------------------------------------------

                            // --- GUARDAR NUEVOS BYES EN LA CONFIGURACIÓN ---
                            // Esto asegura que el equipo que descansa hoy avance mañana
                            if (!empty($newByesForNextRound)) {
                                $settings['current_byes'][$groupName] = $newByesForNextRound;
                            } else {
                                // Siempre guardamos la configuración (incluso vacía) para mantener la llave activa
                                $settings['current_byes'][$groupName] = $newByesForNextRound;
                            }
                            // Actualizamos la BD
                            $tournament->settings()->update(['settings' => $settings]);
                            // --------------------------------------------------

                            // Calcular fechas
                            $lastGameDate = $lastRoundGames->max('date_time');
                            $nextStartDate = $lastGameDate ? \Carbon\Carbon::parse($lastGameDate)->addDay() : \Carbon\Carbon::parse($tournament->start_date)->addDay();
                            
                            $nextEndDate = $nextStartDate->copy()->addWeek();
                            
                            // Ajuste de fechas
                            if ($tournament->end_date) {
                                $tEndDate = \Carbon\Carbon::parse($tournament->end_date);
                                if ($nextStartDate->gt($tEndDate)) {
                                    $nextEndDate = $nextStartDate->copy()->addWeek();
                                } else {
                                    $nextEndDate = $tEndDate;
                                }
                            }

                            // Generar juegos
                            $this->calendarService->schedulePlayoffRound($tournament, $nextMatchups, $nextStartDate, $nextEndDate, $settings);

                            // =================================================================
                            // CORRECCIÓN FORZADA: RONDA Y NOMBRE DE GRUPO
                            // =================================================================
                            
                            // 1. Obtener IDs de los equipos que acabamos de programar
                            $teamIdsInNewRound = [];
                            foreach ($nextMatchups as $m) {
                                $teamIdsInNewRound[] = $m['local'];
                                $teamIdsInNewRound[] = $m['away'];
                            }

                            // 2. Calcular cuál es el número de la SIGUIENTE ronda
                            // Buscamos el número más alto en los juegos YA FINALIZADOS de este grupo
                            $currentMaxRound = $groupPlayoffGames->max('round_number') ?? 1;
                            $nextRoundNumber = $currentMaxRound + 1;

                            // 3. Actualizar los juegos recién creados en la base de datos
                            \App\Models\Game::where('tournament_id', $tournament->id)
                                ->where(function($q) use ($teamIdsInNewRound) {
                                    // Buscamos partidos donde jueguen estos equipos
                                    $q->whereIn('local_team_id', $teamIdsInNewRound)
                                      ->orWhereIn('away_team_id', $teamIdsInNewRound);
                                })
                                // Aseguramos que sean los partidos futuros (los que acabamos de crear)
                                ->where('date_time', '>=', $nextStartDate)
                                ->update([
                                    'group_name' => $groupName,
                                    'category_group' => $groupName,
                                    'round_number' => $nextRoundNumber
                                ]);
                            // =================================================================

                            $gamesCreated += count($nextMatchups);
                            
                            $message = "Ronda avanzada. Juegos creados: " . count($nextMatchups) . ". Byes para la siguiente: " . count($newByesForNextRound);
                        } else {
                            // Si solo queda 1 ganador y no hay pendientes, es el CAMPEÓN
                            $championName = "";
                            $championId = reset($allSurvivors); // El único restante
                            if($championId) {
                                $championTeam = Team::find($championId);
                                if($championTeam) $championName = $championTeam->name;
                            }
                            $message = "¡Tenemos un campeón en el grupo " . $groupName . (!empty($championName) ? ": " . $championName : "!");
                        }
                    }
                }
            }
            
            // --- BLOQUE DE LÓGICA FASE DE GRUPOS ELIMINADO ---

        // --- 3. LÓGICA DOBLE ELIMINATORIA ---
        elseif ($tournamentType === 'double_elimination') {
            $doubleElimGroups = Game::where('tournament_id', $tournament->id)
                ->where('is_playoff', true)
                ->whereNotNull('category_group')
                ->distinct()
                ->pluck('category_group');

            $groupsUpdated = 0;

            foreach ($doubleElimGroups as $groupName) {
                // 1. Preparar configuración temporal
                $tempSettings = $settings;
                $tempSettings['group_name'] = $groupName;
                
                // 2. Cargar estado actual DESDE brackets_data
                if (isset($settings['brackets_data'][$groupName])) {
                    $groupData = $settings['brackets_data'][$groupName];
                    $tempSettings['specific_team_ids'] = $groupData['specific_team_ids'] ?? null;
                    $tempSettings['team_count'] = $groupData['team_count'] ?? null;
                    $tempSettings['wb_total_rounds'] = $groupData['wb_total_rounds'] ?? 1;
                    $tempSettings['wb_current_round'] = $groupData['wb_current_round'] ?? 1;
                    $tempSettings['wb_byes'] = $groupData['wb_byes'] ?? [];
                    $tempSettings['late_teams'] = $groupData['late_teams'] ?? ($settings['late_teams'] ?? []);
                    $tempSettings['lb_pending_pool'] = $groupData['lb_pending_pool'] ?? ($settings['lb_pending_pool'] ?? []);
                    $tempSettings['byes_count'] = $groupData['byes_count'] ?? ($settings['byes_count'] ?? []);
                } else {
                    $tempSettings['wb_byes'] = [];
                    $tempSettings['wb_current_round'] = 1;
                    $tempSettings['late_teams'] = $settings['late_teams'] ?? [];
                    $tempSettings['lb_pending_pool'] = $settings['lb_pending_pool'] ?? [];
                    $tempSettings['byes_count'] = $settings['byes_count'] ?? [];
                }

                // --- NUEVO: AUTO-REPARACIÓN DE BYES (DETECTIVA) ---
                // Si estamos en Ronda 1 y no hay byes guardados, pero la matemática indica que debería haberlos...
                if ($tempSettings['wb_current_round'] == 1 && empty($tempSettings['wb_byes']) && !empty($tempSettings['specific_team_ids'])) {
                    
                    $allTeamIds = $tempSettings['specific_team_ids'];
                    $teamsInPlay = [];

                    // Buscar qué equipos están jugando en WB_R1
                    $r1Games = Game::where('tournament_id', $tournament->id)
                        ->where('group_name', 'WB_R1')
                        ->where('category_group', $groupName)
                        ->get();

                    foreach ($r1Games as $game) {
                        $teamsInPlay[] = $game->local_team_id;
                        $teamsInPlay[] = $game->away_team_id;
                    }

                    // La diferencia (los que están en la lista pero no juegan) son los BYES
                    $missingByes = array_diff($allTeamIds, $teamsInPlay);
                    
                    if (!empty($missingByes)) {
                        $tempSettings['wb_byes'] = array_values($missingByes);
                        
                        // Guardamos este hallazgo inmediatamente en la configuración maestra para que no se pierda
                        $settings['brackets_data'][$groupName]['wb_byes'] = $tempSettings['wb_byes'];
                        
                        // Opcional: Mensaje de log para depuración
                        // \Log::info("Auto-repair: Byes recuperados para $groupName: " . implode(',', $tempSettings['wb_byes']));
                    }
                }
                // -------------------------------------------------------

                // 3. Verificar juegos antes
                $countBefore = Game::where('tournament_id', $tournament->id)->count();

                // 4. Ejecutar lógica del servicio
                $this->doubleElimService->processBracketProgression($tournament, $tempSettings);

                // 5. Verificar juegos después
                $countAfter = Game::where('tournament_id', $tournament->id)->count();

                if ($countAfter > $countBefore) {
                    $groupsUpdated++;

                    // 6. PERSISTENCIA: Guardar el nuevo estado
                    $settings['brackets_data'][$groupName]['wb_current_round'] = $tempSettings['wb_current_round'];
                    $settings['brackets_data'][$groupName]['wb_byes'] = $tempSettings['wb_byes'];
                    $settings['brackets_data'][$groupName]['lb_pending_pool'] = $tempSettings['lb_pending_pool'] ?? [];
                    $settings['brackets_data'][$groupName]['late_teams'] = $tempSettings['late_teams'] ?? [];
                    $settings['brackets_data'][$groupName]['byes_count'] = $tempSettings['byes_count'] ?? [];
                    
                    if (isset($tempSettings['wb_total_rounds'])) {
                         $settings['brackets_data'][$groupName]['wb_total_rounds'] = $tempSettings['wb_total_rounds'];
                    }
                }
            }

            // 7. Guardar toda la configuración actualizada en BD
            if ($groupsUpdated > 0) {
                $tournament->settings()->update(['settings' => $settings]);
                $message = "Brackets de doble eliminación actualizados.";
            } else {
                $message = "No se generaron nuevos partidos. Revisa si todos los partidos anteriores están finalizados.";
            }
        }

            return response()->json([
                'success' => true,
                'message' => $message,
                'games_created' => $gamesCreated
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

        public function swapTeamsGlobal(Request $request, Tournament $tournament)
    {
        // 1. Validación de entrada
        $request->validate([
            'team_out_id' => 'required|integer|exists:teams,id', 
            'team_in_id'  => 'required|integer|exists:teams,id', 
        ]);

        $teamOutId = (int) $request->team_out_id;
        $teamInId = (int) $request->team_in_id;

        if ($teamOutId == $teamInId) {
            return response()->json(['success' => false, 'message' => 'No puedes seleccionar el mismo equipo.']);
        }

        // 2. Obtener modelos básicos
        $teamOut = \App\Models\Team::find($teamOutId);
        $teamIn = \App\Models\Team::find($teamInId);

        if (!$teamOut || !$teamIn) {
             return response()->json(['success' => false, 'message' => 'Uno de los equipos no existe.']);
        }

        // --- 3. CÁLCULO DINÁMICO DEL GRUPO (CORRECCIÓN CRÍTICA) ---
        // Definimos una pequeña función auxiliar para calcular el grupo igual que en la Vista
        $getTeamGroup = function($teamId) use ($tournament) {
            // Prioridad 1: Buscar el grupo en los partidos asignados a este equipo en ESTE torneo
            $game = $tournament->games()
                ->where(function($q) use ($teamId) {
                    $q->where('local_team_id', $teamId)
                      ->orWhere('away_team_id', $teamId);
                })
                ->whereNotNull('group_name')
                ->first();

            if ($game) {
                return $game->group_name;
            }

            // Prioridad 2: Si no tiene partidos, usar categoría/fuerza de la tabla teams
            $team = \App\Models\Team::find($teamId);
            $cat = $team->category ?? 'General';
            $str = $team->strength ?? 'General';
            return trim($cat . ' - ' . $str);
        };

        $groupOut = $getTeamGroup($teamOutId);
        $groupIn  = $getTeamGroup($teamInId);

        // Verificamos que los grupos sean idénticos
        if ($groupOut != $groupIn) {
             return response()->json([
                'success' => false, 
                'message' => "Error de grupo: El equipo que sale es de '{$groupOut}' y el que entra es de '{$groupIn}'. Solo se permiten reemplazos dentro del mismo grupo."
            ], 400);
        }
        // -------------------------------------------------------

        // --- 4. VALIDACIÓN DE SEGURIDAD: ¿EL EQUIPO YA JUGÓ? ---
        // Verificamos SOLO el equipo que sale (teamOutId)
        $hasPlayed = $tournament->games()
            ->where(function($q) use ($teamOutId) {
                $q->where('local_team_id', $teamOutId)
                  ->orWhere('away_team_id', $teamOutId);
            })
            ->where('status', '!=', 'pending') // playing, finished, cancelled, etc.
            ->exists();

        if ($hasPlayed) {
            return response()->json([
                'success' => false, 
                'message' => "No se puede reemplazar. El equipo seleccionado ('{$teamOut->name}') ya tiene partidos iniciados o finalizados en el torneo."
            ], 403);
        }
        // -----------------------------------------------------------

        // 5. LÓGICA DE INTERCAMBIO DIRECTO (SWAP)
        try {
            DB::transaction(function () use ($tournament, $teamOutId, $teamInId) {
                
                DB::table('games')
                    ->where('tournament_id', $tournament->id)
                    ->where(function($q) use ($teamOutId, $teamInId) {
                        // Afectar filas donde aparezca cualquiera de los dos equipos
                        $q->whereIn('local_team_id', [$teamOutId, $teamInId])
                          ->orWhereIn('away_team_id', [$teamOutId, $teamInId]);
                    })
                    ->update([
                        // Intercambio Local
                        'local_team_id' => DB::raw("
                            CASE 
                                WHEN local_team_id = {$teamOutId} THEN {$teamInId} 
                                WHEN local_team_id = {$teamInId} THEN {$teamOutId} 
                                ELSE local_team_id 
                            END
                        "),
                        // Intercambio Visitante
                        'away_team_id' => DB::raw("
                            CASE 
                                WHEN away_team_id = {$teamOutId} THEN {$teamInId} 
                                WHEN away_team_id = {$teamInId} THEN {$teamOutId} 
                                ELSE away_team_id 
                            END
                        ")
                    ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Reasignación completada. Los equipos han sido intercambiados en el calendario.'
            ]);

        } catch (\Exception $e) {
            // En caso de error inesperado en la transacción
            return response()->json([
                'success' => false, 
                'message' => 'Error al realizar el cambio en base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clona el torneo, su configuración de calendario y sus equipos registrados
     * con sus respectivos jugadores en un nuevo torneo con estado 'pendiente'.
     */
    public function cloneTournament(Request $request, Tournament $tournament)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Clonar el Torneo
            $newTournament = $tournament->replicate();
            $newName = $tournament->name . ' - Copia';
            
            // Garantizar que no sobrepase los 255 caracteres
            $newTournament->name = substr($newName, 0, 255);
            $newTournament->status = 'pending';
            $newTournament->save();

            // 2. Clonar configuraciones del torneo (TournamentSetting)
            $settings = TournamentSetting::where('tournament_id', $tournament->id)->get();
            foreach ($settings as $setting) {
                $newSetting = $setting->replicate();
                $newSetting->tournament_id = $newTournament->id;
                $newSetting->save();
            }

            // 3. Clonar los equipos y sus jugadores
            $teams = Team::where('tournament_id', $tournament->id)->get();
            foreach ($teams as $team) {
                $newTeam = $team->replicate();
                $newTeam->tournament_id = $newTournament->id;
                $newTeam->save();

                // Clonar jugadores de este equipo
                $players = \App\Models\Player::where('team_id', $team->id)->get();
                foreach ($players as $player) {
                    $newPlayer = $player->replicate();
                    $newPlayer->team_id = $newTeam->id;
                    $newPlayer->save();
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Torneo clonado exitosamente en estado pendiente.',
                'tournament' => $newTournament
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al clonar el torneo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Inscribe un equipo tardío en un torneo de Doble Eliminatoria en curso (activo).
     */
    public function addLateTeam(Request $request, Tournament $tournament)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'category_group' => 'nullable|string'
        ]);

        $settings = $tournament->settings ? $tournament->settings->settings : [];
        $tournamentType = $settings['tournament_type'] ?? ($tournament->tournament_settings['tournament_type'] ?? null);
        $hasDoubleElimGames = $tournament->games()->where('is_playoff', true)->whereIn('group_name', ['WB_R1', 'LB_R1'])->exists();

        if ($tournamentType !== 'double_elimination' && !$hasDoubleElimGames) {
            return back()->with('error', 'La inscripción tardía en torneo iniciado solo está disponible para Doble Eliminatoria.');
        }

        $teamId = (int) $request->team_id;

        try {
            $doubleElimService = app(\App\Services\DoubleEliminationService::class);
            $doubleElimService->addLateTeam($tournament, $teamId, $request->category_group);

            return back()->with('success', 'Equipo inscrito tardíamente con éxito. Se ha integrado directamente al Bracket de Perdedores con 1 derrota técnica.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al inscribir equipo tardío: ' . $e->getMessage());
        }
    }

    /**
     * Calcula la siguiente potencia de 2 mayor o igual al número dado.
     * Ej: 5 -> 8, 6 -> 8, 9 -> 16.
     */
    private function getNextPowerOfTwo($number) {
        $value = 1;
        while ($value < $number) {
            $value *= 2;
        }
        return $value;
    }
}