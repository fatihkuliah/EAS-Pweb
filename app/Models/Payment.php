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
                $grouped[$row['category']][] = array_merge([
                    'nama' => $row['nama'],
                    'logo' => $row['logo'],
                ], self::detailFor((string) $row['nama'], (string) $row['category']));
            }
            
            if (!empty($grouped)) {
                return $grouped;
            }
        } catch (\Throwable $e) {
            // DB fallback
        }
        
        return self::fallbackMethods();
    }

    public static function choose(string $name): void
    {
        $_SESSION['payment'] = self::findMethod($name) ?? ['nama' => $name];
    }

    public static function current(): ?array
    {
        $payment = $_SESSION['payment'] ?? null;

        if (!$payment || empty($payment['nama'])) {
            return null;
        }

        return array_merge(self::findMethod((string) $payment['nama']) ?? [], $payment);
    }

    private static function fallbackMethods(): array
    {
        $methods = [
            'Bank Transfer' => [
                ['nama' => 'BCA', 'logo' => 'assets/images/payment/Bank_Central_Asia.svg'],
                ['nama' => 'BRI', 'logo' => 'assets/images/payment/BANK_BRI_logo.svg'],
                ['nama' => 'BNI', 'logo' => 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg'],
                ['nama' => 'Mandiri', 'logo' => 'assets/images/payment/Bank_Mandiri_logo_2016.svg'],
            ],
            'E-Wallet' => [
                ['nama' => 'GoPay', 'logo' => 'assets/images/payment/Gopay_logo.svg'],
                ['nama' => 'DANA', 'logo' => 'assets/images/payment/Logo_dana_blue.svg'],
                ['nama' => 'OVO', 'logo' => 'assets/images/payment/Logo_ovo_purple.svg'],
                ['nama' => 'LinkAja', 'logo' => 'assets/images/payment/LinkAja.svg'],
            ],
            'Virtual Account' => [
                ['nama' => 'VA BCA', 'logo' => 'assets/images/payment/Bank_Central_Asia.svg'],
                ['nama' => 'VA BRI', 'logo' => 'assets/images/payment/BANK_BRI_logo.svg'],
                ['nama' => 'VA BNI', 'logo' => 'assets/images/payment/Bank_Negara_Indonesia_logo_(2004).svg'],
                ['nama' => 'VA Mandiri', 'logo' => 'assets/images/payment/Bank_Mandiri_logo_2016.svg'],
            ],
            'QR Payment' => [
                ['nama' => 'QRIS', 'logo' => 'assets/images/payment/Logo_QRIS.svg'],
            ],
        ];

        foreach ($methods as $category => $items) {
            foreach ($items as $index => $item) {
                $methods[$category][$index] = array_merge($item, self::detailFor($item['nama'], $category));
            }
        }

        return $methods;
    }

    private static function findMethod(string $name): ?array
    {
        foreach (self::methods() as $category => $items) {
            foreach ($items as $item) {
                if (($item['nama'] ?? '') === $name) {
                    return array_merge(['category' => $category], $item);
                }
            }
        }

        return null;
    }

    private static function detailFor(string $name, string $category): array
    {
        $numbers = [
            'BCA' => '1234567890',
            'BRI' => '112233445566',
            'BNI' => '0099887766',
            'Mandiri' => '9000012345678',
            'GoPay' => '081234567890',
            'DANA' => '081234567891',
            'OVO' => '081234567892',
            'LinkAja' => '081234567893',
            'VA BCA' => '88081234567890',
            'VA BRI' => '26215081234567890',
            'VA BNI' => '988081234567890',
            'VA Mandiri' => '8950801234567890',
        ];

        $labels = [
            'Bank Transfer' => 'No. Rekening',
            'E-Wallet' => 'Nomor E-Wallet',
            'Virtual Account' => 'Nomor Virtual Account',
        ];

        return [
            'category' => $category,
            'number_label' => $labels[$category] ?? '',
            'number' => $numbers[$name] ?? '',
            'account_name' => $category === 'QR Payment' ? '' : 'MieME Indonesia',
        ];
    }
}
