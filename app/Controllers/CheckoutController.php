<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\OrderService;

class CheckoutController extends Controller
{
    public function index(): void
    {
        $cart = new CartService();

        $this->view('checkout', [
            'title' => 'Checkout MieME',
            'cart' => $cart->items(),
            'cartTotal' => $cart->total(),
            'user' => User::current(),
        ]);
    }

    public function store(): never
    {
        (new CheckoutService())->save($_POST);
        redirect('detail-order');
    }

    public function detail(): void
    {
        $orders = new OrderService();
        $checkoutService = new CheckoutService();
        $order = isset($_GET['id']) ? $orders->find((string) $_GET['id']) : null;
        $checkout = $order ?? $checkoutService->current();

        $this->view('orders/detail', [
            'title' => 'Detail Pesanan MieME',
            'checkout' => $checkout,
            'order' => $order,
        ]);
    }

    public function pay(): never
    {
        redirect('payment');
    }
}
