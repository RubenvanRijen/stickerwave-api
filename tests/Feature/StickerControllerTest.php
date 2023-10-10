<?php

namespace Tests\Feature;

use App\Models\Sticker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class StickerControllerTest extends TestCase
{
    use RefreshDatabase; // Automatically reset the database after each test
    use WithFaker;       // Use Faker for generating fake data
    protected $headers = [];

    protected function setUp(): void
    {
        parent::setUp();

        // set the authentication for certain specific routes.
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
    public function it_can_list_all_stickers()
    {
        // Create some sticker records in the database
        Sticker::factory(3)->create();

        $response = $this->get('/api/stickers');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data'); // Assuming you have 3 stickers in the database
    }

    /** @test */
    public function it_can_show_a_specific_sticker()
    {
        // Create a sticker record in the database
        $sticker = Sticker::create([
            'title' => 'Test Sticker',
            'description' => 'This is a test sticker.',
        ]);

        $response = $this->get('/api/stickers/' . $sticker->id);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Test Sticker',
                'description' => 'This is a test sticker.',
            ]);
    }

    /** @test */
    public function it_can_create_a_sticker()
    {
        $data = [
            'title' => 'Test Sticker',
            'description' => 'This is a test sticker.',
        ];

        $response = $this->postJson('/api/stickers', $data, $this->headers);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Test Sticker',
                'description' => 'This is a test sticker.',
            ]);

        // Verify that the record exists in the database
        $this->assertDatabaseHas('stickers', $data);
    }

    /** @test */
    public function it_can_update_a_sticker()
    {
        // Create a sticker record in the database
        $sticker = Sticker::create([
            'title' => 'Original Title',
            'description' => 'Original Description',
        ]);

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ];

        $response = $this->putJson('/api/stickers/' . $sticker->id, $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'description' => 'Updated Description',
            ]);

        // Verify that the record has been updated in the database
        $this->assertDatabaseHas('stickers', $data);
    }

    /** @test */
    public function it_can_delete_a_sticker()
    {
        // Create a sticker record in the database
        $sticker = Sticker::create([
            'title' => 'To Be Deleted',
            'description' => 'Delete this sticker.',
        ]);

        $response = $this->delete('/api/stickers/' . $sticker->id, [], $this->headers);

        $response->assertStatus(204);

        // Verify that the record has been deleted from the database
        $this->assertDatabaseMissing('stickers', [
            'title' => 'To Be Deleted',
            'description' => 'Delete this sticker.',
        ]);
    }
}
