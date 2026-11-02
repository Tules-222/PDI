<?php

declare(strict_types=1);

use Psr\Http\Message\UploadedFileInterface;

const STORE_BRAND_IMAGE_MAX_BYTES = 5242880;

final class StoreBrandImageException extends RuntimeException
{
}

function isValidStoreColor(string $color): bool
{
  return preg_match('/^#[0-9a-f]{6}$/iD', $color) === 1;
}

function storeBrandColorText(string $color): string
{
  if (!isValidStoreColor($color)) {
    $color = '#166534';
  }

  $channels = [
    hexdec(substr($color, 1, 2)) / 255,
    hexdec(substr($color, 3, 2)) / 255,
    hexdec(substr($color, 5, 2)) / 255,
  ];
  $linear = array_map(
    static fn(float $channel): float => $channel <= 0.04045
      ? $channel / 12.92
      : (($channel + 0.055) / 1.055) ** 2.4,
    $channels
  );
  $luminance = 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
  $whiteContrast = 1.05 / ($luminance + 0.05);
  $darkContrast = ($luminance + 0.05) / 0.05;

  return $whiteContrast >= $darkContrast ? '#ffffff' : '#0f172a';
}

function validateStoreBrandImage(mixed $upload, string $kind): ?UploadedFileInterface
{
  if ($upload === null || !$upload instanceof UploadedFileInterface) {
    if ($upload === null) {
      return null;
    }

    throw new StoreBrandImageException('El formulario de imágenes no es válido.');
  }

  if ($upload->getError() === UPLOAD_ERR_NO_FILE) {
    return null;
  }
  if ($upload->getError() !== UPLOAD_ERR_OK) {
    throw new StoreBrandImageException('No se pudo recibir la imagen. Probá con un archivo de hasta 5 MB.');
  }
  if ($upload->getSize() === null || $upload->getSize() < 1 || $upload->getSize() > STORE_BRAND_IMAGE_MAX_BYTES) {
    throw new StoreBrandImageException('El logo y el banner deben pesar como máximo 5 MB cada uno.');
  }

  $temporaryPath = $upload->getStream()->getMetadata('uri');
  if (!is_string($temporaryPath) || !is_file($temporaryPath)) {
    throw new StoreBrandImageException('No se pudo verificar la imagen seleccionada.');
  }

  $imageInfo = @getimagesize($temporaryPath);
  $mime = (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);
  $minimumWidth = $kind === 'banner' ? 640 : 100;
  $minimumHeight = $kind === 'banner' ? 180 : 100;
  if (
    !in_array($kind, ['logo', 'banner'], true)
    || $imageInfo === false
    || !in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)
    || ($imageInfo['mime'] ?? null) !== $mime
    || $imageInfo[0] < $minimumWidth
    || $imageInfo[1] < $minimumHeight
    || $imageInfo[0] * $imageInfo[1] > 40000000
  ) {
    throw new StoreBrandImageException(
      $kind === 'banner'
        ? 'El banner debe ser una imagen JPEG, PNG o WebP válida, de al menos 640 × 180 píxeles.'
        : 'El logo debe ser una imagen JPEG, PNG o WebP válida, de al menos 100 × 100 píxeles.'
    );
  }

  return $upload;
}

function storeStoreBrandImage(UploadedFileInterface $upload, string $kind): string
{
  $upload = validateStoreBrandImage($upload, $kind);
  if ($upload === null) {
    throw new StoreBrandImageException('No se recibió una imagen para guardar.');
  }

  $temporaryPath = $upload->getStream()->getMetadata('uri');
  $mime = is_string($temporaryPath) ? (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath) : false;
  $extensions = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
  ];
  if (!is_string($mime) || !isset($extensions[$mime])) {
    throw new StoreBrandImageException('La imagen dejó de ser válida. Volvé a seleccionarla.');
  }

  $directory = dirname(__DIR__, 2) . '/public/uploads/emprendimientos';
  if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
    throw new RuntimeException('No se pudo crear el directorio para imágenes de emprendimientos.');
  }

  $filename = $kind . '-' . bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
  $upload->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

  return '/uploads/emprendimientos/' . $filename;
}

function deleteStoreBrandImage(?string $path): void
{
  if (!is_string($path) || !preg_match('~^/uploads/emprendimientos/(?:logo|banner)-[a-f0-9]{32}\.(?:jpg|png|webp)$~D', $path)) {
    return;
  }

  $filePath = dirname(__DIR__, 2) . '/public/uploads/emprendimientos/' . basename($path);
  if (is_file($filePath) && !unlink($filePath)) {
    error_log('No se pudo eliminar una imagen comercial: ' . basename($path));
  }
}
