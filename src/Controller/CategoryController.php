<?php
namespace App\Controller;

use App\Helper\Session;
use App\Service\Category\ICategoryService;

class CategoryController
{
    public function __construct(private ICategoryService $service) {}

    public function index(): void
    {
        Session::start();
        $cats  = $this->service->getAll();
        $title = 'Manage Categories';
        $content = __DIR__ . '/../../public/views/categories/list.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function showCreate(): void
    {
        $title   = 'New Category';
        $content = __DIR__ . '/../../public/views/categories/create.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function create(): void
    {
        Session::start();
        $name = $_POST['name'] ?? '';
        $this->service->create($name);
        header('Location: /categories');
    }

    public function showEdit(string $id): void
    {
        Session::start();
        $cat = $this->service->getById((int)$id);
        $title   = 'Edit Category';
        $content = __DIR__ . '/../../public/views/categories/edit.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function edit(string $id): void
    {
        Session::start();
        $name = $_POST['name'] ?? '';
        $this->service->update((int)$id, $name);
        header('Location: /categories');
    }

    public function delete(string $id): void
    {
        Session::start();
        $this->service->delete((int)$id);
        header('Location: /categories');
    }
}
