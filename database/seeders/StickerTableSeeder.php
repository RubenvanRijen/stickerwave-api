<?php

namespace Database\Seeders;

use App\Models\Sticker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


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

        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Sticker::create([
                'title' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1, 100) // Random price between 1 and 100 with 2 decimal places
            ]);
        }
    }
}
