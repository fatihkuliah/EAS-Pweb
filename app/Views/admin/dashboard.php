<?php
$activePage = 'dashboard';
$pageTitle = 'Dashboard Overview';
require __DIR__ . '/layout_start.php';
?>

<!-- KPI Cards -->
<div class="row g-4 mb-5">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card-brutalist text-center" style="background-color: #ffeed3;">
            <i class="bi bi-cart3 fs-2 mb-2 d-block"></i>
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800;">Orders Today</h6>
            <h2 class="mt-2 mb-0" style="font-weight: 800;"><?= (int) ($kpi['total_orders_today'] ?? 0) ?></h2>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card-brutalist text-center" style="background-color: #ffc38b;">
            <i class="bi bi-cash-stack fs-2 mb-2 d-block"></i>
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800;">Revenue Today</h6>
            <h3 class="mt-2 mb-0" style="font-weight: 800; font-size: 16px;"><?= rupiah($kpi['revenue_today'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card-brutalist text-center" style="background-color: #ffeed3;">
            <i class="bi bi-hourglass-split fs-2 mb-2 d-block"></i>
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800;">Wait Payment</h6>
            <h2 class="mt-2 mb-0" style="font-weight: 800;"><?= (int) ($kpi['waiting_payment'] ?? 0) ?></h2>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card-brutalist text-center" style="background-color: #ffeed3;">
            <i class="bi bi-clock-history fs-2 mb-2 d-block"></i>
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800;">Pending Verify</h6>
            <h2 class="mt-2 mb-0" style="font-weight: 800; color: #ff9533;"><?= (int) ($kpi['pending_payments'] ?? 0) ?></h2>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card-brutalist text-center" style="background-color: #ffeed3;">
            <i class="bi bi-arrow-repeat fs-2 mb-2 d-block"></i>
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800;">Processing</h6>
            <h2 class="mt-2 mb-0" style="font-weight: 800; color: #ff9533 !important;"><?= (int) ($kpi['processing_orders'] ?? 0) ?></h2>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card-brutalist text-center" style="background-color: <?= ($kpi['low_stock_menus'] ?? 0) > 0 ? '#ff6b6b' : '#ffeed3' ?>; color: <?= ($kpi['low_stock_menus'] ?? 0) > 0 ? '#fff' : '#000' ?>;">
            <i class="bi bi-exclamation-triangle fs-2 mb-2 d-block"></i>
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800;">Low Stock</h6>
            <h2 class="mt-2 mb-0" style="font-weight: 800;"><?= (int) ($kpi['low_stock_menus'] ?? 0) ?></h2>
        </div>
    </div>
</div>

<!-- Main Row -->
<div class="row g-4">
    <!-- Left Column (Actions & Orders) -->
    <div class="col-lg-8">
        <!-- Need Action / Pending Payments -->
        <div class="card-brutalist mb-4" style="background-color: #ffeed3;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0" style="font-weight: 800;"><i class="bi bi-shield-exclamation me-2"></i>Butuh Verifikasi Pembayaran</h5>
                <span class="badge-brutalist bg-warning"><?= count($pendingPayments) ?> Menunggu</span>
            </div>
            <?php if (empty($pendingPayments)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Tidak ada bukti pembayaran baru yang perlu diverifikasi.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-brutalist m-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Metode</th>
                                <th>Total</th>
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingPayments as $p): ?>
                                <tr>
                                    <td><?= e($p['order_number']) ?></td>
                                    <td><?= e($p['customer_name'] ?? 'Guest') ?></td>
                                    <td><?= e($p['payment_method']) ?></td>
                                    <td><?= rupiah($p['amount']) ?></td>
                                    <td>
                                        <?php if ($p['payment_proof']): ?>
                                            <a href="<?= asset('storage/' . $p['payment_proof']) ?>" target="_blank" class="btn-brutalist btn-brutalist-secondary py-1 px-2" style="font-size: 12px;">
                                                <i class="bi bi-image"></i> Lihat Bukti
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
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
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Orders -->
        <div class="card-brutalist" style="background-color: #ffeed3;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0" style="font-weight: 800;"><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</h5>
                <a href="<?= url('admin/orders') ?>" class="btn-brutalist btn-brutalist-secondary py-1 px-2" style="font-size: 12px;">Semua Pesanan</a>
            </div>
            <?php if (empty($recentOrders)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Belum ada pesanan masuk.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-brutalist m-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Penerima</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): 
                                $statusClass = 'bg-secondary';
                                if ($order['status'] === 'paid') $statusClass = 'bg-primary text-white';
                                elseif ($order['status'] === 'Diproses') $statusClass = 'bg-info text-dark';
                                elseif ($order['status'] === 'Dikirim') $statusClass = 'bg-warning text-dark';
                                elseif ($order['status'] === 'Selesai') $statusClass = 'bg-success text-white';
                                elseif ($order['status'] === 'Batal') $statusClass = 'bg-danger text-white';
                            ?>
                                <tr>
                                    <td><?= e($order['order_number']) ?></td>
                                    <td><?= e($order['penerima']) ?></td>
                                    <td style="font-size: 12px;"><?= e(date('d M Y H:i', strtotime($order['created_at']))) ?></td>
                                    <td><?= rupiah($order['total']) ?></td>
                                    <td>
                                        <span class="badge-brutalist <?= $statusClass ?>"><?= e($order['status']) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= url('admin/orders/detail?id=' . $order['order_number']) ?>" class="btn-brutalist btn-brutalist-secondary py-1 px-2" style="font-size: 12px;">Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column (Reviews & Top Selling) -->
    <div class="col-lg-4">
        <!-- Top Selling Menus -->
        <div class="card-brutalist mb-4" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-trophy me-2"></i>Menu Terlaris</h5>
            <?php if (empty($topSelling)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Belum ada penjualan menu.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush border border-2 border-dark rounded-3" style="overflow: hidden;">
                    <?php 
                    $rank = 1;
                    foreach ($topSelling as $menu): 
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #ffeed3; border-bottom: 2px solid #000; font-weight: 700;">
                            <div>
                                <span class="badge bg-dark rounded-circle me-2" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;"><?= $rank++ ?></span>
                                <?= e($menu['menu_name']) ?>
                            </div>
                            <span class="badge-brutalist bg-warning"><?= (int) $menu['sold'] ?> Porsi</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Latest Reviews -->
        <div class="card-brutalist" style="background-color: #ffeed3;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0" style="font-weight: 800;"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan Terbaru</h5>
                <a href="<?= url('admin/reviews') ?>" class="btn-brutalist btn-brutalist-secondary py-1 px-2" style="font-size: 12px;">Semua</a>
            </div>
            <?php if (empty($latestReviews)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Belum ada ulasan masuk.</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($latestReviews as $review): ?>
                        <div class="p-3 border border-2 border-dark rounded-3" style="background-color: #ffeed3; font-weight: 600;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span style="font-weight: 800; font-size: 13px;"><?= e($review['user_name']) ?></span>
                                <div style="color: #ff9533;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?= $i <= (int) $review['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="m-0 mb-1" style="font-size: 12px; font-style: italic; color: #555;">Menu: <?= e($review['menu_name']) ?></p>
                            <p class="m-0" style="font-size: 13px;"><?= e($review['comment']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
