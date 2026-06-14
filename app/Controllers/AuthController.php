<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function index(): void
    {
        $this->view('auth', [
            'title' => 'Login MieME',
            'mode' => $_GET['mode'] ?? 'login',
        ]);
    }

    public function store(): never
    {
        $name = post('mode') === 'register' ? post('nama', 'Customer MieME') : 'Customer MieME';

        User::save([
            'nama' => $name,
            'email' => post('email', 'customer@mieme.test'),
            'telepon' => '081234567890',
            'alamat' => 'Jl. Jendral Sudirman No. 1, Jakarta',
            'avatar' => 'assets/images/user.png',
        ]);

        redirect('profile');
    }

    public function logout(): never
    {
        User::logout();
        redirect('');
    }
}
