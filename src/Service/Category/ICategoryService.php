<?php
namespace App\Service\Category;

interface ICategoryService
{
    /** @return array<int,array{id:int,name:string}> */
    public function getAll(): array;

    public function getById(int $id): array;

    public function create(string $name): int;

    public function update(int $id, string $name): void;

    public function delete(int $id): void;
}
