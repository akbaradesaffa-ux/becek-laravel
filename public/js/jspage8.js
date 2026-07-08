// Fungsi membuka jendela modal input user
function openModal() {
    document.getElementById('userModal').style.display = 'flex';
}

// Fungsi menutup modal dan mereset form isian didalamnya
function closeModal() {
    document.getElementById('userModal').style.display = 'none';
    document.querySelector('#userModal form').reset();
}

// AJAX submit form tambah user
document.getElementById('addUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    try {
        const response = await fetch('/register', { method: 'POST', body: formData });
        const result = await response.text();
        if (result === 'success') {
            alert('User berhasil ditambahkan!');
            location.reload(); // refresh halaman
        } else if (result === 'exists') {
            alert('Username sudah terdaftar!');
        } else {
            alert('Gagal menambahkan user.');
        }
    } catch (err) {
        alert('Terjadi kesalahan sistem.');
    }
});