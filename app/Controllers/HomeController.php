<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index(): void
    {
        $faqs = [];
        try {
            $db = Database::connect();
            $stmt = $db->query('SELECT question AS q, answer AS a FROM faqs ORDER BY faq_id ASC');
            $faqs = $stmt->fetchAll();
        } catch (\Throwable $e) {
            // fallback if db not configured yet
        }

        $this->view('home', [
            'title' => 'MieME',
            'menus' => array_slice(Menu::all(), 0, 4),
            'faqs' => $faqs,
        ]);
    }

    public function notFound(): void
    {
        $this->view('errors/not-found', ['title' => 'Halaman Tidak Ditemukan']);
    }
}
