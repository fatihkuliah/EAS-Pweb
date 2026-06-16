<section class="order-page position-relative overflow-hidden">
      <div class="container position-relative z-1">
        <div class="row py-5">
          <div class="col-lg-8">
            <div class="judul-menu d-inline-block">Profil</div>
            <p class="menu-tagline mt-3"><span>Kelola akun anda</span><br />Data ini otomatis dipakai saat checkout berikutnya.</p>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-lg-end gap-2">
            <a href="<?= url("orders") ?>" class="order-back text-decoration-none">Riwayat</a>
            <a href="<?= url("") ?>" class="order-back text-decoration-none">Home</a>
          </div>
        </div>
        <div class="row pb-5 justify-content-center">
          <div class="col-lg-6">
            <form class="form-box" id="profileForm">

              <div class="form-field"><label>Nama Lengkap</label><input id="profileName" required /></div>
              <div class="form-field"><label>Email</label><input id="profileEmail" type="email" required /></div>
              <div class="form-field"><label>Nomor Telepon</label><input id="profilePhone" required /></div>
              <div class="form-field"><label>Alamat</label><textarea id="profileAddress" rows="4" required></textarea></div>
              <div class="d-flex gap-2 mt-3">
                <button class="checkout-btn flex-grow-1" type="submit">Simpan Profil</button>
                <button class="order-back" type="button" id="logoutBtn">Logout</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <script src="<?= asset("assets/js/core/mieme-app.js") ?>"></script>
    <script src="<?= asset("assets/js/features/profile/profile.js") ?>"></script>
