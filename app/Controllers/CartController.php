<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CartService;

class CartController
{
    public function add(): never
    {
        (new CartService())->add((int) post('id'), (int) post('qty', 1));
        redirect((string) post('back', 'order'));
    }

    public function update(): never
    {
        (new CartService())->update((int) post('id'), (int) post('qty'));
        redirect('order');
    }
}
