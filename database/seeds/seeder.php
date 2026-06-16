<?php

declare(strict_types=1);

namespace Database\Seeds;

use PDO;

class Seeder
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function run(): void
    {
        $this->db->beginTransaction();

        try {
            // 1. Seed Categories
            echo "Seeding categories...\n";
            $categories = ['Makanan', 'Minuman'];
            $categoryIds = [];
            
            $stmt = $this->db->prepare('INSERT INTO categories (category_name) VALUES (:name) ON DUPLICATE KEY UPDATE category_id=LAST_INSERT_ID(category_id)');
            foreach ($categories as $cat) {
                $stmt->execute(['name' => $cat]);
                $categoryIds[$cat] = (int) $this->db->lastInsertId();
            }

            // 2. Seed Users
            echo "Seeding users...\n";
            $stmt = $this->db->prepare('
                INSERT INTO users (name, email, password_hash, phone, address, profile_image, role) 
                VALUES (:name, :email, :password_hash, :phone, :address, :profile_image, :role)
                ON DUPLICATE KEY UPDATE user_id=LAST_INSERT_ID(user_id)
            ');
            $stmt->execute([
                'name' => 'Customer MieME',
                'email' => 'customer@mieme.test',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'phone' => '081234567890',
                'address' => 'Jl. Jendral Sudirman No. 1, Jakarta',
                'profile_image' => 'assets/images/user.png',
                'role' => 'customer'
            ]);
            $userId = (int) $this->db->lastInsertId();

            $stmt->execute([
                'name' => 'Admin MieME',
                'email' => 'admin@mieme.test',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'phone' => '081234567891',
                'address' => 'Kantor Pusat MieME',
                'profile_image' => 'assets/images/user.png',
                'role' => 'admin'
            ]);

            // 3. Seed Menus
            echo "Seeding menus...\n";
            $menus = [
                ['id' => 1, 'nama' => 'Mie Spesial Sambal Matah', 'kategori' => 'Makanan', 'deskripsi' => 'Mie signature dengan sambal matah segar dan racikan rempah khas MieME.', 'gambar' => 'assets/images/mie1.png', 'harga' => 28000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
                ['id' => 2, 'nama' => 'Mie Signature', 'kategori' => 'Makanan', 'deskripsi' => 'Menu andalan dengan rasa autentik, gurih, dan tekstur mie yang lembut.', 'gambar' => 'assets/images/mi2.png', 'harga' => 28000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
                ['id' => 3, 'nama' => 'Mie Goreng Topping Istimewah', 'kategori' => 'Makanan', 'deskripsi' => 'Mie goreng lengkap dengan topping spesial untuk rasa yang lebih mantap.', 'gambar' => 'assets/images/mi3.png', 'harga' => 30000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
                ['id' => 4, 'nama' => 'Mie Kuah Udang Spesial', 'kategori' => 'Makanan', 'deskripsi' => 'Mie kuah gurih dengan udang segar dan aroma laut yang menggugah selera.', 'gambar' => 'assets/images/mi-udang.png', 'harga' => 35000, 'porsi' => '1 Orang', 'waktu' => '30 Menit'],
                ['id' => 5, 'nama' => 'Mie Kuah Spesial', 'kategori' => 'Makanan', 'deskripsi' => 'Mie kuah hangat dengan bumbu spesial yang cocok dinikmati kapan saja.', 'gambar' => 'assets/images/mi-kuah-s.png', 'harga' => 28000, 'porsi' => '1 Orang', 'waktu' => '25 Menit'],
                ['id' => 6, 'nama' => 'Es Teh', 'kategori' => 'Minuman', 'deskripsi' => 'Teh dingin menyegarkan untuk menemani setiap menu MieME.', 'gambar' => 'assets/images/esteh.png', 'harga' => 8000, 'porsi' => '1 Gelas', 'waktu' => '5 Menit'],
                ['id' => 7, 'nama' => 'Es Buah Segar', 'kategori' => 'Minuman', 'deskripsi' => 'Potongan buah segar dengan kuah manis dingin yang menyegarkan.', 'gambar' => 'assets/images/esbuah.png', 'harga' => 15000, 'porsi' => '1 Gelas', 'waktu' => '5 Menit'],
                ['id' => 8, 'nama' => 'Es Jeruk', 'kategori' => 'Minuman', 'deskripsi' => 'Jeruk segar dingin dengan rasa manis dan asam yang pas.', 'gambar' => 'assets/images/esjeruk.png', 'harga' => 10000, 'porsi' => '1 Gelas', 'waktu' => '5 Menit'],
            ];

            $menuStmt = $this->db->prepare('
                INSERT INTO menus (menu_id, category_id, menu_name, description, price, image, serving_time, `portion`, stock, is_available)
                VALUES (:menu_id, :category_id, :menu_name, :description, :price, :image, :serving_time, :portion, :stock, :is_available)
                ON DUPLICATE KEY UPDATE 
                    category_id = VALUES(category_id),
                    menu_name = VALUES(menu_name),
                    description = VALUES(description),
                    price = VALUES(price),
                    image = VALUES(image),
                    serving_time = VALUES(serving_time),
                    `portion` = VALUES(`portion`),
                    stock = VALUES(stock),
                    is_available = VALUES(is_available)
            ');

            foreach ($menus as $menu) {
                $catName = $menu['kategori'] ?? 'Makanan';
                $catId = $categoryIds[$catName] ?? 1;

                $menuStmt->execute([
                    'menu_id' => $menu['id'],
                    'category_id' => $catId,
                    'menu_name' => $menu['nama'],
                    'description' => $menu['deskripsi'] ?? '',
                    'price' => $menu['harga'],
                    'image' => $menu['gambar'] ?? null,
                    'serving_time' => $menu['waktu'] ?? '25 Menit',
                    'portion' => $menu['porsi'] ?? '1 Orang',
                    'stock' => 100,
                    'is_available' => 1,
                ]);
            }

            // 4. Seed Orders
            echo "Seeding orders, items and payments...\n";
            $orders = [
                [
                    'id' => 'ORD-20260601-001',
                    'tanggal' => '2026-06-01 10:30:00',
                    'item_ids' => [1 => 2, 6 => 2],
                    'status' => 'Selesai',
                    'penerima' => 'Budi Santoso',
                    'telepon' => '081234567890',
                    'alamat' => 'Jl. Jendral Sudirman No. 1, Jakarta',
                    'catatan' => 'Sambal dipisah.',
                    'metode' => 'QRIS',
                    'bukti' => '',
                ],
            ];

            $orderStmt = $this->db->prepare('
                INSERT INTO orders (user_id, order_number, recipient_name, recipient_phone, shipping_address, note, total_price, status, created_at, updated_at)
                VALUES (:user_id, :order_number, :recipient_name, :recipient_phone, :shipping_address, :note, :total_price, :status, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE order_id=LAST_INSERT_ID(order_id)
            ');

            $itemStmt = $this->db->prepare('
                INSERT INTO order_items (order_id, menu_id, quantity, price, subtotal)
                VALUES (:order_id, :menu_id, :quantity, :price, :subtotal)
            ');

            $paymentStmt = $this->db->prepare('
                INSERT INTO payments (order_id, payment_method, amount, payment_proof, payment_status, paid_at, created_at)
                VALUES (:order_id, :payment_method, :amount, :payment_proof, :payment_status, :paid_at, :created_at)
                ON DUPLICATE KEY UPDATE payment_id=LAST_INSERT_ID(payment_id)
            ');

            foreach ($orders as $order) {
                // Calculate total
                $total = 0;
                $itemsData = [];
                foreach ($order['item_ids'] as $menuId => $qty) {
                    $menuQ = $this->db->prepare('SELECT price FROM menus WHERE menu_id = ?');
                    $menuQ->execute([$menuId]);
                    $price = (float) $menuQ->fetchColumn();
                    if (!$price) {
                        $price = 28000.0;
                    }
                    $subtotal = $price * $qty;
                    $total += $subtotal;
                    $itemsData[] = [
                        'menu_id' => $menuId,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ];
                }

                $status = $order['status'] ?? 'Selesai';

                $orderStmt->execute([
                    'user_id' => $userId,
                    'order_number' => $order['id'],
                    'recipient_name' => $order['penerima'] ?? 'Budi Santoso',
                    'recipient_phone' => $order['telepon'] ?? '081234567890',
                    'shipping_address' => $order['alamat'] ?? 'Jl. Jendral Sudirman No. 1, Jakarta',
                    'note' => $order['catatan'] ?? '',
                    'total_price' => $total,
                    'status' => $status,
                    'created_at' => $order['tanggal'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => $order['tanggal'] ?? date('Y-m-d H:i:s'),
                ]);

                $orderDbId = (int) $this->db->lastInsertId();

                foreach ($itemsData as $item) {
                    $itemStmt->execute([
                        'order_id' => $orderDbId,
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal']
                    ]);
                }

                if (in_array($status, ['Selesai', 'Diproses', 'Dikirim'])) {
                    $paymentStmt->execute([
                        'order_id' => $orderDbId,
                        'payment_method' => $order['metode'] ?? 'QRIS',
                        'amount' => $total,
                        'payment_proof' => $order['bukti'] ?? null,
                        'payment_status' => 'Lunas',
                        'paid_at' => $order['tanggal'] ?? date('Y-m-d H:i:s'),
                        'created_at' => $order['tanggal'] ?? date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // 5. Seed FAQs
            echo "Seeding FAQs...\n";
            $faqs = [
                ['q' => 'Apakah bisa pesan online?', 'a' => 'Bisa. Pilih menu, checkout, pilih pembayaran, lalu upload bukti.'],
                ['q' => 'Metode pembayaran apa saja?', 'a' => 'Bank transfer, e-wallet, virtual account, dan QRIS.'],
                ['q' => 'Apakah data sudah dari database?', 'a' => 'Ya, versi ini sudah sepenuhnya terintegrasi dengan database MySQL/MariaDB menggunakan migrasi dan seeder.'],
            ];

            $faqStmt = $this->db->prepare('INSERT INTO faqs (question, answer) VALUES (?, ?)');
            foreach ($faqs as $faq) {
                $faqStmt->execute([$faq['q'], $faq['a']]);
            }

            // 6. Seed Payment Methods
            echo "Seeding payment methods...\n";
            $paymentMethods = [
                // Bank Transfer
                ['category' => 'Bank Transfer', 'name' => 'BCA', 'logo' => 'assets/images/payment/Bank_Central_Asia.svg'],
                ['category' => 'Bank Transfer', 'name' => 'BRI', 'logo' => 'assets/images/payment/BANK_BRI_logo.svg'],
                ['category' => 'Bank Transfer', 'name' => 'BNI', 'logo' => 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg'],
                ['category' => 'Bank Transfer', 'name' => 'Mandiri', 'logo' => 'assets/images/payment/Bank_Mandiri_logo_2016.svg'],
                // E-Wallet
                ['category' => 'E-Wallet', 'name' => 'GoPay', 'logo' => 'assets/images/payment/Gopay_logo.svg'],
                ['category' => 'E-Wallet', 'name' => 'DANA', 'logo' => 'assets/images/payment/Logo_dana_blue.svg'],
                ['category' => 'E-Wallet', 'name' => 'OVO', 'logo' => 'assets/images/payment/Logo_ovo_purple.svg'],
                ['category' => 'E-Wallet', 'name' => 'LinkAja', 'logo' => 'assets/images/payment/LinkAja.svg'],
                // Virtual Account
                ['category' => 'Virtual Account', 'name' => 'VA BCA', 'logo' => 'assets/images/payment/Bank_Central_Asia.svg'],
                ['category' => 'Virtual Account', 'name' => 'VA BRI', 'logo' => 'assets/images/payment/BANK_BRI_logo.svg'],
                ['category' => 'Virtual Account', 'name' => 'VA BNI', 'logo' => 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg'],
                ['category' => 'Virtual Account', 'name' => 'VA Mandiri', 'logo' => 'assets/images/payment/Bank_Mandiri_logo_2016.svg'],
                // QRIS
                ['category' => 'QR Payment', 'name' => 'QRIS', 'logo' => 'assets/images/payment/Logo_QRIS.svg'],
            ];

            $pmStmt = $this->db->prepare('INSERT INTO payment_methods (category, name, logo) VALUES (?, ?, ?)');
            foreach ($paymentMethods as $pm) {
                $pmStmt->execute([$pm['category'], $pm['name'], $pm['logo']]);
            }

            $this->db->commit();
            echo "Seeding completed successfully.\n";
        } catch (\Throwable $e) {
            $this->db->rollBack();
            echo "Seeding failed: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
