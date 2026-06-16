<?php
$activePage = 'reports';
$pageTitle = 'Reports & Analytics';
require __DIR__ . '/layout_start.php';
?>

<!-- Revenue Summaries -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card-brutalist text-center" style="background-color: #ffeed3;">
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800; color: #555;">Pendapatan Hari Ini</h6>
            <h3 class="mt-2 mb-0" style="font-weight: 900; color: #000;"><?= rupiah($revenue['today'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-brutalist text-center" style="background-color: #ffc38b;">
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800; color: #58391b;">Pendapatan Bulan Ini</h6>
            <h3 class="mt-2 mb-0" style="font-weight: 900; color: #000;"><?= rupiah($revenue['month'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-brutalist text-center" style="background-color: #ffeed3;">
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800; color: #555;">Pendapatan Tahun Ini</h6>
            <h3 class="mt-2 mb-0" style="font-weight: 900; color: #000;"><?= rupiah($revenue['year'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-brutalist text-center" style="background-color: #ff9533;">
            <h6 class="text-uppercase m-0" style="font-size: 11px; font-weight: 800; color: #000;">Total Pendapatan</h6>
            <h3 class="mt-2 mb-0" style="font-weight: 900; color: #000;"><?= rupiah($revenue['total'] ?? 0) ?></h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Order Statistics -->
    <div class="col-md-6">
        <div class="card-brutalist h-100" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-pie-chart me-2"></i>Statistik Status Pesanan</h5>
            <div class="table-responsive">
                <table class="table table-brutalist m-0">
                    <thead>
                        <tr>
                            <th>Status Pesanan</th>
                            <th>Jumlah Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderStats as $stat): 
                            $badgeClass = 'bg-secondary';
                            if ($stat['status'] === 'Selesai') $badgeClass = 'bg-success text-white';
                            elseif ($stat['status'] === 'Batal') $badgeClass = 'bg-danger text-white';
                            elseif ($stat['status'] === 'Diproses') $badgeClass = 'bg-info text-dark';
                            elseif ($stat['status'] === 'paid') $badgeClass = 'bg-primary text-white';
                            elseif ($stat['status'] === 'Menunggu Pembayaran') $badgeClass = 'bg-warning text-dark';
                        ?>
                            <tr>
                                <td>
                                    <span class="badge-brutalist <?= $badgeClass ?>"><?= e($stat['status']) ?></span>
                                </td>
                                <td style="font-weight: 800;"><?= (int) $stat['count'] ?> Pesanan</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top 5 Menus -->
    <div class="col-md-6">
        <div class="card-brutalist h-100" style="background-color: #ffeed3;">
            <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-trophy me-2"></i>Top 5 Menu Paling Laris</h5>
            <?php if (empty($topSelling)): ?>
                <p class="m-0 text-muted" style="font-weight: 600;">Belum ada penjualan tercatat.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-brutalist m-0">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Menu</th>
                                <th>Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = 1;
                            foreach ($topSelling as $menu): 
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-dark rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">
                                            <?= $rank++ ?>
                                        </span>
                                    </td>
                                    <td style="font-weight: 800;"><?= e($menu['menu_name']) ?></td>
                                    <td>
                                        <span class="badge-brutalist bg-warning"><?= (int) $menu['sold'] ?> Porsi</span>
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

<!-- Export Block -->
<div class="card-brutalist" style="background-color: #ffeed3;">
    <h5 class="mb-3" style="font-weight: 800;"><i class="bi bi-download me-2"></i>Ekspor Laporan Penjualan</h5>
    <p class="text-muted" style="font-weight: 600;">Unduh laporan transaksi dalam format CSV (dapat dibuka dengan Excel atau Google Sheets). Silakan filter rentang tanggal laporan.</p>
    
    <form action="<?= url('admin/reports/export') ?>" method="GET" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label font-weight-700" style="font-weight: 700;">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control form-control-brutalist" value="<?= date('Y-m-01') ?>">
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label font-weight-700" style="font-weight: 700;">Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control form-control-brutalist" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-12 col-md-4">
            <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Unduh Laporan CSV
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
