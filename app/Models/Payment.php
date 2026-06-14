<?php

declare(strict_types=1);

namespace App\Models;

class Payment
{
    public static function methods(): array
    {
        return data_file('payment_methods');
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
