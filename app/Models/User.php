<?php

namespace App\Models;

use App\Models\Role; // IMPORTANTE: Importar el modelo Role
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'client_id',
    ];
    // Relación con el Cliente (opcional pero útil)
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // CAMBIO 2: Relación con la tabla roles
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // CAMBIO 3: Método auxiliar para verificar el rol
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }
}