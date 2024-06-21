<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_and_update_pin()
    {
        $user = User::factory()->create([
            'pin' => null,
        ]);

        $this->actingAs($user, 'sanctum');

        // Setting the PIN
        $response = $this->postJson('/api/set-pin', [
            'pin' => '1234',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('1234', $user->fresh()->pin));

        // Updating the PIN
        $response = $this->postJson('/api/update-pin', [
            'pin' => '5678',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('5678', $user->fresh()->pin));
    }
}