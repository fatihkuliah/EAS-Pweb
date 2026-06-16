<?php

declare(strict_types=1);

return new class {
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS orders (
                order_id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                order_number VARCHAR(50) UNIQUE NOT NULL,
                recipient_name VARCHAR(255) NOT NULL,
                recipient_phone VARCHAR(20) NOT NULL,
                shipping_address TEXT NOT NULL,
                note TEXT DEFAULT NULL,
                total_price DECIMAL(10, 2) NOT NULL,
                status ENUM('Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai', 'Batal', 'paid') DEFAULT 'Menunggu Pembayaran',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS orders;");
    }
};
