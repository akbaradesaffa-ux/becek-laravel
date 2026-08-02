<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AccountController extends Controller
{
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $userId = (int) session('id_user');
        $user = User::find($userId);

        if (! $user) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        $passwordIsHashed = password_get_info((string) $user->password)['algoName'] !== 'unknown';
        $passwordValid = $passwordIsHashed && Hash::check($request->input('password'), $user->password);

        if (! $passwordValid) {
            return redirect()->back()->with('account_error', 'Password salah. Akun tidak dihapus.');
        }

        if (Schema::hasTable('tb_favorit')) {
            Favorite::where('user_id', $userId)->delete();
        }

        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('account_deleted', 'Akun berhasil dihapus.');
    }
}
