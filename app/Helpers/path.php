<?php

declare(strict_types=1);

function base_path(string $path = ''): string
{
    return BASE_PATH . ($path ? '/' . ltrim($path, '/') : '');
}

function storage_path(string $path = ''): string
{
    return base_path('storage' . ($path ? '/' . ltrim($path, '/') : ''));
}

