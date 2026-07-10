<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AccountController extends Controller
{
    public function destroy(Request $request)
    {
        if (!session('id_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        $userId = (int) session('id_user');
        $user = User::find($userId);

        if (!$user) {
            session()->flush();
            return redirect()->route('login');
        }

        $passwordValid = password_verify($request->password, $user->password) || $user->password === $request->password;

        if (!$passwordValid) {
            return redirect()->back()->with('account_error', 'Password salah. Akun tidak dihapus.');
        }

        if (Schema::hasTable('tb_favorit')) {
            Favorite::where('user_id', $userId)->delete();
        }

        $user->delete();
        session()->flush();

        return redirect()->route('login')->with('account_deleted', 'Akun berhasil dihapus.');
    }
}
