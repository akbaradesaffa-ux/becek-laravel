<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        RateLimiter::clear('user@example.com|127.0.0.1');
        parent::tearDown();
    }

    public function test_security_headers_are_added_to_web_responses(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_user_can_login_with_a_hashed_password(): void
    {
        $user = User::create([
            'nama_lengkap' => 'User Test',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'status_role' => 'User',
        ]);

        $this->postJson('/login', [
            'email' => 'USER@EXAMPLE.COM',
            'password' => 'password123',
        ])
            ->assertOk()
            ->assertJson(['success' => true, 'role' => 'User'])
            ->assertSessionHas('id_user', $user->id)
            ->assertSessionHas('role', 'User');
    }

    public function test_plaintext_password_is_not_accepted_by_login(): void
    {
        User::query()->insert([
            'nama_lengkap' => 'Legacy User',
            'email' => 'legacy@example.com',
            'password' => 'password123',
            'status_role' => 'User',
        ]);

        $this->postJson('/login', [
            'email' => 'legacy@example.com',
            'password' => 'password123',
        ])
            ->assertUnauthorized()
            ->assertJson(['success' => false]);
    }

    public function test_login_is_rate_limited_after_repeated_failures(): void
    {
        User::create([
            'nama_lengkap' => 'User Test',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'status_role' => 'User',
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->postJson('/login', [
                'email' => 'user@example.com',
                'password' => 'salah-password',
            ])->assertUnauthorized();
        }

        $this->postJson('/login', [
            'email' => 'user@example.com',
            'password' => 'salah-password',
        ])->assertStatus(429);
    }

    public function test_logout_only_accepts_post_and_clears_session(): void
    {
        $this->get('/logout')->assertStatus(405);

        $this->withSession([
            'id_user' => 1,
            'role' => 'User',
            'email' => 'user@example.com',
        ])->post('/logout')
            ->assertRedirect(route('login'))
            ->assertSessionMissing('id_user')
            ->assertSessionMissing('role');
    }

    public function test_legacy_get_delete_routes_are_not_available(): void
    {
        $this->withSession([
            'id_user' => 1,
            'role' => 'Administrator',
        ])->get('/admin/lokasi/delete/1')->assertNotFound();

        $this->withSession([
            'id_user' => 1,
            'role' => 'Administrator',
        ])->get('/admin/users/delete/1')->assertNotFound();
    }
}
