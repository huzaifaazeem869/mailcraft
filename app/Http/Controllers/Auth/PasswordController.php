<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\EmailVerificationCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Send OTP for password update
     */
    public function sendCode(Request $request): JsonResponse
    {
        $user = $request->user();
        $code = (string) random_int(100000, 999999);

        EmailVerificationCode::where('email', $user->email)
            ->where('type', 'password_change')
            ->delete();

        EmailVerificationCode::create([
            'email' => $user->email,
            'code' => $code,
            'type' => 'password_change',
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, 'password_change'));
        } catch (\Exception $e) {
            // Local fallback
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to ' . $user->email,
            'debug_code' => config('app.debug') ? $code : null,
        ]);
    }

    /**
     * Update the user's password with OTP verification and NO current_password requirement
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        $record = EmailVerificationCode::where('email', $user->email)
            ->where('type', 'password_change')
            ->latest()
            ->first();

        if (!$record || !$record->isValid($validated['code'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!',
        ]);
    }
}
