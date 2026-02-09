<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pecho',   'icon_path' => 'icons/chest.svg'],
            ['name' => 'Espalda', 'icon_path' => 'icons/back.svg'],
            ['name' => 'Pierna',  'icon_path' => 'icons/legs.svg'],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(['name' => $c['name']], $c);
        }
    }
}
