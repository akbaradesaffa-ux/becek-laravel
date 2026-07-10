<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

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

        $users = $userQuery->get();
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
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:tb_user,email',
            'password' => 'required|min:6|confirmed',
            'status_role' => ['required', Rule::in(['User', 'Administrator'])],
        ]);

        $payload = [
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => strtolower($data['email']),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'status_role' => $data['status_role'],
        ];

        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = strtolower($data['email']);
        }

        User::create($payload);

        return redirect()->route('admin.users')->with('success', 'User baru berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => ['required', 'email', 'max:150', Rule::unique('tb_user', 'email')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'status_role' => ['required', Rule::in(['User', 'Administrator'])],
        ]);

        $role = $data['status_role'];
        if ((int) session('id_user') === (int) $user->id) {
            $role = 'Administrator';
        }

        $payload = [
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => strtolower($data['email']),
            'status_role' => $role,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = strtolower($data['email']);
        }

        $user->update($payload);

        return redirect()->route('admin.users')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = (int) $id;

        if ($id === (int) session('id_user')) {
            return redirect()->route('admin.users')->with('error', 'Akun admin yang sedang aktif tidak bisa dihapus dari halaman ini.');
        }

        if (Schema::hasTable('tb_favorit')) {
            Favorite::where('user_id', $id)->delete();
        }

        User::destroy($id);

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }
}
