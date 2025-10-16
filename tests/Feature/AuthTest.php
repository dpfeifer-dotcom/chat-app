<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ApiVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_registers_a_user()
    {
        Notification::fake();

        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => ['url'],
            'api_version',
        ]);

        $user = User::where('email', $payload['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals($payload['name'], $user->name);
        $this->assertTrue(Hash::check($payload['password'], $user->password));

        Notification::assertSentTo($user, ApiVerifyEmail::class);
    }

    #[Test]
    public function it_logs_in_a_user()
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => ['token'],
            'api_version',
        ]);

        $response->assertJson(['message' => 'success']);
        $this->assertNotEmpty($response->json('data.token'));
    }

    #[Test]
    public function it_logs_in_a_user_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'PASSWORD',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }
}
