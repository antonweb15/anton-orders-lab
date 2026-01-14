<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use PHPUnit\Framework\Attributes\Test;

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

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/profile')
            ->assertStatus(200)
            ->assertJson([
                'email' => $user->email,
            ]);
    }
}
