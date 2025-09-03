<?php
// src/Service/Task/ITaskService.php
namespace App\Service\Task;

interface ITaskService
{
    public function getAllTasks(
        int $userId,
        ?string $status,
        ?string $sortBy,
        ?string $search
    ): array;
    public function getTask(int $id, int $userId): array;
    public function createTask(int $userId, array $data): int;
    public function updateTask(int $id, array $data): void;
    public function deleteTask(int $id, int $userId): void;
    public function toggleComplete(int $id, int $userId, bool $completed): void;
    public function getDashboardStats(int $userId): array;
    public function reorderTasks(int $userId, array $orderedIds): void;
}
