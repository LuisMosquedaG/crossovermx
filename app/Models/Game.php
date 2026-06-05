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
}