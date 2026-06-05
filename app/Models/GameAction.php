<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameAction extends Model
{
    protected $fillable = [
        'game_id',
        'player_id',
        'team_side',
        'action_type',
        'value',
        'period',
        'seconds',
    ];

    // Relación con el juego
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // Relación con el jugador
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}