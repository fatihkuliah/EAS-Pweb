<?php

declare(strict_types=1);

return new class {
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS cart_items (
                cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                menu_id INT NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (menu_id) REFERENCES menus(menu_id) ON DELETE CASCADE ON UPDATE CASCADE,
                UNIQUE KEY user_menu_cart (user_id, menu_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS cart_items;");
    }
};
