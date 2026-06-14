const favoriteList = document.querySelector('#favoriteList');

function renderFavorites() {
  const items = MieME.menus.filter((menu) => MieME.favorites().includes(menu.id));
  if (!items.length) {
    favoriteList.innerHTML = '<div class="col-12"><div class="detail-box text-center"><h1>Belum ada favorit</h1><p>Simpan menu dari katalog dengan tombol hati.</p></div></div>';
    return;
  }
  favoriteList.innerHTML = items
    .map(
      (menu) => `
        <div class="col-lg-4">
          <div class="menu-menu order-card h-100">
            <img src="${menu.gambar}" alt="${menu.nama}" />
            <h1>${menu.nama}</h1>
            <p>${menu.deskripsi}</p>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <h2>${MieME.rupiah(menu.harga)}</h2>
              <div class="d-flex gap-2">
                <button class="fav-order active" onclick="hapus(${menu.id})">♥</button>
                <button class="tambah-order" onclick="tambah(${menu.id})">Tambah</button>
              </div>
            </div>
          </div>
        </div>
      `
    )
    .join('');
}

function hapus(id) {
  MieME.toggleFavorite(id);
  renderFavorites();
}

function tambah(id) {
  MieME.addCart(id);
  window.location.href = 'order';
}

renderFavorites();
