<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    // GET /about
    public function about()
    {
        return view('user.about', [
            'namaLengkap'     => session('nama_lengkap', 'Pengunjung'),
            'activePage'      => 'about',
            'isAuthenticated' => (bool) session('id_user'),
        ]);
    }
}
