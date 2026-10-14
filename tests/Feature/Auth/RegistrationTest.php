<?php

namespace Tests\Feature\Auth;

use App\Models\EmailVerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_via_otp(): void
    {
        EmailVerificationCode::create([
            'email' => 'test@example.com',
            'code' => '123456',
            'type' => 'signup',
            'payload' => [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ],
            'expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->postJson('/register/verify', [
            'email' => 'test@example.com',
            'code' => '123456',
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }
}
