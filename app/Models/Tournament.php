<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
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
        $totalGames = $this->games()->count();
        
        // Si hay partidos generados
        if ($totalGames > 0) {
            $finishedGames = $this->games()->where('status', 'finished')->count();

            // Si todos los partidos están finalizados
            if ($totalGames === $finishedGames) {
                // Verificar si hay una siguiente vuelta activa sin juegos creados aún
                $settings = $this->settings ? $this->settings->settings : [];
                $hasActiveNextRound = false;
                if (!empty($settings['is_manual']) && !empty($settings['active_rounds'])) {
                    foreach ($settings['active_rounds'] as $grp => $activeRnd) {
                        $maxRndGames = $this->games()->where(function($q) use ($grp) {
                            $q->where('group_name', $grp)->orWhere('category_group', $grp);
                        })->where('is_playoff', false)->max('round_number');
                        if ($activeRnd > ($maxRndGames ?: 1)) {
                            $hasActiveNextRound = true;
                            break;
                        }
                    }
                }

                if (!$hasActiveNextRound) {
                    $this->status = 'finished';
                    $this->save();
                }
            } else {
                if ($this->status === 'finished') {
                    $this->status = 'active';
                    $this->save();
                }
            }
        }
    }
    // En app/Models/Tournament.php

public function client()
{
    return $this->belongsTo(Client::class);
}
}