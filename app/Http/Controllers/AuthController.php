<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('id_user') && session('role')) {
            return session('role') === 'Administrator'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password belum benar.',
            ], 422);
        }

        $throttleKey = $this->loginThrottleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . RateLimiter::availableIn($throttleKey) . ' detik.',
            ], 429);
        }

        $user = User::where('email', $request->input('email'))->first();
        $passwordIsHashed = $user && password_get_info((string) $user->password)['algoName'] !== 'unknown';
        $loginSuccess = $passwordIsHashed && Hash::check($request->input('password'), $user->password);

        if (! $loginSuccess) {
            RateLimiter::hit($throttleKey, 60);

            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah!',
            ], 401);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        $request->session()->put([
            'id_user' => $user->id,
            'email' => $user->email,
            'nama_lengkap' => $user->nama_lengkap,
            'role' => $user->status_role,
        ]);

        return response()->json([
            'success' => true,
            'role' => $user->status_role,
        ]);
    }

    public function register(Request $request)
    {
        $request->merge([
            'nama_lengkap' => trim((string) $request->input('nama_lengkap')),
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('password')) {
                return response('password_invalid', 422);
            }

            return response('invalid', 422);
        }

        if (User::where('email', $request->input('email'))->exists()) {
            return response('exists', 409);
        }

        $payload = [
            'nama_lengkap' => $request->input('nama_lengkap'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'status_role' => 'User',
        ];

        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = $request->input('email');
        }

        User::create($payload);

        return response('success', 201);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function loginThrottleKey(Request $request): string
    {
        return Str::lower((string) $request->input('email')) . '|' . $request->ip();
    }
}
