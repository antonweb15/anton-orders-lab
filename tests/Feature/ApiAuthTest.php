<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_cannot_access_protected_api()
    {
        $this->getJson('/api/profile')
            ->assertStatus(401);
    }

    #[Test]
    public function user_can_login_and_access_profile()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $login = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $login->json('token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/profile')
            ->assertStatus(200)
            ->assertJson([
                'email' => $user->email,
            ]);
    }

    #[Test]
    public function login_returns_401_for_invalid_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(401)->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }

    #[Test]
    public function login_returns_422_for_invalid_payload()
    {
        $this->postJson('/api/login', [
            'email' => 'not-an-email',
        ])->assertStatus(422)->assertJsonValidationErrors([
            'email',
            'password',
        ]);
    }
}
