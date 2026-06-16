<?php
$activePage = 'orders';
$pageTitle = 'Manage Orders';
require __DIR__ . '/layout_start.php';
?>

<!-- Orders Filter & Search -->
<div class="card-brutalist mb-4" style="background-color: #ffeed3;">
    <form method="GET" action="<?= url('admin/orders') ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label text-dark font-weight-700" style="font-weight: 700;">Cari Pesanan</label>
            <input type="text" name="search" class="form-control form-control-brutalist" placeholder="No. Pesanan atau Nama..." value="<?= e($filters['search'] ?? '') ?>">
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label text-dark font-weight-700" style="font-weight: 700;">Status Pesanan</label>
            <select name="status" class="form-select form-select-brutalist">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu Pembayaran', 'paid', 'Diproses', 'Dikirim', 'Selesai', 'Batal'] as $status): ?>
                    <option value="<?= $status ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">
                <i class="bi bi-search"></i> Filter & Cari
            </button>
        </div>
        <div class="col-12 col-md-2">
            <a href="<?= url('admin/orders') ?>" class="btn-brutalist btn-brutalist-secondary w-100 py-2 text-center d-block">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Main Row -->
<div class="row g-4">
    <!-- Orders List Table -->
    <div class="col-12">
        <div class="card-brutalist" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-list-task me-2"></i>Daftar Pesanan</h5>
            <?php if (empty($orders)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Tidak ada pesanan ditemukan.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-brutalist m-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Penerima</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): 
                                $statusClass = 'bg-secondary';
                                if ($order['status'] === 'paid') $statusClass = 'bg-primary text-white';
                                elseif ($order['status'] === 'Diproses') $statusClass = 'bg-info text-dark';
                                elseif ($order['status'] === 'Dikirim') $statusClass = 'bg-warning text-dark';
                                elseif ($order['status'] === 'Selesai') $statusClass = 'bg-success text-white';
                                elseif ($order['status'] === 'Batal') $statusClass = 'bg-danger text-white';
                            ?>
                                <tr>
                                    <td><?= e($order['order_number']) ?></td>
                                    <td><?= e($order['user_name'] ?? 'Guest') ?></td>
                                    <td><?= e($order['penerima']) ?></td>
                                    <td><?= rupiah($order['total']) ?></td>
                                    <td>
                                        <span class="badge-brutalist <?= $statusClass ?>"><?= e($order['status']) ?></span>
                                    </td>
                                    <td style="font-size: 13px;"><?= e(date('d M Y H:i', strtotime($order['created_at']))) ?></td>
                                    <td>
                                        <a href="<?= url('admin/orders/detail?id=' . $order['order_number']) ?>" class="btn-brutalist btn-brutalist-secondary py-1 px-3">Detail & Ubah</a>
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
