<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Order
{
    public static function all(): array
    {
        $user = User::current();
        if (!$user || !isset($user['user_id'])) {
            // Seed session fallback if DB not populated or user not logged in
            $_SESSION['orders'] ??= self::seed();
            return $_SESSION['orders'];
        }

        $db = Database::connect();
        // Fetch all orders for this user
        $stmt = $db->prepare('
            SELECT o.*, p.payment_method, p.payment_proof, p.payment_status 
            FROM orders o
            LEFT JOIN payments p ON o.order_id = p.order_id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ');
        $stmt->execute([$user['user_id']]);
        $orders = $stmt->fetchAll();

        $result = [];
        foreach ($orders as $orderRow) {
            $result[] = self::mapOrderWithItems($db, $orderRow);
        }

        return $result;
    }

    public static function find(string $id): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare('
            SELECT o.*, p.payment_method, p.payment_proof, p.payment_status 
            FROM orders o
            LEFT JOIN payments p ON o.order_id = p.order_id
            WHERE o.order_number = ? OR o.order_id = ?
        ');
        $stmt->execute([$id, $id]);
        $row = $stmt->fetch();

        return $row ? self::mapOrderWithItems($db, $row) : null;
    }

    public static function create(array $order): array
    {
        $user = User::current();
        if (!$user) {
            User::save([
                'nama' => 'Customer MieME',
                'email' => 'customer@mieme.test',
            ]);
            $user = User::current();
        }

        $userId = (int) $user['user_id'];
        $db = Database::connect();
        $db->beginTransaction();

        try {
            // Insert order
            $stmt = $db->prepare('
                INSERT INTO orders (user_id, order_number, recipient_name, recipient_phone, shipping_address, note, total_price, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([
                $userId,
                $order['id'],
                $order['penerima'],
                $order['telepon'],
                $order['alamat'],
                $order['catatan'],
                $order['total'],
                $order['status']
            ]);
            $orderId = (int) $db->lastInsertId();

            // Insert order items
            $itemStmt = $db->prepare('
                INSERT INTO order_items (order_id, menu_id, quantity, price, subtotal)
                VALUES (?, ?, ?, ?, ?)
            ');
            foreach ($order['items'] as $item) {
                $itemStmt->execute([
                    $orderId,
                    (int) $item['id'],
                    (int) $item['qty'],
                    (float) $item['harga'],
                    (float) ($item['harga'] * $item['qty'])
                ]);
            }

            // Insert payment
            $paymentStmt = $db->prepare('
                INSERT INTO payments (order_id, payment_method, amount, payment_proof, payment_status, paid_at)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            
            $paymentMethod = $order['metode'];
            $paymentStatus = 'Menunggu Konfirmasi';
            $paidAt = null;

            if ($order['status'] === 'Selesai') {
                $paymentStatus = 'Lunas';
                $paidAt = date('Y-m-d H:i:s');
            }

            $paymentStmt->execute([
                $orderId,
                $paymentMethod,
                $order['total'],
                $order['bukti'] ?: null,
                $paymentStatus,
                $paidAt
            ]);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        // Keep session updated as well
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

        $db = Database::connect();
        
        $order = self::find($orderId);
        if (!$order) {
            return;
        }

        $menuId = 1; // default fallback
        if (!empty($order['items'])) {
            $menuId = (int) $order['items'][0]['id'];
        }

        $userId = (int) ($order['user_id'] ?? 1);
        $orderDbId = (int) $order['order_id'];

        $stmt = $db->prepare('
            INSERT INTO reviews (order_id, user_id, menu_id, rating, comment)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $orderDbId,
            $userId,
            $menuId,
            $rating,
            $comment
        ]);
    }

    private static function mapOrderWithItems(PDO $db, array $orderRow): array
    {
        $stmt = $db->prepare('
            SELECT oi.*, m.menu_name, m.image, m.portion, m.serving_time, c.category_name 
            FROM order_items oi
            JOIN menus m ON oi.menu_id = m.menu_id
            JOIN categories c ON m.category_id = c.category_id
            WHERE oi.order_id = ?
        ');
        $stmt->execute([$orderRow['order_id']]);
        $items = $stmt->fetchAll();

        $mappedItems = array_map(function (array $itemRow): array {
            return [
                'id' => (int) $itemRow['menu_id'],
                'nama' => $itemRow['menu_name'],
                'kategori' => $itemRow['category_name'] ?? '',
                'harga' => (int) $itemRow['price'],
                'gambar' => storage_public_path($itemRow['image']),
                'porsi' => $itemRow['portion'],
                'waktu' => $itemRow['serving_time'],
                'qty' => (int) $itemRow['quantity'],
            ];
        }, $items);

        return [
            'id' => $orderRow['order_number'],
            'tanggal' => $orderRow['created_at'],
            'items' => $mappedItems,
            'total' => (int) $orderRow['total_price'],
            'status' => $orderRow['status'],
            'penerima' => $orderRow['recipient_name'],
            'telepon' => $orderRow['recipient_phone'],
            'alamat' => $orderRow['shipping_address'],
            'catatan' => $orderRow['note'],
            'metode' => $orderRow['payment_method'] ?? 'QRIS',
            'bukti' => $orderRow['payment_proof'] ?? '',
            // DB keys
            'order_id' => (int) $orderRow['order_id'],
            'user_id' => (int) $orderRow['user_id'],
            'order_number' => $orderRow['order_number'],
            'total_price' => (float) $orderRow['total_price'],
            'created_at' => $orderRow['created_at'],
            'updated_at' => $orderRow['updated_at'],
        ];
    }

    private static function seed(): array
    {
        return [
            [
                'id' => 'ORD-20260601-001',
                'tanggal' => '2026-06-01 10:30:00',
                'items' => [
                    [
                        'id' => 1,
                        'nama' => 'Mie Spesial Sambal Matah',
                        'kategori' => 'Makanan',
                        'deskripsi' => 'Mie signature dengan sambal matah segar dan racikan rempah khas MieME.',
                        'gambar' => 'assets/images/mie1.png',
                        'harga' => 28000,
                        'porsi' => '1 Orang',
                        'waktu' => '25 Menit',
                        'qty' => 2
                    ],
                    [
                        'id' => 6,
                        'nama' => 'Es Teh',
                        'kategori' => 'Minuman',
                        'deskripsi' => 'Teh dingin menyegarkan untuk menemani setiap menu MieME.',
                        'gambar' => 'assets/images/esteh.png',
                        'harga' => 8000,
                        'porsi' => '1 Gelas',
                        'waktu' => '5 Menit',
                        'qty' => 2
                    ]
                ],
                'total' => 72000,
                'status' => 'Selesai',
                'penerima' => 'Budi Santoso',
                'telepon' => '081234567890',
                'alamat' => 'Jl. Jendral Sudirman No. 1, Jakarta',
                'catatan' => 'Sambal dipisah.',
                'metode' => 'QRIS',
                'bukti' => '',
            ]
        ];
    }
}
