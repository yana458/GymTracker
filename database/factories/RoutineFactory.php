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
            $userIds = User::inRandomOrder()
                ->take(rand(1, 4))
                ->pluck('id')
                ->all();

            $routine->users()->attach($userIds);

            // 2) Asignar ejercicios aleatorios con datos de pivote (exercise_routine)
            $exercises = Exercise::inRandomOrder()
                ->take(rand(3, 7))
                ->get();

            $attach = [];
            $sequence = 1;

            foreach ($exercises as $ex) {
                $attach[$ex->id] = [
                    'sequence' => $sequence++,
                    'target_sets' => rand(3, 5),
                    'target_reps' => rand(8, 15),
                    'rest_seconds' => [30, 45, 60, 90, 120][array_rand([30,45,60,90,120])],
                ];
            }

            $routine->exercises()->attach($attach);
        });
    }
}
