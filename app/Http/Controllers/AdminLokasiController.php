<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class AdminLokasiController extends Controller
{
    // GET /admin/lokasi
    public function index(Request $request)
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        $lokasiList = Lokasi::with('fasilitas')->orderBy('id', 'desc')->get();
        $fasilitasList = Fasilitas::orderBy('id', 'desc')->get();
        $fasilitasCheckbox = Fasilitas::orderBy('nama_fasilitas')->get();
        $namaLogin = session('nama_lengkap', 'Admin');

        return view('admin.lokasi', [
            'lokasiList'        => $lokasiList,
            'fasilitasList'     => $fasilitasList,
            'fasilitasCheckbox' => $fasilitasCheckbox,
            'namaLogin'         => $namaLogin,
            'initial'           => strtoupper(substr($namaLogin, 0, 1)),
            'status'            => $request->query('status'),
            'activePage'        => 'admin_lokasi',
        ]);
    }

    // POST /admin/lokasi/store
    public function store(Request $request)
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        $request->validate([
            'nama'      => 'required|string',
            'kategori'  => 'required|string',
            'harga'     => 'required|string',
            'link_maps' => 'required|url',
            'foto'      => 'required|image',
        ]);

        // Mapping ke nilai enum yang valid, sesuai perilaku lama
        $kategoriInput = $request->input('kategori');
        $kategori = in_array($kategoriInput, ['Manual Brew', 'Modern Espresso'], true) ? 'Cafe' : $kategoriInput;

        $foto = $request->file('foto');
        $namaFoto = time() . '_' . $foto->getClientOriginalName();
        $foto->move(public_path('uploads'), $namaFoto);

        $lokasi = Lokasi::create([
            'kode_lokasi'      => $this->generateUniqueCode(),
            'nama'             => $request->input('nama'),
            'kategori'         => $kategori,
            'rentang_harga'    => $request->input('harga'),
            'link_google_maps' => $request->input('link_maps'),
            'jalur_foto'       => $namaFoto,
        ]);

        $fasilitasIds = Fasilitas::whereIn('nama_fasilitas', $request->input('fasilitas', []))->pluck('id');
        $lokasi->fasilitas()->attach($fasilitasIds);

        return redirect()->route('admin.lokasi');
    }

    // GET /admin/lokasi/delete/{id}
    public function destroy($id)
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        $lokasi = Lokasi::find($id);

        if ($lokasi) {
            $filePath = public_path('uploads/' . $lokasi->jalur_foto);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $lokasi->delete();
        }

        return redirect()->route('admin.lokasi');
    }

    // POST /admin/fasilitas/store
    public function storeFasilitas(Request $request)
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        $nama = trim($request->input('nama_fasilitas_baru', ''));

        if (empty($nama)) {
            return redirect()->route('admin.lokasi');
        }

        if (Fasilitas::where('nama_fasilitas', $nama)->exists()) {
            return redirect()->route('admin.lokasi', ['status' => 'exists']);
        }

        Fasilitas::create(['nama_fasilitas' => $nama]);

        return redirect()->route('admin.lokasi', ['status' => 'success']);
    }

    // GET /admin/fasilitas/delete/{id}
    public function deleteFasilitas($id)
    {
        if (session('role') !== 'Administrator') {
            return redirect()->route('login');
        }

        Fasilitas::destroy($id);

        return redirect()->route('admin.lokasi');
    }

    private function generateUniqueCode(): string
    {
        do {
            $kode = '#' . random_int(100, 999);
        } while (Lokasi::where('kode_lokasi', $kode)->exists());

        return $kode;
    }
}