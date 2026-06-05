<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Game;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver el partido en vivo.
     */
    public function view(User $user, Game $game)
    {
        // 1. Super Admin y Admin ven todo
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) return true;

        // 2. Determinar a qué cliente pertenece el partido
        // Si tiene torneo, usa el cliente del torneo. Si es manual (null), usa el cliente del juego.
        $gameClientId = $game->tournament_id 
            ? ($game->tournament->client_id ?? null) 
            : $game->client_id;

        // Si por alguna razón no tenemos client_id, denegamos
        if (!$gameClientId) return false;

        // 3. Verificar que el usuario pertenezca al mismo cliente
        if ($user->client_id !== $gameClientId) return false;

        // 4. Verificaciones específicas de rol (Árbitro o Coach)
        if ($user->id === $game->referee_id) return true;
        
        if ($game->localTeam && $game->localTeam->coach_id === $user->id) return true;
        
        if ($game->awayTeam && $game->awayTeam->coach_id === $user->id) return true;

        return false;
    }

    /**
     * Determina si el usuario puede gestionar el partido en vivo (Tiempos, Puntos, Periodos).
     * IMPORTANTE: Esto controla el botón de Cancelar y Operar Partido.
     */
    public function update(User $user, Game $game)
    {
        // 1. Super Admin y Admin pueden alterar el estado del juego
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) return true;
        
        // 2. Seguridad para Partidos Manuales: Verificar que el árbitro es del mismo cliente
        // Si es un partido manual, $game->tournament es null, así que usamos la lógica dual:
        $gameClientId = $game->tournament_id 
            ? ($game->tournament->client_id ?? null) 
            : $game->client_id;

        // Evitar que un árbitro del Cliente A opere un partido del Cliente B
        if ($user->client_id !== $gameClientId) return false;

        // 3. Árbitro asignado
        return $user->id === $game->referee_id;
    }

    /**
     * Determina si el usuario puede finalizar el partido.
     */
    public function finish(User $user, Game $game)
    {
        // 1. Admin y Super Admin globales
        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) return true;
        
        // 2. Si es árbitro, verificamos también el cliente (por seguridad en partidos manuales)
        if ($user->hasRole('Arbitro')) {
            $gameClientId = $game->tournament_id 
                ? ($game->tournament->client_id ?? null) 
                : $game->client_id;

            return $user->client_id === $gameClientId && $user->id === $game->referee_id;
        }

        return false;
    }

    /**
     * Determina si el usuario puede asignar árbitros.
     */
    public function assignReferee(User $user, Game $game)
    {
        // 1. Super Admin y Admin pueden asignar
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) return true;
        
        // 2. Verificación dual de cliente (Torneo vs Manual)
        $gameClientId = $game->tournament_id 
            ? ($game->tournament->client_id ?? null) 
            : $game->client_id;

        return $user->client_id === $gameClientId;
    }

    /**
     * Determina si el usuario puede suspender (Jugador o Equipo).
     */
    public function suspend(User $user, Game $game)
    {
        // 1. Super Admin y Admin pueden decretar suspensiones
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) return true;
        
        // 2. Verificación dual de cliente (Torneo vs Manual)
        $gameClientId = $game->tournament_id 
            ? ($game->tournament->client_id ?? null) 
            : $game->client_id;
            
        return $user->client_id === $gameClientId;
    }
    
}