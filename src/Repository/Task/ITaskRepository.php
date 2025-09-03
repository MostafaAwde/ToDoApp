<?php
namespace App\Repository\Task;

interface ITaskRepository
{
    public function fetchAllByUserId(
        int $userId,
        ?string $status   = null,
        ?string $sortBy   = 'position',
        ?string $search   = null
    ): array;

    public function fetchByIdAndUserId(int $id, int $userId): array;

    public function insert(array $data): int;

    public function update(int $id, array $data): void;

    public function delete(int $id): void;

    public function toggleComplete(int $id, bool $completed): void;

    public function countByUserId(int $userId): array;
    
    public function reorder(int $userId, array $orderedIds): void;
}
