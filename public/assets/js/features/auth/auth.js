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

  MieME.write(MieME.keys.user, {
    nama: mode == 'register' ? name : 'Customer MieME',
    email,
    telepon: '081234567890',
    alamat: 'Jl. Jendral Sudirman No. 1, Jakarta',
    avatar: '',
  });
  window.location.href = 'profile';
});

renderMode();
