<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillPaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_buy_airtime()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id,
            'balance' => 1000,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/pay/airtime', [
            'network' => 'mtn',
            'phone' => '08012345678',
            'amount' => 500,
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_buy_data()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id,
            'balance' => 1000,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/pay/data', [
            'network' => 'mtn',
            'phone' => '08012345678',
            'amount' => 500,
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_pay_electricity_bill()
    {
        
        $user = User::factory()->create();
        Wallet::factory()->create(['user_id' => $user->id, 'balance' => 1000]);

        
        $this->actingAs($user);

       
        $response = $this->postJson('/api/pay/electricity', [
            'serviceID' => 'eko-electric',
            'meter_number' => '1234567890',
            'type' => 'prepaid',
            'phone' => '08012345678',
            'amount' => 500,
        ]);

        // Output the response for debugging purposes
        $response->dump();

        $response->assertStatus(200);
    }


    /** @test */
    public function it_can_subscribe_tv()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id,
            'balance' => 1000,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/pay/tv', [
            'serviceID' => 'dstv',
            'smartcard_number' => '1234567890',
            'amount' => 500,
            'phone' => '08012345678',
        ]);

        $response->assertStatus(200);
    }
}
