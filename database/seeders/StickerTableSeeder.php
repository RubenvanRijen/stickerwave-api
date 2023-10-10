<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StickerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stickers')->insert([
            [
                'title' => 'Sticker 1',
                'description' => 'Description for Sticker 1',
                'price' => 10.30
            ],
            [
                'title' => 'Sticker 2',
                'description' => 'Description for Sticker 2',
                'price' => 11.30
            ],
            [
                'title' => 'Sticker 3',
                'description' => 'Description for Sticker 3',
                'price' => 12.30
            ],
            [
                'title' => 'Sticker 4',
                'description' => 'Description for Sticker 4',
                'price' => 13.30
            ],
        ]);
    }
}
