<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@tfg.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'department' => 'IT'
        ]);
    }
}