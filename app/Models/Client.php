<?php

namespace App\Models;

use App\Services\TenantStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }
}
