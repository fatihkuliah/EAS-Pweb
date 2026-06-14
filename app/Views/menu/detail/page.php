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
        <div class="row pb-5" id="menuDetail"></div>
      </div>
    </section>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/menu/menu-detail.js") ?>"></script>
