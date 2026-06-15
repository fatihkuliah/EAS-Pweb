<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function current(): ?array
    {
        if (!isset($_SESSION['user']['user_id'])) {
            return $_SESSION['user'] ?? null;
        }

        try {
            $db = Database::connect();
            $stmt = $db->prepare('SELECT * FROM users WHERE user_id = ?');
            $stmt->execute([$_SESSION['user']['user_id']]);
            $user = $stmt->fetch();

            if ($user) {
                // Map db columns to existing view keys if necessary
                return [
                    'user_id' => (int) $user['user_id'],
                    'nama' => $user['name'],
                    'email' => $user['email'],
                    'telepon' => $user['phone'],
                    'alamat' => $user['address'],
                    'avatar' => $user['profile_image'] ?: 'assets/images/user.png',
                    // Keep db columns
                    'name' => $user['name'],
                    'email_address' => $user['email'],
                    'phone' => $user['phone'],
                    'address' => $user['address'],
                    'profile_image' => $user['profile_image']
                ];
            }
        } catch (\Throwable $e) {
            // Silence DB exception and fallback to session if DB is not configured yet
        }

        return $_SESSION['user'] ?? null;
    }

    public static function save(array $data): void
    {
        $name = trim((string) ($data['nama'] ?? $data['name'] ?? 'Customer MieME'));
        $email = trim((string) ($data['email'] ?? 'customer@mieme.test'));
        $phone = trim((string) ($data['telepon'] ?? $data['phone'] ?? '081234567890'));
        $address = trim((string) ($data['alamat'] ?? $data['address'] ?? 'Jl. Jendral Sudirman No. 1, Jakarta'));
        $avatar = $data['avatar'] ?? $data['profile_image'] ?? 'assets/images/user.png';

        $userId = null;

        try {
            $db = Database::connect();
            
            // Check if user already exists
            $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $existing = $stmt->fetch();

            if ($existing) {
                $updateStmt = $db->prepare('
                    UPDATE users 
                    SET name = ?, phone = ?, address = ?, profile_image = ? 
                    WHERE email = ?
                ');
                $updateStmt->execute([$name, $phone, $address, $avatar, $email]);
                $userId = (int) $existing['user_id'];
            } else {
                $insertStmt = $db->prepare('
                    INSERT INTO users (name, email, password_hash, phone, address, profile_image)
                    VALUES (?, ?, ?, ?, ?, ?)
                ');
                $insertStmt->execute([$name, $email, password_hash('password', PASSWORD_DEFAULT), $phone, $address, $avatar]);
                $userId = (int) $db->lastInsertId();
            }
        } catch (\Throwable $e) {
            // DB error (e.g. not migrated yet) - keep fallback session logic
        }

        $_SESSION['user'] = [
            'user_id' => $userId,
            'nama' => $name,
            'email' => $email,
            'telepon' => $phone,
            'alamat' => $address,
            'avatar' => $avatar,
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }
}
