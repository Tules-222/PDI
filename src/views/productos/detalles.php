<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    nombre del producto : <?= htmlspecialchars((string) $nombre, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
    precio del producto : <?= htmlspecialchars((string) $precio, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
    descripcion del producto : <?= htmlspecialchars((string) $descripcion, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
</body>
</html>