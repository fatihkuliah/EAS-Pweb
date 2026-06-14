<section class="order-page position-relative overflow-hidden">
      <img src="assets/images/aboutus atas kanan.svg" alt="" class="aboutus-atas-kanan position-absolute z-0" />
      <div class="container position-relative z-1">
        <div class="row py-5 justify-content-center">
          <div class="col-lg-7 text-center">
            <div class="judul-menu d-inline-block" id="authTitle">Login</div>
            <p class="menu-tagline mt-3"><span>Masuk ke MieME</span><br />Akun dipakai untuk profil, favorit, checkout, dan riwayat pesanan.</p>
          </div>
        </div>

        <div class="row pb-5 justify-content-center">
          <div class="col-lg-5">
            <div class="form-box">
              <div class="auth-tabs">
                <button class="active" id="loginTab">Login</button>
                <button id="registerTab">Register</button>
              </div>
              <form id="authForm" class="mt-4">
                <div class="form-field register-only d-none">
                  <label>Nama Lengkap</label>
                  <input type="text" id="nameInput" placeholder="Budi Santoso" />
                </div>
                <div class="form-field">
                  <label>Email</label>
                  <input type="email" id="emailInput" required placeholder="email@contoh.com" />
                </div>
                <div class="form-field">
                  <label>Password</label>
                  <input type="password" id="passwordInput" required placeholder="Minimal 6 karakter" />
                </div>
                <div class="form-field register-only d-none">
                  <label>Konfirmasi Password</label>
                  <input type="password" id="confirmInput" placeholder="Ulangi password" />
                </div>
                <button class="checkout-btn w-100 mt-3" type="submit" id="authSubmit">Login</button>
              </form>
              <p class="form-note mt-4 mb-0" id="authNote">Demo login akan menyimpan akun di browser.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/auth/auth.js") ?>"></script>
