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

return [
    'GET' => [
        '' => [HomeController::class, 'index'],
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
    ],
];
