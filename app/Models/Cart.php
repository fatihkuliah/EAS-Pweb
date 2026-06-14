<?php

declare(strict_types=1);

namespace App\Models;

class Cart
{
    public static function items(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public static function add(int $menuId, int $qty = 1): void
    {
        $menu = Menu::find($menuId);

        if ($menu === null) {
            return;
        }

        $_SESSION['cart'] ??= [];

        foreach ($_SESSION['cart'] as &$item) {
            if ((int) $item['id'] === $menuId) {
                $item['qty'] += max(1, $qty);
                return;
            }
        }

        $menu['qty'] = max(1, $qty);
        $_SESSION['cart'][] = $menu;
    }

    public static function update(int $menuId, int $qty): void
    {
        $_SESSION['cart'] = array_values(array_filter(array_map(function (array $item) use ($menuId, $qty): array {
            if ((int) $item['id'] === $menuId) {
                $item['qty'] = $qty;
            }

            return $item;
        }, self::items()), fn (array $item): bool => (int) $item['qty'] > 0));
    }

    public static function total(): int
    {
        return array_reduce(self::items(), fn (int $sum, array $item): int => $sum + ((int) $item['harga'] * (int) $item['qty']), 0);
    }

    public static function clear(): void
    {
        unset($_SESSION['cart']);
    }
}
