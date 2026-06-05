<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tournament;
use Illuminate\Auth\Access\HandlesAuthorization;

class TournamentPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver un torneo específico.
     * LÓGICA ACTUALIZADA: Admins y Super Admins ven TODO.
     */
    public function view(User $user, Tournament $tournament)
    {
        
        // Si eres Super Admin o Admin, ves TODO.
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }

        // Si NO eres Admin, aplicamos la protección estricta por Cliente
        if ($user->client_id === $tournament->client_id) {
            return true;
        }

        return false; 
    }

    public function create(User $user)
    {
        // Super Admin y Admin pueden crear
        return $user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->client_id !== null;
    }

    public function update(User $user, Tournament $tournament)
    {
        // Super Admin y Admin pueden editar cualquier torneo
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }
        
        return $user->client_id === $tournament->client_id;
    }

    public function delete(User $user, Tournament $tournament)
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }

        return $user->client_id === $tournament->client_id;
    }

    public function manageStandings(User $user, Tournament $tournament)
    {
        // Para ver standings, admin ve todo.
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }
        
        return $this->view($user, $tournament);
    }
}