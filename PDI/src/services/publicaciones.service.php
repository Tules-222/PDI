<?php

declare(strict_types=1);

require_once __DIR__ . '/../persistence/publicaciones.persistence.php';

use Psr\Http\Message\UploadedFileInterface;

const TAMANO_MAXIMO_IMAGEN_PUBLICACION = 5242880;

function validar_publicacion(array $data): array
{
    $nombreInput = $data['nombre'] ?? null;
    $nombre = is_string($nombreInput) || is_numeric($nombreInput)
        ? trim((string) $nombreInput)
        : '';
    $precioValue = $data['precio'] ?? null;
    $precioInput = is_string($precioValue) || is_int($precioValue) || is_float($precioValue)
        ? $precioValue
        : null;
    $stockValue = $data['stock'] ?? null;
    $stockInput = is_string($stockValue) || is_int($stockValue)
        ? $stockValue
        : null;
    $errors = [];

    if ($nombre === '' || mb_strlen($nombre, 'UTF-8') > 100) {
        $errors[] = 'El nombre es obligatorio y no puede superar los 100 caracteres.';
    }

    if (!is_numeric($precioInput) || (float) $precioInput <= 0 || (float) $precioInput > 99999999.99) {
        $errors[] = 'El precio debe ser mayor que cero y no superar 99.999.999,99.';
    }

    $stock = filter_var($stockInput, FILTER_VALIDATE_INT);
    if ($stock === false || $stock < 1) {
        $errors[] = 'El stock debe ser un número entero de al menos una unidad.';
    }

    return [
        'errors' => $errors,
        'data' => [
            'nombre' => $nombre,
            'precio' => is_numeric($precioInput) ? (float) $precioInput : 0.0,
            'stock' => $stock === false ? 0 : $stock,
        ],
    ];
}

/**
 * @return array{errors: array<int, string>, path: string|null}
 */
function guardar_imagen_publicacion(?UploadedFileInterface $imagen): array
{
    if ($imagen === null || $imagen->getError() === UPLOAD_ERR_NO_FILE) {
        return ['errors' => [], 'path' => null];
    }

    $uploadError = $imagen->getError();
    if ($uploadError !== UPLOAD_ERR_OK) {
        $message = $uploadError === UPLOAD_ERR_INI_SIZE || $uploadError === UPLOAD_ERR_FORM_SIZE
            ? 'La imagen supera el tamaño máximo permitido de 5 MB.'
            : 'No se pudo recibir la imagen. Intentá nuevamente.';

        return ['errors' => [$message], 'path' => null];
    }

    $fileSize = $imagen->getSize();
    if ($fileSize === null || $fileSize < 1 || $fileSize > TAMANO_MAXIMO_IMAGEN_PUBLICACION) {
        return ['errors' => ['La imagen debe pesar más de 0 bytes y no superar los 5 MB.'], 'path' => null];
    }

    $stream = $imagen->getStream();
    $temporaryPath = $stream->getMetadata('uri');
    if (!is_string($temporaryPath) || !is_file($temporaryPath)) {
        return ['errors' => ['No se pudo leer la imagen cargada.'], 'path' => null];
    }

    $actualSize = filesize($temporaryPath);
    if ($actualSize === false || $actualSize < 1 || $actualSize > TAMANO_MAXIMO_IMAGEN_PUBLICACION) {
        return ['errors' => ['La imagen debe pesar más de 0 bytes y no superar los 5 MB.'], 'path' => null];
    }

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($temporaryPath);
    $imageInfo = getimagesize($temporaryPath);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!is_string($mimeType)
        || !isset($extensions[$mimeType])
        || !is_array($imageInfo)
        || ($imageInfo['mime'] ?? null) !== $mimeType) {
        return ['errors' => ['El archivo debe ser una imagen JPG, PNG o WebP válida.'], 'path' => null];
    }

    $width = (int) ($imageInfo[0] ?? 0);
    $height = (int) ($imageInfo[1] ?? 0);
    if ($width < 1 || $height < 1 || $width > 6000 || $height > 6000) {
        return ['errors' => ['La imagen no puede superar los 6000 píxeles de ancho o alto.'], 'path' => null];
    }

    $uploadDirectory = dirname(__DIR__, 2) . '/public/images';
    if (!is_dir($uploadDirectory)) {
        throw new RuntimeException('No se encontró el directorio de imágenes del sitio.');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    $targetPath = $uploadDirectory . '/' . $filename;
    $imagen->moveTo($targetPath);

    return [
        'errors' => [],
        'path' => '/images/' . $filename,
    ];
}

function eliminar_imagen_publicacion(string $path): void
{
    if (!preg_match('#^/images/[a-f0-9]{32}\.(?:jpg|png|webp)$#D', $path)) {
        throw new InvalidArgumentException('La ruta de imagen de publicación no es válida.');
    }

    $absolutePath = dirname(__DIR__, 2) . '/public' . $path;
    if (is_file($absolutePath) && !unlink($absolutePath)) {
        throw new RuntimeException('No se pudo eliminar la imagen de publicación no guardada.');
    }
}

function crear_publicacion(int $usuarioId, array $data, ?string $imagenPath): int
{
    $validated = validar_publicacion($data);
    if ($validated['errors'] !== []) {
        throw new InvalidArgumentException(implode(' ', $validated['errors']));
    }

    return crear_publicacion_usuario(
        $usuarioId,
        $validated['data']['nombre'],
        $validated['data']['precio'],
        $validated['data']['stock'],
        $imagenPath,
    );
}
