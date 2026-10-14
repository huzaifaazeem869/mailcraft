<?php

namespace Tests\Feature;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_sends_verification_code(): void
    {
        $response = $this->postJson('/register/send-code', [
            'name' => 'Ali Khan',
            'email' => 'ali@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('email_verification_codes', [
            'email' => 'ali@example.com',
            'type' => 'signup',
        ]);
    }

    public function test_signup_verifies_code_and_creates_user(): void
    {
        // First create pending code
        EmailVerificationCode::create([
            'email' => 'ali@example.com',
            'code' => '654321',
            'type' => 'signup',
            'payload' => [
                'name' => 'Ali Khan',
                'password' => Hash::make('password123'),
            ],
            'expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->postJson('/register/verify', [
            'email' => 'ali@example.com',
            'code' => '654321',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'ali@example.com',
            'name' => 'Ali Khan',
        ]);

        $this->assertAuthenticated();
    }

    public function test_password_update_requires_code_and_no_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        // Send OTP
        $this->actingAs($user)->postJson('/password/send-code')->assertStatus(200);

        $codeRecord = EmailVerificationCode::where('email', $user->email)
            ->where('type', 'password_change')
            ->first();

        $this->assertNotNull($codeRecord);

        // Update password with code and NO current_password
        $response = $this->actingAs($user)->putJson('/password', [
            'code' => $codeRecord->code,
            'password' => 'newbrandpassword123',
            'password_confirmation' => 'newbrandpassword123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $user->refresh();
        $this->assertTrue(Hash::check('newbrandpassword123', $user->password));
    }
}
