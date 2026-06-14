<?php

declare(strict_types=1);

function url(string $path = ''): string
{
    $base = base_url();
    $path = ltrim($path, '/');

    return rtrim($base, '/') . ($path ? '/' . $path : '/');
}

function asset(string $path): string
{
    return url($path);
}

function base_url(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $base = rtrim(dirname($script), '/');

    return $base === '' ? '' : $base;
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function post(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}
