<?php
$activePage = 'users';
$pageTitle = 'Manage Users';
require __DIR__ . '/layout_start.php';
?>

<!-- Search & Filter Users -->
<div class="card-brutalist mb-4" style="background-color: #ffeed3;">
    <form method="GET" action="<?= url('admin/users') ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-5">
            <label class="form-label text-dark font-weight-700" style="font-weight: 700;">Cari Pengguna</label>
            <input type="text" name="search" class="form-control form-control-brutalist" placeholder="Nama atau email..." value="<?= e($filters['search'] ?? '') ?>">
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label text-dark font-weight-700" style="font-weight: 700;">Role</label>
            <select name="role" class="form-select form-select-brutalist">
                <option value="">Semua Role</option>
                <option value="customer" <?= ($filters['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="admin" <?= ($filters['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
    </form>
</div>

<!-- Main Row -->
<div class="row g-4">
    <!-- User Detail Panel (Optional) -->
    <?php if (isset($userDetail)): ?>
        <div class="col-lg-4">
            <div class="card-brutalist" style="background-color: #ffeed3;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0" style="font-weight: 800;"><i class="bi bi-person-badge me-2"></i>Detail Pengguna</h5>
                    <a href="<?= url('admin/users') ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle text-dark mb-2 d-inline-block" style="font-size: 64px;"></i>
                    <h5 class="m-0" style="font-weight: 800; color: #000 !important;"><?= e($userDetail['name']) ?></h5>
                    <span class="badge-brutalist bg-dark text-white mt-1"><?= e($userDetail['role']) ?></span>
                </div>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex justify-content-between border-bottom border-dark pb-2">
                        <span class="text-muted" style="font-size: 13px; font-weight: 700;">Email</span>
                        <span style="font-weight: 700;"><?= e($userDetail['email']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-dark pb-2">
                        <span class="text-muted" style="font-size: 13px; font-weight: 700;">Telepon</span>
                        <span style="font-weight: 700;"><?= e($userDetail['phone'] ?: '-') ?></span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-dark pb-2">
                        <span class="text-muted" style="font-size: 13px; font-weight: 700;">Alamat</span>
                        <span style="font-weight: 700; text-align: right;"><?= e($userDetail['address'] ?: '-') ?></span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-dark pb-2">
                        <span class="text-muted" style="font-size: 13px; font-weight: 700;">Jumlah Pesanan</span>
                        <span style="font-weight: 800; color: #ff9533;"><?= (int) $userDetail['order_count'] ?> Kali</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted" style="font-size: 13px; font-weight: 700;">Total Belanja</span>
                        <span style="font-weight: 800; color: #25c38c;"><?= rupiah($userDetail['total_spent']) ?></span>
                    </div>
                </div>

                <!-- Change Role Form -->
                <form action="<?= url('admin/users/update') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah role pengguna ini?')">
                    <input type="hidden" name="user_id" value="<?= (int) $userDetail['user_id'] ?>">
                    <div class="mb-3">
                        <label class="form-label font-weight-700" style="font-weight: 700;">Ubah Role Pengguna</label>
                        <select name="role" class="form-select form-select-brutalist" <?= $userDetail['user_id'] == $admin['user_id'] ? 'disabled' : '' ?>>
                            <option value="customer" <?= $userDetail['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                            <option value="admin" <?= $userDetail['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <?php if ($userDetail['user_id'] == $admin['user_id']): ?>
                            <small class="text-danger d-block mt-1">Anda tidak dapat mengubah role Anda sendiri.</small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2" <?= $userDetail['user_id'] == $admin['user_id'] ? 'disabled' : '' ?>>
                        Simpan Role Baru
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Table Column -->
    <div class="col-lg-<?= isset($userDetail) ? '8' : '12' ?>">
        <div class="card-brutalist" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-people me-2"></i>Daftar Pengguna</h5>
            <?php if (empty($users)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Tidak ada pengguna ditemukan.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-brutalist m-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Jumlah Order</th>
                                <th>Total Belanja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-circle text-dark" style="font-size: 20px;"></i>
                                            <strong style="font-weight: 800; color: #000 !important;"><?= e($user['name']) ?></strong>
                                        </div>
                                    </td>
                                    <td><?= e($user['email']) ?></td>
                                    <td>
                                        <span class="badge-brutalist <?= $user['role'] === 'admin' ? 'bg-warning text-dark' : 'bg-light text-dark' ?>">
                                            <?= e($user['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= (int) ($user['order_count'] ?? 0) ?> Orders</td>
                                    <td><?= rupiah($user['total_spent'] ?? 0) ?></td>
                                    <td>
                                        <a href="<?= url('admin/users?detail_id=' . $user['user_id'] . '&' . http_build_query($filters)) ?>" class="btn-brutalist btn-brutalist-secondary py-1 px-3">
                                            Detail
                                        </a>
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
