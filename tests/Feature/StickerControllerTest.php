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
    { // Create a category
        $category = Category::factory()->create();

        // Create stickers related to the category
        Sticker::factory(10)->create()->each(function ($sticker) use ($category) {
            $sticker->categories()->attach($category);
        });

        // Request stickers by category ID
        $response = $this->get('api/stickers/category/' . $category->id);

        $response->assertStatus(200)
            ->assertJsonCount(13, 'data');
    }

    /** @test */
    public function it_attaches_categories_to_sticker()
    {
        $sticker = Sticker::factory()->create();
        $categories = Category::factory(3)->create();
        $categoryIds = $categories->pluck('id')->toArray();

        $response = $this->post("api/stickers/{$sticker->id}/categories/attach", ['category_ids' => $categoryIds]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    // Other expected fields
                ]
            ]);

        foreach ($categoryIds as $categoryId) {
            $this->assertDatabaseHas('sticker_category', [
                'category_id' => $categoryId,
                'sticker_id' => $sticker->id,
            ]);
        }
    }

    /** @test */
    public function it_detaches_categories_from_sticker()
    {
        // Create a sticker
        $sticker = Sticker::factory()->create();

        // Create categories
        $categories = Category::factory(3)->create();

        // Attach categories to the sticker
        $sticker->categories()->sync($categories);

        // Get category IDs attached to the sticker
        $categoryIds = $categories->pluck('id')->toArray();

        // Detach categories from the sticker
        $response = $this->delete("api/stickers/{$sticker->id}/categories/detach", ['category_ids' => $categoryIds]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    // Add other expected fields here
                ]
            ]);

        // Ensure the categories are detached from the sticker
        foreach ($categoryIds as $categoryId) {
            $this->assertDatabaseMissing('sticker_category', [
                'category_id' => $categoryId,
                'sticker_id' => $sticker->id,
            ]);
        }
    }
}
