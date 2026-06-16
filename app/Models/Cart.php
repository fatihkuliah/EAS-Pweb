<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Cart
{
    public static function items(): array
    {
        $user = User::current();
        if ($user && isset($user['user_id'])) {
            try {
                $db = Database::connect();
                $stmt = $db->prepare('
                    SELECT c.*, m.menu_name, m.price, m.image, m.portion, m.serving_time, cat.category_name
                    FROM cart_items c
                    JOIN menus m ON c.menu_id = m.menu_id
                    JOIN categories cat ON m.category_id = cat.category_id
                    WHERE c.user_id = ?
                ');
                $stmt->execute([$user['user_id']]);
                $rows = $stmt->fetchAll();

                $items = array_map(function (array $row): array {
                    return [
                        'id' => (int) $row['menu_id'],
                        'nama' => $row['menu_name'],
                        'kategori' => $row['category_name'] ?? '',
                        'deskripsi' => '',
                        'gambar' => storage_public_path($row['image']),
                        'harga' => (int) $row['price'],
                        'porsi' => $row['portion'],
                        'waktu' => $row['serving_time'],
                        'qty' => (int) $row['quantity'],
                    ];
                }, $rows);

                $_SESSION['cart'] = $items;
                return $items;
            } catch (\Throwable $e) {
                // DB error fallback to session
            }
        }

        return $_SESSION['cart'] ?? [];
    }

    public static function add(int $menuId, int $qty = 1): void
    {
        $menu = Menu::find($menuId);

        if ($menu === null) {
            return;
        }

        $_SESSION['cart'] ??= [];

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ((int) $item['id'] === $menuId) {
                $item['qty'] += max(1, $qty);
                $found = true;
                break;
            }
        }

        if (!$found) {
            $menu['qty'] = max(1, $qty);
            $_SESSION['cart'][] = $menu;
        }

        $user = User::current();
        if ($user && isset($user['user_id'])) {
            try {
                $db = Database::connect();
                $stmt = $db->prepare('
                    INSERT INTO cart_items (user_id, menu_id, quantity)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE quantity = quantity + ?
                ');
                $stmt->execute([$user['user_id'], $menuId, max(1, $qty), max(1, $qty)]);
            } catch (\Throwable $e) {
                // DB fallback
            }
        }
    }

    public static function update(int $menuId, int $qty): void
    {
        $_SESSION['cart'] = array_values(array_filter(array_map(function (array $item) use ($menuId, $qty): array {
            if ((int) $item['id'] === $menuId) {
                $item['qty'] = $qty;
            }

            return $item;
        }, self::items()), fn (array $item): bool => (int) $item['qty'] > 0));

        $user = User::current();
        if ($user && isset($user['user_id'])) {
            try {
                $db = Database::connect();
                if ($qty <= 0) {
                    $stmt = $db->prepare('DELETE FROM cart_items WHERE user_id = ? AND menu_id = ?');
                    $stmt->execute([$user['user_id'], $menuId]);
                } else {
                    $stmt = $db->prepare('
                        INSERT INTO cart_items (user_id, menu_id, quantity)
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE quantity = ?
                    ');
                    $stmt->execute([$user['user_id'], $menuId, $qty, $qty]);
                }
            } catch (\Throwable $e) {
                // DB fallback
            }
        }
    }

    public static function total(): int
    {
        return array_reduce(self::items(), fn (int $sum, array $item): int => $sum + ((int) $item['harga'] * (int) $item['qty']), 0);
    }

    public static function clear(): void
    {
        unset($_SESSION['cart']);

        $user = User::current();
        if ($user && isset($user['user_id'])) {
            try {
                $db = Database::connect();
                $stmt = $db->prepare('DELETE FROM cart_items WHERE user_id = ?');
                $stmt->execute([$user['user_id']]);
            } catch (\Throwable $e) {
                // DB fallback
            }
        }
    }
}
