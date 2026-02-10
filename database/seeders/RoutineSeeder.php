<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Routine;

class RoutineSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar que existan usuarios para poder asignar rutinas por pivote
        if (User::count() === 0) {
            User::factory()->count(5)->create();
        }

        Routine::factory()->count(6)->create();
    }
}
