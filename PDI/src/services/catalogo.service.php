<?php

declare(strict_types=1);

require_once __DIR__ . '/../persistence/publicaciones.persistence.php';

function obtener_productos_catalogo(): array
{
    $productos = [
        [
            'id' => 1,
            'nombre' => 'Taza de cerámica artesanal esmaltada azul',
            'seller' => 'Barro & Fuego',
            'categoria' => 'Hogar',
            'descripcion' => 'Taza de cerámica artesanal esmaltada en azul, hecha para acompañar tus momentos de café o té.',
            'caracteristicas' => ['Cerámica artesanal', 'Esmaltado azul', 'Envío gratis'],
            'precio' => 32900,
            'originalPrice' => 41000,
            'bestSeller' => true,
            'freeShipping' => true,
            'rating' => 4.9,
            'reviews' => 214,
            'images' => [
                ['src' => '/images/taza-1.png', 'alt' => 'Taza de cerámica azul marino sobre fondo claro'],
                ['src' => '/images/taza-2.png', 'alt' => 'Taza de cerámica azul con café sobre una mesa de madera'],
            ],
        ],
        [
            'id' => 2,
            'nombre' => 'Vela aromática de soya en frasco ámbar',
            'seller' => 'Luz de Campo',
            'categoria' => 'Bienestar',
            'descripcion' => 'Vela aromática elaborada con cera de soya y presentada en un frasco de vidrio ámbar.',
            'caracteristicas' => ['Cera de soya', 'Frasco de vidrio ámbar', 'Ideal para ambientar espacios'],
            'precio' => 28500,
            'bestSeller' => true,
            'rating' => 4.8,
            'reviews' => 389,
            'images' => [
                ['src' => '/images/vela-1.png', 'alt' => 'Vela de soya en frasco de vidrio ámbar'],
                ['src' => '/images/vela-2.png', 'alt' => 'Vela encendida sobre una mesa de noche'],
            ],
        ],
        [
            'id' => 3,
            'nombre' => 'Billetera de cuero cosida a mano',
            'seller' => 'Taller Raíz',
            'categoria' => 'Accesorios',
            'descripcion' => 'Billetera de cuero cosida a mano, práctica para organizar tus tarjetas y dinero.',
            'caracteristicas' => ['Cuero', 'Cosida a mano', 'Envío gratis'],
            'precio' => 89900,
            'originalPrice' => 115000,
            'freeShipping' => true,
            'rating' => 4.7,
            'reviews' => 96,
            'images' => [
                ['src' => '/images/billetera-1.png', 'alt' => 'Billetera de cuero marrón cerrada'],
                ['src' => '/images/billetera-2.png', 'alt' => 'Billetera de cuero abierta mostrando ranuras para tarjetas'],
            ],
        ],
        [
            'id' => 4,
            'nombre' => 'Café de especialidad en grano 500 g',
            'seller' => 'Finca Alta',
            'categoria' => 'Alimentos',
            'descripcion' => 'Café de especialidad en grano, seleccionado por Finca Alta para disfrutar recién molido.',
            'caracteristicas' => ['Café de especialidad', 'Presentación de 500 g', 'En grano', 'Envío gratis'],
            'precio' => 42000,
            'originalPrice' => 48000,
            'bestSeller' => true,
            'freeShipping' => true,
            'rating' => 4.9,
            'reviews' => 527,
            'images' => [
                ['src' => '/images/cafe-1.png', 'alt' => 'Bolsa kraft de café de especialidad'],
                ['src' => '/images/cafe-2.png', 'alt' => 'Granos de café tostado en una cuchara de madera'],
            ],
        ],
        [
            'id' => 5,
            'nombre' => 'Set de 4 jabones naturales con lavanda',
            'seller' => 'Botánica Viva',
            'categoria' => 'Bienestar',
            'descripcion' => 'Set de cuatro jabones naturales con lavanda, preparados por Botánica Viva.',
            'caracteristicas' => ['Set de 4 jabones', 'Ingredientes naturales', 'Con lavanda'],
            'precio' => 36000,
            'rating' => 4.6,
            'reviews' => 142,
            'images' => [
                ['src' => '/images/jabones.png', 'alt' => 'Cuatro jabones artesanales apilados con lavanda'],
            ],
        ],
        [
            'id' => 6,
            'nombre' => 'Colgante de macramé para plantas',
            'seller' => 'Nudos del Sur',
            'categoria' => 'Decoración',
            'descripcion' => 'Colgante de macramé para exhibir tus plantas y sumar un detalle artesanal a tus espacios.',
            'caracteristicas' => ['Macramé', 'Hecho para plantas', 'Detalle decorativo'],
            'precio' => 45500,
            'originalPrice' => 52000,
            'rating' => 4.8,
            'reviews' => 73,
            'images' => [
                ['src' => '/images/macrame.png', 'alt' => 'Colgante de macramé con una maceta y planta'],
            ],
        ],
        [
            'id' => 7,
            'nombre' => 'Tabla de cortar en madera de nogal',
            'seller' => 'Madera Noble',
            'categoria' => 'Cocina',
            'descripcion' => 'Tabla de cortar en madera de nogal con correa de cuero, funcional para tu cocina y tu mesa.',
            'caracteristicas' => ['Madera de nogal', 'Correa de cuero', 'Envío gratis'],
            'precio' => 74900,
            'freeShipping' => true,
            'rating' => 4.9,
            'reviews' => 58,
            'images' => [
                ['src' => '/images/tabla.png', 'alt' => 'Tabla de cortar de madera de nogal con correa de cuero'],
            ],
        ],
        [
            'id' => 8,
            'nombre' => 'Aretes de plata 925 con piedra azul',
            'seller' => 'Joyas Alma',
            'categoria' => 'Accesorios',
            'descripcion' => 'Aretes de plata 925 con piedra azul, elaborados por Joyas Alma.',
            'caracteristicas' => ['Plata 925', 'Piedra azul', 'Diseño artesanal'],
            'precio' => 64000,
            'bestSeller' => true,
            'rating' => 4.7,
            'reviews' => 181,
            'images' => [
                ['src' => '/images/aretes.png', 'alt' => 'Par de aretes de plata con piedras azules'],
            ],
        ],
    ];

    foreach (listar_publicaciones_catalogo() as $publicacion) {
        $id = (int) $publicacion['id'];
        $nombre = (string) $publicacion['nombre'];
        $vendedor = (string) $publicacion['vendedor'];
        $stock = (int) $publicacion['stock'];
        $productos[] = [
            'id' => $id,
            'cartId' => 'publicacion-' . $id,
            'url' => '/publicaciones/' . $id,
            'nombre' => $nombre,
            'seller' => $vendedor,
            'categoria' => 'Publicación',
            'descripcion' => 'Producto publicado por ' . $vendedor . '.',
            'caracteristicas' => ['Unidades disponibles: ' . $stock],
            'precio' => (float) $publicacion['precio'],
            'stock' => $stock,
            'images' => imagenes_publicacion($publicacion, $nombre),
        ];
    }

    return $productos;
}

