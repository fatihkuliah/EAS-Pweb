<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;

class CartService
{
    public function items(): array
    {
        return Cart::items();
    }

    public function total(): int
    {
        return Cart::total();
    }

    public function add(int $menuId, int $qty = 1): void
    {
        Cart::add($menuId, $qty);
    }

    public function update(int $menuId, int $qty): void
    {
        Cart::update($menuId, $qty);
    }
}
