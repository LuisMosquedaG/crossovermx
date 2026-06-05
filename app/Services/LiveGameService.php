<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Collection;

class LiveGameService
{
    /**
     * Prepara todos los datos necesarios para la vista del partido en vivo.
     */
    public function getGameData(Game $game): array
    {
        $game->load(['localTeam', 'awayTeam', 'tournament.settings']);

        // 1. Obtener Jugadores Activos (con lógica de recuperación inteligente)
        $localActivePlayers = $this->getOrRestoreActivePlayers($game, 'local');
        $awayActivePlayers = $this->getOrRestoreActivePlayers($game, 'away');

        // 2. Obtener Acciones y Estadísticas
        $actions = $game->actions()->with('player')->get();
        $stats = $this->calculateStats($actions);

        // --- NUEVO: Calcular FALTAS DE EQUIPO POR PERIODO ---
        // Obtenemos el periodo actual (si no hay, asumimos 1)
        $currentPeriod = $game->period ?? 1;

        // Filtramos acciones SOLO del periodo actual
        $periodActions = $actions->where('period', $currentPeriod);
        
        // Faltas Equipo Local (suma cualquier acción que contenga 'foul')
        $localTeamFouls = $periodActions->where('team_side', 'local')
            ->filter(function($action) {
                return strpos($action->action_type, 'foul') !== false;
            })
            ->count();

        // Faltas Equipo Visitante
        $awayTeamFouls = $periodActions->where('team_side', 'away')
            ->filter(function($action) {
                return strpos($action->action_type, 'foul') !== false;
            })
            ->count();
        // -------------------------------------------------

        // 3. Obtener Configuración del Torneo
        // CORRECCIÓN PUNTO 3: Prioridad a la configuración individual del juego manual
        // Si el juego tiene settings propios (partido manual), úsalos.
        // Si es null, usa la configuración del torneo padre.
        $settings = $game->settings ?? ($game->tournament->settings->settings ?? []);
        
        // Configuración por defecto si aún no existe nada (fallback final)
        $config = [
            'game_duration' => $settings['game_duration'] ?? 10,
            'total_periods' => $settings['periods_per_game'] ?? 4,
            'timeouts_per_game' => $settings['timeouts_per_game'] ?? 5,
            'foul_limits' => $settings['fouls_per_game'] ?? [
                'personal' => 5, 'technical' => 2, 'unsportsmanlike' => 2, 'disqualifying' => 1
            ]
        ];

        // 4. Calcular Tiempos Muertos Restantes
        $timeoutsLeft = $this->calculateTimeoutsLeft($actions, $config['timeouts_per_game']);

         return [
            'game' => $game,
            'localActivePlayers' => $localActivePlayers,
            'awayActivePlayers' => $awayActivePlayers,
            'actions' => $actions,
            'localStats' => $stats['local'],
            'awayStats' => $stats['away'],
            'localTimeoutsLeft' => $timeoutsLeft['local'],
            'awayTimeoutsLeft' => $timeoutsLeft['away'],
            // Extraemos las variables individuales del config para que la vista las lea directo
            'gameDurationMinutes' => $config['game_duration'],
            'totalPeriods' => $config['total_periods'],
            'timeoutsPerGame' => $config['timeouts_per_game'],
            'foulLimits' => $config['foul_limits'],
            // --- RETORNAMOS LAS VARIABLES QUE ACABAMOS DE CALCULAR ---
            'localTeamFouls' => $localTeamFouls,
            'awayTeamFouls' => $awayTeamFouls
        ];
    }

