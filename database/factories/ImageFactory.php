<?php

namespace Database\Factories;

use App\Models\Sticker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'filename' => $this->faker->word . '.jpg', // You can adjust this to generate suitable filenames.
            'mime' => 'image/jpeg',
            'data' => base64_encode(file_get_contents(public_path('seederImages/pikachu.jpg'))), // Replace with actual image data
            'sticker_id' => function () {
                // Assuming you have Sticker records in the database, select a random sticker ID.
                return Sticker::inRandomOrder()->first()->id;
            },
        ];
    }
}
