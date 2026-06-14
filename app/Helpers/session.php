<?php

declare(strict_types=1);

function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'] = $message;
        return null;
    }

    $message = $_SESSION['_flash'] ?? null;
    unset($_SESSION['_flash']);

    return $message;
}
