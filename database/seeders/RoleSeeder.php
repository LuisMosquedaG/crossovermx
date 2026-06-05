<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Creamos los roles si no existen
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
    }
}