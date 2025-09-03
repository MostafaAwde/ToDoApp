<?php
// src/Model/Task.php

namespace App\Model;

use DateTimeImmutable;

class Task
{
    private ?int $id;
    private int $userId;
    private string $title;
    private string $description;
    private DateTimeImmutable $dueDate;
    private string $priority;    // 'low', 'medium', 'high'
    private bool $completed;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        int $userId,
        string $title,
        string $description,
        DateTimeImmutable $dueDate,
        string $priority = 'medium',
        bool $completed = false,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id          = $id;
        $this->userId      = $userId;
        $this->title       = $title;
        $this->description = $description;
        $this->dueDate     = $dueDate;
        $this->priority    = $priority;
        $this->completed   = $completed;
        $this->createdAt   = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt   = $updatedAt;
    }

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Setters (for update operations)

    public function setTitle(string $title): void
    {
        $this->title = $title;
        $this->touch();
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->touch();
    }

    public function setDueDate(DateTimeImmutable $dueDate): void
    {
        $this->dueDate = $dueDate;
        $this->touch();
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
        $this->touch();
    }

    public function markCompleted(): void
    {
        $this->completed = true;
        $this->touch();
    }

    public function markPending(): void
    {
        $this->completed = false;
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
