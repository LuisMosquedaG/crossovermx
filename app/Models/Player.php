<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        $category = null;
        if ($this->team_id) {
            // Buscamos el equipo y su categoría de forma eficiente
            $team = $this->team;
            if ($team) {
                $category = trim(strtolower($team->category ?? ''));
            }
        }
        
        if ($category === 'infantil') {
            return $this->gender === 'mujer' ? asset('images/niña.png') : asset('images/niño.png');
        }
        
        return $this->gender === 'mujer' ? asset('images/mujer.png') : asset('images/hombre.png');
    }

    protected $fillable = [
        'name',
        'number',
        'position',
        'gender',
        'team_id',
        'rfc',
        'image_path',
        'status',
        'suspension_games',
        'client_id',
        'curp',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_address',
        'emergency_contact_phone',        
    ];

    protected $casts = [
        // Usamos 'encrypted' como texto (string) en lugar de la clase
        'rfc' => 'encrypted',
        'curp' => 'encrypted',
        'blood_type' => 'encrypted',
        
        // Datos de emergencia
        'emergency_contact_name' => 'encrypted',
        'emergency_contact_phone' => 'encrypted',
        'emergency_contact_address' => 'encrypted',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_player')
                    ->withPivot('is_starter', 'team_side');
    }
}