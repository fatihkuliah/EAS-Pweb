<?php
$activePage = 'categories';
$pageTitle = 'Manage Categories';
require __DIR__ . '/layout_start.php';
?>

<div class="row g-4">
    <!-- Form Column -->
    <div class="col-lg-4">
        <?php if (isset($editingCategory)): ?>
            <!-- Edit Category Card -->
            <div class="card-brutalist" style="background-color: #ffeed3;">
                <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-pencil me-2"></i>Ubah Kategori</h5>
                <form action="<?= url('admin/categories/update') ?>" method="POST">
                    <input type="hidden" name="category_id" value="<?= (int) $editingCategory['category_id'] ?>">
                    <div class="mb-3">
                        <label class="form-label font-weight-700" style="font-weight: 700;">Nama Kategori *</label>
                        <input type="text" name="name" class="form-control form-control-brutalist" value="<?= e($editingCategory['category_name']) ?>" required>
                    </div>
                    <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">
                        Perbarui Kategori
                    </button>
                    <a href="<?= url('admin/categories') ?>" class="btn-brutalist btn-brutalist-secondary w-100 mt-2 py-2 text-center d-block">
                        Batal
                    </a>
                </form>
            </div>
        <?php else: ?>
            <!-- Add Category Card -->
            <div class="card-brutalist" style="background-color: #ffeed3;">
                <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori</h5>
                <form action="<?= url('admin/categories/store') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label font-weight-700" style="font-weight: 700;">Nama Kategori *</label>
                        <input type="text" name="name" class="form-control form-control-brutalist" placeholder="Contoh: Makanan Penutup" required>
                    </div>
                    <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">
                        Simpan Kategori
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- Table Column -->
    <div class="col-lg-8">
        <div class="card-brutalist" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-tags me-2"></i>Daftar Kategori</h5>
            <?php if (empty($categories)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Belum ada kategori yang didefinisikan.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-brutalist m-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Menu</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?= (int) $cat['category_id'] ?></td>
                                    <td style="font-weight: 800;"><?= e($cat['category_name']) ?></td>
                                    <td>
                                        <span class="badge bg-dark rounded-pill py-1 px-3">
                                            <?= (int) ($cat['menu_count'] ?? 0) ?> Item
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= url('admin/categories?edit_id=' . $cat['category_id']) ?>" class="btn-brutalist btn-brutalist-secondary py-1 px-3">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="<?= url('admin/categories/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Seluruh menu dengan kategori ini akan kehilangan relasi kategori.')">
                                                <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
                                                <button type="submit" class="btn-brutalist btn-brutalist-danger py-1 px-3">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
