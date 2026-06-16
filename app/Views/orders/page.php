<section class="order-page position-relative overflow-hidden">
      <div class="container position-relative z-1">
        <div class="row py-5">
          <div class="col-lg-8">
            <div class="judul-menu d-inline-block">Riwayat Pesanan</div>
            <p class="menu-tagline mt-3"><span>Semua transaksi anda</span><br />Cek status, lihat detail, unduh invoice, ekspor CSV, dan beri review.</p>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-lg-end gap-2">
            <a href="<?= url("") ?>" class="order-back text-decoration-none">Kembali</a>
            <button class="order-back" id="exportCsv">Export CSV</button>
            <a href="<?= url("order") ?>" class="order-back text-decoration-none">Order Lagi</a>
          </div>
        </div>
        <div class="row pb-5">
          <div class="col-12" id="ordersList"></div>
        </div>
      </div>
    </section>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/order/orders.js") ?>"></script>
