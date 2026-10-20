<?php

namespace App\Models;

use App\Services\TenantStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'logo_path',
        'prefix',
        'storage_limit_mb',
    ];

    protected $casts = [
        'storage_limit_mb' => 'integer',
    ];

    protected static function booted()
    {
        static::created(function (Client $client) {
            TenantStorageService::initializeTenantDirectories($client->id);
        });
    }

    /**
     * Inicializa la estructura de carpetas de almacenamiento para este cliente.
     */
    public function initializeStorage(): void
    {
        TenantStorageService::initializeTenantDirectories($this->id);
    }

    /**
     * Retorna el límite de almacenamiento formateado (ej. "1 GB", "500 MB").
     */
    public function getFormattedStorageLimitAttribute(): string
    {
        $mb = $this->storage_limit_mb ?: 1024;
        return TenantStorageService::formatBytes($mb * 1024 * 1024, 0);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }

    /**
     * Elimina completamente el cliente, todos sus registros asociados y su almacenamiento físico.
     */
    public function purgeAllData(): void
    {
        $clientId = $this->id;

        DB::transaction(function () use ($clientId) {
            // 1. Obtener IDs relacionados
            $tournamentIds = Tournament::where('client_id', $clientId)->pluck('id');
            $teamIds = Team::where('client_id', $clientId)
                ->orWhereIn('tournament_id', $tournamentIds)
                ->pluck('id');
            $playerIds = Player::where('client_id', $clientId)
                ->orWhereIn('team_id', $teamIds)
                ->pluck('id');
            $courtIds = Court::where('client_id', $clientId)->pluck('id');
            $gameIds = Game::where('client_id', $clientId)
                ->orWhereIn('tournament_id', $tournamentIds)
                ->orWhereIn('local_team_id', $teamIds)
                ->orWhereIn('away_team_id', $teamIds)
                ->pluck('id');
            $userIds = User::where('client_id', $clientId)->pluck('id');

            // 2. Limpieza de archivos individuales heredados (fuera de la carpeta clients/{id})
            $legacyFiles = collect();
            if ($this->logo_path) {
                $legacyFiles->push($this->logo_path);
            }

            $tournamentFiles = Tournament::whereIn('id', $tournamentIds)
                ->get(['logo_path', 'reglamento'])
                ->flatMap(fn($t) => [$t->logo_path, $t->reglamento])
                ->filter();
            $legacyFiles = $legacyFiles->merge($tournamentFiles);

            $teamFiles = Team::whereIn('id', $teamIds)->pluck('image_path')->filter();
            $legacyFiles = $legacyFiles->merge($teamFiles);

            $playerFiles = Player::whereIn('id', $playerIds)->pluck('image_path')->filter();
            $legacyFiles = $legacyFiles->merge($playerFiles);

            $courtFiles = Court::whereIn('id', $courtIds)->pluck('image_path')->filter();
            $legacyFiles = $legacyFiles->merge($courtFiles);

            $gameFiles = Game::whereIn('id', $gameIds)->pluck('scoresheet_path')->filter();
            $legacyFiles = $legacyFiles->merge($gameFiles);

            $disk = Storage::disk('public');
            foreach ($legacyFiles->unique() as $filePath) {
                if ($filePath && $disk->exists($filePath)) {
                    $disk->delete($filePath);
                }
            }

            // 3. Eliminar carpeta física clients/{clientId} completa
            TenantStorageService::deleteTenantDirectory($clientId);

            // 4. Eliminar dependencias de partidos
            GameAction::whereIn('game_id', $gameIds)->delete();
            GameComment::whereIn('game_id', $gameIds)->orWhereIn('user_id', $userIds)->delete();
            DB::table('game_player')
                ->whereIn('game_id', $gameIds)
                ->orWhereIn('player_id', $playerIds)
                ->delete();
            Game::whereIn('id', $gameIds)->delete();

            // 5. Eliminar configuraciones y horarios
            CourtSchedule::whereIn('court_id', $courtIds)->delete();
            TeamSchedule::whereIn('team_id', $teamIds)->delete();
            TournamentSetting::whereIn('tournament_id', $tournamentIds)->delete();

            // 6. Eliminar jugadores, equipos, canchas, torneos y fuerzas
            Player::whereIn('id', $playerIds)->delete();
            Team::whereIn('id', $teamIds)->delete();
            Court::whereIn('id', $courtIds)->delete();
            Tournament::whereIn('id', $tournamentIds)->delete();
            Strength::where('client_id', $clientId)->delete();

            // 7. Cerrar sesiones activas y eliminar usuarios del cliente de forma permanente
            DB::table('sessions')->whereIn('user_id', $userIds)->delete();
            User::where('client_id', $clientId)->forceDelete();

            // 8. Eliminar permanentemente el registro del cliente
            $this->forceDelete();
        });
    }
}
