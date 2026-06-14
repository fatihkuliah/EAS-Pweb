<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;

class CheckoutService
{
    public function current(): ?array
    {
        return $_SESSION['checkout'] ?? null;
    }

    public function save(array $data): void
    {
        $_SESSION['checkout'] = [
            'items' => Cart::items(),
            'total' => Cart::total(),
            'penerima' => trim((string) ($data['penerima'] ?? '')),
            'telepon' => trim((string) ($data['telepon'] ?? '')),
            'alamat' => trim((string) ($data['alamat'] ?? '')),
            'catatan' => trim((string) ($data['catatan'] ?? '')),
            'tanggal' => date('Y-m-d H:i:s'),
        ];
    }

    public function clear(): void
    {
        unset($_SESSION['checkout'], $_SESSION['payment']);
    }
}
