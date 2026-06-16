<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\HomeController;
use App\Controllers\MenuController;
use App\Controllers\OrderController;
use App\Controllers\PaymentController;
use App\Controllers\ProfileController;
use App\Controllers\AdminController;
use App\Controllers\SeoController;

return [
    'GET' => [
        '' => [HomeController::class, 'index'],
        'robots.txt' => [SeoController::class, 'robots'],
        'sitemap.xml' => [SeoController::class, 'sitemap'],
        'order' => [MenuController::class, 'index'],
        'menu-detail' => [MenuController::class, 'detail'],
        'favorites' => [MenuController::class, 'favorites'],
        'checkout' => [CheckoutController::class, 'index'],
        'detail-order' => [CheckoutController::class, 'detail'],
        'payment' => [PaymentController::class, 'index'],
        'qris' => [PaymentController::class, 'qris'],
        'upload-payment' => [PaymentController::class, 'upload'],
        'orders' => [OrderController::class, 'index'],
        'orders/export' => [OrderController::class, 'export'],
        'orders/invoice' => [OrderController::class, 'invoice'],
        'review' => [OrderController::class, 'review'],
        'auth' => [AuthController::class, 'index'],
        'profile' => [ProfileController::class, 'index'],

        // Admin Routes
        'admin' => [AdminController::class, 'index'],
        'admin/orders' => [AdminController::class, 'orders'],
        'admin/orders/detail' => [AdminController::class, 'orderDetail'],
        'admin/payments' => [AdminController::class, 'payments'],
        'admin/menus' => [AdminController::class, 'menus'],
        'admin/menus/create' => [AdminController::class, 'menuCreate'],
        'admin/menus/edit' => [AdminController::class, 'menuEdit'],
        'admin/categories' => [AdminController::class, 'categories'],
        'admin/users' => [AdminController::class, 'users'],
        'admin/reviews' => [AdminController::class, 'reviews'],
        'admin/reports' => [AdminController::class, 'reports'],
        'admin/reports/export' => [AdminController::class, 'reportsExport'],
    ],
    'POST' => [
        'cart/add' => [CartController::class, 'add'],
        'cart/update' => [CartController::class, 'update'],
        'favorite/toggle' => [MenuController::class, 'toggleFavorite'],
        'checkout' => [CheckoutController::class, 'store'],
        'detail-order/pay' => [CheckoutController::class, 'pay'],
        'payment/select' => [PaymentController::class, 'select'],
        'upload-payment' => [PaymentController::class, 'storeUpload'],
        'review' => [OrderController::class, 'storeReview'],
        'auth' => [AuthController::class, 'store'],
        'profile' => [ProfileController::class, 'update'],
        'logout' => [AuthController::class, 'logout'],

        // Admin POST actions
        'admin/orders/update' => [AdminController::class, 'orderUpdate'],
        'admin/payments/action' => [AdminController::class, 'paymentAction'],
        'admin/menus/store' => [AdminController::class, 'menuStore'],
        'admin/menus/update' => [AdminController::class, 'menuUpdate'],
        'admin/menus/delete' => [AdminController::class, 'menuDelete'],
        'admin/categories/store' => [AdminController::class, 'categoryStore'],
        'admin/categories/update' => [AdminController::class, 'categoryUpdate'],
        'admin/categories/delete' => [AdminController::class, 'categoryDelete'],
        'admin/users/update' => [AdminController::class, 'userUpdate'],
        'admin/reviews/delete' => [AdminController::class, 'reviewDelete'],
    ],
];
