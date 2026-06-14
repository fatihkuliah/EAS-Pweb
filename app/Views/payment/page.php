<section class="order-page position-relative overflow-hidden">
      <img src="assets/images/htestis1.svg" class="htestis1-payment position-absolute z-0" alt="" />
      <img src="assets/images/htestis2.svg" class="htestis2 position-absolute z-0" alt="" />
      <div class="container position-relative z-1">
        <div class="row py-5">
          <div class="col-lg-8">
            <div class="judul-menu d-inline-block">Payment</div>
            <p class="menu-tagline mt-3">
              <span>Pilih metode pembayaran</span>
              <br />
              Bank, e-wallet, virtual account, atau QRIS untuk menyelesaikan pesanan.
            </p>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-lg-end">
            <div class="payment-kanan">
              <a href="<?= url("detail-order") ?>" class="order-back text-decoration-none d-inline-block mb-3">Kembali</a>
              <div class="payment-total">
                <p class="m-0">Total</p>
                <h1 id="paymentTotal">Rp0</h1>
              </div>
            </div>
          </div>
        </div>

        <div class="row pb-5">
          <div class="col-lg-12">
            <div class="payment-section">
              <h2>Bank Transfer</h2>
              <div class="payment-grid" id="bankList"></div>
            </div>

            <div class="payment-section mt-4">
              <h2>E-Wallet</h2>
              <div class="payment-grid" id="ewalletList"></div>
            </div>

            <div class="payment-section mt-4">
              <h2>Virtual Account</h2>
              <div class="payment-grid" id="vaList"></div>
            </div>

            <div class="payment-section mt-4">
              <h2>QR Payment</h2>
              <div class="payment-grid" id="qrisList"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/payment/payment.js") ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
