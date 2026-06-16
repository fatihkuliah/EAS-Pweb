<?php

declare(strict_types=1);

function flash(?string $message = null, string $type = 'success'): ?string
{
    if ($message !== null) {
        $_SESSION[$type === 'error' ? 'error' : 'flash'] = $message;
        return null;
    }

    $message = $_SESSION['_flash'] ?? $_SESSION['flash'] ?? $_SESSION['error'] ?? null;
    unset($_SESSION['_flash']);
    unset($_SESSION['flash'], $_SESSION['error']);

    return $message;
}
