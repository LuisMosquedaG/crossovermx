<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

        protected $fillable = [
        'name',
        'logo',
        'image_path',
        'coach_name',
        'tournament_id',
        'coach_id',
        'status',
        'suspension_games',
        'contract_accepted_at',
        'category',
        'strength',
        'client_id',
    ];

    protected $casts = [
        
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
    
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    public function schedules()
    {
        return $this->hasMany(TeamSchedule::class);
    }
}