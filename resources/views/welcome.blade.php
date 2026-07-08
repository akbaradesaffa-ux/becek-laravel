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
            <div class="form-wrapper">
                <div class="header-wrapper">
                    <div class="brand">
                        <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=200&auto=format&fit=crop" alt="Logo BECEK">
                        <span>BECEK</span>
                    </div>
                    <div class="login-header">
                        <h1>Welcome Back</h1>
                        <p>Silakan masuk menggunakan akun terdaftar Anda untuk menjelajahi katalog dan mengelola ekosistem.</p>
                    </div>
                </div>
                
                <form id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Masukkan username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-go" id="loginBtn">
                        <span class="btn-text">Sign In</span>
                        <div id="loginLoader" class="loader"></div>
                    </button>
                </form>
                
                <p class="footer">Belum punya akses? <a href="javascript:void(0)" id="openSignUp">Daftar Sekarang</a></p>
            </div>
        </div>
        <div class="right"></div>
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