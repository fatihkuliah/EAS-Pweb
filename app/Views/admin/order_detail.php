<?php
$activePage = 'orders';
$pageTitle = 'Order Detail: ' . $order['order_number'];
require __DIR__ . '/layout_start.php';
?>

<div class="mb-4">
    <a href="<?= url('admin/orders') ?>" class="btn-brutalist btn-brutalist-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="row g-4">
    <!-- Order Summary & Items -->
    <div class="col-lg-8">
        <div class="card-brutalist mb-4" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-cart me-2"></i>Item Pesanan</h5>
            <div class="table-responsive">
                <table class="table table-brutalist m-0">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= asset($item['gambar']) ?>" alt="<?= e($item['nama']) ?>" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #000; border-radius: 10px;">
                                        <div>
                                            <strong class="d-block" style="font-weight: 700; color: #000 !important;"><?= e($item['nama']) ?></strong>
                                            <small class="d-block text-muted" style="font-size: 11px; color: #555 !important;"><?= e($item['kategori']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= rupiah($item['harga']) ?></td>
                                <td><?= (int) $item['qty'] ?></td>
                                <td><?= rupiah($item['harga'] * $item['qty']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr style="border-top: 3px solid #000;">
                            <td colspan="3" class="text-end" style="font-weight: 850;">Total Harga:</td>
                            <td style="font-weight: 850; background-color: #ffc38b !important;"><?= rupiah($order['total']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-brutalist" style="background-color: #ffeed3; color: #000;">
            <h5 class="mb-3" style="font-weight: 800; color: #000;"><i class="bi bi-geo-alt me-2"></i>Informasi Penerima & Pengiriman</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted d-block" style="font-size: 12px; font-weight: 700; color: #555 !important;">Nama Penerima</label>
                    <strong class="text-dark d-block" style="font-weight: 700; font-size: 16px; color: #000 !important;"><?= e($order['penerima']) ?></strong>
                </div>
                <div class="col-md-6">
                    <label class="text-muted d-block" style="font-size: 12px; font-weight: 700; color: #555 !important;">No. Telepon</label>
                    <strong class="text-dark d-block" style="font-weight: 700; font-size: 16px; color: #000 !important;"><?= e($order['telepon']) ?></strong>
                </div>
                <div class="col-12">
                    <label class="text-muted d-block" style="font-size: 12px; font-weight: 700; color: #555 !important;">Alamat Pengiriman</label>
                    <strong class="text-dark d-block" style="font-weight: 700; color: #000 !important;"><?= e($order['alamat']) ?></strong>
                </div>
                <div class="col-12">
                    <label class="text-muted d-block" style="font-size: 12px; font-weight: 700; color: #555 !important;">Catatan Pesanan</label>
                    <strong class="d-block" style="font-weight: 700; font-style: italic; color: #333 !important;"><?= $order['catatan'] ? e($order['catatan']) : '-' ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions & Payment -->
    <div class="col-lg-4">
        <!-- Update Status Card -->
        <div class="card-brutalist mb-4" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-pencil-square me-2"></i>Kelola Status</h5>
            <form action="<?= url('admin/orders/update') ?>" method="POST">
                <input type="hidden" name="order_number" value="<?= e($order['order_number']) ?>">
                
                <div class="mb-3">
                    <label class="form-label font-weight-700" style="font-weight: 700;">Status Pesanan</label>
                    <select name="status" class="form-select form-select-brutalist">
                        <?php foreach (['Menunggu Pembayaran', 'paid', 'Diproses', 'Dikirim', 'Selesai', 'Batal'] as $status): ?>
                            <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="w-100 btn-brutalist btn-brutalist-primary py-2 text-center d-inline-block">
                    <i class="bi bi-save"></i> Perbarui Status
                </button>
            </form>
        </div>

        <!-- Payment Info Card -->
        <div class="card-brutalist" style="background-color: #ffeed3; color: #000;">
            <h5 class="mb-3" style="font-weight: 800; color: #000;"><i class="bi bi-credit-card me-2"></i>Informasi Pembayaran</h5>
            <div class="d-flex flex-column gap-3">
                <div>
                    <small class="text-muted d-block" style="font-size: 12px; font-weight: 700; color: #555 !important;">Metode Pembayaran</small>
                    <strong class="d-block" style="font-weight: 700; color: #000 !important;"><?= e($order['metode']) ?></strong>
                </div>
                <div>
                    <small class="text-muted d-block" style="font-size: 12px; font-weight: 700; color: #555 !important;">Status Pembayaran</small>
                    <span class="badge-brutalist <?= $order['payment_status'] === 'verified' || $order['payment_status'] === 'Lunas' ? 'bg-success text-white' : ($order['payment_status'] === 'rejected' || $order['payment_status'] === 'Gagal' ? 'bg-danger text-white' : 'bg-warning text-dark') ?>">
                        <?= e($order['payment_status'] ?: 'Menunggu Pembayaran') ?>
                    </span>
                </div>
                <?php if ($order['bukti']): ?>
                    <div>
                        <span class="text-muted d-block mb-2" style="font-size: 12px; font-weight: 700;">Bukti Transaksi</span>
                        <a href="<?= asset('storage/' . $order['bukti']) ?>" target="_blank" class="d-block border border-2 border-dark rounded-3 overflow-hidden" style="max-height: 250px;">
                            <img src="<?= asset('storage/' . $order['bukti']) ?>" alt="Bukti Pembayaran" style="width: 100%; object-fit: contain;">
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning border border-2 border-dark rounded-3 text-center py-2 m-0" style="font-weight: 700;">
                        Belum Mengunggah Bukti
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
