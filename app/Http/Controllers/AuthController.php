<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // GET /  -> tampilkan form login
    public function showLogin()
    {
        // Kalau sudah login, langsung arahkan sesuai role
        if (session('role')) {
            return session('role') === 'Administrator'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // POST /login -> proses login (dipanggil via AJAX, response JSON)
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password belum benar.',
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $loginSuccess = false;

        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $loginSuccess = true;
            } elseif ($user->password === $request->password) {
                // Migrasi password lama yang masih plain text
                $user->password = password_hash($request->password, PASSWORD_DEFAULT);
                $user->save();
                $loginSuccess = true;
            }
        }

        if ($loginSuccess) {
            session([
                'id_user'      => $user->id,
                'email'        => $user->email,
                'nama_lengkap' => $user->nama_lengkap,
                'role'         => $user->status_role,
            ]);

            return response()->json(['success' => true, 'role' => $user->status_role]);
        }

        return response()->json(['success' => false, 'message' => 'Email atau password salah!']);
    }

    // POST /register -> proses signup (dipanggil via AJAX, response text biasa)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:150',
            'email'        => 'required|email|max:150',
            'password'     => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('password')) {
                return 'password_invalid';
            }

            return 'invalid';
        }

        if (User::where('email', $request->email)->exists()) {
            return 'exists';
        }

        $payload = [
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => strtolower($request->email),
            'password'     => password_hash($request->password, PASSWORD_DEFAULT),
            'status_role'  => 'User',
        ];

        // Kompatibilitas untuk database lama yang masih punya kolom username.
        // Kolom ini tidak lagi dipakai di tampilan, tetapi diisi agar constraint lama tidak error.
        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = strtolower($request->email);
        }

        User::create($payload);

        return 'success';
    }

    // GET /logout
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
