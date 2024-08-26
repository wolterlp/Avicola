<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EggCategory;
use Illuminate\Support\Facades\DB;

class EggCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() 
        {

        DB::table('egg_categories')->insert([
            ['category' => 'B', 'description' => 'Huevos pequeños, con un peso entre 46 y 52,9 gramos.', 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'A', 'description' => 'Huevos medianos, con un peso entre 53 y 59,9 gramos.', 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'AA', 'description' => 'Huevos grandes, con un peso entre 60 y 66,9 gramos.', 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'AAA', 'description' => 'Huevos extra grandes, con un peso entre 67 y 77,9 gramos.', 'created_at' => now(), 'updated_at' => now()],
            ['category' => 'Jumbo', 'description' => 'Huevos extremadamente grandes, con un peso superior a 78 gramos.', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
