<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>document.documentElement.classList.add('page-transition-active');</script>
    <title>Sign In - BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/stylepage1.css') }}">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}">
</head>
<body>
    <div class="auth-shell">
        <section class="auth-panel">
            <div class="auth-card">
                <div class="brand">
                    <img src="{{ asset('images/logo-becek.png') . '?v=logo-baru-20260709' }}" alt="Logo BECEK" class="brand-logo-img">
                    <span class="brand-logo-text">BECEK</span>
                </div>

                <div class="login-header">
                    <p class="eyebrow">Direktori Kopi Bekasi</p>
                    <h1>Welcome<br>Back</h1>
                    <p>Masuk untuk mengelola sistem dashboard.</p>
                </div>

                @if (session('account_deleted'))
                    <div class="alert-success">{{ session('account_deleted') }}</div>
                @endif

                <form id="loginForm" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@example.com" autocomplete="email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="btn-go" id="loginBtn">
                        <span class="btn-text">Sign In to Dashboard</span>
                        <div id="loginLoader" class="loader"></div>
                    </button>
                </form>

                <p class="footer">Belum punya akun? <a href="javascript:void(0)" id="openSignUp">Daftar Sekarang</a></p>
            </div>
        </section>

        <section class="auth-hero" aria-label="Coffee experience">
            <div class="auth-hero-overlay"></div>
            <div class="auth-hero-content">
                <h2>The Best<br>Coffee<br>Experience.</h2>
                <p>Platform manajemen lokasi dan komunitas kopi Bekasi.</p>
            </div>
        </section>
    </div>

    <div id="signUpModal" class="modal">
        <div class="modal-content">
            <button type="button" class="close-modal" aria-label="Tutup modal">&times;</button>
            <p class="eyebrow">Akun Baru</p>
            <h2>Create Account</h2>
            <form id="signUpForm" class="auth-form">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" placeholder="Nama lengkap" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@example.com" autocomplete="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" autocomplete="new-password" minlength="6" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password" autocomplete="new-password" minlength="6" required>
                </div>
                <button type="submit" class="btn-go" id="signUpBtn">
                    <span class="btn-text">Daftar Akun</span>
                    <div id="signUpLoader" class="loader"></div>
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/jspage1.js') }}"></script>
</body>
</html>
