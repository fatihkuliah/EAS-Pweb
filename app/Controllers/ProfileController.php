<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(): void
    {
        if (User::current() === null) {
            redirect('auth');
        }

        $this->view('profile', [
            'title' => 'Profil MieME',
            'user' => User::current(),
            'message' => flash(),
        ]);
    }

    public function update(): never
    {
        if (User::current() === null) {
            redirect('auth');
        }

        User::save($_POST);
        flash('Profil berhasil disimpan.');
        redirect('profile');
    }
}
