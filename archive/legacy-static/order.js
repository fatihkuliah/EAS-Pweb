if (window.AOS) AOS.init();

const menuOrder = document.querySelector('#menuOrder');
const cartList = document.querySelector('#cartList');
const cartTotal = document.querySelector('#cartTotal');
const checkoutBtn = document.querySelector('#checkoutBtn');

const menus = MieME.menus;
let cart = MieME.cart();

function rupiah(angka) {
  return MieME.rupiah(angka);
}

function simpanCart() {
  MieME.write(MieME.keys.cart, cart);
}

function cariCart(id) {
  return cart.find((item) => item.id == id);
}

function tambahCart(id) {
  const menu = menus.find((item) => item.id == id);
  const cartItem = cariCart(id);

  if (cartItem) {
    cartItem.qty += 1;
  } else {
    cart.push({ ...menu, qty: 1 });
  }

  simpanCart();
  renderCart();
}

function kurangCart(id) {
  const cartItem = cariCart(id);

  if (!cartItem) return;

  cartItem.qty -= 1;

  if (cartItem.qty <= 0) {
    cart = cart.filter((item) => item.id != id);
  }

  simpanCart();
  renderCart();
}

function renderMenu() {
  menuOrder.innerHTML = menus
    .map(
      (menu, index) => `
        <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="${100 + index * 50}">
          <div class="menu-menu order-card row h-100 m-0">
            <div class="col-5 text-start text p-0 d-flex align-items-center justify-content-center order-card-img">
              <img src="${menu.gambar}" alt="${menu.nama}" />
            </div>
            <div class="col-7">
              <div class="order-card-body d-flex h-100 flex-column justify-content-between">
                <div>
                  <a href="menu-detail.html?id=${menu.id}" class="text-decoration-none"><h1>${menu.nama}</h1></a>
                  <p class="mt-3">${menu.deskripsi}</p>
                </div>
                <div>
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="porsi text-center">
                      <p class="m-0">Porsi</p>
                      <i class="bi bi-person-fill order-person"></i>
                    </div>
                    <svg class="mx-4" xmlns="http://www.w3.org/2000/svg" width="3" height="31" viewBox="0 0 3 31" fill="none">
                      <path d="M1.13452 29.0179L1.13452 1" stroke="#7A4818" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <div class="waktu-saji">
                      <p class="m-0">Waktu Penyajian</p>
                      <h2>${menu.waktu}</h2>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="price text-start">
                      <p class="m-0">Price</p>
                      <h2>${rupiah(menu.harga)}</h2>
                    </div>
                    <div class="d-flex gap-2">
                      <button class="fav-order ${MieME.favorites().includes(menu.id) ? 'active' : ''}" onclick="toggleFavorit(${menu.id}, this)">♥</button>
                      <button class="tambah-order" onclick="tambahCart(${menu.id})">Tambah</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `
    )
    .join('');
}

function renderCart() {
  const total = cart.reduce((hasil, item) => hasil + item.harga * item.qty, 0);

  if (cart.length == 0) {
    cartList.innerHTML = '<p class="m-0 cart-empty">Belum ada menu yang dipilih</p>';
  } else {
    cartList.innerHTML = cart
      .map(
        (item) => `
          <div class="cart-item mx-2">
            <img src="${item.gambar}" alt="${item.nama}" />
            <div>
              <h1>${item.nama}</h1>
              <p class="m-0">${rupiah(item.harga)} x ${item.qty}</p>
            </div>
            <div class="cart-action">
              <button onclick="kurangCart(${item.id})">-</button>
              <span>${item.qty}</span>
              <button onclick="tambahCart(${item.id})">+</button>
            </div>
          </div>
        `
      )
      .join('');
  }

  cartTotal.innerHTML = rupiah(total);
}

checkoutBtn.addEventListener('click', () => {
  if (cart.length == 0) return;

  localStorage.setItem(
    'miemeCheckout',
    JSON.stringify({
      items: cart,
      total: cart.reduce((hasil, item) => hasil + item.harga * item.qty, 0),
      tanggal: new Date().toISOString(),
    })
  );

  window.location.href = 'checkout.html';
});

renderMenu();
renderCart();

function toggleFavorit(id, button) {
  const next = MieME.toggleFavorite(id);
  button.classList.toggle('active', next.includes(id));
}
