<?php

declare(strict_types=1);

function upload_file(array $file, string $folder, array $allowedExtensions): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return '';
    }

    $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        return '';
    }

    $targetDir = storage_path($folder);

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    $prefix = $folder === 'menus' ? 'menu' : 'receipt';
    $filename = $prefix . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(3)) . '.' . $extension;
    $target = $targetDir . '/' . $filename;

    return move_uploaded_file($file['tmp_name'], $target) ? $folder . '/' . $filename : '';
}

function storage_public_path(?string $path): string
{
    $path = trim((string) $path);

    if ($path === '') {
        return '';
    }

    if (
        str_starts_with($path, 'storage/')
        || str_starts_with($path, 'assets/')
        || str_starts_with($path, '/')
        || preg_match('/^https?:\/\//i', $path) === 1
    ) {
        return $path;
    }

    return 'storage/' . ltrim($path, '/');
}
