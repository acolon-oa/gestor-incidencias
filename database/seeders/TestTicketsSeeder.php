<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;

class TestTicketsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $depts = Department::all();
        
        if ($users->isEmpty() || $depts->isEmpty()) {
            return;
        }

        $titles = [
            'Error al iniciar sesión en el portal',
            'La impresora de marketing no funciona',
            'Solicitud de nuevo monitor para diseño',
            'Problema con el acceso VPN',
            'Error de base de datos en producción',
            'Instalación de software de diseño 3D',
            'Cambio de contraseña bloqueado',
            'Ruido extraño en el equipo de IT',
            'Monitor parpadeando en recepción',
            'Fallo en la conexión Wi-Fi de la planta 2'
        ];

        for ($i = 0; $i < 10; $i++) {
            Ticket::create([
                'title' => $titles[$i],
                'description' => 'Esta es una descripción detallada de la incidencia número ' . ($i + 1) . ' generada automáticamente para pruebas de sistema.',
                'status' => ['open', 'in_progress', 'closed'][rand(0, 2)],
                'priority' => ['low', 'medium', 'high', 'urgent'][rand(0, 3)],
                'user_id' => $users->random()->id,
                'department_id' => $depts->random()->id,
            ]);
        }
    }
}
