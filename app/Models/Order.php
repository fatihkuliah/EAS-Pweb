<?php

declare(strict_types=1);

namespace App\Models;

class Order
{
    public static function all(): array
    {
        $_SESSION['orders'] ??= self::seed();

        return $_SESSION['orders'];
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $order) {
            if ($order['id'] === $id) {
                return $order;
            }
        }

        return null;
    }

    public static function create(array $order): array
    {
        $_SESSION['orders'] = [$order, ...self::all()];

        return $order;
    }

    public static function saveReview(string $orderId, int $rating, string $comment): void
    {
        $_SESSION['reviews'] ??= [];
        $_SESSION['reviews'][] = [
            'order_id' => $orderId,
            'rating' => $rating,
            'komentar' => $comment,
            'tanggal' => date('Y-m-d H:i:s'),
        ];
    }

    private static function seed(): array
    {
        $orders = [];

        foreach (data_file('orders') as $seed) {
            $items = [];

            foreach ($seed['item_ids'] as $menuId => $qty) {
                $menu = Menu::find((int) $menuId);

                if ($menu !== null) {
                    $menu['qty'] = $qty;
                    $items[] = $menu;
                }
            }

            unset($seed['item_ids']);
            $orders[] = array_merge($seed, [
                'items' => $items,
                'total' => array_reduce($items, fn (int $sum, array $item): int => $sum + ($item['harga'] * $item['qty']), 0),
            ]);
        }

        return $orders;
    }
}
