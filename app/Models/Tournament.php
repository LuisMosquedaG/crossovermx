<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'status',
        'is_playoffs',
        'category',
        'fuerza',
        'reglamento',
        'client_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_playoffs' => 'boolean',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
    
    public function settings()
    {
        return $this->hasOne(TournamentSetting::class);
    }

    /**
     * Método para verificar si el torneo ha terminado y actualizar el estado.
     * Debe llamarse cada vez que un partido finaliza.
     */
    public function checkCompletionStatus()
    {
        // Solo verificamos si el torneo ya está activo (no pendiente ni terminado ya)
        if ($this->status !== 'active') {
            return;
        }

        $totalGames = $this->games()->count();
        
        // Si hay partidos generados
        if ($totalGames > 0) {
            $finishedGames = $this->games()->where('status', 'finished')->count();

            // Si todos los partidos están finalizados
            if ($totalGames === $finishedGames) {
                $this->status = 'finished';
                $this->save();
            }
        }
    }
    // En app/Models/Tournament.php

public function client()
{
    return $this->belongsTo(Client::class);
}
}