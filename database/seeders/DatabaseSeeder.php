<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles definitivos si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Crear usuario administrador definitivo
        $admin = User::firstOrCreate(
            ['email' => 'admin@tfg.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('Admin1234') // cambia a contraseña segura
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Crear usuario normal definitivo
        $user = User::firstOrCreate(
            ['email' => 'user@tfg.com'],
            [
                'name' => 'Usuario Normal',
                'password' => bcrypt('User1234') // cambia a contraseña segura
            ]
        );
        if (!$user->hasRole('user')) {
            $user->assignRole($userRole);
        }
    }
}
