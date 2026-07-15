<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('becek-theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme === 'light' ? 'light' : 'dark');
            } catch (error) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
            document.documentElement.classList.add('page-transition-active');
        })();
    </script>
    <title>User Management - BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-admin.css') }}?v=academic-footer-20260716">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=advanced-20260709">
</head>
<body>
@include('partials.navbar_admin', [
    'activePage'   => $activePage,
    'namaLogin'    => $namaLogin,
    'namaLengkap'  => $namaLogin,
    'initial'      => $initial
])
    <main class="main-content page-enter">
        @if (session('success'))
            <div class="alert-box success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-box danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-box danger">{{ $errors->first() }}</div>
        @endif

        <div class="header-actions">
            <div class="header-title">
                <p class="eyebrow">Kontrol Akses</p>
                <h1>Manage Users</h1>
                <p>Tambah, edit, hapus, cari, dan atur role akun pengguna aplikasi.</p>
            </div>
            <button class="btn-add" onclick="openCreateUserModal()">+ Tambah User</button>
        </div>

        <form action="{{ route('admin.users') }}" method="GET" class="admin-search-panel" role="search">
            <div class="admin-search-field">
                <span>⌕</span>
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, email, atau role user...">
            </div>
            <button type="submit" class="btn-add">Cari</button>
            @if(!empty($search))
                <a href="{{ route('admin.users') }}" class="btn-reset-search">Reset</a>
            @endif
        </form>

        <p class="admin-result-note">Menampilkan {{ $users->count() }} user{{ !empty($search) ? ' untuk pencarian: ' . $search : '' }}.</p>

        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $row)
                    <tr>
                        <td>
                            <div class="user-flex">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($row->nama_lengkap, 0, 1)) }}
                                </div>
                                <span class="user-name">{{ $row->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td><span class="text-primary-code">{{ $row->email ?? '-' }}</span></td>
                        <td>
                            @if ($row->status_role === 'Administrator')
                                <span class="badge-role-admin">Administrator</span>
                            @else
                                <span class="badge-role-user">User</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div class="action-inline">
                                <button type="button"
                                        class="btn-edit"
                                        onclick="openEditUserModal(this)"
                                        data-id="{{ $row->id }}"
                                        data-update-url="{{ route('admin.users.update', $row->id) }}"
                                        data-nama="{{ e($row->nama_lengkap) }}"
                                        data-email="{{ e($row->email) }}"
                                        data-role="{{ $row->status_role }}"
                                        data-is-current="{{ (int)$row->id === (int)$currentAdminId ? '1' : '0' }}">
                                    Edit
                                </button>
                                @if ((int)$row->id !== (int)$currentAdminId)
                                    <form action="{{ route('admin.users.delete', $row->id) }}" method="POST" class="inline-delete-form" onsubmit="return confirmAdminDelete('Hapus user {{ e($row->nama_lengkap) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-main-user">Akun Aktif</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-row">Belum ada data pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    @include('partials.footer_admin')

    <div id="userModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="userModalTitle">Tambah User Baru</h3>
            <form id="userForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="userMethod" value="POST" disabled>

                <label class="modal-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="userNama" class="form-control" placeholder="Nama Lengkap" required>

                <label class="modal-label">Email</label>
                <input type="email" name="email" id="userEmail" class="form-control" placeholder="email@example.com" autocomplete="email" required>

                <label class="modal-label">Role</label>
                <select name="status_role" id="userRole" class="form-control" required>
                    <option value="User">User</option>
                    <option value="Administrator">Administrator</option>
                </select>
                <small id="roleLockNote" class="muted-small" style="display:none;">Akun admin yang sedang aktif tetap dipertahankan sebagai Administrator.</small>

                <label class="modal-label">Password</label>
                <input type="password" name="password" id="userPassword" class="form-control" placeholder="Minimal 6 karakter" autocomplete="new-password" minlength="6" required>

                <label class="modal-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="userPasswordConfirmation" class="form-control" placeholder="Ulangi kata sandi" autocomplete="new-password" minlength="6" required>
                <small id="passwordEditNote" class="muted-small" style="display:none;">Saat edit, kosongkan password jika tidak ingin mengganti kata sandi.</small>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeUserModal()">Batal</button>
                    <button type="submit" class="btn-submit" id="userSubmitButton">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
    <script src="{{ asset('js/jspage8.js') }}?v=recommend-search-20260709"></script>
</body>
</html>
