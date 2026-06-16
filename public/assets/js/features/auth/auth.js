const authTitle = document.querySelector('#authTitle');
const loginTab = document.querySelector('#loginTab');
const registerTab = document.querySelector('#registerTab');
const authForm = document.querySelector('#authForm');
const authSubmit = document.querySelector('#authSubmit');
const registerOnly = document.querySelectorAll('.register-only');

let mode = new URLSearchParams(window.location.search).get('mode') == 'register' ? 'register' : 'login';

function renderMode() {
  const isRegister = mode == 'register';
  authTitle.innerHTML = isRegister ? 'Register' : 'Login';
  authSubmit.innerHTML = isRegister ? 'Daftar' : 'Login';
  loginTab.classList.toggle('active', !isRegister);
  registerTab.classList.toggle('active', isRegister);
  registerOnly.forEach((item) => item.classList.toggle('d-none', !isRegister));
}

loginTab.addEventListener('click', () => {
  mode = 'login';
  renderMode();
});

registerTab.addEventListener('click', () => {
  mode = 'register';
  renderMode();
});

authForm.addEventListener('submit', (event) => {
  event.preventDefault();
  const email = document.querySelector('#emailInput').value.trim();
  const password = document.querySelector('#passwordInput').value;
  const name = document.querySelector('#nameInput').value.trim() || 'Customer MieME';
  const confirm = document.querySelector('#confirmInput').value;

  if (mode == 'register' && password != confirm) {
    alert('Password dan konfirmasi password belum sama.');
    return;
  }

  const formData = new FormData();
  formData.append('mode', mode);
  formData.append('email', email);
  formData.append('password', password);
  formData.append('nama', name);

  fetch('auth', { method: 'POST', body: formData })
    .then((response) => {
      return response.json().then((data) => {
        if (response.ok) {
          window.location.href = data.redirect || 'profile';
        } else {
          alert(data.message || 'Gagal melakukan autentikasi.');
        }
      });
    })
    .catch(() => {
      alert('Terjadi kesalahan jaringan.');
    });
});

renderMode();
