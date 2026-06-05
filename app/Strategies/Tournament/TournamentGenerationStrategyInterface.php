<?php

namespace App\Strategies\Tournament;

use App\Models\Tournament;
use Illuminate\Support\Collection;

interface TournamentGenerationStrategyInterface
{
    /**
     * Genera los juegos basados en la configuración.
     * 
     * @param Tournament $tournament
     * @param array $config La configuración completa del torneo (se pasará por referencia para modificarla)
     * @param Collection $cumulativeGames Juegos ya generados en pasos anteriores (para evitar solapamiento)
     * @return array Debe retornar un array con:
     *               ['games' => Collection, 'config' => array]
     */
    public function generate(Tournament $tournament, array $config, Collection $cumulativeGames): array;
}