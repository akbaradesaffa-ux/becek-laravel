<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - BECEK</title>
    <link rel="stylesheet" href="{{ asset('css/stylepage1.css') }}">
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="brand">
                <img src="{{ asset('images/logo-becek.png') }}" alt="Logo BECEK" class="brand-logo-img">        <span class="brand-logo-text">BECEK</span>
            </div>
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Masuk untuk mengelola sistem dashboard.</p>
            </div>
            <form id="loginForm">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Username Anda" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-go" id="loginBtn">
                    <span class="btn-text">Sign In to Dashboard</span>
                    <div id="loginLoader" class="loader"></div>
                </button>
            </form>
            <p class="footer">Belum punya akun? <a href="javascript:void(0)" id="openSignUp">Daftar Sekarang</a></p>
        </div>
        <div class="right">
            <div class="right-content">
                <h2>The Best Coffee Experience.</h2>
                <p>Platform manajemen lokasi dan komunitas kopi Bekasi.</p>
            </div>
        </div>
    </div>

    <div id="signUpModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Create Account</h2>
            <form id="signUpForm">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-go" id="signUpBtn">
                    <span class="btn-text">Daftar Akun</span>
                    <div id="signUpLoader" class="loader"></div>
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/jspage1.js') }}"></script>
</body>
</html>