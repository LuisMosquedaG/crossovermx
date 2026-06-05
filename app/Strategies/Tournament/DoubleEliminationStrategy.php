<?php

namespace App\Strategies\Tournament;

use App\Models\Tournament;
use App\Services\DoubleEliminationService;
use App\Services\CalendarGeneratorService;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DoubleEliminationStrategy implements TournamentGenerationStrategyInterface
{
    protected $doubleElimService;
    protected $calendarService;

    public function __construct(DoubleEliminationService $doubleElimService, CalendarGeneratorService $calendarService)
    {
        $this->doubleElimService = $doubleElimService;
        $this->calendarService = $calendarService;
    }

    public function generate(Tournament $tournament, array $config, Collection $cumulativeGames): array
    {
        $groups = $tournament->teams->groupBy(function ($item) {
            $cat = trim($item->category ?? 'General');
            $str = trim($item->strength ?? 'General');
            return $cat . ' - ' . $str;
        });
        
        // Ordenar grupos para consistencia
        $groups = $groups->sortKeys();

        // Array para almacenar los datos de cada categoría por separado
        $bracketsDataByCategory = [];

        // FASE 1: RECOLECCIÓN DE DATOS
        // Recorremos todas las categorías para generar sus estructuras matemáticas y guardar su config
        foreach ($groups as $groupName => $teams) {
            if ($teams->count() < 2) continue;

            // Generamos la estructura del bracket (Rondas, Byes, etc.)
            $bracketData = $this->doubleElimService->calculateBracketStructure(
                $teams->pluck('id')->toArray(), 
                $groupName
            );

            if (empty($bracketData['matchups'])) {
                continue;
            }

            // Guardamos los datos de este bracket en el array maestro
            $bracketsDataByCategory[$groupName] = [
                'matchups' => $bracketData['matchups'],
                'config' => [
                    'mode' => 'generic_double_elimination',
                    'team_count' => count($teams),
                    'specific_team_ids' => $teams->pluck('id')->toArray(),
                    'group_name' => $groupName,
                    'wb_total_rounds' => $bracketData['config']['wb_total_rounds'] ?? 1,
                    'wb_current_round' => 1,
                    'wb_byes' => $bracketData['config']['wb_byes'] ?? [],
                ]
            ];
        }

        if (empty($bracketsDataByCategory)) {
            return ['games' => $cumulativeGames, 'config' => $config];
        }

        // Verificamos la configuración real del usuario
        $isInterleaved = $config['interleave_categories'] ?? true;

        $startDate = Carbon::parse($tournament->start_date);
        $endDate = $tournament->end_date ? Carbon::parse($tournament->end_date) : $startDate->copy()->addMonth();

        // FASE 2: PROGRAMACIÓN
        // =================================================================
        // CASO A: INTERCALADO (Marcado)
        // =================================================================
        if ($isInterleaved) {
            $allMatchups = [];

            // Unificamos los juegos de todas las categorías en una sola lista
            foreach ($bracketsDataByCategory as $data) {
                $allMatchups = array_merge($allMatchups, $data['matchups']);
                
                // Guardamos la config en el array final
                $config['brackets_data'][$data['config']['group_name']] = $data['config'];
            }

            // Forzamos el intercalado en la config (ya que el usuario lo pidió)
            $config['interleave_categories'] = true;

            // Llamada única al servicio
            $gamesScheduled = $this->calendarService->schedulePlayoffRound(
                $tournament,
                $allMatchups,
                $startDate,
                $endDate,
                $config,
                $cumulativeGames,
                true
            );
            
            $cumulativeGames = $cumulativeGames->concat($gamesScheduled);
        } 
        
        // =================================================================
        // CASO B: NO INTERCALADO (Desmarcado)
        // =================================================================
        else {
            // Iteramos categoría por categoría para que salgan "corridas"
            foreach ($bracketsDataByCategory as $groupName => $categoryData) {
                
                // Ajustamos la fecha de inicio para esta categoría basándonos en lo anterior
                $currentStartDate = $startDate->copy();
                if ($cumulativeGames->isNotEmpty()) {
                    $lastGameDate = $cumulativeGames->max('date_time');
                    if ($lastGameDate) {
                        $currentStartDate = Carbon::parse($lastGameDate)->addDay();
                    }
                }

                // Guardamos la config de este grupo
                $config['brackets_data'][$groupName] = $categoryData['config'];

                // TRUCO: Forzamos interleave_categories = true internamente.
                // Esto activa el motor de Cola (que funciona), pero como le pasamos
                // solo los juegos de UNA categoría, los programará en secuencia.
                $config['interleave_categories'] = true;

                $gamesForCategory = $this->calendarService->schedulePlayoffRound(
                    $tournament,
                    $categoryData['matchups'], // Solo juegos de esta categoría
                    $currentStartDate,
                    $endDate,
                    $config,
                    $cumulativeGames,
                    true
                );

                // Acumulamos los juegos generados
                $cumulativeGames = $cumulativeGames->concat($gamesForCategory);
            }
        }

        // Actualización de número de ronda para WB_R1 (Lógica auxiliar)
        foreach ($groups->keys() as $groupName) {
             \App\Models\Game::where('tournament_id', $tournament->id)
                ->where('group_name', 'WB_R1')
                ->where('category_group', $groupName)
                ->update(['round_number' => 1]);
        }

        return [
            'games' => $cumulativeGames,
            'config' => $config
        ];
    }
}