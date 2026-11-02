<?php

declare(strict_types=1);

final class GeorefServiceException extends RuntimeException
{
}

/**
 * @return array<string, mixed>
 */
function georefGet(string $resource, array $parameters = []): array
{
  if (!in_array($resource, ['provincias', 'localidades', 'direcciones'], true)) {
    throw new InvalidArgumentException('Recurso geográfico no válido.');
  }

  $url = 'https://apis.datos.gob.ar/georef/api/' . $resource . '?' . http_build_query($parameters);
  if (function_exists('curl_init')) {
    $handle = curl_init($url);
    if ($handle === false) {
      throw new GeorefServiceException('No se pudo preparar la consulta de direcciones.');
    }

    $options = [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => false,
      CURLOPT_CONNECTTIMEOUT => 5,
      CURLOPT_TIMEOUT => 8,
      CURLOPT_HTTPHEADER => ['Accept: application/json'],
      CURLOPT_USERAGENT => 'Emprendi/1.0',
      CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_SSL_VERIFYHOST => 2,
    ];
    $caBundle = trim((string) ($_ENV['GEOREF_CA_BUNDLE'] ?? ''));
    if ($caBundle !== '') {
      if (!is_file($caBundle) || !is_readable($caBundle)) {
        curl_close($handle);
        throw new GeorefServiceException('El certificado configurado para Georef no es legible.');
      }
      $options[CURLOPT_CAINFO] = $caBundle;
    } elseif (PHP_OS_FAMILY === 'Windows' && defined('CURLSSLOPT_NATIVE_CA')) {
      $options[CURLOPT_SSL_OPTIONS] = CURLSSLOPT_NATIVE_CA;
    }

    if (!curl_setopt_array($handle, $options)) {
      $error = curl_error($handle);
      curl_close($handle);
      throw new GeorefServiceException('No se pudo configurar la conexión segura con Georef: ' . $error);
    }
    $body = curl_exec($handle);
    $status = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
    $error = curl_error($handle);
    curl_close($handle);
    if ($body === false) {
      throw new GeorefServiceException('No se pudo conectar con el servicio de direcciones de Argentina: ' . $error);
    }
    if ($status < 200 || $status >= 300) {
      throw new GeorefServiceException('El servicio de direcciones de Argentina respondió con HTTP ' . $status . '.');
    }
  } else {
    $context = stream_context_create([
      'http' => [
        'method' => 'GET',
        'timeout' => 8,
        'ignore_errors' => true,
        'header' => "Accept: application/json\r\nUser-Agent: Emprendi/1.0\r\n",
      ],
    ]);
    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
      throw new GeorefServiceException('No se pudo conectar con el servicio de direcciones de Argentina.');
    }

    $status = 0;
    foreach ($http_response_header ?? [] as $header) {
      if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $matches)) {
        $status = (int) $matches[1];
      }
    }
    if ($status < 200 || $status >= 300) {
      throw new GeorefServiceException('El servicio de direcciones de Argentina no está disponible.');
    }
  }

  try {
    $result = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
  } catch (JsonException $exception) {
    throw new GeorefServiceException('El servicio de direcciones devolvió una respuesta inválida.', 0, $exception);
  }
  if (!is_array($result)) {
    throw new GeorefServiceException('El servicio de direcciones devolvió una respuesta inválida.');
  }

  return $result;
}

function argentinaPhoneIsValid(string $phone): bool
{
  if (preg_match('/^[0-9\s()+.\/-]+$/', trim($phone)) !== 1) {
    return false;
  }

  $digits = preg_replace('/\D+/', '', $phone);
  if (!is_string($digits) || strlen($digits) > 13) {
    return false;
  }

  if (str_starts_with($digits, '549') && strlen($digits) === 13) {
    $digits = substr($digits, 3);
  } elseif (str_starts_with($digits, '54') && strlen($digits) >= 12) {
    $digits = substr($digits, 2);
  }

  if (str_starts_with($digits, '0') && strlen($digits) === 13) {
    $national = substr($digits, 1);
    for ($areaLength = 2; $areaLength <= 4; $areaLength++) {
      $subscriber = substr($national, $areaLength);
      if (preg_match('/^15\d{6,8}$/', $subscriber) === 1) {
        $digits = substr($national, 0, $areaLength) . substr($subscriber, 2);
        break;
      }
    }
  } elseif (strlen($digits) === 12) {
    for ($areaLength = 2; $areaLength <= 4; $areaLength++) {
      $subscriber = substr($digits, $areaLength);
      if (preg_match('/^15\d{6,8}$/', $subscriber) === 1) {
        $digits = substr($digits, 0, $areaLength) . substr($subscriber, 2);
        break;
      }
    }
  } elseif (str_starts_with($digits, '0') && strlen($digits) === 11) {
    $digits = substr($digits, 1);
  }

  return strlen($digits) === 10 && preg_match('/^[1-9]\d{9}$/', $digits) === 1;
}

function argentinaAddressIsValid(string $address, string $locality, string $province): bool
{
  $localities = georefGet('localidades', [
    'nombre' => $locality,
    'provincia' => $province,
    'campos' => 'id,nombre,provincia',
    'max' => 20,
  ]);
  $matches = $localities['localidades'] ?? [];
  $matchingLocality = false;
  foreach (is_array($matches) ? $matches : [] as $match) {
    if (
      is_array($match)
      && isset($match['nombre'], $match['provincia']['nombre'])
      && mb_strtolower(trim((string) $match['nombre'])) === mb_strtolower(trim($locality))
      && mb_strtolower(trim((string) $match['provincia']['nombre'])) === mb_strtolower(trim($province))
    ) {
      $matchingLocality = true;
      break;
    }
  }
  if (!$matchingLocality) {
    return false;
  }

  $addresses = georefGet('direcciones', [
    'direccion' => $address,
    'localidad' => $locality,
    'provincia' => $province,
    'max' => 10,
  ]);
  foreach ($addresses['direcciones'] ?? [] as $candidate) {
    if (
      is_array($candidate)
      && isset($candidate['provincia']['nombre'])
      && mb_strtolower(trim((string) $candidate['provincia']['nombre'])) === mb_strtolower(trim($province))
    ) {
      return true;
    }
  }

  return false;
}
