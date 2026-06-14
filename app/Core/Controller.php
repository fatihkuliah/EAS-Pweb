<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = base_path('app/Views/' . $view . '/page.php');

        if (!is_file($viewFile)) {
            $viewFile = base_path('app/Views/' . $view . '.php');
        }

        require base_path('app/Views/layout.php');
    }
}
