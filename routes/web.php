<?php
// routes/web.php

/** @var \App\Routing\Router $router */
/** @var \DI\Container     $container */

// IDE helper
$container = $container ?? null;

// Resolve controllers once
$authCtrl     = $container->get(\App\Controller\AuthController::class);
$taskCtrl     = $container->get(\App\Controller\TaskController::class);
$categoryCtrl = $container->get(\App\Controller\CategoryController::class);

// Auth routes
$router->add('GET',  '/signup',           [$authCtrl, 'showSignup']);
$router->add('POST', '/signup',           [$authCtrl, 'signup']);
$router->add('GET',  '/login',            [$authCtrl, 'showLogin']);
$router->add('POST', '/login',            [$authCtrl, 'login']);
$router->add('GET',  '/logout',           [$authCtrl, 'logout']);

// Dashboard
$router->add('GET',  '/dashboard',        [$taskCtrl, 'dashboard']);

// Task CRUD
$router->add('GET',    '/tasks',              [$taskCtrl, 'index']);
$router->add('GET',    '/tasks/create',       [$taskCtrl, 'showCreateForm']);
$router->add('POST',   '/tasks/create',       [$taskCtrl, 'create']);
$router->add('GET',    '/tasks/edit/{id}',    [$taskCtrl, 'showEditForm']);
$router->add('POST',   '/tasks/edit/{id}',    [$taskCtrl, 'edit']);
$router->add('POST',   '/tasks/delete/{id}',  [$taskCtrl, 'delete']);
$router->add('POST',   '/tasks/toggle/{id}',  [$taskCtrl, 'toggle']);
$router->add('POST',   '/tasks/reorder',      [$taskCtrl, 'reorder']);

// Category CRUD
$router->add('GET',  '/categories',          [$categoryCtrl, 'index']);
$router->add('GET',  '/categories/create',   [$categoryCtrl, 'showCreate']);
$router->add('POST', '/categories/create',   [$categoryCtrl, 'create']);
$router->add('GET',  '/categories/edit/{id}',[$categoryCtrl, 'showEdit']);
$router->add('POST', '/categories/edit/{id}',[$categoryCtrl, 'edit']);
$router->add('POST', '/categories/delete/{id}',[$categoryCtrl, 'delete']);

// Export endpoints
$router->add('GET', '/tasks/export/csv', [$taskCtrl, 'exportCsv']);
$router->add('GET', '/tasks/export/pdf', [$taskCtrl, 'exportPdf']);