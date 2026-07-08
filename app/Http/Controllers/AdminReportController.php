<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;

class AdminReportController extends Controller
{
    // GET /admin/reports
    public function index()
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        $totalLokasi = Lokasi::count();
        $totalCafe   = Lokasi::where('kategori', 'Cafe')->count();
        $totalWarkop = Lokasi::where('kategori', 'Warkop')->count();

        $pctCafe   = $totalLokasi > 0 ? round(($totalCafe / $totalLokasi) * 100) : 0;
        $pctWarkop = $totalLokasi > 0 ? round(($totalWarkop / $totalLokasi) * 100) : 0;

        $namaLogin = session('nama_lengkap', 'Admin');

        return view('admin.reports', [
            'totalLokasi' => $totalLokasi,
            'totalCafe'   => $totalCafe,
            'totalWarkop' => $totalWarkop,
            'pctCafe'     => $pctCafe,
            'pctWarkop'   => $pctWarkop,
            'namaLogin'   => $namaLogin,
            'initial'     => strtoupper(substr($namaLogin, 0, 1)),
            'activePage'  => 'admin_reports',
        ]);
    }
}