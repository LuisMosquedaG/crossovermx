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
}
