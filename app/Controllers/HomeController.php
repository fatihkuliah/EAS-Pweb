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

        $seoFaqs = [
            ['q' => 'Apa yang membuat MieME berbeda dari mie lainnya?', 'a' => 'MieME dibuat dengan bahan-bahan berkualitas tinggi, memperhatikan rasa autentik dan kesehatan. Kami juga menawarkan variasi rasa yang unik.'],
            ['q' => 'Apakah MieME menggunakan bahan-bahan alami?', 'a' => 'Ya, kami menggunakan bahan-bahan alami berkualitas tinggi untuk menjaga kelezatan dan kualitas dari setiap hidangan MieME.'],
            ['q' => 'Apakah tersedia pilihan mie untuk diet khusus, seperti mie gluten-free atau vegetarian?', 'a' => 'Kami memiliki pilihan mie yang dapat disesuaikan untuk kebutuhan diet tertentu. Silakan tanyakan kepada staf kami untuk opsi yang tersedia.'],
            ['q' => 'Bagaimana cara memesan MieME?', 'a' => 'Anda dapat memesan MieME melalui situs web kami, aplikasi ponsel, atau datang langsung ke outlet kami. Pengiriman dan layanan take-away juga tersedia.'],
            ['q' => 'Apakah MieME menyediakan layanan pengiriman?', 'a' => 'Ya, kami menyediakan layanan pengiriman untuk wilayah tertentu. Mohon hubungi kami atau cek platform pengiriman kami untuk info lebih lanjut.'],
            ['q' => 'Bagaimana cara menyimpan sisa mie yang tidak habis?', 'a' => 'Saran kami adalah untuk menyimpan mie yang tidak habis dalam wadah kedap udara di dalam lemari es dan konsumsi dalam waktu 2 hari untuk menjaga kesegarannya.'],
        ];
        $description = 'MieME menyajikan mie premium dan minuman segar dengan pemesanan online yang praktis, cepat, dan cocok dinikmati bersama keluarga.';

        $this->view('home', [
            'title' => 'MieME',
            'menus' => array_slice(Menu::all(), 0, 4),
            'faqs' => $faqs,
            'seo' => [
                'title' => 'MieME - Mie Premium Siap Pesan Online',
                'description' => $description,
                'url' => absolute_url(),
                'image' => 'assets/images/Mockup.jpg',
                'schema' => [
                    organization_schema(),
                    website_schema(),
                    restaurant_schema(),
                    faq_schema($seoFaqs),
                    webpage_schema('MieME - Mie Premium Siap Pesan Online', $description, ''),
                ],
            ],
        ]);
    }

    public function notFound(): void
    {
        $this->view('errors/not-found', [
            'title' => 'Halaman Tidak Ditemukan',
            'seo' => [
                'title' => 'Halaman Tidak Ditemukan - MieME',
                'description' => 'Halaman yang anda cari belum tersedia di MieME.',
                'robots' => 'noindex, follow',
            ],
        ]);
    }
}
