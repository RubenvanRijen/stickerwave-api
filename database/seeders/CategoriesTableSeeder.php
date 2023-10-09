<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('categories')->insert([
            [
                'title' => 'Category 1',
            ],
            [
                'title' => 'Category 2',
            ],
            [
                'title' => 'Category 3',
            ],
            [
                'title' => 'Category 4',
            ],
        ]);
    }
}
