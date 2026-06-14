<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function methods(): array
    {
        return Payment::methods();
    }

    public function choose(string $name): void
    {
        Payment::choose($name);
    }

    public function current(): ?array
    {
        return Payment::current();
    }

    public function uploadReceipt(array $file): string
    {
        return upload_file($file, 'uploads', ['jpg', 'jpeg', 'png']);
    }
}
