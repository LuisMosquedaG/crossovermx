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

    /**
     * Calcula el peso total en bytes consumido por las carpetas del cliente (o de todos si clientId es null).
     *
     * @param int|string|null $clientId
     * @return int
     */
    public static function getTenantStorageUsageBytes($clientId = null): int
    {
        $relativePath = $clientId ? "clients/{$clientId}" : "clients";
        $fullPath = storage_path("app/public/{$relativePath}");

        if (!is_dir($fullPath)) {
            return 0;
        }

        $size = 0;
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($fullPath, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        } catch (\Throwable $e) {
            return 0;
        }

        return $size;
    }

    /**
     * Formatea un valor en bytes a formato legible (B, KB, MB, GB, TB).
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min((int) $pow, count($units) - 1);
        $value = $bytes / (1 << (10 * $pow));

        return round($value, $precision) . ' ' . $units[$pow];
    }

    /**
     * Elimina todo el almacenamiento físico de un cliente (su carpeta completa en clients/{clientId}).
     *
     * @param int|string $clientId
     * @return void
     */
    public static function deleteTenantDirectory($clientId): void
    {
        if (empty($clientId)) {
            return;
        }

        $disk = Storage::disk('public');
        $path = "clients/{$clientId}";
        if ($disk->exists($path)) {
            $disk->deleteDirectory($path);
        }
    }
}
