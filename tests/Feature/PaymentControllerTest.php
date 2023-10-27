<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Sticker;
use App\Models\User;
use Database\Seeders\RolesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase; // Automatically reset the database after each test
    use WithFaker;       // Use Faker for generating fake data
    protected $adminHeaders = [];


    protected function setUp(): void
    {
        parent::setUp();
        // set the authentication for certain specific routes.
        Artisan::call('db:seed', ['--class' => RolesTableSeeder::class]);
        $this->createAdminUser();
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

    public function testInitiatePayment(): void
    {
        $sticker = Sticker::factory()->create();

        $response = $this->get("/api/payment/sticker/{$sticker->id}", $this->adminHeaders);

        $response->assertStatus(200)
            ->assertJsonStructure(['payment_url']);
    }

    /** @test */
    public function testInitiatePaymentWithNonExistentSticker(): void
    {
        $response = $this->get('/api/payment/sticker/999',  $this->adminHeaders);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Sticker not found']);
    }

    // /** @test */
    // public function testHandleCallbackPaymentSuccess(): void
    // {
    //     $sticker = Sticker::factory()->create();

    //     $paymentId = 'test_payment_id'; // TODO Replace with a valid Mollie payment ID

    //     $response = $this->post("/api/payment/callback/{$sticker->id}", ['id' => $paymentId], $this->adminHeaders);

    //     $response->assertStatus(200)
    //         ->assertJson(['message' => 'Payment successful']);

    //     // Assert that a transaction has been created in the database
    //     $this->assertDatabaseHas('transactions', [
    //         'sticker_id' => $sticker->id,
    //         'status' => 'paid',
    //     ]);
    // }

    // /** @test */
    // public function testHandleCallbackPaymentPending(): void
    // {
    //     $sticker = Sticker::factory()->create();

    //     $paymentId = 'test_payment_id'; // TODO Replace with a valid Mollie payment ID

    //     // Simulate a pending payment status
    //     $response = $this->post("/api/payment/callback/{$sticker->id}", ['id' => $paymentId, 'status' => 'pending'], $this->adminHeaders);

    //     $response->assertStatus(202)
    //         ->assertJson(['message' => 'Payment is pending']);

    //     // Assert that no transaction has been created in the database
    //     $this->assertDatabaseMissing('transactions', [
    //         'sticker_id' => $sticker->id,
    //     ]);
    // }

    // /** @test */
    // public function testHandleCallbackPaymentFailure(): void
    // {
    //     $sticker = Sticker::factory()->create();

    //     $paymentId = 'test_payment_id'; // TODO Replace with a valid Mollie payment ID

    //     // Simulate a failed payment status
    //     $response = $this->post("/api/payment/callback/{$sticker->id}", ['id' => $paymentId, 'status' => 'failed'], $this->adminHeaders);

    //     $response->assertStatus(400)
    //         ->assertJson(['error' => 'Payment failed']);

    //     // Assert that no transaction has been created in the database
    //     $this->assertDatabaseMissing('transactions', [
    //         'sticker_id' => $sticker->id,
    //     ]);
    // }

    // /** @test */
    // public function testHandleCallbackPaymentException(): void
    // {
    //     $sticker = Sticker::factory()->create();

    //     $paymentId = 'test_payment_id'; // TODO Replace with a valid Mollie payment ID

    //     // Simulate an exception during payment handling
    //     $this->mockMollieApiException();

    //     $response = $this->post("/api/payment/callback/{$sticker->id}", ['id' => $paymentId], $this->adminHeaders);

    //     $response->assertStatus(500)
    //         ->assertJson(['error' => 'Payment failed']);

    //     // Assert that no transaction has been created in the database
    //     $this->assertDatabaseMissing('transactions', [
    //         'sticker_id' => $sticker->id,
    //     ]);
    // }

    /** @test */
    protected function mockMollieApiException(): void
    {
        // Mock an exception from Mollie's API by returning a response with a 500 status code
        // This simulates an internal server error on the Mollie API side


        // Define the URL to mock (the Mollie API endpoint that is being called)
        $mollieApiUrl = 'https://api.mollie.com/v2/payments'; // Replace with your actual URL

        // Use Http::fake to set up the mock response
        Http::fake([
            $mollieApiUrl => Http::response([], 500), // Simulate a 500 Internal Server Error
        ]);
    }
}
