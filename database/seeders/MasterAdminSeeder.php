<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buscar o crear el rol "Master Admin"
        $masterRole = Role::firstOrCreate(
            ['name' => 'Master Admin']
        );

        // 2. Buscar o crear el usuario "madmin@crossovermx.com"
        // Usamos firstOrCreate para no duplicarlo si ejecutas el seeder varias veces
        User::firstOrCreate(
            ['email' => 'madmin@crossovermx.com'],
            [
                'name' => 'Master Admin',
                'password' => Hash::make('paso1234'),
                'role_id' => $masterRole->id,
                'email_verified_at' => now(), // Marcar como verificado para evitar errores
            ]
        );
    }
}