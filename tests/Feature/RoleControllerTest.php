<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Sticker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Database\Seeders\RolesTableSeeder;


class RoleControllertest extends TestCase
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
    public function it_can_list_all_role()
    {
        // Create some sticker records in the database
        Role::factory(3)->create();

        $response = $this->get('/api/roles', $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'current_page',
                    'data',
                    'first_page_url',
                ]
            ]);
        $response->assertJsonCount(5, 'data.data');
    }

    /** @test */
    public function it_can_show_a_specific_role()
    {
        // Create a sticker record in the database
        $sticker = Role::create([
            'name' => 'Test role',
        ]);

        $response = $this->get('/api/roles/' . $sticker->id, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Test role',
            ]);
    }

    /** @test */
    public function it_can_create_a_role()
    {
        $data = [
            'name' => 'Test role',
        ];

        $response = $this->postJson('/api/roles', $data, $this->headers);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test role',
            ]);

        // Verify that the record exists in the database
        $this->assertDatabaseHas('roles', $data);
    }

    /** @test */
    public function it_can_update_a_role()
    {
        // Create a sticker record in the database
        $sticker = Role::create([
            'name' => 'Original name'
        ]);

        $data = [
            'name' => 'Updated name',
        ];

        $response = $this->putJson('/api/roles/' . $sticker->id, $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated name',
            ]);

        // Verify that the record has been updated in the database
        $this->assertDatabaseHas('roles', $data);
    }

    /** @test */
    public function it_can_delete_a_role()
    {
        // Create a sticker record in the database
        $sticker = Role::create([
            'name' => 'To Be Deleted'
        ]);

        $response = $this->delete('/api/roles/' . $sticker->id, [], $this->headers);

        $response->assertStatus(204);

        // Verify that the record has been deleted from the database
        $this->assertDatabaseMissing('roles', [
            'name' => 'To Be Deleted'
        ]);
    }

    /** @test */
    public function it_can_detach_a_role_from_a_user()
    {
        // Create a user and a role
        $user = User::factory()->create();
        $role = Role::factory()->create();

        // Attach the role to the user
        $user->roles()->attach($role);

        // Call the API to detach the role
        $response = $this->delete("/api/roles/{$role->id}/user/detach/{$user->id}", [], $this->headers);

        // Assert a successful response
        $response->assertStatus(200);

        // Check if the role is detached from the user
        $this->assertDatabaseMissing('roles_users', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_can_attach_a_role_to_a_user()
    {
        // Create a user and a role
        $user = User::factory()->create();
        $role = Role::factory()->create();

        // Call the API to attach the role to the user
        $response = $this->post("/api/roles/{$role->id}/user/attach/{$user->id}", [], $this->headers);

        // Assert a successful response
        $response->assertStatus(200);

        // Check if the role is attached to the user
        $this->assertDatabaseHas('roles_users', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }
}
