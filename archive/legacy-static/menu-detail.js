const id = new URLSearchParams(window.location.search).get('id');
const menu = MieME.getMenu(id) || MieME.menus[0];
const menuDetail = document.querySelector('#menuDetail');
const detailName = document.querySelector('#detailName');
let qty = 1;

detailName.innerHTML = menu.nama;

function render() {
  const isFavorite = MieME.favorites().includes(menu.id);
  menuDetail.innerHTML = `
    <div class="col-lg-6">
      <div class="detail-image-box"><img src="${menu.gambar}" alt="${menu.nama}" /></div>
    </div>
    <div class="col-lg-6 mt-4 mt-lg-0">
      <div class="summary-box h-100">
        <p class="m-0">${menu.kategori}</p>
        <h1>${menu.nama}</h1>
        <p>${menu.deskripsi}</p>
        <div class="info-row">
          <span><i class="bi bi-person-fill"></i> ${menu.porsi}</span>
          <span><i class="bi bi-clock-fill"></i> ${menu.waktu}</span>
        </div>
        <h1 class="mt-4">${MieME.rupiah(menu.harga * qty)}</h1>
        <div class="quantity-row">
          <button onclick="ubahQty(-1)">-</button>
          <span>${qty}</span>
          <button onclick="ubahQty(1)">+</button>
        </div>
        <div class="d-flex gap-2 mt-4">
          <button class="fav-order ${isFavorite ? 'active' : ''}" onclick="favorit()">♥</button>
          <button class="checkout-btn flex-grow-1" onclick="keranjang()">Tambah ke Keranjang</button>
        </div>
      </div>
    </div>
  `;
}

function ubahQty(value) {
  qty = Math.max(1, qty + value);
  render();
}

function favorit() {
  MieME.toggleFavorite(menu.id);
  render();
}

function keranjang() {
  MieME.addCart(menu.id, qty);
  window.location.href = 'order.html';
}

render();
