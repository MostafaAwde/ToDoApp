<?php
// src/Model/Category.php

declare(strict_types=1);

namespace App\Model;

final class Category
{
    private int    $id;
    private string $name;

    private function __construct(int $id, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     * Factory to create from a DB row (assoc array).
     */
    public static function fromArray(array $row): self
    {
        // Expecting keys 'id' and 'name'
        return new self(
            (int)$row['id'],
            (string)$row['name']
        );
    }

    /**
     * Optionally convert back to an array.
     */
    public function toArray(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }

    // —————————————— Getters ——————————————

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    // If you need to rename a category at runtime, you can add a setter:
    // public function setName(string $name): void
    // {
    //     $this->name = $name;
    // }
}
