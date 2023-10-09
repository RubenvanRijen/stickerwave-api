<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StickerCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sticker_category')->insert([
            [
                'sticker_id' => 1,
                'category_id' => 1,
            ],
            [
                'sticker_id' => 2,
                'category_id' => 2,
            ],
            [
                'sticker_id' => 3,
                'category_id' => 3,
            ],
            [
                'sticker_id' => 4,
                'category_id' => 4,
            ],
        ]);
    }
}
