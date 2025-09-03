<?php
// src/Repository/Category/CategoryRepository.php

declare(strict_types=1);

namespace App\Repository\Category;

use App\Helper\Database;
use App\Model\Category;
use PDOException;
use RuntimeException;

class CategoryRepository implements ICategoryRepository
{
    public function __construct(private Database $db) {}

    /**
     * Fetch all categories via the view.
     *
     * @return Category[]
     */
    public function fetchAll(): array
    {
        $rows = $this->db->queryDB(
            'SELECT category_id AS id, category_name AS name FROM categories_view',
            Database::SELECTALL,
            []
        );

        return array_map(
            fn(array $row): Category => Category::fromArray($row),
            $rows
        );
    }

    /**
     * Fetch one category by ID via the view.
     */
    public function fetchById(int $id): ?Category
    {
        $row = $this->db->queryDB(
            'SELECT category_id AS id, category_name AS name FROM categories_view WHERE category_id = :id',
            Database::SELECTSINGLE,
            [[':id', $id]]
        );

        return $row
            ? Category::fromArray($row)
            : null;
    }

    /**
     * Insert a new category via sp_insert_category.
     */
    public function insert(string $name): int
    {
        $row = $this->db->queryDB(
            'CALL sp_insert_category(:p_name)',
            Database::SELECTSINGLE,
            [[':p_name', $name]]
        );

        if (!isset($row['category_id'])) {
            throw new RuntimeException('Failed to insert category.');
        }

        return (int)$row['category_id'];
    }

    /**
     * Update a category via sp_update_category.
     */
    public function update(int $id, string $name): void
    {
        try {
            $this->db->queryDB(
                'CALL sp_update_category(:p_id, :p_name)',
                Database::EXECUTE,
                [
                    [':p_id',   $id],
                    [':p_name', $name],
                ]
            );
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to update category: ' . $e->getMessage());
        }
    }

    /**
     * Delete a category via sp_delete_category.
     */
    public function delete(int $id): void
    {
        try {
            $this->db->queryDB(
                'CALL sp_delete_category(:p_id)',
                Database::EXECUTE,
                [[':p_id', $id]]
            );
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to delete category: ' . $e->getMessage());
        }
    }
}
