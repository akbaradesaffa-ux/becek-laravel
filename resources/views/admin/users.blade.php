<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - BECEK</title>
    <link rel="stylesheet" href="{{ asset('css/stylepage8.css') }}">
</head>
<body>
@include('partials.navbar_admin', [
    'activePage'   => $activePage,
    'namaLogin'    => $namaLogin,
    'namaLengkap'  => $namaLogin,
    'initial'      => $initial
])
    <main class="main-content">
        <div class="header-actions">
            <div class="header-title">
                <h1>Manage Users</h1>
                <p>Manajemen hak akses akun pendaftar aplikasi.</p>
            </div>
            <button class="btn-add" onclick="openModal()">+ Tambah User</button>
        </div>

        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $row)
                    <tr>
                        <td>
                            <div class="user-flex">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($row->nama_lengkap, 0, 1)) }}
                                </div>
                                <span class="user-name">{{ $row->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td>{{ $row->username }}</td>
                        <td>
                            @if ($row->status_role === 'Administrator')
                                <span class="badge-role-admin">Administrator</span>
                            @else
                                <span class="badge-role-user">User</span>
                            @endif
                        </td>
                        <td>
                            @if ($row->id != 1)
                                <a href="{{ route('admin.users.delete', $row->id) }}" class="btn-delete" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                            @else
                                <span class="text-main-user">Utama</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div id="userModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Tambah User Baru</h3>
            <form id="addUserForm">
                @csrf
                <label class="modal-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required>

                <label class="modal-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Username Baru" required>

                <label class="modal-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/jspage8.js') }}"></script>
</body>
</html>