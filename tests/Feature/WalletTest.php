<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_wallet_balance()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id,
            'balance' => 1000,
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/wallet/balance');

        $response->assertStatus(200)
                 ->assertJson([
                     'balance' => 1000,
                 ]);
    }
}
