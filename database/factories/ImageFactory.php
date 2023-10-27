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
            'filename' => $this->faker->word . '.jpg', 
            'mime' => 'image/jpeg',
            'data' => base64_encode(file_get_contents(public_path('seederImages/pikachu.jpg'))),
            'sticker_id' => function () {
                return Sticker::inRandomOrder()->first()->id;
            },
        ];
    }
}
