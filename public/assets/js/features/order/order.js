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

function cariCart(id) {
  return cart.find((item) => item.id == id);
}

function tambahCart(id) {
  const cartItem = cariCart(id);
  const promise = cartItem 
    ? MieME.updateCart(id, cartItem.qty + 1)
    : MieME.addCart(id, 1);

  promise.then((newCart) => {
    cart = newCart;
    renderCart();
  });
}

function kurangCart(id) {
  const cartItem = cariCart(id);
  if (!cartItem) return;
  
  MieME.updateCart(id, cartItem.qty - 1).then((newCart) => {
    cart = newCart;
    renderCart();
  });
}

function renderMenu() {
  menuOrder.innerHTML = menus
    .map(
      (menu, index) => `
        <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="${100 + index * 50}">
          <div class="menu-menu order-card row h-100 m-0">
            <div class="col-5 text-start text p-0 d-flex align-items-center justify-content-center order-card-img">
              <img src="${MieME.assetUrl(menu.gambar)}" alt="${menu.nama}" width="234" height="325" loading="lazy" decoding="async" />
            </div>
            <div class="col-7">
              <div class="order-card-body d-flex h-100 flex-column justify-content-between">
                <div>
                  <a href="${MieME.menuUrl(menu)}" class="text-decoration-none"><h3>${menu.nama}</h3></a>
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
                      <button class="fav-order ${MieME.favorites().map(f => Number(f.id)).includes(Number(menu.id)) ? 'active' : ''}" onclick="toggleFavorit(${menu.id}, this)">♥</button>
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
  checkoutBtn.disabled = cart.length == 0;
  checkoutBtn.classList.toggle('is-disabled', cart.length == 0);

  if (cart.length == 0) {
    cartList.innerHTML = '<p class="m-0 cart-empty">Belum ada menu yang dipilih</p>';
  } else {
    cartList.innerHTML = cart
      .map(
        (item) => `
          <div class="cart-item mx-2">
            <img src="${MieME.assetUrl(item.gambar)}" alt="${item.nama}" width="64" height="64" loading="lazy" decoding="async" />
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
  if (cart.length == 0) {
    alert('Pilih menu terlebih dahulu sebelum checkout.');
    return;
  }

  localStorage.setItem(
    'miemeCheckout',
    JSON.stringify({
      items: cart,
      total: cart.reduce((hasil, item) => hasil + item.harga * item.qty, 0),
      tanggal: new Date().toISOString(),
    })
  );

  window.location.href = checkoutBtn.dataset.checkoutUrl || 'checkout';
});

renderMenu();
renderCart();

function toggleFavorit(id, button) {
  MieME.toggleFavorite(id).then((newFavorites) => {
    const isFav = newFavorites.map(f => Number(f.id)).includes(Number(id));
    button.classList.toggle('active', isFav);
  });
}
