<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'local_team_id',
        'away_team_id',
        'court_id',
        'date_time',
        'status',
        'local_team_score',
        'away_team_score',
        'is_playoff',
        'group_name',
        'category_group',
        'round_number',
        'settings',
        'client_id',
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'is_playoff' => 'boolean',
        'settings' => 'array'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function localTeam()
    {
        return $this->belongsTo(Team::class, 'local_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'game_player')
                    ->withPivot('is_starter', 'team_side', 'is_active'); // <--- Agregamos 'is_active'
    }
    
    public function actions()
    {
        return $this->hasMany(GameAction::class)->orderBy('created_at', 'desc');
    }
        public function referee()
    {
        return $this->belongsTo(User::class, 'referee_id');
    }
    public function comments()
    {
        return $this->hasMany(GameComment::class)->orderBy('created_at', 'desc');
    }
    public function getWinnerId()
{
    if ($this->status !== 'finished') return null;
    // Si local > away es local, si no es away (asumimos no empates en playoffs o que ya está resuelto)
    return ($this->local_team_score > $this->away_team_score) 
        ? $this->local_team_id 
        : $this->away_team_id;
}

public function getLoserId()
{
    if ($this->status !== 'finished') return null;
    return ($this->local_team_score > $this->away_team_score) 
        ? $this->away_team_id 
        : $this->local_team_id;
}

    /**
     * Cache en memoria por request para evitar consultas redundantes
     */
    protected static array $roundClosedCache = [];

    /**
     * Determina si la ronda de este partido ya fue cerrada y no debe permitirse modificar el marcador.
     * Evita inconsistencias en Doble Eliminatoria, Eliminatoria Directa y Todos Contra Todos.
     */
    public function isRoundClosed(): bool
    {
        if (!$this->tournament_id) {
            return false;
        }

        $cacheKey = $this->id;
        if ($cacheKey && isset(self::$roundClosedCache[$cacheKey])) {
            return self::$roundClosedCache[$cacheKey];
        }

        $tournament = $this->tournament;
        if (!$tournament) {
            return false;
        }

        // 1. Si el torneo completo ya finalizó, verificar si realmente terminó o si tiene juegos pendientes/en juego
        if ($tournament->status === 'finished') {
            $hasActiveGames = self::where('tournament_id', $this->tournament_id)
                ->whereIn('status', ['pending', 'playing'])
                ->exists();
            if (!$hasActiveGames) {
                return self::$roundClosedCache[$cacheKey] = true;
            } else {
                // Hay juegos pendientes o en juego, el torneo debe permanecer activo
                $tournament->status = 'active';
                $tournament->save();
            }
        }

        $tournamentSettings = $tournament->settings ? $tournament->settings->settings : [];
        $tournamentType = $tournamentSettings['tournament_type'] ?? 'round_robin';

        $gn = $this->group_name ?? '';

        $isDoubleElim = (
            $tournamentType === 'double_elimination' ||
            str_starts_with($gn, 'WB_') ||
            str_starts_with($gn, 'LB_') ||
            in_array($gn, ['GF', 'GR'])
        );

        // --- 2. DOBLE ELIMINATORIA ---
        if ($isDoubleElim) {
            $categoryGamesQuery = self::where('tournament_id', $this->tournament_id)
                ->where('is_playoff', true)
                ->when($this->category_group, function ($q) {
                    $q->where('category_group', $this->category_group);
                });

            // 2a. Si es Winner Bracket: WB_R{N}
            if (preg_match('/^WB_R(\d+)$/', $gn, $matches)) {
                $currentRoundNum = (int) $matches[1];

                $allGroups = (clone $categoryGamesQuery)->pluck('group_name')->unique();

                foreach ($allGroups as $otherGroup) {
                    if (preg_match('/^WB_R(\d+)$/', $otherGroup, $m)) {
                        if ((int) $m[1] > $currentRoundNum) {
                            return self::$roundClosedCache[$cacheKey] = true;
                        }
                    }
                }

                if ($allGroups->contains('GF') || $allGroups->contains('GR')) {
                    return self::$roundClosedCache[$cacheKey] = true;
                }

                // Verificar si alguno de los dos equipos ya tiene un partido asignado en LB o en rondas superiores
                $teamIds = array_filter([$this->local_team_id, $this->away_team_id]);
                if (!empty($teamIds)) {
                    $hasAdvanced = (clone $categoryGamesQuery)
                        ->where('id', '!=', $this->id)
                        ->where(function ($q) use ($teamIds) {
                            $q->whereIn('local_team_id', $teamIds)
                              ->orWhereIn('away_team_id', $teamIds);
                        })
                        ->where(function ($q) {
                            $q->where('group_name', 'like', 'LB_%')
                              ->orWhere('group_name', 'GF')
                              ->orWhere('group_name', 'GR');
                        })
                        ->exists();

                    if ($hasAdvanced) {
                        return self::$roundClosedCache[$cacheKey] = true;
                    }
                }
            }

            // 2b. Si es Loser Bracket: LB_R{N}
            if (preg_match('/^LB_R(\d+)$/', $gn, $matches)) {
                $currentRoundNum = (int) $matches[1];

                $allGroups = (clone $categoryGamesQuery)->pluck('group_name')->unique();

                foreach ($allGroups as $otherGroup) {
                    if (preg_match('/^LB_R(\d+)$/', $otherGroup, $m)) {
                        if ((int) $m[1] > $currentRoundNum) {
                            return self::$roundClosedCache[$cacheKey] = true;
                        }
                    }
                }

                if ($allGroups->contains('GF') || $allGroups->contains('GR')) {
                    return self::$roundClosedCache[$cacheKey] = true;
                }

                $teamIds = array_filter([$this->local_team_id, $this->away_team_id]);
                if (!empty($teamIds)) {
                    $hasAdvanced = (clone $categoryGamesQuery)
                        ->where('id', '!=', $this->id)
                        ->where(function ($q) use ($teamIds) {
                            $q->whereIn('local_team_id', $teamIds)
                              ->orWhereIn('away_team_id', $teamIds);
                        })
                        ->where(function ($q) {
                            $q->where('group_name', 'GF')
                              ->orWhere('group_name', 'GR');
                        })
                        ->exists();

                    if ($hasAdvanced) {
                        return self::$roundClosedCache[$cacheKey] = true;
                    }
                }
            }

            // 2c. Si es Gran Final: GF
            if ($gn === 'GF') {
                $hasGr = (clone $categoryGamesQuery)->where('group_name', 'GR')->exists();
                if ($hasGr) {
                    return self::$roundClosedCache[$cacheKey] = true;
                }
            }
        }

        // --- 3. ELIMINATORIA DIRECTA / PLAYOFFS ---
        if ($this->is_playoff && !$isDoubleElim) {
            $cat = $this->category_group ?: $this->group_name;
            $playoffQuery = self::where('tournament_id', $this->tournament_id)
                ->where('is_playoff', true)
                ->when($cat, function ($q) use ($cat) {
                    $q->where(function ($sq) use ($cat) {
                        $sq->where('category_group', $cat)
                           ->orWhere('group_name', $cat);
                    });
                });

            $currentRoundNum = $this->round_number ?: 1;

            $hasHigherRound = (clone $playoffQuery)
                ->where('round_number', '>', $currentRoundNum)
                ->exists();

            if ($hasHigherRound) {
                return self::$roundClosedCache[$cacheKey] = true;
            }

            $teamIds = array_filter([$this->local_team_id, $this->away_team_id]);
            if (!empty($teamIds)) {
                $hasAdvanced = (clone $playoffQuery)
                    ->where('id', '!=', $this->id)
                    ->where(function ($q) use ($teamIds) {
                        $q->whereIn('local_team_id', $teamIds)
                          ->orWhereIn('away_team_id', $teamIds);
                    })
                    ->where(function ($q) use ($currentRoundNum) {
                        $q->where('round_number', '>', $currentRoundNum)
                          ->orWhere('created_at', '>', $this->created_at);
                    })
                    ->exists();

                if ($hasAdvanced) {
                    return self::$roundClosedCache[$cacheKey] = true;
                }
            }
        }

        // --- 4. TODOS CONTRA TODOS (Round Robin / Fase regular) ---
        if (!$this->is_playoff) {
            $group = $this->group_name ?: $this->category_group;
            $groupGamesQuery = self::where('tournament_id', $this->tournament_id)
                ->when($group, function ($q) use ($group) {
                    $q->where(function ($sq) use ($group) {
                        $sq->where('group_name', $group)
                           ->orWhere('category_group', $group);
                    });
                });

            $currentRoundNum = $this->round_number ?: 1;

            // 4a. Si en la configuración del torneo la ronda activa es superior a la de este partido
            $activeRoundForGroup = (int)($tournamentSettings['active_rounds'][$group] ?? 0);
            if ($activeRoundForGroup > $currentRoundNum) {
                return self::$roundClosedCache[$cacheKey] = true;
            }

            // 4b. Si ya se generó una vuelta posterior (round_number superior)
            $hasHigherRoundNumber = (clone $groupGamesQuery)
                ->where('is_playoff', false)
                ->where('round_number', '>', $currentRoundNum)
                ->exists();

            if ($hasHigherRoundNumber) {
                return self::$roundClosedCache[$cacheKey] = true;
            }

            // 4b. Si ya se generaron los Playoffs basados en esta fase
            $hasPlayoffs = (clone $groupGamesQuery)
                ->where('is_playoff', true)
                ->exists();

            if ($hasPlayoffs || !empty($tournament->is_playoffs)) {
                return self::$roundClosedCache[$cacheKey] = true;
            }
        }

        return self::$roundClosedCache[$cacheKey] = false;
    }
}