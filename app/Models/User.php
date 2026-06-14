<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    public static function current(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function save(array $data): void
    {
        $_SESSION['user'] = [
            'nama' => trim((string) ($data['nama'] ?? 'Customer MieME')),
            'email' => trim((string) ($data['email'] ?? 'customer@mieme.test')),
            'telepon' => trim((string) ($data['telepon'] ?? '081234567890')),
            'alamat' => trim((string) ($data['alamat'] ?? 'Jl. Jendral Sudirman No. 1, Jakarta')),
            'avatar' => $data['avatar'] ?? 'assets/images/user.png',
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }
}
