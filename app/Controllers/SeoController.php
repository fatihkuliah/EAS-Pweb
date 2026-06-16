<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Menu;

class SeoController
{
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /logout\n";
        echo "Sitemap: " . absolute_url('sitemap.xml') . "\n";
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');

        $urls = [
            ['loc' => absolute_url(), 'priority' => '1.0'],
            ['loc' => absolute_url('order'), 'priority' => '0.9'],
        ];

        foreach (Menu::all() as $menu) {
            $urls[] = ['loc' => absolute_menu_url($menu), 'priority' => '0.8'];
        }

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($urls as $item) {
            echo "  <url>\n";
            echo "    <loc>" . e($item['loc']) . "</loc>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>" . e($item['priority']) . "</priority>\n";
            echo "  </url>\n";
        }
        echo "</urlset>\n";
    }
}
