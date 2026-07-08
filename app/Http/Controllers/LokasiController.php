<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    // GET /explore
    public function explore()
    {
        if (!session('id_user')) {
            return redirect()->route('login');
        }

        $lokasi = Lokasi::with('fasilitas')->orderBy('id', 'desc')->get();
        $categories = $lokasi->pluck('kategori')->filter()->unique()->values();
        $fasilitasMaster = Fasilitas::orderBy('nama_fasilitas')->get();
        $namaLengkap = session('nama_lengkap', 'Pengguna');

        return view('user.explore', [
            'lokasi'          => $lokasi,
            'categories'      => $categories,
            'fasilitasMaster' => $fasilitasMaster,
            'namaLengkap'     => $namaLengkap,
            'activePage'      => 'explore',
        ]);
    }

// GET /lokasi/{id}
    public function detail($id)
    {
        if (!session('id_user')) {
            return redirect()->route('login');
        }

        $lokasi = Lokasi::with('fasilitas')->find($id);

        if (!$lokasi) {
            return redirect()->route('dashboard');
        }

        return view('user.detail', [
            'lokasi'       => $lokasi,
            'fasilitasStr' => $lokasi->fasilitas_string,
        ]);
    }
}