<?php
// config/dbal.php
declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
Dotenv::createImmutable(__DIR__ . '/../')->load();

return [
    'driver'   => 'pdo_mysql',
    'host'     => getenv('DB_HOST')     ?: ($_ENV['DB_HOST']     ?? 'localhost'),
    'port'     => getenv('DB_PORT')     ?: ($_ENV['DB_PORT']     ?? '3306'),
    'dbname'   => getenv('DB_DATABASE') ?: ($_ENV['DB_DATABASE'] ?? 'TodoApp'),
    'user'     => getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? 'mostafa'),
    'password' => getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? 'abcd@1234'),
    'charset'  => 'utf8mb4',
];
