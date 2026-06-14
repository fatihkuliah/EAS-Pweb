<section class="order-page position-relative overflow-hidden">
      <div class="container position-relative z-1">
        <div class="row py-5">
          <div class="col-lg-8">
            <div class="judul-menu d-inline-block">Favorit</div>
            <p class="menu-tagline mt-3"><span>Menu yang anda simpan</span><br />Favorit memudahkan repeat order tanpa mencari ulang.</p>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-lg-end"><a href="<?= url("order") ?>" class="order-back text-decoration-none">Katalog Menu</a></div>
        </div>
        <div class="row g-4 pb-5" id="favoriteList"></div>
      </div>
    </section>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/favorites/favorites.js") ?>"></script>
