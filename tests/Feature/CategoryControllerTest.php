<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase; // Automatically reset the database after each test
    use WithFaker;       // Use Faker for generating fake data

    public function testIndex()
    {
        // Create some categories in the database for testing
        Category::factory()->count(3)->create();

        $response = $this->get('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => []]);
    }

    public function testShow()
    {
        // Create a category in the database for testing
        $category = Category::factory()->create();

        $response = $this->get("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function testStore()
    {
        $data = ['title' => 'New Category'];

        $response = $this->post('/api/categories', $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Category created successfully']);
    }

    public function testUpdate()
    {
        // Create a category in the database for testing
        $category = Category::factory()->create();

        $data = ['title' => 'Updated Category'];

        $response = $this->put("/api/categories/{$category->id}", $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Category updated successfully']);
    }

    public function testDestroy()
    {
        // Create a category in the database for testing
        $category = Category::factory()->create();

        $response = $this->delete("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Category deleted successfully']);
    }
}
