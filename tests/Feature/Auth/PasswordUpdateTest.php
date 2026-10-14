<?php

namespace Tests\Feature\Auth;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated_with_otp_and_no_current_password(): void
    {
        $user = User::factory()->create();

        EmailVerificationCode::create([
            'email' => $user->email,
            'code' => '999888',
            'type' => 'password_change',
            'expires_at' => now()->addMinutes(15),
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson('/password', [
                'code' => '999888',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_invalid_otp_fails_password_update(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->putJson('/password', [
                'code' => '000000',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertStatus(422);
    }
}
