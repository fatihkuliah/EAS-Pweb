<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($title ?? 'MieME') ?></title>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('assets/images/favicon/apple-touch-icon.png') ?>" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('assets/images/favicon/favicon-32x32.png') ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('assets/images/favicon/favicon-16x16.png') ?>" />
    <link rel="manifest" href="<?= asset('assets/images/favicon/site.webmanifest') ?>" />
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700&family=Montserrat:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script>
      window.MieMEDatabaseData = {
          menus: <?= json_encode(\App\Models\Menu::all()) ?>,
          user: <?= json_encode(\App\Models\User::current()) ?>,
          orders: <?= json_encode(\App\Models\Order::all()) ?>,
          favorites: <?= json_encode(\App\Models\Menu::favorites()) ?>,
          cart: <?= json_encode(\App\Models\Cart::items()) ?>,
          cartTotal: <?= json_encode(\App\Models\Cart::total()) ?>,
      };
    </script>
  </head>
  <body style="font-family: Montserrat; background-color: #101010">
    <?php require $viewFile; ?>
  </body>
</html>
