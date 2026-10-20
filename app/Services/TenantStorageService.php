<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class TenantStorageService
{
    /**
     * Subcarpetas estándar para cada cliente.
     */
    public const FOLDERS = [
        'logos',
        'tournaments',
        'teams',
        'players',
        'courts',
        'scoresheets',
    ];

    /**
     * Inicializa las carpetas para un cliente específico en storage/app/public/clients/{clientId}/
     *
     * @param int|string $clientId
     * @return void
     */
    public static function initializeTenantDirectories($clientId): void
    {
        if (empty($clientId)) {
            return;
        }

        $disk = Storage::disk('public');
        foreach (self::FOLDERS as $folder) {
            $path = "clients/{$clientId}/{$folder}";
            if (!$disk->exists($path)) {
                $disk->makeDirectory($path);
            }
        }
    }

    /**
     * Devuelve la ruta relativa dentro del disco público para un recurso de un cliente.
     * Ejemplo: TenantStorageService::path('teams', 1) => "clients/1/teams"
     *
     * @param string $category
     * @param int|string|null $clientId
     * @return string
     */
    public static function path(string $category, $clientId = null): string
    {
        $resolvedClientId = $clientId ?? (auth()->check() ? auth()->user()->client_id : null);

        if (!empty($resolvedClientId)) {
            return "clients/{$resolvedClientId}/{$category}";
        }

        // Fallback para administradores globales o registros sin cliente asociado
        return $category === 'logos' ? 'clients_logos' : $category;
    }
}
