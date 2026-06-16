<?php
$activePage = 'menus';
$pageTitle = 'Edit Menu: ' . $menu['menu_name'];
require __DIR__ . '/layout_start.php';
?>

<div class="mb-4">
    <a href="<?= url('admin/menus') ?>" class="btn-brutalist btn-brutalist-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card-brutalist" style="background-color: #ffeed3; max-width: 800px;">
    <h5 class="mb-4" style="font-weight: 800;"><i class="bi bi-pencil me-2"></i>Formulir Edit Menu</h5>
    
    <form action="<?= url('admin/menus/update') ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="menu_id" value="<?= (int) $menu['menu_id'] ?>">
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Nama Menu *</label>
                <input type="text" name="name" class="form-control form-control-brutalist" value="<?= e($menu['menu_name']) ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Kategori *</label>
                <select name="category_id" class="form-select form-select-brutalist" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $menu['category_id'] == $cat['category_id'] ? 'selected' : '' ?>><?= e($cat['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label font-weight-700" style="font-weight: 700;">Deskripsi Menu</label>
                <textarea name="description" class="form-control form-control-brutalist" rows="3"><?= e($menu['description']) ?></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Harga (Rupiah) *</label>
                <input type="number" name="price" class="form-control form-control-brutalist" value="<?= (int) $menu['price'] ?>" min="0" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Stok *</label>
                <input type="number" name="stock" class="form-control form-control-brutalist" value="<?= (int) $menu['stock'] ?>" min="0" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Ukuran Porsi *</label>
                <input type="text" name="portion" class="form-control form-control-brutalist" value="<?= e($menu['portion']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Waktu Penyajian *</label>
                <input type="text" name="serving_time" class="form-control form-control-brutalist" value="<?= e($menu['serving_time']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Gambar Menu (Opsional)</label>
                <input type="file" name="image" class="form-control form-control-brutalist" accept=".jpg,.jpeg,.png,image/png,image/jpeg">
                <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                
                <div class="mt-3">
                    <span class="d-block text-muted mb-1" style="font-size: 12px; font-weight: 700;">Gambar Saat Ini:</span>
                    <img src="<?= asset($menu['image']) ?>" alt="<?= e($menu['menu_name']) ?>" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #000; border-radius: 15px;">
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Ketersediaan Menu *</label>
                <select name="is_available" class="form-select form-select-brutalist" required>
                    <option value="1" <?= $menu['is_available'] == 1 ? 'selected' : '' ?>>Tersedia</option>
                    <option value="0" <?= $menu['is_available'] == 0 ? 'selected' : '' ?>>Habis / Kosong</option>
                </select>
            </div>
        </div>

        <div class="mt-4 pt-2">
            <button type="submit" class="btn-brutalist btn-brutalist-primary py-2 px-4">
                <i class="bi bi-save"></i> Perbarui Menu
            </button>
            <a href="<?= url('admin/menus') ?>" class="btn-brutalist btn-brutalist-secondary py-2 px-4 ms-2">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
