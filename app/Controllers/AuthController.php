<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function index(): void
    {
        if (User::current() !== null) {
            redirect('profile');
        }

        $this->view('auth', [
            'title' => 'Login MieME',
            'mode' => $_GET['mode'] ?? 'login',
        ]);
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        $mode = post('mode');
        $email = trim((string) post('email', ''));
        $password = (string) post('password', '');
        $name = trim((string) post('nama', ''));

        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['message' => 'Email dan password wajib diisi.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['message' => 'Format email tidak valid.']);
            exit;
        }

        if ($mode === 'register') {
            if (empty($name)) {
                http_response_code(400);
                echo json_encode(['message' => 'Nama lengkap wajib diisi.']);
                exit;
            }
            if (strlen($password) < 6) {
                http_response_code(400);
                echo json_encode(['message' => 'Password minimal terdiri dari 6 karakter.']);
                exit;
            }

            $success = User::register($name, $email, $password);
            if (!$success) {
                http_response_code(400);
                echo json_encode(['message' => 'Email sudah terdaftar. Silakan login.']);
                exit;
            }
        } else {
            $success = User::login($email, $password);
            if (!$success) {
                http_response_code(401);
                echo json_encode(['message' => 'Email atau password salah.']);
                exit;
            }
        }

        echo json_encode(['success' => true]);
        exit;
    }

    public function logout(): never
    {
        User::logout();
        redirect('');
    }
}