function imagenes_publicacion(array $publicacion, string $nombre): array
{
    $path = $publicacion['imagen_path'] ?? null;
    if (!is_string($path) || !preg_match('#^/images/[a-f0-9]{32}\.(?:jpg|png|webp)$#D', $path)) {
        return [];
    }

    return [['src' => $path, 'alt' => $nombre]];
}

function obtener_producto_catalogo_por_id(int $id): ?array
{
    foreach (obtener_productos_catalogo() as $producto) {
        if (!isset($producto['url']) && (int) $producto['id'] === $id) {
            return $producto;
        }
    }

    return null;
}

function obtener_publicacion_catalogo_por_id(int $id): ?array
{
    $publicacion = buscar_publicacion_por_id($id);
    if ($publicacion === null) {
        return null;
    }

    $nombre = (string) $publicacion['nombre'];
    $vendedor = (string) $publicacion['vendedor'];
    $stock = (int) $publicacion['stock'];

    return [
        'id' => $id,
        'cartId' => 'publicacion-' . $id,
        'url' => '/publicaciones/' . $id,
        'nombre' => $nombre,
        'seller' => $vendedor,
        'categoria' => 'Publicación',
        'descripcion' => 'Producto publicado por ' . $vendedor . '.',
        'caracteristicas' => ['Unidades disponibles: ' . $stock],
        'precio' => (float) $publicacion['precio'],
        'stock' => $stock,
        'images' => imagenes_publicacion($publicacion, $nombre),
    ];
}

function buscar_productos_catalogo(string $termino): array
{
    $normalizar = static function (string $texto): string {
        $texto = mb_strtolower(trim($texto), 'UTF-8');
        $normalizado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);

        if ($normalizado === false) {
            return $texto;
        }

        $normalizado = strtr($normalizado, ["'" => '', '`' => '']);
        $normalizado = preg_replace('/[^a-z0-9]+/', ' ', $normalizado);

        return $normalizado === null ? $texto : trim($normalizado);
    };

    $terminoNormalizado = $normalizar($termino);
    if ($terminoNormalizado === '') {
        return [];
    }

    return array_values(array_filter(
        obtener_productos_catalogo(),
        static fn (array $producto): bool => str_contains(
            $normalizar((string) ($producto['nombre'] ?? '')),
            $terminoNormalizado
        )
    ));
}

function obtener_productos_recomendados(int $productoId, int $limite = 4): array
{
    $productoActual = obtener_producto_catalogo_por_id($productoId);
    if ($productoActual === null || $limite < 1) {
        return [];
    }

    return obtener_productos_recomendados_para_producto($productoActual, $limite);
}

function obtener_productos_recomendados_para_producto(array $productoActual, int $limite = 4): array
{
    if ($limite < 1) {
        return [];
    }

    $urlActual = (string) ($productoActual['url'] ?? '/productos/' . (int) $productoActual['id']);
    $productos = array_values(array_filter(
        obtener_productos_catalogo(),
        static fn (array $producto): bool => (string) ($producto['url'] ?? '/productos/' . (int) $producto['id']) !== $urlActual
    ));

    usort($productos, static function (array $a, array $b) use ($productoActual): int {
        $aCoincide = $a['categoria'] === $productoActual['categoria'];
        $bCoincide = $b['categoria'] === $productoActual['categoria'];

        return $bCoincide <=> $aCoincide;
    });

    return array_slice($productos, 0, $limite);
}
