<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminUserController extends Controller
{
    // GET /admin/users
    public function index()
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        $users = User::orderBy('id')->get();
        $namaLogin = session('nama_lengkap', 'Admin');

        return view('admin.users', [
            'users'      => $users,
            'namaLogin'  => $namaLogin,
            'initial'    => strtoupper(substr($namaLogin, 0, 1)),
            'activePage' => 'admin_users',
        ]);
    }

    // GET /admin/users/delete/{id}
    public function destroy($id)
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        User::destroy($id);

        return redirect()->route('admin.users');
    }
}