const userModal = document.getElementById('userModal');
const userForm = document.getElementById('userForm');
const userMethod = document.getElementById('userMethod');
const userModalTitle = document.getElementById('userModalTitle');
const userSubmitButton = document.getElementById('userSubmitButton');
const userPassword = document.getElementById('userPassword');
const userPasswordConfirmation = document.getElementById('userPasswordConfirmation');
const passwordEditNote = document.getElementById('passwordEditNote');
const roleLockNote = document.getElementById('roleLockNote');

function openCreateUserModal() {
    userForm.reset();
    userForm.action = userForm.dataset.storeUrl || '/admin/users/store';
    userMethod.disabled = true;
    userMethod.value = 'POST';
    userModalTitle.textContent = 'Tambah User Baru';
    userSubmitButton.textContent = 'Simpan User';
    userPassword.required = true;
    userPasswordConfirmation.required = true;
    userPassword.placeholder = 'Minimal 6 karakter';
    passwordEditNote.style.display = 'none';
    roleLockNote.style.display = 'none';
    document.getElementById('userRole').disabled = false;
    userModal.style.display = 'flex';
}

function openEditUserModal(button) {
    userForm.reset();
    userForm.action = button.dataset.updateUrl;
    userMethod.disabled = false;
    userMethod.value = 'PUT';
    userModalTitle.textContent = 'Edit User';
    userSubmitButton.textContent = 'Update User';
    userPassword.required = false;
    userPasswordConfirmation.required = false;
    userPassword.placeholder = 'Kosongkan jika tidak diganti';
    passwordEditNote.style.display = 'block';

    document.getElementById('userNama').value = button.dataset.nama || '';
    document.getElementById('userEmail').value = button.dataset.email || '';
    document.getElementById('userRole').value = button.dataset.role || 'User';

    const isCurrentAdmin = button.dataset.isCurrent === '1';
    roleLockNote.style.display = isCurrentAdmin ? 'block' : 'none';

    userModal.style.display = 'flex';
}

function closeUserModal() {
    userModal.style.display = 'none';
    userForm.reset();
}

function confirmAdminDelete(message) {
    return window.confirm(message || 'Yakin ingin menghapus data ini?');
}

if (userForm) {
    userForm.dataset.storeUrl = userForm.action;

    userForm.addEventListener('submit', function (event) {
        const password = userPassword.value;
        const confirmation = userPasswordConfirmation.value;

        if (password || confirmation || userPassword.required) {
            if (password.length < 6) {
                event.preventDefault();
                alert('Password minimal 6 karakter.');
                return;
            }

            if (password !== confirmation) {
                event.preventDefault();
                alert('Password dan konfirmasi password harus sama.');
            }
        }
    });
}

if (userModal) {
    userModal.addEventListener('click', function (event) {
        if (event.target === userModal) {
            closeUserModal();
        }
    });
}
