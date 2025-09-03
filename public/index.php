<?php
// public/index.php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap.php';

$router = $app['router'];

// 1) Grab & decode the request path
$raw     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$request = urldecode($raw);

// 2) Determine the public folder path
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$basePath   = rtrim(dirname($scriptName), '/');

// 3) Strip off either the full script or just its folder
if (strpos($request, $scriptName) === 0) {
    $path = substr($request, strlen($scriptName));
} elseif (strpos($request, $basePath) === 0) {
    $path = substr($request, strlen($basePath));
} else {
    $path = $request;
}

// 4) Normalize to "/"
$path = $path === '' ? '/' : $path;

// 5) Dispatch directly (no error middleware)
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