    /**
     * Recupera o inicializa los jugadores activos para un lado de la cancha.
     */
    private function getOrRestoreActivePlayers(Game $game, string $side): Collection
    {
        $activePlayers = $game->players()
            ->wherePivot('team_side', $side)
            ->wherePivot('is_active', true)
            ->get();

        if ($activePlayers->isNotEmpty()) {
            return $activePlayers;
        }

        // Si no hay activos, intentamos recuperar los "starters"
        $starters = $game->players()
            ->wherePivot('team_side', $side)
            ->wherePivot('is_starter', true)
            ->get();

        if ($starters->isNotEmpty()) {
            foreach ($starters as $player) {
                $game->players()->updateExistingPivot($player->id, ['is_active' => true]);
            }
            return $starters;
        }

        // Si no hay starters, tomamos los primeros 5 jugadores del equipo
        $teamId = ($side === 'local') ? $game->local_team_id : $game->away_team_id;
        
        $teamPlayers = Player::where('team_id', $teamId)
            ->where('status', 'active')
            ->orderBy('number', 'asc')
            ->take(5)
            ->get();

        if ($teamPlayers->isNotEmpty()) {
            foreach ($teamPlayers as $player) {
                $game->players()->attach($player->id, [
                    'team_side' => $side,
                    'is_starter' => true,
                    'is_active' => true
                ]);
            }
            return $teamPlayers;
        }

        return collect();
    }

    /**
     * Calcula puntos y faltas por jugador a partir de las acciones.
     */
    private function calculateStats(Collection $actions): array
    {
        $stats = ['local' => [], 'away' => []];

        foreach ($actions as $action) {
            if (!$action->player_id) continue;

            $side = $action->team_side;
            $pid = $action->player_id;

            if (!isset($stats[$side][$pid])) {
                $stats[$side][$pid] = [
                    'points' => 0,
                    'fouls' => [
                        'personal' => 0, 'technical' => 0, 
                        'unsportsmanlike' => 0, 'disqualifying' => 0
                    ]
                ];
            }

            if ($action->action_type === 'point_scored') {
                $stats[$side][$pid]['points'] += $action->value;
            } elseif (strpos($action->action_type, 'foul') !== false) {
                $typeKey = 'personal';
                if ($action->action_type === 'foul_technical') $typeKey = 'technical';
                if ($action->action_type === 'foul_unsportsmanlike') $typeKey = 'unsportsmanlike';
                if ($action->action_type === 'foul_disqualifying') $typeKey = 'disqualifying';
                
                $stats[$side][$pid]['fouls'][$typeKey]++;
            }
        }

        return $stats;
    }

    private function calculateTimeoutsLeft(Collection $actions, int $limitPerGame): array
    {
        $usedLocal = $actions->where('team_side', 'local')->where('action_type', 'timeout_called')->count();
        $usedAway = $actions->where('team_side', 'away')->where('action_type', 'timeout_called')->count();

        return [
            'local' => max(0, $limitPerGame - $usedLocal),
            'away' => max(0, $limitPerGame - $usedAway)
        ];
    }

    /**
     * Valida si los jugadores seleccionados pueden iniciar (suspensiones y pertenencia).
     */
    public function validateStartingLineup(Game $game, array $localIds, array $awayIds): ?string
    {
        // Validar Pertenencia
        $localPlayers = Player::whereIn('id', $localIds)->where('team_id', $game->local_team_id)->get();
        $awayPlayers = Player::whereIn('id', $awayIds)->where('team_id', $game->away_team_id)->get();

        if ($localPlayers->count() !== count($localIds) || $awayPlayers->count() !== count($awayIds)) {
            return 'Uno o más jugadores no pertenecen al equipo correspondiente.';
        }

        // Validar Suspensiones (Locales)
        $suspendedLocal = $localPlayers->where('status', 'suspended');
        if ($suspendedLocal->isNotEmpty()) {
            return "Jugadores locales suspendidos: " . $suspendedLocal->pluck('name')->implode(', ');
        }

        // Validar Suspensiones (Visitantes)
        $suspendedAway = $awayPlayers->where('status', 'suspended');
        if ($suspendedAway->isNotEmpty()) {
            return "Jugadores visitantes suspendidos: " . $suspendedAway->pluck('name')->implode(', ');
        }

        return null; // Todo OK
    }
}