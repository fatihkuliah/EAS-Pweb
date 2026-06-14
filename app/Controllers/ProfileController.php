<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(): void
    {
        $this->view('profile', [
            'title' => 'Profil MieME',
            'user' => User::current() ?? [
                'nama' => '',
                'email' => '',
                'telepon' => '',
                'alamat' => '',
                'avatar' => 'assets/images/user.png',
            ],
            'message' => flash(),
        ]);
    }

    public function update(): never
    {
        User::save($_POST);
        flash('Profil berhasil disimpan.');
        redirect('profile');
    }
}
