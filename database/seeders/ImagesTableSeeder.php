<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('images')->insert([
            [
                'filename' => 'image1.jpg',
                'mime' => 'image/jpeg',
                'data' => base64_encode(file_get_contents(public_path('seederImages/pikachu.jpg'))),
                'sticker_id' => 1, 
            ],
            [
                'filename' => 'image2.jpg',
                'mime' => 'image/jpeg',
                'data' => base64_encode(file_get_contents(public_path('seederImages/skyrim-dragon.jpg'))),
                'sticker_id' => 2, 
            ],
            [
                'filename' => 'image2.jpg',
                'mime' => 'image/jpeg',
                'data' => base64_encode(file_get_contents(public_path('seederImages/skyrim-arrow.jpg'))),
                'sticker_id' => 3, 
            ],
            [
                'filename' => 'image2.jpg',
                'mime' => 'image/jpeg',
                'data' => base64_encode(file_get_contents(public_path('seederImages/stickerwave-logo.jpg'))),
                'sticker_id' => 4, 
            ],
        ]);
    }
}
