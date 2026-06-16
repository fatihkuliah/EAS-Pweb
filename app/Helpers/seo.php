<?php

declare(strict_types=1);

function absolute_url(string $path = ''): string
{
    if (preg_match('/^https?:\/\//i', $path) === 1) {
        return $path;
    }

    $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
    if (!$scheme) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = ltrim($path, '/');
    $base = rtrim(base_url(), '/');

    return $scheme . '://' . $host . $base . ($path ? '/' . $path : '/');
}

function current_canonical_url(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = base_url();

    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base));
    }

    return absolute_url(trim($path, '/'));
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'menu';
}

function menu_slug(array $menu): string
{
    return slugify((string) ($menu['nama'] ?? $menu['menu_name'] ?? 'menu'));
}

function menu_url(array $menu): string
{
    return url('menu/' . menu_slug($menu));
}

function absolute_menu_url(array $menu): string
{
    return absolute_url('menu/' . menu_slug($menu));
}

function default_seo(): array
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = base_url();
    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base));
    }
    $path = trim($path, '/');
    $privatePrefixes = ['admin', 'auth', 'checkout', 'detail-order', 'payment', 'qris', 'upload-payment', 'orders', 'profile', 'review', 'favorites'];
    $robots = 'index, follow';

    foreach ($privatePrefixes as $prefix) {
        if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
            $robots = 'noindex, follow';
            break;
        }
    }

    return [
        'title' => 'MieME - Mie Premium Siap Pesan',
        'description' => 'MieME menyajikan mie premium, minuman segar, dan pengalaman pesan online yang praktis untuk dinikmati bersama keluarga.',
        'image' => 'assets/images/Mockup.png',
        'image_width' => 1920,
        'image_height' => 1080,
        'type' => 'website',
        'robots' => $robots,
        'url' => current_canonical_url(),
        'site_name' => 'MieME',
    ];
}

function normalize_seo(array $seo = []): array
{
    $data = array_merge(default_seo(), $seo);
    $data['title'] = trim((string) $data['title']);
    $data['description'] = trim((string) $data['description']);
    $data['url'] = absolute_url((string) $data['url']);
    $data['image'] = absolute_url((string) $data['image']);

    return $data;
}

function render_meta(array $seo = []): string
{
    $seo = normalize_seo($seo);
    $tags = [
        '<title>' . e($seo['title']) . '</title>',
        '<meta name="description" content="' . e($seo['description']) . '" />',
        '<meta name="robots" content="' . e($seo['robots']) . '" />',
        '<link rel="canonical" href="' . e($seo['url']) . '" />',
        '<meta property="og:title" content="' . e($seo['title']) . '" />',
        '<meta property="og:description" content="' . e($seo['description']) . '" />',
        '<meta property="og:image" content="' . e($seo['image']) . '" />',
        '<meta property="og:image:width" content="' . e((string) $seo['image_width']) . '" />',
        '<meta property="og:image:height" content="' . e((string) $seo['image_height']) . '" />',
        '<meta property="og:url" content="' . e($seo['url']) . '" />',
        '<meta property="og:type" content="' . e($seo['type']) . '" />',
        '<meta property="og:site_name" content="' . e($seo['site_name']) . '" />',
        '<meta property="og:locale" content="id_ID" />',
        '<meta name="twitter:card" content="summary_large_image" />',
        '<meta name="twitter:title" content="' . e($seo['title']) . '" />',
        '<meta name="twitter:description" content="' . e($seo['description']) . '" />',
        '<meta name="twitter:image" content="' . e($seo['image']) . '" />',
    ];

    if (!empty($seo['schema'])) {
        foreach ((array) $seo['schema'] as $schema) {
            $tags[] = '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        }
    }

    return implode("\n    ", $tags);
}

function image_tag(string $src, string $alt, int $width, int $height, array $attrs = []): string
{
    $attrs = array_merge([
        'src' => asset($src),
        'alt' => $alt,
        'width' => (string) $width,
        'height' => (string) $height,
        'loading' => 'lazy',
        'decoding' => 'async',
    ], $attrs);

    $html = '<img';
    foreach ($attrs as $key => $value) {
        if ($value === null || $value === false) {
            continue;
        }
        $html .= ' ' . e((string) $key) . '="' . e((string) $value) . '"';
    }

    return $html . ' />';
}

function organization_schema(): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'MieME',
        'url' => absolute_url(),
        'logo' => absolute_url('assets/images/favicon/android-chrome-512x512.png'),
        'image' => absolute_url('assets/images/Mockup.png'),
    ];
}

function website_schema(): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'MieME',
        'url' => absolute_url(),
    ];
}

function restaurant_schema(): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => ['Restaurant', 'LocalBusiness'],
        'name' => 'MieME',
        'url' => absolute_url(),
        'image' => absolute_url('assets/images/Mockup.png'),
        'servesCuisine' => ['Mie', 'Indonesian'],
        'priceRange' => 'Rp8.000-Rp35.000',
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.8',
            'reviewCount' => '2',
        ],
    ];
}

function webpage_schema(string $title, string $description, string $url): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $title,
        'description' => $description,
        'url' => absolute_url($url),
        'isPartOf' => [
            '@type' => 'WebSite',
            'name' => 'MieME',
            'url' => absolute_url(),
        ],
    ];
}

function product_schema(array $menu): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $menu['nama'],
        'description' => $menu['deskripsi'],
        'image' => absolute_url($menu['gambar']),
        'category' => $menu['kategori'],
        'brand' => [
            '@type' => 'Brand',
            'name' => 'MieME',
        ],
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'IDR',
            'price' => (string) $menu['harga'],
            'availability' => 'https://schema.org/InStock',
            'url' => absolute_menu_url($menu),
        ],
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.8',
            'reviewCount' => '2',
        ],
        'review' => [
            [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => 'Monkey D Luffy'],
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '5'],
                'reviewBody' => 'Rasanya autentik dan bumbunya pas.',
            ],
        ],
    ];
}

function breadcrumb_schema(array $items): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_map(
            fn (array $item, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => absolute_url($item['url']),
            ],
            $items,
            array_keys($items)
        ),
    ];
}

function faq_schema(array $faqs): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn (array $faq): array => [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['a'],
            ],
        ], $faqs),
    ];
}
