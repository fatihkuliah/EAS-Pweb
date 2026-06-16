<div class="<?= e($col ?? 'col-lg-6') ?>">
  <div class="menu-menu order-card row h-100 m-0">
    <div class="col-5 text-start text p-0 d-flex align-items-center justify-content-center order-card-img">
      <?= image_tag($menu['gambar'], $menu['nama'], 234, 325) ?>
    </div>
    <div class="col-7">
      <div class="order-card-body d-flex h-100 flex-column justify-content-between">
        <div>
          <a href="<?= menu_url($menu) ?>" class="text-decoration-none">
            <h3><?= e($menu['nama']) ?></h3>
          </a>
          <p class="mt-3"><?= e($menu['deskripsi']) ?></p>
        </div>
        <div>
          <div class="d-flex justify-content-start align-items-center">
            <div class="porsi text-center">
              <p class="m-0">Porsi</p>
              <i class="bi bi-person-fill order-person"></i>
            </div>
            <svg class="mx-4" xmlns="http://www.w3.org/2000/svg" width="3" height="31" viewBox="0 0 3 31" fill="none">
              <path d="M1.13452 29.0179L1.13452 1" stroke="#7A4818" stroke-width="2" stroke-linecap="round" />
            </svg>
            <div class="waktu-saji">
              <p class="m-0">Waktu Penyajian</p>
              <h2><?= e($menu['waktu']) ?></h2>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="price text-start">
              <p class="m-0">Price</p>
              <h2><?= rupiah($menu['harga']) ?></h2>
            </div>
            <div class="d-flex gap-2">
              <form method="post" action="<?= url('favorite/toggle') ?>">
                <input type="hidden" name="id" value="<?= e((string) $menu['id']) ?>" />
                <input type="hidden" name="back" value="<?= e($back ?? 'order') ?>" />
                <button class="fav-order <?= in_array((int) $menu['id'], $favorites ?? [], true) ? 'active' : '' ?>" type="submit">♥</button>
              </form>
              <form method="post" action="<?= url('cart/add') ?>">
                <input type="hidden" name="id" value="<?= e((string) $menu['id']) ?>" />
                <input type="hidden" name="back" value="<?= e($back ?? 'order') ?>" />
                <button class="tambah-order" type="submit">Tambah</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
