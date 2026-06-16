<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Menu
{
    public static function all(): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->query('
                SELECT m.*, c.category_name 
                FROM menus m 
                JOIN categories c ON m.category_id = c.category_id
                ORDER BY m.menu_id ASC
            ');
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                return array_map([self::class, 'mapMenu'], $rows);
            }
        } catch (\Throwable $e) {
            // DB error fallback
        }

        return self::fallbackMenus();
    }

    public static function find(int $id): ?array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare('
                SELECT m.*, c.category_name 
                FROM menus m 
                JOIN categories c ON m.category_id = c.category_id 
                WHERE m.menu_id = ?
            ');
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            if ($row) {
                return self::mapMenu($row);
            }
        } catch (\Throwable $e) {
            // DB error fallback
        }

        foreach (self::fallbackMenus() as $menu) {
            if ((int) $menu['id'] === $id) {
                return $menu;
            }
        }

        return null;
    }

    public static function findBySlug(string $slug): ?array
    {
        foreach (self::all() as $menu) {
            if (menu_slug($menu) === $slug) {
                return $menu;
            }
        }

        return null;
    }

    private static function fallbackMenus(): array
    {
        return [
            ['id' => 1, 'nama' => 'Mie Spesial Sambal Matah', 'kategori' => 'Makanan', 'deskripsi' => 'Mie signature dengan sambal matah segar dan racikan rempah khas MieME.', 'gambar' => 'assets/images/mie1.png', 'harga' => 28000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
            ['id' => 2, 'nama' => 'Mie Signature', 'kategori' => 'Makanan', 'deskripsi' => 'Menu andalan dengan rasa autentik, gurih, dan tekstur mie yang lembut.', 'gambar' => 'assets/images/mi2.png', 'harga' => 28000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
            ['id' => 3, 'nama' => 'Mie Goreng Topping Istimewah', 'kategori' => 'Makanan', 'deskripsi' => 'Mie goreng lengkap dengan topping spesial untuk rasa yang lebih mantap.', 'gambar' => 'assets/images/mi3.png', 'harga' => 30000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
            ['id' => 4, 'nama' => 'Mie Kuah Udang Spesial', 'kategori' => 'Makanan', 'deskripsi' => 'Mie kuah gurih dengan udang segar dan aroma laut yang menggugah selera.', 'gambar' => 'assets/images/mi-udang.png', 'harga' => 35000, 'porsi' => '1 Orang', 'waktu' => '30 Menit'],
            ['id' => 5, 'nama' => 'Mie Kuah Spesial', 'kategori' => 'Makanan', 'deskripsi' => 'Mie kuah hangat dengan bumbu spesial yang cocok dinikmati kapan saja.', 'gambar' => 'assets/images/mi-kuah-s.png', 'harga' => 28000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
            ['id' => 6, 'nama' => 'Es Teh', 'kategori' => 'Minuman', 'deskripsi' => 'Teh dingin menyegarkan untuk menemani setiap menu MieME.', 'gambar' => 'assets/images/esteh.png', 'harga' => 8000, 'porsi' => '1 Gelas', 'waktu' => '5 Menit'],
            ['id' => 7, 'nama' => 'Es Buah Segar', 'kategori' => 'Minuman', 'deskripsi' => 'Potongan buah segar dengan kuah manis dingin yang menyegarkan.', 'gambar' => 'assets/images/esbuah.png', 'harga' => 15000, 'porsi' => '1 Gelas', 'waktu' => '5 Menit'],
            ['id' => 8, 'nama' => 'Es Jeruk', 'kategori' => 'Minuman', 'deskripsi' => 'Jeruk segar dingin dengan rasa manis dan asam yang pas.', 'gambar' => 'assets/images/esjeruk.png', 'harga' => 10000, 'porsi' => '1 Gelas', 'waktu' => '5 Menit'],
        ];
    }

    public static function favorites(): array
    {
        $user = User::current();
        if ($user && isset($user['user_id'])) {
            try {
                $db = Database::connect();
                $stmt = $db->prepare('
                    SELECT m.*, c.category_name 
                    FROM favorites f 
                    JOIN menus m ON f.menu_id = m.menu_id 
                    JOIN categories c ON m.category_id = c.category_id 
                    WHERE f.user_id = ?
                ');
                $stmt->execute([$user['user_id']]);
                $rows = $stmt->fetchAll();
                return array_map([self::class, 'mapMenu'], $rows);
            } catch (\Throwable $e) {
                // DB error fallback
            }
        }

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
        } else {
            $_SESSION['favorites'][] = $id;
        }

        $user = User::current();
        if ($user && isset($user['user_id'])) {
            try {
                $db = Database::connect();
                
                // Check if already in favorites
                $stmt = $db->prepare('SELECT 1 FROM favorites WHERE user_id = ? AND menu_id = ?');
                $stmt->execute([$user['user_id'], $id]);
                
                if ($stmt->fetch()) {
                    // Remove
                    $delStmt = $db->prepare('DELETE FROM favorites WHERE user_id = ? AND menu_id = ?');
                    $delStmt->execute([$user['user_id'], $id]);
                } else {
                    // Add
                    $insStmt = $db->prepare('INSERT INTO favorites (user_id, menu_id) VALUES (?, ?)');
                    $insStmt->execute([$user['user_id'], $id]);
                }
            } catch (\Throwable $e) {
                // DB error fallback
            }
        }
    }

    private static function mapMenu(array $row): array
    {
        return [
            'id' => (int) $row['menu_id'],
            'nama' => $row['menu_name'],
            'kategori' => $row['category_name'] ?? '',
            'deskripsi' => $row['description'],
            'gambar' => storage_public_path($row['image']),
            'harga' => (int) $row['price'],
            'slug' => slugify($row['menu_name']),
            'porsi' => $row['portion'],
            'waktu' => $row['serving_time'],
            'stock' => (int) $row['stock'],
            'is_available' => (bool) $row['is_available'],
            // DB keys
            'menu_id' => (int) $row['menu_id'],
            'category_id' => (int) $row['category_id'],
            'menu_name' => $row['menu_name'],
            'description' => $row['description'],
            'price' => (float) $row['price'],
            'image' => storage_public_path($row['image']),
            'serving_time' => $row['serving_time'],
            'portion' => $row['portion'],
        ];
    }
}
