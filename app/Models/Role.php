<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // Relación inversa: Un rol tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }
}