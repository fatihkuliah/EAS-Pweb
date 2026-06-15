<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CartService;

class CartController
{
    public function add(): void
    {
        $cartService = new CartService();
        $cartService->add((int) post('id'), (int) post('qty', 1));

        if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'cart' => $cartService->items(),
                'total' => $cartService->total()
            ]);
            exit;
        }

        redirect((string) post('back', 'order'));
    }

    public function update(): void
    {
        $cartService = new CartService();
        $cartService->update((int) post('id'), (int) post('qty'));

        if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'cart' => $cartService->items(),
                'total' => $cartService->total()
            ]);
            exit;
        }

        redirect('order');
    }
}
