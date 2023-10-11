<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase; // Automatically reset the database after each test
    use WithFaker;       // Use Faker for generating fake data
    protected $adminHeaders = [];
    protected $userHeaders = [];


    protected function setUp(): void
    {
        parent::setUp();

        // set the authentication for certain specific routes.
        Artisan::call('db:seed', ['--class' => RolesTableSeeder::class]);
        $this->createAdminUser();
        $this->createNormalUser();
    }

    protected function createAdminUser()
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();
        $user->roles()->attach($adminRole);
        $token = JWTAuth::fromUser($user);
        $headers = ['Authorization' => 'Bearer ' . $token];
        $this->setAdminHeaders($headers);
    }

    protected function setAdminHeaders(array $headers)
    {
        $this->adminHeaders = $headers;
    }

    protected function createNormalUser()
    {
        $user = User::factory()->create();
        $userRole = Role::where('name', 'user')->first();
        $user->roles()->attach($userRole);
        $token = JWTAuth::fromUser($user);
        $headers = ['Authorization' => 'Bearer ' . $token];
        $this->setUserHeaders($headers);
    }

    protected function setUserHeaders(array $headers)
    {
        $this->userHeaders = $headers;
    }

    /** @test */
    public function testIndex()
    {
        // Create some categories in the database for testing
        Category::factory()->count(3)->create();

        $response = $this->get('/api/categories');

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
    public function testShow()
    {
        // Create a category in the database for testing
        $category = Category::factory()->create();

        $response = $this->get("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    /** @test */
    public function testStore()
    {
        $data = ['title' => 'New Category'];

        $response = $this->post('/api/categories', $data, $this->adminHeaders);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Category created successfully']);
    }

    /** @test */
    public function testUpdate()
    {
        // Create a category in the database for testing
        $category = Category::factory()->create();

        $data = ['title' => 'Updated Category'];

        $response = $this->put("/api/categories/{$category->id}", $data, $this->adminHeaders);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Category updated successfully']);
    }

    /** @test */
    public function testDestroy()
    {
        // Create a category in the database for testing
        $category = Category::factory()->create();

        $response = $this->delete("/api/categories/{$category->id}", [], $this->adminHeaders);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Category deleted successfully']);
    }

    /** @test */
    public function testDestroyfailed()
    {
        // Create a category in the database for testing
        $category = Category::factory()->create();

        $response = $this->delete("/api/categories/{$category->id}", [], $this->userHeaders);

        $response->assertStatus(403);
    }
}
