const profileForm = document.querySelector('#profileForm');
const logoutBtn = document.querySelector('#logoutBtn');
let user = MieME.user();

if (!user) {
  window.location.href = 'auth';
}

document.querySelector('#profileName').value = user?.nama || '';
document.querySelector('#profileEmail').value = user?.email || '';
document.querySelector('#profilePhone').value = user?.telepon || '';
document.querySelector('#profileAddress').value = user?.alamat || '';

profileForm.addEventListener('submit', (event) => {
  event.preventDefault();

  const formData = new FormData();
  formData.append('nama', document.querySelector('#profileName').value.trim());
  formData.append('email', document.querySelector('#profileEmail').value.trim());
  formData.append('telepon', document.querySelector('#profilePhone').value.trim());
  formData.append('alamat', document.querySelector('#profileAddress').value.trim());

  fetch('profile', { method: 'POST', body: formData })
    .then((response) => {
      if (response.ok) {
        alert('Profil berhasil disimpan.');
        window.location.reload();
      } else {
        alert('Gagal memperbarui profil.');
      }
    });
});

logoutBtn.addEventListener('click', () => {
  fetch('logout', { method: 'POST' })
    .then(() => {
      localStorage.removeItem(MieME.keys.user);
      window.location.href = '';
    });
});
