<?php

$assetFiles = [
	'/assets/login.css' => __DIR__ . '/../src/views/usuario/login.css',
	'/assets/register.css' => __DIR__ . '/../src/views/usuario/register.css',
];
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if (is_string($requestPath) && isset($assetFiles[$requestPath])) {
	header('Content-Type: text/css; charset=UTF-8');
	readfile($assetFiles[$requestPath]);
	exit;
}

$app = require __DIR__ . "/../src/bootstrap.php";

$app->run();
