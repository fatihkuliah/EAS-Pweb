<?php
$activePage = 'menus';
$pageTitle = 'Tambah Menu Baru';
require __DIR__ . '/layout_start.php';
?>

<div class="mb-4">
    <a href="<?= url('admin/menus') ?>" class="btn-brutalist btn-brutalist-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card-brutalist" style="background-color: #ffeed3; max-width: 800px;">
    <h5 class="mb-4" style="font-weight: 800;"><i class="bi bi-plus-circle me-2"></i>Formulir Tambah Menu</h5>
    
    <form action="<?= url('admin/menus/store') ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Nama Menu *</label>
                <input type="text" name="name" class="form-control form-control-brutalist" placeholder="Nama menu..." required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Kategori *</label>
                <select name="category_id" class="form-select form-select-brutalist" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>"><?= e($cat['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label font-weight-700" style="font-weight: 700;">Deskripsi Menu</label>
                <textarea name="description" class="form-control form-control-brutalist" rows="3" placeholder="Tuliskan deskripsi menu..."></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Harga (Rupiah) *</label>
                <input type="number" name="price" class="form-control form-control-brutalist" placeholder="Contoh: 28000" min="0" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Stok *</label>
                <input type="number" name="stock" class="form-control form-control-brutalist" value="100" min="0" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Ukuran Porsi *</label>
                <input type="text" name="portion" class="form-control form-control-brutalist" value="1 Orang" placeholder="Contoh: 1 Orang" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Waktu Penyajian *</label>
                <input type="text" name="serving_time" class="form-control form-control-brutalist" value="25 Menit" placeholder="Contoh: 25 Menit" required>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Gambar Menu *</label>
                <input type="file" name="image" class="form-control form-control-brutalist" accept=".jpg,.jpeg,.png,image/png,image/jpeg" required>
                <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG.</small>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-700" style="font-weight: 700;">Ketersediaan Menu *</label>
                <select name="is_available" class="form-select form-select-brutalist" required>
                    <option value="1">Tersedia</option>
                    <option value="0">Habis / Kosong</option>
                </select>
            </div>
        </div>

        <div class="mt-4 pt-2">
            <button type="submit" class="btn-brutalist btn-brutalist-primary py-2 px-4">
                <i class="bi bi-save"></i> Simpan Menu
            </button>
            <a href="<?= url('admin/menus') ?>" class="btn-brutalist btn-brutalist-secondary py-2 px-4 ms-2">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
