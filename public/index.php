<?php

declare(strict_types=1);

session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

define('BASE_PATH', dirname(__DIR__));

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$staticFile = __DIR__ . '/' . ltrim(rawurldecode($path), '/');

if (strpos($path, '/storage/') === 0) {
    $storageFile = BASE_PATH . '/' . ltrim(rawurldecode($path), '/');
    if (is_file($storageFile)) {
        $extension = strtolower(pathinfo($storageFile, PATHINFO_EXTENSION));
        $types = [
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
        ];
        $type = $types[$extension] ?? 'application/octet-stream';
        header('Content-Type: ' . $type);
        header('Content-Length: ' . filesize($storageFile));
        readfile($storageFile);
        exit;
    }
}

if (PHP_SAPI === 'cli-server' && is_file($staticFile) && pathinfo($staticFile, PATHINFO_EXTENSION) !== 'php') {
    $extension = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    $types = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'ico' => 'image/x-icon',
        'webmanifest' => 'application/manifest+json',
    ];
    $type = $types[$extension] ?? (mime_content_type($staticFile) ?: 'application/octet-stream');
    header('Content-Type: ' . $type);
    header('Content-Length: ' . filesize($staticFile));

    if ($_SERVER['REQUEST_METHOD'] !== 'HEAD') {
        readfile($staticFile);
    }

    return true;
}

require BASE_PATH . '/app/helpers.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $path = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

    if (is_file($path)) {
        require $path;
    }
});
$route = trim(str_replace(base_url(), '', $path), '/');
$method = $_SERVER['REQUEST_METHOD'] === 'HEAD' ? 'GET' : $_SERVER['REQUEST_METHOD'];
$routes = require BASE_PATH . '/config/routes.php';

$handler = $routes[$method][$route] ?? null;

if ($handler === null && $method === 'GET' && preg_match('#^menu/([^/]+)$#', $route) === 1) {
    $handler = [\App\Controllers\MenuController::class, 'detail'];
}

if ($handler === null) {
    http_response_code(404);
    (new \App\Controllers\HomeController())->notFound();
    exit;
}

[$controller, $action] = $handler;
(new $controller())->$action();
