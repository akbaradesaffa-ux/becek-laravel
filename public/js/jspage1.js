// Login handler
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('loginBtn');
    const formData = new FormData(this);
    try {
        const response = await fetch('/login', { method: 'POST', body: formData });
        const data = await response.json();

        if (data.success) {
            // Arahkan berdasarkan role dari server
            if (data.role === 'Administrator') {
                window.becekNavigate ? window.becekNavigate('/admin/dashboard') : window.location.href = '/admin/dashboard';
            } else {
                window.becekNavigate ? window.becekNavigate('/dashboard') : window.location.href = '/dashboard'; // halaman untuk user biasa
            }
        } else {
            alert(data.message);
        }
    } catch (err) {
        alert('Terjadi kesalahan sistem.');
    }
});

// Sign Up handler
document.getElementById('signUpForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    try {
        const response = await fetch('/register', { method: 'POST', body: formData });
        const result = await response.text();
        if (result === 'success') {
            alert('Pendaftaran berhasil! Silakan login.');
            closeSignUpModal();
            document.getElementById('loginForm').reset();
        } else if (result === 'exists') {
            alert('Email sudah terdaftar!');
        } else if (result === 'password_invalid') {
            alert('Password minimal 6 karakter dan konfirmasi password harus sama.');
        } else if (result === 'invalid') {
            alert('Data belum lengkap atau format email belum benar.');
        } else {
            alert('Pendaftaran gagal. Coba lagi.');
        }
    } catch (err) {
        alert('Terjadi kesalahan sistem.');
    }
});

// Modal Sign Up
const modal = document.getElementById('signUpModal');
const openBtn = document.getElementById('openSignUp');
const closeBtn = document.querySelector('.close-modal');

openBtn.onclick = function() {
    modal.style.display = 'flex';
}
closeBtn.onclick = function() {
    closeSignUpModal();
}
window.onclick = function(event) {
    if (event.target == modal) {
        closeSignUpModal();
    }
}
function closeSignUpModal() {
    modal.style.display = 'none';
    document.getElementById('signUpForm').reset();
}