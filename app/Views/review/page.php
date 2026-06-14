<section class="order-page position-relative overflow-hidden">
      <div class="container position-relative z-1">
        <div class="row py-5 justify-content-center">
          <div class="col-lg-8 text-center">
            <div class="judul-menu d-inline-block">Review</div>
            <p class="menu-tagline mt-3"><span>Bagikan pengalaman anda</span><br />Review bisa diberikan untuk pesanan yang sudah selesai.</p>
          </div>
        </div>
        <div class="row pb-5 justify-content-center">
          <div class="col-lg-5">
            <form class="form-box text-center" id="reviewForm">
              <p class="form-note" id="reviewOrder">Nomor Pesanan</p>
              <div class="rating-row" id="ratingRow"></div>
              <div class="form-field text-start"><label>Komentar</label><textarea id="reviewComment" rows="4" required></textarea></div>
              <div class="form-field text-start"><label>Foto Makanan (Opsional)</label><input type="file" id="reviewPhoto" accept="image/*" /></div>
              <button class="checkout-btn w-100 mt-3" type="submit">Kirim Review</button>
            </form>
            <div class="success-box text-center d-none" id="reviewSuccess">
              <h1>Terima Kasih!</h1>
              <p>Review anda berhasil disimpan.</p>
              <a href="<?= url("orders") ?>" class="order-back text-decoration-none d-inline-block mt-3">Kembali Riwayat</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/review/review.js") ?>"></script>
