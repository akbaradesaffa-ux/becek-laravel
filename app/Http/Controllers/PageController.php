<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    // GET /about
    public function about()
    {
        if (!session('id_user')) {
            return redirect()->route('login');
        }

        return view('user.about', [
            'namaLengkap' => session('nama_lengkap', 'Pengguna'),
            'activePage'  => 'about',
        ]);
    }
}