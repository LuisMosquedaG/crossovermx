<?php

namespace App\Strategies\Tournament;

use App\Models\Tournament;
use App\Services\CalendarGeneratorService;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class LeagueStrategy implements TournamentGenerationStrategyInterface
{
    protected $calendarService;

    public function __construct(CalendarGeneratorService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function generate(Tournament $tournament, array $config, Collection $cumulativeGames): array
    {
        // 1. Agrupamos equipos
        $groups = $tournament->teams->groupBy(function ($item) {
            $cat = trim($item->category ?? 'Sin Categoria');
            $strength = trim($item->strength ?? 'General');
            return $cat . ' - ' . $strength;
        });

        // Ordenamos los grupos alfabéticamente
        $groups = $groups->sortKeys();

        // Validación de mínimo 2 equipos
        $invalidGroups = [];
        foreach ($groups as $groupName => $teams) {
            if ($teams->count() < 2) {
                $invalidGroups[] = "{$groupName} ({$teams->count()} equipo(s))";
            }
        }

        if (!empty($invalidGroups)) {
            throw new \Exception(
                "No se puede generar el calendario. Las siguientes categorías/fuerzas tienen menos de 2 equipos: " . 
                implode(', ', $invalidGroups) . 
                ". Se requieren al menos 2 equipos."
            );
        }

        // 2. Preparamos estructura de rondas (Solo matemática)
        $groupsScheduleStructure = [];
        $maxRounds = 0;

        foreach ($groups as $groupName => $teams) {
            if ($teams->count() < 2) continue;
            
            $teamIds = $teams->pluck('id')->toArray();
            $rounds = $this->calendarService->getRoundRobinStructure($teamIds);
            
            $groupsScheduleStructure[$groupName] = $rounds;
            
            if (count($rounds) > $maxRounds) $maxRounds = count($rounds);
        }

        // 3. Determinar estrategia
        $isInterleaved = $config['interleave_categories'] ?? true;
        
        // Fechas base
        $currentStartDate = Carbon::parse($tournament->start_date);
        $tournamentEnd = $tournament->end_date ? Carbon::parse($tournament->end_date) : null;

        // =================================================================
        // MODO INTERCALADO (Original)
        // =================================================================
        if ($isInterleaved) {
            for ($r = 0; $r < $maxRounds; $r++) {
                $currentRoundMatchups = [];
                
                foreach ($groupsScheduleStructure as $groupName => $rounds) {
                    if (isset($rounds[$r])) {
                        foreach ($rounds[$r] as $pair) {
                            $currentRoundMatchups[] = [
                                'local' => $pair['home'],
                                'away' => $pair['away'],
                                'group_name' => $groupName
                            ];
                        }
                    }
                }

                if (empty($currentRoundMatchups)) continue;

                // Ajuste de fecha dinámico
                if ($cumulativeGames->isNotEmpty()) {
                    $lastGameDate = $cumulativeGames->max('date_time');
                    if ($lastGameDate) {
                        $currentStartDate = Carbon::parse($lastGameDate)->addDay();
                    }
                }

                $config['interleave_categories'] = true; 

                $gamesScheduled = $this->calendarService->schedulePlayoffRound(
                    $tournament, 
                    $currentRoundMatchups, 
                    $currentStartDate,
                    $tournamentEnd ?? $currentStartDate->copy()->addMonth(),
                    $config, 
                    $cumulativeGames, 
                    false
                );

                $cumulativeGames = $cumulativeGames->concat($gamesScheduled);
            }
        } 
        
        // =================================================================
        // MODO NO INTERCALADO (CORREGIDO)
        // =================================================================
        else {
            // En lugar de pasar TODOS los juegos de golpe (lo que fallaba),
            // iteramos Categoría por Categoría, y dentro de cada categoría, Ronda por Ronda.
            // Así usamos el motor "Robusto" (Cola) pero simulando el flujo de corridas.
            
            foreach ($groupsScheduleStructure as $groupName => $rounds) {
                // Iteramos cada ronda de ESTA categoría
                foreach ($rounds as $roundPairings) {
                    $currentRoundMatchups = [];

                    foreach ($roundPairings as $pair) {
                        $currentRoundMatchups[] = [
                            'local' => $pair['home'],
                            'away' => $pair['away'],
                            'group_name' => $groupName
                        ];
                    }

                    if (empty($currentRoundMatchups)) continue;

                    // Ajuste de fecha
                    if ($cumulativeGames->isNotEmpty()) {
                        $lastGameDate = $cumulativeGames->max('date_time');
                        if ($lastGameDate) {
                            $currentStartDate = Carbon::parse($lastGameDate)->addDay();
                        } else {
                            $currentStartDate = Carbon::parse($tournament->start_date);
                        }
                    } else {
                        $currentStartDate = Carbon::parse($tournament->start_date);
                    }

                    // TRUCO: Forzamos interleave_categories = true.
                    // Aunque solo pasemos juegos de una categoría, esto activa el algoritmo de Cola
                    // que sí funciona, en lugar del algoritmo de "Corridas" que falla.
                    // Al ser una sola categoría, la cola simplemente los ordenará secuencialmente.
                    $config['interleave_categories'] = true; 

                    $gamesScheduled = $this->calendarService->schedulePlayoffRound(
                        $tournament, 
                        $currentRoundMatchups, 
                        $currentStartDate,
                        $tournamentEnd ?? $currentStartDate->copy()->addMonth(),
                        $config, 
                        $cumulativeGames, 
                        false
                    );
                    
                    $cumulativeGames = $cumulativeGames->concat($gamesScheduled);
                }
            }
        }

        return ['games' => $cumulativeGames, 'config' => $config];
    }
}