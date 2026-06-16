<?php
$activePage = 'menus';
$pageTitle = 'Manage Menus';
require __DIR__ . '/layout_start.php';
?>

<!-- Action Bar & Filter -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card-brutalist" style="background-color: #ffeed3;">
            <form method="GET" action="<?= url('admin/menus') ?>" class="row g-3 align-items-end">
                <div class="col-12 col-sm-5">
                    <label class="form-label font-weight-700" style="font-weight: 700;">Cari Menu</label>
                    <input type="text" name="search" class="form-control form-control-brutalist" placeholder="Nama menu..." value="<?= e($filters['search'] ?? '') ?>">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label font-weight-700" style="font-weight: 700;">Kategori</label>
                    <select name="category" class="form-select form-select-brutalist">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" <?= ($filters['category'] ?? '') == $cat['category_id'] ? 'selected' : '' ?>><?= e($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-sm-3">
                    <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">Cari</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-stretch">
        <a href="<?= url('admin/menus/create') ?>" class="btn-brutalist btn-brutalist-primary w-100 fs-5 d-flex align-items-center justify-content-center py-3" style="box-shadow: 4px 4px 0px #000; border-radius: 20px;">
            <i class="bi bi-plus-circle-fill me-2 fs-4"></i> Tambah Menu Baru
        </a>
    </div>
</div>

<!-- Menus Table -->
<div class="card-brutalist" style="background-color: #ffeed3;">
    <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-egg-fried me-2"></i>Katalog Menu</h5>
    <?php if (empty($menus)): ?>
        <p class="m-0 text-muted" style="font-weight: 600;">Tidak ada menu ditemukan.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-brutalist m-0">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menus as $m): ?>
                        <tr>
                            <td>
                                <img src="<?= asset(storage_public_path($m['gambar'])) ?>" alt="<?= e($m['nama']) ?>" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #000; border-radius: 10px;">
                            </td>
                            <td>
                                <strong class="d-block" style="font-weight: 800; font-size: 15px; color: #000 !important;"><?= e($m['nama']) ?></strong>
                                <small class="text-muted d-block" style="font-size: 11px;">Porsi: <?= e($m['porsi']) ?> | Saji: <?= e($m['waktu']) ?></small>
                            </td>
                            <td><?= e($m['kategori']) ?></td>
                            <td style="font-weight: 700;"><?= rupiah($m['harga']) ?></td>
                            <td>
                                <span class="badge-brutalist <?= $m['stock'] < 10 ? 'bg-danger text-white' : 'bg-light text-dark' ?>">
                                    <?= (int) $m['stock'] ?> Porsi
                                </span>
                            </td>
                            <td>
                                <span class="badge-brutalist <?= $m['is_available'] ? 'bg-success text-white' : 'bg-secondary text-white' ?>">
                                    <?= $m['is_available'] ? 'Tersedia' : 'Kosong' ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?= url('admin/menus/edit?id=' . $m['id']) ?>" class="btn-brutalist btn-brutalist-secondary py-1 px-3">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="<?= url('admin/menus/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                        <input type="hidden" name="menu_id" value="<?= $m['id'] ?>">
                                        <button type="submit" class="btn-brutalist btn-brutalist-danger py-1 px-3">
                                            <i class="bi bi-trash"></i> Hapus
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

<?php require __DIR__ . '/layout_end.php'; ?>
