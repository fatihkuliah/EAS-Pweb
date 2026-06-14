<section id="order-landing" class="position-relative overflow-hidden">
      <div class="border-landing order-landingcon position-relative overflow-hidden z-3">
        <img src="assets/images/kiri.svg" alt="" class="gambar-kiri position-absolute" />
        <img src="assets/images/lope.svg" alt="" class="lope position-absolute" />
        <div class="container order-landingcon position-relative">
          <div class="Qdayak d-block position-absolute">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="20" viewBox="0 0 28 20" fill="none">
              <path d="M0 14.0669V4.40188L8.70806 0V9.76068L27.2725 0V9.76068L8.70806 19.4257V9.76068L0 14.0669Z" fill="#FF9533" />
              <path d="M23.2534 13.9712L27.2725 11.6746V17.4161L23.2534 19.9999V13.9712Z" fill="#FF9533" />
            </svg>
            <span style="color: #000; text-align: center; font-family: Montserrat; font-size: 20px; font-style: normal; font-weight: 800; line-height: normal; letter-spacing: -1.8px">MieME</span>
          </div>

          <div class="d-lg-inline-block d-none nav-order position-absolute">
            <a style="text-decoration: none" href="<?= url("") ?>">
              <p class="m-0 my-2 mx-3">Back</p>
            </a>
          </div>

          <div class="row align-content-end order-landingcon gap-4">
            <div class="text-center text-landing order-hero-text mx-lg-3">
              <div class="d-flex align-items-center justify-content-center">
                <p class="welcome-landing d-block" data-aos="zoom-in" data-aos-delay="100">ORDER MIEME</p>
              </div>
              <div class="tagline-landing mb-3" data-aos="zoom-in" data-aos-delay="200"><span>Pilih menu</span> favorit anda</div>
              <p class="deskripsi-tagline-landing" data-aos="zoom-in" data-aos-delay="300">Pilih menu favorit, atur porsinya di keranjang, lalu lanjutkan ke pembayaran dengan total yang langsung terhitung otomatis.</p>
            </div>

            <div class="d-flex justify-content-center align-self-end position-relative">
              <img src="assets/images/emot-lope.svg" alt="emot-lope" class="emot-lope position-absolute" />
              <img src="assets/images/king.svg" alt="" class="king position-absolute" />
              <img class="gambar-order-landing" src="assets/images/Group 18325.png" alt="" data-aos="fade-up" data-aos-delay="500" />
            </div>
          </div>
        </div>
      </div>
      <img src="assets/images/aboutus atas kanan.svg" alt="" class="aboutus-atas-kanan position-absolute z-0" />
      <img src="assets/images/aboutus atas kiri.svg" alt="" class="aboutus-atas-kiri position-absolute z-0" />
    </section>

    <section id="order-menu" class="position-relative">
      <div class="bungkus-menu order-bungkus">
        <div class="container">
          <div class="row py-5">
            <div class="col-md-8">
              <div class="judul-menu d-inline-block" data-aos="zoom-in" data-aos-delay="100">Pilih Menu</div>
              <p class="menu-tagline" data-aos="zoom-in" data-aos-delay="200">
                <span>Pesan Menu MieME</span>
                <br />
                Pilih makanan atau minuman favorit anda. Pesanan akan tetap tersimpan meskipun halaman ditutup.
              </p>
            </div>
            <div class="col-md-4 justify-content-start justify-content-lg-end align-items-center d-flex see-menu">
              <a href="<?= url("favorites") ?>" class="text-decoration-none" data-aos="zoom-in" data-aos-delay="200"> Favorit </a>
            </div>
          </div>

          <div class="row g-4 pb-5 order-menu-list" id="menuOrder"></div>
        </div>
      </div>
    </section>

    <div class="cart-bawah fixed-bottom">
      <div class="container">
        <div class="cart-panel row align-items-center">
          <div class="col-lg-7">
            <div class="cart-list" id="cartList">
              <p class="m-0 cart-empty">Belum ada menu yang dipilih</p>
            </div>
          </div>
          <div class="col-lg-5 mt-3 mt-lg-0">
            <div class="d-flex align-items-center justify-content-between gap-3">
              <div>
                <p class="m-0 cart-label">Total Harga</p>
                <h2 class="m-0" id="cartTotal">Rp0</h2>
              </div>
              <button class="checkout-btn" id="checkoutBtn" data-checkout-url="<?= url("checkout") ?>" type="button">Checkout</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/order/order.js") ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
