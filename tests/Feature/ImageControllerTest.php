<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Sticker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ImageControllerTest extends TestCase
{
    use RefreshDatabase; // Automatically reset the database after each test
    use WithFaker;       // Use Faker for generating fake data
    protected $headers = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with test data
        // $this->seed();
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $headers = ['Authorization' => 'Bearer ' . $token];
        $this->setHeaders($headers);
    }

    // Custom method to set headers
    protected function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }


    /** @test */
    public function it_can_list_images_for_a_specific_sticker()
    {
        $sticker = Sticker::factory()->create();
        $image = Image::factory()->create(['sticker_id' => $sticker->id]);

        $response = $this->get("/api/stickers/{$sticker->id}/images");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => []]);
    }

    /** @test */
    public function it_can_create_an_image_for_a_specific_sticker()
    {



        $sticker = Sticker::factory()->create();
        $data = [
            'filename' => 'test_image.jpg',
            'mime' => 'image/jpeg',
            'data' => base64_encode(file_get_contents(public_path('seederImages/pikachu.jpg')))
        ];

        $response = $this->post("/api/stickers/{$sticker->id}/images", $data, $this->headers);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Image created successfully']);

        $this->assertDatabaseHas('images', [
            'sticker_id' => $sticker->id,
            'filename' => 'test_image.jpg',
        ]);
    }

    /** @test */
    public function it_can_update_an_image_for_a_specific_sticker()
    {
        $sticker = Sticker::factory()->create();
        $image = Image::factory()->create(['sticker_id' => $sticker->id]);
        $data = [
            'filename' => 'updated_image.jpg',
            'mime' => 'image/jpeg',
            'data' => base64_encode(file_get_contents(public_path('seederImages/pikachu.jpg')))
        ];

        $response = $this->put("/api/stickers/{$sticker->id}/images/{$image->id}", $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Image updated successfully']);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'sticker_id' => $sticker->id,
            'filename' => 'updated_image.jpg',
        ]);
    }

    /** @test */
    public function it_can_delete_an_image_for_a_specific_sticker()
    {
        $sticker = Sticker::factory()->create();
        $image = Image::factory()->create(['sticker_id' => $sticker->id]);

        $response = $this->delete("/api/stickers/{$sticker->id}/images/{$image->id}", [], $this->headers);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Image deleted successfully']);

        $this->assertDatabaseMissing('images', [
            'id' => $image->id,
            'sticker_id' => $sticker->id,
        ]);
    }
}
