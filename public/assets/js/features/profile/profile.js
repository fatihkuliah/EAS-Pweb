const profileForm = document.querySelector('#profileForm');
const logoutBtn = document.querySelector('#logoutBtn');
const avatarInput = document.querySelector('#avatarInput');
const avatarPreview = document.querySelector('#avatarPreview');
let user = MieME.user();

if (!user) {
  window.location.href = 'auth';
}

document.querySelector('#profileName').value = user?.nama || '';
document.querySelector('#profileEmail').value = user?.email || '';
document.querySelector('#profilePhone').value = user?.telepon || '';
document.querySelector('#profileAddress').value = user?.alamat || '';
if (user?.avatar) avatarPreview.src = user.avatar;

avatarInput.addEventListener('change', () => {
  const file = avatarInput.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = () => {
    avatarPreview.src = reader.result;
  };
  reader.readAsDataURL(file);
});

profileForm.addEventListener('submit', (event) => {
  event.preventDefault();

  const formData = new FormData();
  formData.append('nama', document.querySelector('#profileName').value.trim());
  formData.append('email', document.querySelector('#profileEmail').value.trim());
  formData.append('telepon', document.querySelector('#profilePhone').value.trim());
  formData.append('alamat', document.querySelector('#profileAddress').value.trim());
  
  if (avatarInput.files[0]) {
    formData.append('avatar', avatarInput.files[0]);
  }

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
