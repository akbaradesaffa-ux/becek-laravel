<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\User;

class AdminController extends Controller
{
    // GET /admin/dashboard
    public function index()
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        $countLokasi = Lokasi::count();
        $countUser   = User::count();
        $namaLogin   = session('nama_lengkap', 'Admin');
        $initial     = strtoupper(substr($namaLogin, 0, 1));

        return view('admin.dashboard', [
            'countLokasi' => $countLokasi,
            'countUser'   => $countUser,
            'namaLogin'   => $namaLogin,
            'initial'     => $initial,
            'activePage'  => 'admin_dashboard',
        ]);
    }
}