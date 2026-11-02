<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= html($title) ?></title>
        <script src="/site-theme.js"></script>
        <link rel="stylesheet" href="/styles.css" />
        <?php if (($stylesheet ?? "/carrito.css") !== "/styles.css"): ?>
            <link rel="stylesheet" href="<?= html($stylesheet ?? "/carrito.css") ?>" />
        <?php endif; ?>
        <link rel="stylesheet" href="/site-theme.css" />
    </head>
    <body>
       <?= $content ?>
    </body>
</html>
