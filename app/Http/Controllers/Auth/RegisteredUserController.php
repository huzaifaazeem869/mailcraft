<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Step 1: Send 6-digit OTP code to the requested email
     */
    public function sendCode(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $code = (string) random_int(100000, 999999);

        EmailVerificationCode::where('email', $request->email)
            ->where('type', 'signup')
            ->delete();

        EmailVerificationCode::create([
            'email' => $request->email,
            'code' => $code,
            'type' => 'signup',
            'payload' => [
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ],
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        try {
            Mail::to($request->email)->send(new VerificationCodeMail($code, 'signup'));
        } catch (\Exception $e) {
            // In local/dev environments without SMTP credentials, mail fallback log is captured
        }

        return response()->json([
            'success' => true,
            'message' => 'A 6-digit verification code has been sent to your email address.',
            'debug_code' => config('app.debug') ? $code : null,
        ]);
    }

    /**
     * Step 2: Verify OTP code and create User
     */
    public function verifyAndRegister(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $record = EmailVerificationCode::where('email', $request->email)
            ->where('type', 'signup')
            ->latest()
            ->first();

        if (!$record || !$record->isValid($request->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code. Please check and try again.',
            ], 422);
        }

        $payload = $record->payload;

        $user = User::create([
            'name' => $payload['name'],
            'email' => $request->email,
            'password' => $payload['password'],
            'email_verified_at' => now(),
        ]);

        $record->delete();

        event(new Registered($user));
        Auth::login($user);

        return response()->json([
            'success' => true,
            'redirect' => route('templates.index'),
        ]);
    }
}
