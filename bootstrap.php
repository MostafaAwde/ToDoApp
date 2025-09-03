<?php
// bootstrap.php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use DI\ContainerBuilder;
use Dotenv\Dotenv;

// 1) Load .env
Dotenv::createImmutable(__DIR__)->load();

// 2) Build the DI container
$builder   = new ContainerBuilder();
$diConfig  = require __DIR__ . '/config/di.php';
$diConfig($builder);
$container = $builder->build();

// 3) Resolve router from container
/** @var \App\Routing\Router $router */
$router = $container->get(\App\Routing\Router::class);

// 4) Load all routes
require __DIR__ . '/routes/web.php';

// 5) Return to public/index.php
return [
    'router'    => $router,
    'container' => $container,
];
