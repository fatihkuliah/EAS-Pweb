<?php
$backUrl = !empty($order) ? url("orders") : url("checkout");
?>
<section class="order-page position-relative overflow-hidden">
      <img src="assets/images/aboutus atas kanan.svg" alt="" class="aboutus-atas-kanan position-absolute z-0" />
      <img src="assets/images/aboutus atas kiri.svg" alt="" class="aboutus-atas-kiri position-absolute z-0" />
      <div class="container position-relative z-1">
        <div class="row py-5">
          <div class="col-lg-8">
            <div class="judul-menu d-inline-block">Detail Pesanan</div>
            <p class="menu-tagline mt-3">
              <span>Cek pesanan anda</span>
              <br />
              Pastikan menu dan porsi sudah sesuai sebelum lanjut ke pembayaran.
            </p>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-lg-end gap-2">
            <a href="<?= $backUrl ?>" class="order-back text-decoration-none">Kembali</a>
            <a href="<?= url("orders") ?>" class="order-back text-decoration-none">Riwayat Pesanan</a>
          </div>
        </div>

        <div class="row pb-5">
          <div class="col-lg-8">
            <div class="detail-box" id="detailList"></div>
          </div>
          <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="summary-box">
              <p class="m-0">Total Pembayaran</p>
              <h1 id="detailTotal">Rp0</h1>
              <button id="bayarBtn">Lanjut Pembayaran</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/order/detail-order.js") ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
