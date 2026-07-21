<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Models\User;
use App\Models\GameAction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with stats, charts, and leaderboards.
     */
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('Arbitro')) {
                return redirect()->route('tournaments.index');
            }
            if (auth()->user()->hasRole('Coach')) {
                return $this->coachDashboard();
            }
        }

        $clientId = auth()->check() ? auth()->user()->client_id : null;

        // 1. Stats summary metrics
        $activeTournamentsCount = Tournament::where('status', 'active')
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->count();

        $totalTournamentsCount = Tournament::query()
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->count();

        $totalTeamsCount = Team::query()
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->count();

        $totalPlayersCount = Player::query()
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->count();

        $totalRefereesCount = User::whereHas('role', fn($q) => $q->where('name', 'Arbitro'))
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->count();

        // 2. Tournament status distribution (for doughnut chart)
        $finishedTournamentsCount = Tournament::where('status', 'finished')
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->count();
            
        $pendingTournamentsCount = Tournament::whereNotIn('status', ['active', 'finished'])
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->count();

        // 3. Top scorers per tournament (for recent 10 tournaments)
        $tournaments = Tournament::query()
            ->when($clientId, fn($q) => $q->where('client_id', $clientId))
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $topScorersByTournament = [];
        foreach ($tournaments as $tournament) {
            $scorers = GameAction::query()
                ->join('players', 'game_actions.player_id', '=', 'players.id')
                ->join('teams', 'players.team_id', '=', 'teams.id')
                ->where('teams.tournament_id', $tournament->id)
                ->where('game_actions.action_type', 'point_scored')
                ->select(
                    'players.id',
                    'players.name',
                    'players.number',
                    'teams.name as team_name',
                    DB::raw('SUM(game_actions.value) as total_points')
                )
                ->groupBy('players.id', 'players.name', 'players.number', 'teams.name')
                ->orderByDesc('total_points')
                ->limit(5)
                ->get();
            
            if ($scorers->isNotEmpty()) {
                $topScorersByTournament[] = [
                    'id' => $tournament->id,
                    'name' => $tournament->name,
                    'scorers' => $scorers
                ];
            }
        }

        // 4. Record scorer in a single game
        $topSingleGamePlayer = GameAction::query()
            ->join('players', 'game_actions.player_id', '=', 'players.id')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->leftJoin('games', 'game_actions.game_id', '=', 'games.id')
            ->leftJoin('tournaments', 'teams.tournament_id', '=', 'tournaments.id')
            ->where('game_actions.action_type', 'point_scored')
            ->when($clientId, fn($q) => $q->where('teams.client_id', $clientId))
            ->select(
                'players.id',
                'players.name',
                'players.number',
                'teams.name as team_name',
                'tournaments.name as tournament_name',
                'game_actions.game_id',
                DB::raw('SUM(game_actions.value) as points_scored')
            )
            ->groupBy('players.id', 'players.name', 'players.number', 'teams.name', 'tournaments.name', 'game_actions.game_id')
            ->orderByDesc('points_scored')
            ->first();

        // 5. Upcoming games
        $upcomingGames = \App\Models\Game::where('status', 'pending')
            ->when($clientId, function($q) use ($clientId) {
                $q->whereHas('tournament', function($t) use ($clientId) {
                    $t->where('client_id', $clientId);
                });
            })
            ->with(['localTeam', 'awayTeam', 'court', 'tournament'])
            ->orderBy('date_time', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'activeTournamentsCount',
            'totalTournamentsCount',
            'totalTeamsCount',
            'totalPlayersCount',
            'totalRefereesCount',
            'finishedTournamentsCount',
            'pendingTournamentsCount',
            'topScorersByTournament',
            'topSingleGamePlayer',
            'upcomingGames'
        ));
    }

    /**
     * Dashboard específico para entrenadores (Coach)
     */
    public function coachDashboard()
    {
        $coachId = auth()->id();
        $coachTeams = Team::where('coach_id', $coachId)->get();
        $coachTeamIds = $coachTeams->pluck('id')->toArray();
        $coachPlayerIds = Player::whereIn('team_id', $coachTeamIds)->pluck('id')->toArray();

        // 1. Gráfica de Puntos por Jugador por Equipo
        $teamsPlayerPoints = [];
        foreach ($coachTeams as $team) {
            $players = Player::where('team_id', $team->id)->get();
            $playerData = [];
            foreach ($players as $player) {
                $totalPoints = GameAction::where('player_id', $player->id)
                    ->where('action_type', 'point_scored')
                    ->sum('value');
                $playerData[] = [
                    'name' => $player->name . ($player->number ? " (#{$player->number})" : ''),
                    'points' => (int) $totalPoints
                ];
            }
            usort($playerData, fn($a, $b) => $b['points'] <=> $a['points']);
            $teamsPlayerPoints[] = [
                'team_id' => $team->id,
                'team_name' => $team->name,
                'players' => $playerData
            ];
        }

        // 2. Juego con mayor puntuación ganado
        $highestScoringWin = \App\Models\Game::where('status', 'finished')
            ->where(function($q) use ($coachTeamIds) {
                $q->where(function($q2) use ($coachTeamIds) {
                    $q2->whereIn('local_team_id', $coachTeamIds)
                       ->whereColumn('local_team_score', '>', 'away_team_score');
                })->orWhere(function($q2) use ($coachTeamIds) {
                    $q2->whereIn('away_team_id', $coachTeamIds)
                       ->whereColumn('away_team_score', '>', 'local_team_score');
                });
            })
            ->with(['localTeam', 'awayTeam', 'tournament'])
            ->get()
            ->map(function($game) use ($coachTeamIds) {
                $isLocal = in_array($game->local_team_id, $coachTeamIds);
                $coachScore = $isLocal ? $game->local_team_score : $game->away_team_score;
                $opponentScore = $isLocal ? $game->away_team_score : $game->local_team_score;
                $coachTeamName = $isLocal ? ($game->localTeam->name ?? 'Equipo') : ($game->awayTeam->name ?? 'Equipo');
                $opponentTeamName = $isLocal ? ($game->awayTeam->name ?? 'Rival') : ($game->localTeam->name ?? 'Rival');
                return [
                    'game' => $game,
                    'coach_score' => $coachScore,
                    'opponent_score' => $opponentScore,
                    'coach_team_name' => $coachTeamName,
                    'opponent_team_name' => $opponentTeamName,
                    'tournament_name' => $game->tournament->name ?? 'Partido Independiente',
                    'date' => $game->date_time ? $game->date_time->format('d/m/Y') : ''
                ];
            })
            ->sortByDesc('coach_score')
            ->first();

        // 3. Top 5 jugadores con más puntos
        $top5Scorers = GameAction::query()
            ->whereIn('game_actions.player_id', $coachPlayerIds)
            ->where('game_actions.action_type', 'point_scored')
            ->join('players', 'game_actions.player_id', '=', 'players.id')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->select(
                'players.id',
                'players.name',
                'players.number',
                'players.image_path',
                'players.gender',
                'teams.name as team_name',
                DB::raw('SUM(game_actions.value) as total_points')
            )
            ->groupBy('players.id', 'players.name', 'players.number', 'players.image_path', 'players.gender', 'teams.name')
            ->orderByDesc('total_points')
            ->limit(5)
            ->get();

        // 4. Top 5 jugadores con más faltas
        $top5Fouls = GameAction::query()
            ->whereIn('game_actions.player_id', $coachPlayerIds)
            ->whereIn('game_actions.action_type', ['foul_personal', 'foul_technical', 'foul_unsportsmanlike', 'foul_disqualifying'])
            ->join('players', 'game_actions.player_id', '=', 'players.id')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->select(
                'players.id',
                'players.name',
                'players.number',
                'players.image_path',
                'players.gender',
                'teams.name as team_name',
                DB::raw('COUNT(game_actions.id) as total_fouls')
            )
            ->groupBy('players.id', 'players.name', 'players.number', 'players.image_path', 'players.gender', 'teams.name')
            ->orderByDesc('total_fouls')
            ->limit(5)
            ->get();

        // 5. Jugador con más puntos en un partido (Récord individual)
        $topSingleGamePlayer = GameAction::query()
            ->whereIn('game_actions.player_id', $coachPlayerIds)
            ->where('game_actions.action_type', 'point_scored')
            ->join('players', 'game_actions.player_id', '=', 'players.id')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->leftJoin('games', 'game_actions.game_id', '=', 'games.id')
            ->leftJoin('tournaments', 'teams.tournament_id', '=', 'tournaments.id')
            ->select(
                'players.id',
                'players.name',
                'players.number',
                'players.image_path',
                'players.gender',
                'teams.name as team_name',
                'tournaments.name as tournament_name',
                'game_actions.game_id',
                DB::raw('SUM(game_actions.value) as points_in_game')
            )
            ->groupBy('players.id', 'players.name', 'players.number', 'players.image_path', 'players.gender', 'teams.name', 'tournaments.name', 'game_actions.game_id')
            ->orderByDesc('points_in_game')
            ->first();

        // 6. Próximo juego de sus equipos
        $upcomingGame = \App\Models\Game::where('status', 'pending')
            ->where(function($q) use ($coachTeamIds) {
                $q->whereIn('local_team_id', $coachTeamIds)
                  ->orWhereIn('away_team_id', $coachTeamIds);
            })
            ->with(['localTeam', 'awayTeam', 'court', 'tournament'])
            ->orderBy('date_time', 'asc')
            ->first();

        return view('dashboard_coach', compact(
            'coachTeams',
            'teamsPlayerPoints',
            'highestScoringWin',
            'top5Scorers',
            'top5Fouls',
            'topSingleGamePlayer',
            'upcomingGame'
        ));
    }
}
