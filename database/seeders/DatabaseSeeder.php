<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. CREACIÓN DE ROLES (Directamente aquí para evitar errores de archivos externos)
        $roles = [
            'Master Admin',
            'Super Admin',
            'Admin',
            'Coach',
            'Arbitro'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName
            ]);
        }
        // -------------------------------------------------------

        // 2. BUSCAMOS EL ROL SUPER ADMIN
        $superAdminRole = Role::where('name', 'Master Admin')->first();

        // 3. CREACIÓN DEL USUARIO ADMIN
        User::firstOrCreate(
            ['email' => 'admin@crossovermx.com'], // Busca si el email existe
            [
                'name' => 'Mster Admin',
                'email' => 'madmin@crossovermx.com',
                'password' => Hash::make('paso1234'), // Contraseña encriptada
                'role_id' => $superAdminRole ? $superAdminRole->id : null, // Asigna el rol
                'email_verified_at' => now(),
            ]
        );
    }
}