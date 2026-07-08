<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;

class DashboardController extends Controller
{
    // GET /dashboard -> tampilkan grid cafe/warkop
    public function index()
    {
        if (!session('id_user')) {
            return redirect()->route('login');
        }

        $lokasi = Lokasi::with('fasilitas')->orderBy('id', 'desc')->get();
        $namaLengkap = session('nama_lengkap', 'Pengguna');

        return view('user.dashboard', [
            'lokasi'      => $lokasi,
            'namaLengkap' => $namaLengkap,
            'activePage'  => 'dashboard',
        ]);
    }
}