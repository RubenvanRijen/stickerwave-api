<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\Sticker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Database\Seeders\RolesTableSeeder;


class StickerControllerTest extends TestCase
{
    use RefreshDatabase; // Automatically reset the database after each test
    use WithFaker;       // Use Faker for generating fake data
    protected $headers = [];

    protected function setUp(): void
    {
        parent::setUp();

        // set the authentication for certain specific routes.
        Artisan::call('db:seed', ['--class' => RolesTableSeeder::class]);
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();
        $user->roles()->attach($adminRole);
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
            ->assertJsonStructure([
                'data' => [
                    'current_page',
                    'data',
                    'first_page_url',
                ]
            ]);
        $response->assertJsonCount(3, 'data.data');
    }

    /** @test */
    public function it_can_show_a_specific_sticker()
    {
        // Create a sticker record in the database
        $sticker = Sticker::create([
            'title' => 'Test Sticker',
            'description' => 'This is a test sticker.',
            'price' => 10.30
        ]);

        $response = $this->get('/api/stickers/' . $sticker->id);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Test Sticker',
                'description' => 'This is a test sticker.',
                'price' => 10.30
            ]);
    }

    /** @test */
    public function it_can_create_a_sticker()
    {
        $data = [
            'title' => 'Test Sticker',
            'description' => 'This is a test sticker.',
            'price' => 10.30
        ];

        $response = $this->postJson('/api/stickers', $data, $this->headers);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Test Sticker',
                'description' => 'This is a test sticker.',
                'price' => 10.30
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
            'price' => 10.30
        ]);

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'price' => 10.30
        ];

        $response = $this->putJson('/api/stickers/' . $sticker->id, $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'description' => 'Updated Description',
                'price' => 10.30
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
            'price' => 10.30
        ]);

        $response = $this->delete('/api/stickers/' . $sticker->id, [], $this->headers);

        $response->assertStatus(204);

        // Verify that the record has been deleted from the database
        $this->assertDatabaseMissing('stickers', [
            'title' => 'To Be Deleted',
            'description' => 'Delete this sticker.',
            'price' => 10.30
        ]);
    }

    /** @test */
    public function it_can_show_stickers_of_category()
    {
        $categoryId = 1;
        // Mocking the Sticker model and its behavior
        $stickerModel = $this->mock(Sticker::class);
        $categoryModel = $this->mock(Category::class);
        $stickerModel->categories()->attach($categoryModel->id);

        $stickerModel->shouldReceive('find')->with($categoryId)->andReturn(new Sticker());

        $response = $this->get('api/stickers/category/' . $categoryId);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'price',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }
}
