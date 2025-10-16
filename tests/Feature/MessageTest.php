<?php

namespace Tests\Feature\Message;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_send_message()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $friend = User::factory()->create(['email_verified_at' => now()]);

        Friendship::create([
            'from_user_id' => $user->id,
            'to_user_id' => $friend->id,
        ]);

        Friendship::create([
            'from_user_id' => $friend->id,
            'to_user_id' => $user->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/message/make', [
            'user_id' => $friend->id,
            'message' => 'Test',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'success',
        ]);

        $this->assertDatabaseHas('messages', [
            'from_user_id' => $user->id,
            'to_user_id' => $friend->id,
            'message' => 'Test',
        ]);
    }

    #[Test]
    public function it_send_message_for_not_friend()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $notFriend = User::factory()->create(['email_verified_at' => now()]);

        Friendship::create([
            'from_user_id' => $user->id,
            'to_user_id' => $notFriend->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/message/make', [
            'user_id' => $notFriend->id,
            'message' => 'Test',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid user',
        ]);
    }
}
