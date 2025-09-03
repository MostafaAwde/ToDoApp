<?php
// src/Repository/Task/TaskRepository.php

namespace App\Repository\Task;

use App\Helper\Database;
use PDOException;
use RuntimeException;

class TaskRepository implements ITaskRepository
{
	public function __construct(private Database $db) {}

	public function fetchAllByUserId(
		int $userId,
		?string $status   = null,
		?string $sortBy   = 'position',
		?string $search   = null
	): array {
		$sql = <<<SQL
SELECT
  t.id            AS task_id,
  t.user_id,
  t.category_id,
  t.title,
  t.description,
  t.due_date,
  t.priority,
  t.completed,
  t.position,
  t.created_at,
  c.name          AS category_name,
  u.name          AS user_name
FROM tasks t
  JOIN users u   ON u.id = t.user_id
  LEFT JOIN categories c ON c.id = t.category_id
WHERE t.user_id = :uid
SQL;

		$params = [[':uid', $userId]];

		if ($status === 'completed') {
			$sql .= ' AND t.completed = 1';
		} elseif ($status === 'pending') {
			$sql .= ' AND t.completed = 0';
		}

		if ($search !== null && trim($search) !== '') {
			$sql .= ' AND (t.title LIKE :search OR t.description LIKE :search)';
			$params[] = [':search', '%' . $search . '%'];
		}

		$allowed = ['position', 'due_date', 'priority'];
		if (in_array($sortBy, $allowed, true)) {
			$sql .= " ORDER BY t.$sortBy";
		}

		return $this->db->queryDB($sql, Database::SELECTALL, $params);
	}


	public function fetchByIdAndUserId(int $id, int $userId): array
	{
		$row = $this->db->queryDB(
			'SELECT t.*, c.name AS category_name
             FROM tasks t
             LEFT JOIN categories c ON c.id = t.category_id
             WHERE t.id = :id AND t.user_id = :uid',
			Database::SELECTSINGLE,
			[
				[':id',  $id],
				[':uid', $userId],
			]
		);

		if (! $row) {
			throw new RuntimeException("Task #{$id} not found for user #{$userId}");
		}

		return $row;
	}

	public function insert(array $data): int
	{
		// Include category_id in the INSERT
		$this->db->queryDB(
			'INSERT INTO tasks
               (user_id, title, description, due_date, priority, completed, position, category_id)
             VALUES
               (:uid, :title, :desc, :due, :prio, :comp, 0, :cat)',
			Database::EXECUTE,
			[
				[':uid',   $data['user_id']],
				[':title', $data['title']],
				[':desc',  $data['description']],
				[':due',   $data['due_date']],
				[':prio',  $data['priority']],
				[':comp',  $data['completed']],
				[':cat',   $data['category_id']],
			]
		);

		return (int)$this->db->getPDO()->lastInsertId();
	}

	public function update(int $id, array $data): void
	{
		try {
			$this->db->queryDB(
				'UPDATE tasks
                   SET title       = :title,
                       description = :desc,
                       due_date    = :due,
                       priority    = :prio,
                       completed   = :comp,
                       category_id = :cat
                 WHERE id = :id AND user_id = :uid',
				Database::EXECUTE,
				[
					[':title', $data['title']],
					[':desc',  $data['description']],
					[':due',   $data['due_date']],
					[':prio',  $data['priority']],
					[':comp',  $data['completed']],
					[':cat',   $data['category_id']],
					[':id',    $id],
					[':uid',   $data['user_id']],
				]
			);
		} catch (PDOException $e) {
			throw new RuntimeException('Failed to update task: ' . $e->getMessage());
		}
	}

	public function delete(int $id): void
	{
		$this->db->queryDB(
			'DELETE FROM tasks WHERE id = :id',
			Database::EXECUTE,
			[[':id', $id]]
		);
	}

	public function toggleComplete(int $id, bool $completed): void
	{
		$this->db->queryDB(
			'UPDATE tasks SET completed = :comp WHERE id = :id',
			Database::EXECUTE,
			[
				[':id',   $id],
				[':comp', $completed ? 1 : 0],
			]
		);
	}

	public function countByUserId(int $userId): array
	{
		$stats = $this->db->queryDB(
			'SELECT COUNT(*)      AS total,
                    SUM(completed) AS completed,
                    SUM(1 - completed) AS pending
             FROM tasks
             WHERE user_id = :uid',
			Database::SELECTSINGLE,
			[[':uid', $userId]]
		);

		return [
			'total'     => (int)($stats['total']     ?? 0),
			'completed' => (int)($stats['completed'] ?? 0),
			'pending'   => (int)($stats['pending']   ?? 0),
		];
	}

	public function reorder(int $userId, array $orderedIds): void
	{
		$json = json_encode(array_map('intval', $orderedIds), JSON_UNESCAPED_UNICODE);

		$this->db->queryDB(
			'CALL sp_reorder_tasks(:p_user_id, :p_order)',
			Database::EXECUTE,
			[
				[':p_user_id', $userId],
				[':p_order',   $json],
			]
		);
	}
}
