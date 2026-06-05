<?php

namespace App\Strategies\Tournament;

use App\Models\Tournament;
use App\Services\CalendarGeneratorService;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class EliminationStrategy implements TournamentGenerationStrategyInterface
{
    protected $calendarService;

    public function __construct(CalendarGeneratorService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function generate(Tournament $tournament, array $config, Collection $cumulativeGames): array
    {
        // 1. Agrupamos equipos (Tu lógica original)
        $groups = $tournament->teams->groupBy(function ($item) {
            $cat = trim($item->category ?? 'General');
            $str = trim($item->strength ?? 'General');
            return $cat . ' - ' . $str;
        });

        // Array para guardar los cruces, pero ahora separados por categoría
        $matchupsByCategory = [];
        
        $config['current_byes'] = $config['current_byes'] ?? [];
        $isInterleaved = $config['interleave_categories'] ?? true;

        // 2. Generación de Cruces (Tu lógica original, solo guardamos distinto)
        foreach ($groups as $groupName => $teams) {
            if ($teams->count() < 2) continue;

            $shuffledTeams = $teams->shuffle();
            $teamIds = $shuffledTeams->pluck('id')->toArray();

            // Lógica de Bye (Tu lógica original)
            $targetSize = $this->getNextPowerOfTwo(count($teamIds));
            $byesNeeded = $targetSize - count($teamIds);

            for ($b = 0; $b < $byesNeeded; $b++) {
                $teamIds[] = null;
            }

            // Generar Cruces y guardarlos en el array por categoría
            if (!isset($matchupsByCategory[$groupName])) {
                $matchupsByCategory[$groupName] = [];
            }

            for ($i = 0; $i < count($teamIds); $i += 2) {
                if (!isset($teamIds[$i + 1])) continue;

                $local = $teamIds[$i];
                $away = $teamIds[$i + 1];

                if ($local !== null && $away !== null) {
                    $matchupsByCategory[$groupName][] = [
                        'local' => $local,
                        'away' => $away,
                        'group_name' => $groupName
                    ];
                } else {
                    $teamWithBye = ($local !== null) ? $local : $away;
                    if (!isset($config['current_byes'][$groupName])) {
                        $config['current_byes'][$groupName] = [];
                    }
                    if ($teamWithBye !== null) {
                        $config['current_byes'][$groupName][] = $teamWithBye;
                    }
                }
            }
        }

        // 3. Programación (Parte Corregida)
        $startDate = Carbon::parse($tournament->start_date);
        $endDate = $tournament->end_date ? Carbon::parse($tournament->end_date) : $startDate->copy()->addWeek();

        // =================================================================
        // CASO A: ESTÁ MARCADO "INTERCALAR" (Tu lógica original funciona)
        // =================================================================
        if ($isInterleaved) {
            $allEliminationMatchups = [];
            
            // Unificamos todo
            foreach ($matchupsByCategory as $games) {
                $allEliminationMatchups = array_merge($allEliminationMatchups, $games);
            }

            // Mezclamos
            shuffle($allEliminationMatchups);

            // Llamada única al servicio
            $gamesElimination = $this->calendarService->schedulePlayoffRound(
                $tournament, $allEliminationMatchups, $startDate, $endDate, 
                $config, $cumulativeGames, true
            );

            return ['games' => $cumulativeGames->concat($gamesElimination), 'config' => $config];
        } 
        
        // =================================================================
        // CASO B: NO ESTÁ MARCADO "INTERCALAR" (Nueva Lógica Segura)
        // =================================================================
        else {
            // Iteramos categoría por categoría
            foreach ($matchupsByCategory as $groupName => $games) {
                if (empty($games)) continue;

                // Calculamos la fecha de inicio para esta categoría específica
                // basándonos en lo que ya se ha programado anteriormente
                $currentStartDate = $startDate->copy();
                if ($cumulativeGames->isNotEmpty()) {
                    $lastGameDate = $cumulativeGames->max('date_time');
                    if ($lastGameDate) {
                        $currentStartDate = Carbon::parse($lastGameDate)->addDay();
                    }
                }

                // TRUCO: Forzamos interleave_categories = true.
                // Al pasarle solo los juegos de UNA categoría, el motor de cola
                // los programará en orden secuencial (efecto corrida), pero 
                // usando el algoritmo que no falla.
                $config['interleave_categories'] = true;

                $gamesForCategory = $this->calendarService->schedulePlayoffRound(
                    $tournament, 
                    $games, // Solo juegos de esta categoría
                    $currentStartDate, 
                    $endDate, 
                    $config, 
                    $cumulativeGames, // Pasamos los acumulados para verificar solapamientos
                    true
                );

                // Acumulamos los resultados para la siguiente vuelta del bucle
                $cumulativeGames = $cumulativeGames->concat($gamesForCategory);
            }

            return ['games' => $cumulativeGames, 'config' => $config];
        }
    }

    private function getNextPowerOfTwo($number) {
        $value = 1;
        while ($value < $number) $value *= 2;
        return $value;
    }
}