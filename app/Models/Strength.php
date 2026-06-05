<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strength extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_id',
    ];

    /**
     * RELACIÓN: Una fuerza tiene muchos equipos
     * Ajuste: Busca equipos donde la columna 'strength' (texto) coincida con el 'name' de esta fuerza.
     */
    public function teams()
    {
        // 'strength' = columna en la tabla teams (hija)
        // 'name'    = columna en la tabla strengths (padre)
        return $this->hasMany(Team::class, 'strength', 'name');
    }
    
    /**
     * RELACIÓN: Una fuerza pertenece a un cliente
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}