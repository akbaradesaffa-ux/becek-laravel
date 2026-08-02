<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $userQuery = User::orderBy('id');

        if ($search !== '') {
            $userQuery->where(function ($query) use ($search) {
                $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('status_role', 'like', "%{$search}%");

                if (Schema::hasColumn('tb_user', 'username')) {
                    $query->orWhere('username', 'like', "%{$search}%");
                }
            });
        }

        $users = $userQuery->paginate(5)->withQueryString();
        $namaLogin = session('nama_lengkap', 'Admin');

        return view('admin.users', [
            'users' => $users,
            'namaLogin' => $namaLogin,
            'initial' => strtoupper(substr($namaLogin, 0, 1)),
            'activePage' => 'admin_users',
            'currentAdminId' => (int) session('id_user'),
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $this->normalizeIdentityFields($request);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:tb_user,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'status_role' => ['required', Rule::in(['User', 'Administrator'])],
        ]);

        $payload = [
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status_role' => $data['status_role'],
        ];

        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = $data['email'];
        }

        User::create($payload);

        return redirect()->route('admin.users')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $this->normalizeIdentityFields($request);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('tb_user', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'status_role' => ['required', Rule::in(['User', 'Administrator'])],
        ]);

        $isCurrentAdmin = (int) session('id_user') === (int) $user->id;
        $role = $isCurrentAdmin ? 'Administrator' : $data['status_role'];

        $payload = [
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'status_role' => $role,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = $data['email'];
        }

        $user->update($payload);

        if ($isCurrentAdmin) {
            $request->session()->put([
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'role' => 'Administrator',
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        if ($id === (int) session('id_user')) {
            return redirect()->route('admin.users')->with('error', 'Akun admin yang sedang aktif tidak bisa dihapus dari halaman ini.');
        }

        $user = User::findOrFail($id);

        if ($user->status_role === 'Administrator' && User::where('status_role', 'Administrator')->count() <= 1) {
            return redirect()->route('admin.users')->with('error', 'Administrator terakhir tidak dapat dihapus.');
        }

        if (Schema::hasTable('tb_favorit')) {
            Favorite::where('user_id', $id)->delete();
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    private function normalizeIdentityFields(Request $request): void
    {
        $request->merge([
            'nama_lengkap' => trim((string) $request->input('nama_lengkap')),
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);
    }
}
