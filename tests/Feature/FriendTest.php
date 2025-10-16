<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FriendTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_set_a_friend()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $friend = User::factory()->create(['email_verified_at' => now()]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/friend/set', [
            'user_id' => $friend->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'success',
        ]);

        $this->assertDatabaseHas('friendship', [
            'from_user_id' => $user->id,
            'to_user_id' => $friend->id,
        ]);
    }

    #[Test]
    public function it_set_an_inactive_friend()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $inactiveUser = User::factory()->create(['email_verified_at' => null]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/friend/set', [
            'user_id' => $inactiveUser->id,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Inactive user',
        ]);
    }
}
