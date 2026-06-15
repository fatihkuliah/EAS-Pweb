<?php

declare(strict_types=1);

return new class {
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS menus (
                menu_id INT AUTO_INCREMENT PRIMARY KEY,
                category_id INT NOT NULL,
                menu_name VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                price DECIMAL(10, 2) NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                serving_time VARCHAR(50) DEFAULT NULL,
                `portion` VARCHAR(50) DEFAULT NULL,
                stock INT DEFAULT 0,
                is_available BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS menus;");
    }
};
