<?php
$activePage = 'payments';
$pageTitle = 'Verify Payments';
require __DIR__ . '/layout_start.php';
?>

<!-- Payments Filter -->
<div class="card-brutalist mb-4" style="background-color: #ffeed3;">
    <form method="GET" action="<?= url('admin/payments') ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-5">
            <label class="form-label text-dark font-weight-700" style="font-weight: 700;">Status Pembayaran</label>
            <select name="status" class="form-select form-select-brutalist">
                <option value="">Semua Status</option>
                <?php foreach (['Menunggu Konfirmasi', 'verified', 'rejected', 'Lunas', 'Gagal'] as $status): ?>
                    <option value="<?= $status ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-4">
            <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">
                <i class="bi bi-filter"></i> Saring Pembayaran
            </button>
        </div>
        <div class="col-12 col-md-3">
            <a href="<?= url('admin/payments') ?>" class="btn-brutalist btn-brutalist-secondary w-100 py-2 text-center d-block">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Payments Table -->
<div class="card-brutalist" style="background-color: #ffeed3;">
    <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-wallet2 me-2"></i>Daftar Pembayaran</h5>
    <?php if (empty($payments)): ?>
        <p class="m-0 text-muted" style="font-weight: 600;">Tidak ada data pembayaran.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-brutalist m-0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Waktu Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $p): 
                        $statusClass = 'bg-secondary';
                        if ($p['payment_status'] === 'verified' || $p['payment_status'] === 'Lunas') $statusClass = 'bg-success text-white';
                        elseif ($p['payment_status'] === 'rejected' || $p['payment_status'] === 'Gagal') $statusClass = 'bg-danger text-white';
                        elseif ($p['payment_status'] === 'Menunggu Konfirmasi') $statusClass = 'bg-warning text-dark';
                    ?>
                        <tr>
                            <td><?= e($p['order_number']) ?></td>
                            <td><?= e($p['customer_name'] ?? 'Guest') ?></td>
                            <td><?= e($p['payment_method']) ?></td>
                            <td><?= rupiah($p['amount']) ?></td>
                            <td>
                                <?php if ($p['payment_proof']): ?>
                                    <a href="<?= asset('storage/' . $p['payment_proof']) ?>" target="_blank" class="btn-brutalist btn-brutalist-secondary py-1 px-2" style="font-size: 12px;">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-brutalist <?= $statusClass ?>"><?= e($p['payment_status']) ?></span>
                            </td>
                            <td style="font-size: 13px;"><?= $p['paid_at'] ? e(date('d M Y H:i', strtotime($p['paid_at']))) : '-' ?></td>
                            <td>
                                <?php if ($p['payment_status'] === 'Menunggu Konfirmasi'): ?>
                                    <div class="d-flex gap-1">
                                        <form action="<?= url('admin/payments/action') ?>" method="POST" class="d-inline" onsubmit="return confirm('Setujui pembayaran ini?')">
                                            <input type="hidden" name="payment_id" value="<?= $p['payment_id'] ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn-brutalist btn-brutalist-primary py-1 px-2" style="font-size: 12px; background-color: #25c38c;">Approve</button>
                                        </form>
                                        <form action="<?= url('admin/payments/action') ?>" method="POST" class="d-inline" onsubmit="return confirm('Tolak pembayaran ini?')">
                                            <input type="hidden" name="payment_id" value="<?= $p['payment_id'] ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn-brutalist btn-brutalist-danger py-1 px-2" style="font-size: 12px;">Reject</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 12px; font-weight: 700;">Telah Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
