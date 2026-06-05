<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}