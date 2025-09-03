<?php
// config/di.php

use DI\ContainerBuilder;
use App\Helper\Database;
use App\Helper\Session;
use App\Repository\Auth\IAuthRepository;
use App\Repository\Auth\AuthRepository;
use App\Repository\Task\ITaskRepository;
use App\Repository\Task\TaskRepository;
use App\Repository\Category\ICategoryRepository;
use App\Repository\Category\CategoryRepository;
use App\Service\Auth\IAuthService;
use App\Service\Auth\AuthService;
use App\Service\Task\ITaskService;
use App\Service\Task\TaskService;
use App\Service\Category\ICategoryService;
use App\Service\Category\CategoryService;
use App\Controller\AuthController;
use App\Controller\TaskController;
use App\Controller\CategoryController;
use App\Middleware\ErrorHandlingMiddleware;
use App\Routing\Router;

return function (ContainerBuilder $builder) {
    $builder->addDefinitions([

        // 1) Database: factory reads credentials from .env
        Database::class => DI\factory(function() {
            $host = getenv('DB_HOST')     ?: 'localhost';
            $port = getenv('DB_PORT')     ?: '3306';
            $name = getenv('DB_DATABASE') ?: 'TodoApp';
            $user = getenv('DB_USERNAME') ?: 'mostafa';
            $pass = getenv('DB_PASSWORD') ?: 'abcd@1234';

            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

            return new Database($dsn, $user, $pass);
        }),

        // 2) Session helper
        Session::class => DI\autowire(),

        // 3) Repository bindings
        IAuthRepository::class      => DI\autowire(AuthRepository::class),
        ITaskRepository::class      => DI\autowire(TaskRepository::class),
        ICategoryRepository::class  => DI\autowire(CategoryRepository::class),

        // 4) Service bindings
        IAuthService::class         => DI\autowire(AuthService::class),
        ITaskService::class         => DI\autowire(TaskService::class),
        ICategoryService::class     => DI\autowire(CategoryService::class),

        // 5) Controller bindings
        AuthController::class       => DI\autowire(),
        TaskController::class       => DI\autowire(),
        CategoryController::class   => DI\autowire(),

    ]);
};
