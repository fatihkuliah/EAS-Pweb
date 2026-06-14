<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home', [
            'title' => 'MieME',
            'menus' => array_slice(Menu::all(), 0, 4),
            'faqs' => data_file('faqs'),
        ]);
    }

    public function notFound(): void
    {
        $this->view('errors/not-found', ['title' => 'Halaman Tidak Ditemukan']);
    }
}
