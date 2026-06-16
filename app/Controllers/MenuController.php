<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Menu;
use App\Services\CartService;

class MenuController extends Controller
{
    public function index(): void
    {
        $cart = new CartService();
        $description = 'Lihat katalog menu MieME, mulai dari mie signature sampai minuman segar, lalu pesan langsung dari keranjang online.';

        $this->view('menu', [
            'title' => 'Order MieME',
            'menus' => Menu::all(),
            'cart' => $cart->items(),
            'cartTotal' => $cart->total(),
            'favorites' => $_SESSION['favorites'] ?? [],
            'seo' => [
                'title' => 'Menu MieME - Pesan Mie dan Minuman Online',
                'description' => $description,
                'url' => absolute_url('order'),
                'schema' => [
                    webpage_schema('Menu MieME - Pesan Mie dan Minuman Online', $description, 'order'),
                    breadcrumb_schema([
                        ['name' => 'Home', 'url' => ''],
                        ['name' => 'Menu', 'url' => 'order'],
                    ]),
                ],
            ],
        ]);
    }

    public function detail(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        $base = base_url();
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $path = trim($path, '/');
        $slug = str_starts_with($path, 'menu/') ? substr($path, 5) : '';
        $menu = $slug !== '' ? Menu::findBySlug($slug) : Menu::find((int) ($_GET['id'] ?? 1));
        $menu ??= Menu::all()[0];

        if ($slug === '') {
            header('Location: ' . menu_url($menu), true, 301);
            exit;
        }

        $title = $menu['nama'] . ' - Menu MieME';
        $description = $menu['deskripsi'] . ' Pesan sekarang di MieME dengan harga ' . rupiah($menu['harga']) . '.';

        $this->view('menu/detail', [
            'title' => $title,
            'menu' => $menu,
            'isFavorite' => in_array((int) $menu['id'], $_SESSION['favorites'] ?? [], true),
            'seo' => [
                'title' => $title,
                'description' => $description,
                'url' => absolute_menu_url($menu),
                'image' => $menu['gambar'],
                'image_width' => 234,
                'image_height' => 325,
                'type' => 'product',
                'schema' => [
                    product_schema($menu),
                    breadcrumb_schema([
                        ['name' => 'Home', 'url' => ''],
                        ['name' => 'Menu', 'url' => 'order'],
                        ['name' => $menu['nama'], 'url' => 'menu/' . menu_slug($menu)],
                    ]),
                    webpage_schema($title, $description, 'menu/' . menu_slug($menu)),
                ],
            ],
        ]);
    }

    public function favorites(): void
    {
        $this->view('favorites', [
            'title' => 'Favorit MieME',
            'menus' => Menu::favorites(),
            'seo' => [
                'title' => 'Favorit MieME',
                'description' => 'Daftar menu MieME yang anda simpan sebagai favorit.',
                'url' => absolute_url('favorites'),
                'robots' => 'noindex, follow',
            ],
        ]);
    }

    public function toggleFavorite(): void
    {
        Menu::toggleFavorite((int) post('id'));

        if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'favorites' => Menu::favorites()
            ]);
            exit;
        }

        redirect((string) post('back', 'order'));
    }
}
