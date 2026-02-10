<?php

namespace Database\Factories;

use App\Models\Routine;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoutineFactory extends Factory
{
    protected $model = Routine::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(2, true)),
            'description' => $this->faker->optional()->sentence(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Routine $routine) {

            // 1) Asignar a varios usuarios aleatorios (routine_user)
            if (User::count() === 0) {
                User::factory()->count(5)->create();
            }

            $userIds = User::inRandomOrder()
                ->take(rand(1, 4))
                ->pluck('id')
                ->all();

            if (!empty($userIds)) {
                $routine->users()->attach($userIds);
            }

            // 2) Asignar ejercicios aleatorios con pivote (exercise_routine)
            if (Exercise::count() === 0) {
                // Si aún no hay ejercicios seedados, no hacemos attach
                return;
            }

            $exercises = Exercise::inRandomOrder()
                ->take(rand(3, 7))
                ->get();

            $attach = [];
            $sequence = 1;
            $rests = [30, 45, 60, 90, 120];

            foreach ($exercises as $ex) {
                $attach[$ex->id] = [
                    'sequence' => $sequence++,
                    'target_sets' => rand(3, 5),
                    'target_reps' => rand(8, 15),
                    'rest_seconds' => $rests[array_rand($rests)],
                ];
            }

            $routine->exercises()->attach($attach);
        });
    }
}
