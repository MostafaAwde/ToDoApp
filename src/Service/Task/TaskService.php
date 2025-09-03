<?php
// src/Service/Task/TaskService.php

namespace App\Service\Task;

use App\Repository\Task\ITaskRepository;
use DateTimeImmutable;
use InvalidArgumentException;

class TaskService implements ITaskService
{
    public function __construct(private ITaskRepository $repo) {}

    public function getAllTasks(
        int $userId,
        ?string $status,
        ?string $sortBy,
        ?string $search
    ): array {
        $tasks = $this->repo->fetchAllByUserId(
            $userId,
            $status,
            $sortBy,
            $search
        );

        $today = new DateTimeImmutable('today');
        foreach ($tasks as &$task) {
            $due = $task['due_date']
                 ? new DateTimeImmutable($task['due_date'])
                 : null;
            $task['is_overdue'] = (bool)($due && $due < $today && ! $task['completed']);
        }

        return $tasks;
    }

    public function getTask(int $id, int $userId): array
    {
        return $this->repo->fetchByIdAndUserId($id, $userId);
    }

    public function createTask(int $userId, array $data): int
    {
        if (trim($data['title'] ?? '') === '') {
            throw new InvalidArgumentException('Title is required');
        }

        $data['user_id']   = $userId;
        $data['completed'] = 0;

        return $this->repo->insert($data);
    }

    public function updateTask(int $id, array $data): void
    {
        $this->repo->update($id, $data);
    }

    public function deleteTask(int $id, int $userId): void
    {
        $this->repo->delete($id, $userId);
    }

    public function toggleComplete(int $id, int $userId, bool $completed): void
    {
        $this->repo->toggleComplete($id, $userId, $completed);
    }

    public function getDashboardStats(int $userId): array
    {
        return $this->repo->countByUserId($userId);
    }

    public function reorderTasks(int $userId, array $orderedIds): void
    {
        $this->repo->reorder($userId, $orderedIds);
    }
}
