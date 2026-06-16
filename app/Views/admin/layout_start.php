<?php
$admin = \App\Models\User::current();
$activePage = $activePage ?? 'dashboard';
?>
<div class="admin-wrapper d-flex" style="min-height: 100vh; background-color: #101010; font-family: Montserrat;">
    <!-- Sidebar -->
    <div class="admin-sidebar" style="width: 260px; background-color: #ffeed3; border-right: 3px solid #000; padding: 20px; display: flex; flex-direction: column; flex-shrink: 0; height: 100vh; position: sticky; top: 0;">
        <!-- Top part: Logo (Fixed) -->
        <div class="logo text-center mb-4" style="border: 2px solid #000; background-color: #ff9533; border-radius: 20px; padding: 10px; flex-shrink: 0;">
            <h2 style="font-weight: 800; color: #000; margin: 0; font-size: 24px;">Mie<span style="color: #fff;">ME</span> <span style="font-size: 14px; font-weight: 700; display: block; letter-spacing: 1px;">ADMIN</span></h2>
        </div>
        
        <!-- Middle part: Menu Items (Scrollable) -->
        <div class="admin-sidebar-menu flex-grow-1" style="overflow-y: auto; margin-bottom: 15px; padding-right: 4px;">
            <ul class="nav flex-column gap-2" style="padding-left: 0; list-style: none;">
                <?php
                $menuItems = [
                    'dashboard' => ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => 'admin'],
                    'orders' => ['label' => 'Orders', 'icon' => 'bi-receipt', 'url' => 'admin/orders'],
                    'payments' => ['label' => 'Payments', 'icon' => 'bi-credit-card', 'url' => 'admin/payments'],
                    'menus' => ['label' => 'Menus', 'icon' => 'bi-egg-fried', 'url' => 'admin/menus'],
                    'categories' => ['label' => 'Categories', 'icon' => 'bi-tags', 'url' => 'admin/categories'],
                    'users' => ['label' => 'Users', 'icon' => 'bi-people', 'url' => 'admin/users'],
                    'reviews' => ['label' => 'Reviews', 'icon' => 'bi-star', 'url' => 'admin/reviews'],
                    'reports' => ['label' => 'Reports', 'icon' => 'bi-graph-up', 'url' => 'admin/reports'],
                ];
                foreach ($menuItems as $key => $item):
                    $active = ($activePage === $key);
                ?>
                    <li class="nav-item">
                        <a href="<?= url($item['url']) ?>" class="nav-link d-flex align-items-center gap-3 px-3 py-2" style="font-weight: 700; border-radius: 50px; border: 2px solid <?= $active ? '#000' : 'transparent' ?>; background-color: <?= $active ? '#ff9533' : 'transparent' ?>; color: #000; transition: all 0.2s;">
                            <i class="bi <?= $item['icon'] ?>"></i>
                            <?= $item['label'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Bottom part: Footer (Fixed) -->
        <div class="admin-sidebar-footer" style="flex-shrink: 0; margin-top: auto;">
            <hr style="border-top: 2px solid #000; margin: 10px 0;">
            <a href="<?= url('') ?>" class="nav-link d-flex align-items-center gap-3 px-3 py-2 mb-2" style="font-weight: 700; color: #000;">
                <i class="bi bi-house"></i>
                Lihat Toko
            </a>
            <form action="<?= url('logout') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                <button type="submit" class="w-100 btn d-flex align-items-center justify-content-center gap-3 py-2" style="font-weight: 700; border: 2px solid #000; border-radius: 50px; background-color: #ffc38b; color: #000;">
                    <i class="bi bi-box-arrow-left"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-grow-1 d-flex flex-column" style="min-height: 100vh; overflow-y: auto;">
        <!-- Topbar -->
        <div class="admin-topbar d-flex justify-content-between align-items-center px-4 py-3" style="background-color: #101010; border-bottom: 3px solid #000;">
            <!-- Mobile Menu Toggle -->
            <button class="btn d-md-none" id="mobile-sidebar-toggle" style="border: 2px solid #000; background-color: #ffeed3; border-radius: 10px;">
                <i class="bi bi-list"></i>
            </button>
            <h4 class="m-0 text-white font-weight-800" style="font-family: Montserrat; font-weight: 800;"><?= $pageTitle ?? 'Admin Dashboard' ?></h4>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white d-none d-sm-inline" style="font-weight: 600;">Halo, <?= e($admin['nama'] ?? 'Admin') ?>!</span>
                <div style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid #ff9533; background-image: url('<?= asset($admin['avatar'] ?? 'assets/images/user.png') ?>'); background-size: cover; background-position: center;"></div>
            </div>
        </div>

        <!-- Inner Content -->
        <div class="p-4" style="flex-grow: 1;">
            <!-- Flash Message -->
            <?php if (isset($_SESSION['flash'])): ?>
                <div class="alert alert-success border-2 border-dark rounded-3 mb-4" style="background-color: #ffeed3; color: #000; font-weight: 700; border: 2px solid #000;">
                    <?= e($_SESSION['flash']); unset($_SESSION['flash']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger border-2 border-dark rounded-3 mb-4" style="background-color: #ff6b6b; color: #fff; font-weight: 700; border: 2px solid #000;">
                    <?= e($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
