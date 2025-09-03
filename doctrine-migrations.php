<?php
// doctrine-migrations.php

return [
    'name'                      => 'TodoApp Migrations',
    'migrations_namespace'      => 'App\Migrations',
    'table_name'                => 'doctrine_migration_versions',
    'migrations_directory'      => __DIR__ . '/src/Migrations',
    'all_or_nothing'            => true,
    'check_database_platform'   => true,
];
