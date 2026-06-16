<section class="order-page position-relative overflow-hidden">
      <div class="container position-relative z-1">
        <div class="row py-5 justify-content-center">
          <div class="col-lg-8 text-center">
            <div class="judul-menu d-inline-block">Upload Bukti</div>
            <p class="menu-tagline mt-3"><span>Kirim bukti pembayaran</span><br />Format JPG, JPEG, atau PNG. Setelah terkirim, pesanan masuk ke riwayat.</p>
          </div>
        </div>
        <div class="row pb-5 justify-content-center">
          <div class="col-lg-5">
            <a href="<?= url("payment") ?>" class="order-back text-decoration-none d-inline-block mb-3">Kembali</a>
            <form class="form-box text-center" id="uploadForm">
              <p class="m-0 form-note" id="paymentMethod">Metode Pembayaran</p>
              <div class="payment-target mt-3" id="paymentTarget">
                <p class="m-0" id="paymentNumberLabel">Nomor Tujuan</p>
                <h2 id="paymentNumber">-</h2>
                <span id="paymentAccountName">MieME Indonesia</span>
              </div>
              <h1 id="uploadTotal">Rp0</h1>
              <label class="upload-box mt-4" for="receiptInput">
                <img id="receiptPreview" alt="" class="d-none" />
                <span id="uploadText">Pilih bukti pembayaran</span>
                <input type="file" id="receiptInput" accept=".jpg,.jpeg,.png,image/png,image/jpeg" required hidden />
              </label>
              <button class="checkout-btn w-100 mt-4" type="submit">Kirim Pembayaran</button>
            </form>
            <div class="success-box text-center d-none" id="successBox">
              <h1>Pesanan Berhasil!</h1>
              <p>Bukti pembayaran diterima. Pesanan anda akan segera diproses.</p>
              <a href="<?= url("orders") ?>" class="order-back text-decoration-none d-inline-block mt-3">Lihat Riwayat</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script>
      window.MieMECurrentPayment = <?= json_encode($payment) ?>;
    </script>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/payment/upload-payment.js") ?>"></script>
