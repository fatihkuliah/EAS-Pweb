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

        $this->view('menu', [
            'title' => 'Order MieME',
            'menus' => Menu::all(),
            'cart' => $cart->items(),
            'cartTotal' => $cart->total(),
            'favorites' => $_SESSION['favorites'] ?? [],
        ]);
    }

    public function detail(): void
    {
        $menu = Menu::find((int) ($_GET['id'] ?? 1)) ?? Menu::all()[0];

        $this->view('menu/detail', [
            'title' => 'Detail Menu MieME',
            'menu' => $menu,
            'isFavorite' => in_array((int) $menu['id'], $_SESSION['favorites'] ?? [], true),
        ]);
    }

    public function favorites(): void
    {
        $this->view('favorites', [
            'title' => 'Favorit MieME',
            'menus' => Menu::favorites(),
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
