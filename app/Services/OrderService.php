<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;

class OrderService
{
    public function all(): array
    {
        return Order::all();
    }

    public function find(string $id): ?array
    {
        return Order::find($id);
    }

    public function createFromCheckout(string $receipt = ''): ?array
    {
        $checkout = (new CheckoutService())->current();
        $payment = Payment::current();

        if ($checkout === null || $payment === null) {
            return null;
        }

        $order = array_merge($checkout, [
            'id' => 'ORD-' . date('Ymd-His'),
            'status' => 'Menunggu Pembayaran',
            'metode' => $payment['nama'],
            'bukti' => $receipt,
        ]);

        Cart::clear();
        (new CheckoutService())->clear();

        return Order::create($order);
    }

    public function saveReview(string $orderId, int $rating, string $comment): void
    {
        Order::saveReview($orderId, $rating, $comment);
    }
}
