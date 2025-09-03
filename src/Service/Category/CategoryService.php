<?php
namespace App\Service\Category;

use App\Repository\Category\CategoryRepository;
use RuntimeException;

class CategoryService implements ICategoryService
{
    public function __construct(private CategoryRepository $repo) {}

    public function getAll(): array
    {
        return $this->repo->fetchAll();
    }

    public function getById(int $id): array
    {
        $cat = $this->repo->fetchById($id);
        if (! $cat) {
            throw new RuntimeException("Category #{$id} not found");
        }
        return $cat->toArray();
    }

    public function create(string $name): int
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        return $this->repo->insert($name);
    }

    public function update(int $id, string $name): void
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        $this->repo->update($id, $name);
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }
}
