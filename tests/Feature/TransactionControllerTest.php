<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Sticker;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\StickerTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase; // Automatically reset the database after each test
    use WithFaker;       // Use Faker for generating fake data
    protected $adminHeaders = [];
    protected $userHeaders = [];


    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => RolesTableSeeder::class]);
        $this->createAdminUser();
        $this->createNormalUser();
        Artisan::call('db:seed', ['--class' => StickerTableSeeder::class]);
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
    public function it_can_retrieve_a_list_of_transactions()
    {
        $stickers = Sticker::all();
        $users = User::all();
        Transaction::factory()->create(['user_id' => $users[0]->id, 'sticker_id' => $stickers[0]->id]);

        $response = $this->get('/api/transactions',  $this->adminHeaders);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    /** @test */
    public function it_can_retrieve_a_specific_transaction_by_id()
    {
        $stickers = Sticker::all();
        $users = User::all();
        $transaction =  Transaction::factory()->create(['user_id' => $users[0]->id, 'sticker_id' => $stickers[0]->id]);

        $response = $this->get("/api/transactions/{$transaction->id}", $this->adminHeaders);

        $response->assertStatus(200);
        $response->assertJson(['data' => $transaction->toArray()]);
    }

     /** @test */
    public function it_can_not_retrieve_a_specific_transaction_by_id()
    {
        $stickers = Sticker::all();
        $users = User::all();
        $transaction =  Transaction::factory()->create(['user_id' => $users[0]->id, 'sticker_id' => $stickers[0]->id]);

        $response = $this->get("/api/transactions/{$transaction->id}", $this->userHeaders);

        $response->assertStatus(403);
    }

     /** @test */
    public function it_returns_404_if_transaction_by_id_is_not_found()
    {
        $response = $this->get('/api/transactions/999', $this->adminHeaders);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Transaction not found']);
    }


     /** @test */
    public function it_can_retrieve_a_specific_transaction_by_a_specific_user()
    {
        $stickers = Sticker::all();
        $users = User::all();
        $transaction = Transaction::factory()->create(['user_id' => $users[0]->id, 'sticker_id' => $stickers[0]->id]);

        $response = $this->get("/api/transactions/user", $this->adminHeaders);

        $response->assertStatus(200);
    }
}
