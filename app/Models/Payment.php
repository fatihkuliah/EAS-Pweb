<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Payment
{
    public static function methods(): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->query('SELECT category, name AS nama, logo FROM payment_methods ORDER BY payment_method_id ASC');
            $rows = $stmt->fetchAll();
            
            $grouped = [];
            foreach ($rows as $row) {
                $grouped[$row['category']][] = [
                    'nama' => $row['nama'],
                    'logo' => $row['logo']
                ];
            }
            
            if (!empty($grouped)) {
                return $grouped;
            }
        } catch (\Throwable $e) {
            // DB fallback
        }
        
        return [];
    }

    public static function choose(string $name): void
    {
        $_SESSION['payment'] = ['nama' => $name];
    }

    public static function current(): ?array
    {
        return $_SESSION['payment'] ?? null;
    }
}
