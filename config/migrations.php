<?php
declare(strict_types=1);

return [
    // Where your migration classes live (namespace => path)
    'migrations_paths' => [
        'App\Migration' => __DIR__ . '/../migrations',
    ],

    // Wrap each migration in its own transaction
    'all_or_nothing' => false,

    // Prevent running on a mismatched platform
    'check_database_platform' => true,
];
