<div class="row py-5">
  <div class="col-lg-8">
    <div class="judul-menu d-inline-block"><?= e($heading) ?></div>
    <p class="menu-tagline mt-3">
      <span><?= e($lead) ?></span><br />
      <?= e($description) ?>
    </p>
  </div>
  <?php if (!empty($actionText) && !empty($actionUrl)) : ?>
    <div class="col-lg-4 d-flex align-items-center justify-content-lg-end gap-2">
      <a href="<?= e($actionUrl) ?>" class="order-back text-decoration-none"><?= e($actionText) ?></a>
    </div>
  <?php endif; ?>
</div>
