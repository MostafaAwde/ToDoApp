<?php
// src/Repository/Category/ICategoryRepository.php

declare(strict_types=1);

namespace App\Repository\Category;

use App\Model\Category;

interface ICategoryRepository
{
    /**
     * @return Category[]
     */
    public function fetchAll(): array;

    /**
     * @return Category|null
     */
    public function fetchById(int $id): ?Category;

    /**
     * @return int  The new category ID
     */
    public function insert(string $name): int;

    public function update(int $id, string $name): void;

    public function delete(int $id): void;
}
