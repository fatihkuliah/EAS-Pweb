<?php
$activePage = 'reviews';
$pageTitle = 'Manage Reviews';
require __DIR__ . '/layout_start.php';
?>

<!-- Filter Reviews -->
<div class="card-brutalist mb-4" style="background-color: #ffeed3;">
    <form method="GET" action="<?= url('admin/reviews') ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-5">
            <label class="form-label text-dark font-weight-700" style="font-weight: 700;">Filter Rating</label>
            <select name="rating" class="form-select form-select-brutalist">
                <option value="">Semua Rating</option>
                <?php for ($r = 5; $r >= 1; $r--): ?>
                    <option value="<?= $r ?>" <?= ($filters['rating'] ?? '') == $r ? 'selected' : '' ?>><?= $r ?> Bintang</option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-12 col-md-4">
            <button type="submit" class="btn-brutalist btn-brutalist-primary w-100 py-2">
                Filter Ulasan
            </button>
        </div>
        <div class="col-12 col-md-3">
            <a href="<?= url('admin/reviews') ?>" class="btn-brutalist btn-brutalist-secondary w-100 py-2 text-center d-block">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Reviews Grid -->
<?php if (empty($reviews)): ?>
    <div class="card-brutalist text-center py-4" style="background-color: #ffeed3;">
        <p class="m-0 text-muted" style="font-weight: 600;">Tidak ada ulasan ditemukan.</p>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($reviews as $rev): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card-brutalist h-100 d-flex flex-column justify-content-between" style="background-color: #ffeed3;">
                    <div>
                        <!-- Header: User info & Rating -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="d-block" style="font-weight: 800; font-size: 15px;"><?= e($rev['user_name']) ?></span>
                                <small class="text-muted" style="font-size: 11px;"><?= e(date('d M Y H:i', strtotime($rev['created_at']))) ?></small>
                            </div>
                            <div class="d-flex text-warning">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi <?= $i <= (int) $rev['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <!-- Menu info -->
                        <div class="mb-2 p-2 border border-1 border-dark rounded-3 d-inline-block" style="background-color: #ffc38b; font-size: 12px; font-weight: 700;">
                            <i class="bi bi-egg-fried me-1"></i> <?= e($rev['menu_name']) ?>
                        </div>

                        <!-- Comment -->
                        <p class="m-0" style="font-weight: 600; font-size: 14px; line-height: 1.5; font-style: italic;">
                            "<?= e($rev['comment']) ?>"
                        </p>
                    </div>

                    <!-- Footer: Moderate Actions -->
                    <div class="mt-4 border-top border-dark pt-3 text-end">
                        <form action="<?= url('admin/reviews/delete') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                            <input type="hidden" name="review_id" value="<?= (int) $rev['review_id'] ?>">
                            <button type="submit" class="btn-brutalist btn-brutalist-danger py-1 px-3">
                                <i class="bi bi-trash"></i> Hapus Ulasan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/layout_end.php'; ?>
