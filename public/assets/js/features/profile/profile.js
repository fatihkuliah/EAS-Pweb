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
  MieME.write(MieME.keys.user, {
    nama: document.querySelector('#profileName').value.trim(),
    email: document.querySelector('#profileEmail').value.trim(),
    telepon: document.querySelector('#profilePhone').value.trim(),
    alamat: document.querySelector('#profileAddress').value.trim(),
    avatar: avatarPreview.src,
  });
  alert('Profil berhasil disimpan.');
});

logoutBtn.addEventListener('click', () => {
  localStorage.removeItem(MieME.keys.user);
  window.location.href = '';
});
