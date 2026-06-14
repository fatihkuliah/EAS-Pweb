<?php

declare(strict_types=1);

namespace App\Models;

class Menu
{
    public static function all(): array
    {
        return data_file('menus');
    }

    public static function find(int $id): ?array
    {
        foreach (self::all() as $menu) {
            if ((int) $menu['id'] === $id) {
                return $menu;
            }
        }

        return null;
    }

    public static function favorites(): array
    {
        $favoriteIds = $_SESSION['favorites'] ?? [];

        return array_values(array_filter(self::all(), function (array $menu) use ($favoriteIds): bool {
            return in_array((int) $menu['id'], $favoriteIds, true);
        }));
    }

    public static function toggleFavorite(int $id): void
    {
        $_SESSION['favorites'] ??= [];

        if (in_array($id, $_SESSION['favorites'], true)) {
            $_SESSION['favorites'] = array_values(array_diff($_SESSION['favorites'], [$id]));
            return;
        }

        $_SESSION['favorites'][] = $id;
    }
}
