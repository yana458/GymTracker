<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Routine;

class RoutineSeeder extends Seeder
{
    public function run(): void
    {
        Routine::factory()->count(6)->create();
    }
}
