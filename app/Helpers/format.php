<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function rupiah(int|float $amount): string
{
    return 'Rp' . number_format((float) $amount, 0, ',', '.');
}
