<?php

declare(strict_types=1);

$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    throw new RuntimeException("Configuration error: '.env' file is missing. Please copy '.env.example' to '.env' and set up your database credentials.");
}

$env = parse_ini_file($envFile);
if ($env === false) {
    throw new RuntimeException("Configuration error: Failed to parse '.env' file.");
}

$requiredKeys = ['DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
foreach ($requiredKeys as $key) {
    if (!isset($env[$key])) {
        throw new RuntimeException("Configuration error: Missing required environment variable '{$key}' in '.env'.");
    }
}

return [
    'driver' => $env['DB_CONNECTION'],
    'host' => $env['DB_HOST'],
    'database' => $env['DB_DATABASE'],
    'username' => $env['DB_USERNAME'],
    'password' => $env['DB_PASSWORD'] ?? '',
];
