<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();
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
                'username'     => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'role'         => $user->status_role,
            ]);

            return response()->json(['success' => true, 'role' => $user->status_role]);
        }

        return response()->json(['success' => false, 'message' => 'Username atau Password salah!']);
    }

    // POST /register -> proses signup (dipanggil via AJAX, response text biasa)
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'username'     => 'required',
            'password'     => 'required',
        ]);

        if (User::where('username', $request->username)->exists()) {
            return 'exists';
        }

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'password'     => password_hash($request->password, PASSWORD_DEFAULT),
            'status_role'  => 'User',
        ]);

        return 'success';
    }

    // GET /logout
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
