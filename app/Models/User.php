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
                return [
                    'user_id' => (int) $user['user_id'],
                    'nama' => $user['name'],
                    'email' => $user['email'],
                    'telepon' => $user['phone'],
                    'alamat' => $user['address'],
                    'name' => $user['name'],
                    'email_address' => $user['email'],
                    'phone' => $user['phone'],
                    'address' => $user['address'],
                    'role' => $user['role'] ?: 'customer',
                ];
            }
        } catch (\Throwable $e) {
            // DB fallback
        }

        return $_SESSION['user'] ?? null;
    }

    public static function register(string $name, string $email, string $password): bool
    {
        try {
            $db = Database::connect();
            
            // Check if email already exists
            $stmt = $db->prepare('SELECT 1 FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return false;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $phone = '';
            $address = '';
            $role = 'customer';

            $insertStmt = $db->prepare('
                INSERT INTO users (name, email, password_hash, phone, address, role)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            $insertStmt->execute([$name, $email, $hash, $phone, $address, $role]);
            $userId = (int) $db->lastInsertId();

            session_regenerate_id(true);
            $_SESSION['user'] = [
                'user_id' => $userId,
                'nama' => $name,
                'email' => $email,
                'telepon' => $phone,
                'alamat' => $address,
                'role' => $role,
            ];

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function login(string $email, string $password): bool
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'user_id' => (int) $user['user_id'],
                    'nama' => $user['name'],
                    'email' => $user['email'],
                    'telepon' => $user['phone'] ?: '',
                    'alamat' => $user['address'] ?: '',
                    'role' => $user['role'] ?: 'customer',
                ];
                return true;
            }
        } catch (\Throwable $e) {
            // DB fallback
        }

        return false;
    }

    public static function save(array $data): void
    {
        $name = trim((string) ($data['nama'] ?? $data['name'] ?? 'Customer MieME'));
        $email = trim((string) ($data['email'] ?? 'customer@mieme.test'));
        $phone = trim((string) ($data['telepon'] ?? $data['phone'] ?? '081234567890'));
        $address = trim((string) ($data['alamat'] ?? $data['address'] ?? 'Jl. Jendral Sudirman No. 1, Jakarta'));
        $role = $data['role'] ?? null;

        $userId = null;
        $finalRole = 'customer';

        try {
            $db = Database::connect();
            
            // Check if user already exists
            $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $existing = $stmt->fetch();

            if ($existing) {
                $finalRole = $role ?? $existing['role'] ?? 'customer';
                $updateStmt = $db->prepare('
                    UPDATE users 
                    SET name = ?, phone = ?, address = ?, role = ? 
                    WHERE email = ?
                ');
                $updateStmt->execute([$name, $phone, $address, $finalRole, $email]);
                $userId = (int) $existing['user_id'];
            } else {
                $finalRole = $role ?? 'customer';
                $insertStmt = $db->prepare('
                    INSERT INTO users (name, email, password_hash, phone, address, role)
                    VALUES (?, ?, ?, ?, ?, ?)
                ');
                $insertStmt->execute([$name, $email, password_hash('password', PASSWORD_DEFAULT), $phone, $address, $finalRole]);
                $userId = (int) $db->lastInsertId();
            }
        } catch (\Throwable $e) {
            // DB fallback
        }

        $_SESSION['user'] = [
            'user_id' => $userId,
            'nama' => $name,
            'email' => $email,
            'telepon' => $phone,
            'alamat' => $address,
            'role' => $finalRole,
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
        session_start();
    }
}
