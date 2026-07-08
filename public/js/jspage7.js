// Fungsi untuk memunculkan Jendela Modal Input
function openModal() {
    document.getElementById('locationModal').style.display = 'flex';
}

// Fungsi untuk menyembunyikan Jendela Modal dan mereset form isian
function closeModal() {
    document.getElementById('locationModal').style.display = 'none';
    document.querySelector('#locationModal form').reset();
}