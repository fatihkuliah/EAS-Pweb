<section class="order-page position-relative overflow-hidden">
      <img src="assets/images/aboutus atas kiri.svg" alt="" class="aboutus-atas-kiri position-absolute z-0" />
      <div class="container position-relative z-1">
        <div class="row py-5">
          <div class="col-lg-8">
            <div class="judul-menu d-inline-block">Detail Menu</div>
            <p class="menu-tagline mt-3"><span id="detailName">Menu MieME</span><br />Atur jumlah, simpan favorit, lalu masukkan ke keranjang.</p>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-lg-end">
            <a href="<?= url("order") ?>" class="order-back text-decoration-none">Kembali Menu</a>
          </div>
        </div>
        <div class="row pb-5" id="menuDetail">
          <div class="col-lg-6">
            <div class="detail-image-box">
              <?= image_tag($menu['gambar'], $menu['nama'], 234, 325, ['loading' => null]) ?>
            </div>
          </div>
          <div class="col-lg-6 mt-4 mt-lg-0">
            <div class="summary-box h-100">
              <p class="m-0"><?= e($menu['kategori']) ?></p>
              <h1><?= e($menu['nama']) ?></h1>
              <p><?= e($menu['deskripsi']) ?></p>
              <div class="info-row">
                <span><i class="bi bi-person-fill"></i> <?= e($menu['porsi']) ?></span>
                <span><i class="bi bi-clock-fill"></i> <?= e($menu['waktu']) ?></span>
              </div>
              <h2 class="mt-4"><?= rupiah($menu['harga']) ?></h2>
              <div class="d-flex gap-2 mt-4">
                <form method="post" action="<?= url('favorite/toggle') ?>">
                  <input type="hidden" name="id" value="<?= e((string) $menu['id']) ?>" />
                  <input type="hidden" name="back" value="<?= e('menu/' . menu_slug($menu)) ?>" />
                  <button class="fav-order <?= $isFavorite ? 'active' : '' ?>" type="submit">♥</button>
                </form>
                <form method="post" action="<?= url('cart/add') ?>" class="flex-grow-1">
                  <input type="hidden" name="id" value="<?= e((string) $menu['id']) ?>" />
                  <input type="hidden" name="qty" value="1" />
                  <input type="hidden" name="back" value="<?= e('menu/' . menu_slug($menu)) ?>" />
                  <button class="checkout-btn flex-grow-1" type="submit">Tambah ke Keranjang</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script>
      window.MieMECurrentMenu = <?= json_encode($menu) ?>;
    </script>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/menu/menu-detail.js") ?>"></script>
