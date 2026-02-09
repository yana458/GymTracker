<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $catIds = Category::pluck('id', 'name'); // ['Pecho' => 1, ...]
        
        $exercisesByCategory = [
            'Pecho' => [
                ['name' => 'Press banca', 'instruction' => 'Baja controlado, empuja fuerte y mantén escápulas retraídas.'],
                ['name' => 'Press inclinado mancuernas', 'instruction' => 'Codos a 45°, recorrido completo sin rebotar.'],
                ['name' => 'Aperturas con mancuernas', 'instruction' => 'Abraza el arco, sin bloquear codos.'],
            ],
            'Espalda' => [
                ['name' => 'Dominadas', 'instruction' => 'Activa dorsales, sube con pecho hacia la barra.'],
                ['name' => 'Remo con barra', 'instruction' => 'Espalda neutra, tira al ombligo sin balanceo.'],
                ['name' => 'Jalón al pecho', 'instruction' => 'Baja al pecho, controla la vuelta.'],
            ],
            'Pierna' => [
                ['name' => 'Sentadilla', 'instruction' => 'Rodillas siguen la punta del pie, core firme.'],
                ['name' => 'Prensa', 'instruction' => 'No bloquees rodillas, empuja con talones.'],
                ['name' => 'Peso muerto rumano', 'instruction' => 'Cadera atrás, estira isquios sin curvar espalda.'],
            ],
        ];

        foreach ($exercisesByCategory as $catName => $list) {
            foreach ($list as $e) {
                Exercise::updateOrCreate(
                    ['name' => $e['name'], 'category_id' => $catIds[$catName]],
                    ['instruction' => $e['instruction']]
                );
            }
        }
    }
}
