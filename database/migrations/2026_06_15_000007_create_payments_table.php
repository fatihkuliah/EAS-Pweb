<?php

declare(strict_types=1);

return new class {
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS payments (
                payment_id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT UNIQUE NOT NULL,
                payment_method ENUM('BCA', 'BRI', 'BNI', 'Mandiri', 'GoPay', 'DANA', 'OVO', 'LinkAja', 'VA BCA', 'VA BRI', 'VA BNI', 'VA Mandiri', 'QRIS') NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                payment_proof VARCHAR(255) DEFAULT NULL,
                payment_status ENUM('Menunggu Konfirmasi', 'Lunas', 'Gagal') DEFAULT 'Menunggu Konfirmasi',
                paid_at TIMESTAMP NULL DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS payments;");
    }
};
