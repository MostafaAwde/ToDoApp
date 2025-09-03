<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250903092939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Recreate tasks_with_users view to include the position column';
    }

    public function up(Schema $schema): void
    {
        // Drop the existing view if it exists
        $this->addSql('DROP VIEW IF EXISTS `tasks_with_users`;');

        // Recreate the view with the position column exposed
        $this->addSql(<<<'SQL'
CREATE VIEW `tasks_with_users` AS
SELECT
  t.id         AS task_id,
  t.user_id,
  t.title,
  t.description,
  t.due_date,
  t.priority,
  t.completed,
  t.position,           -- expose the new position column
  t.created_at,
  u.name      AS user_name
FROM tasks t
JOIN users u ON u.id = t.user_id;
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // Drop the view
        $this->addSql('DROP VIEW IF EXISTS `tasks_with_users`;');

        // Recreate the original view without the position column
        $this->addSql(<<<'SQL'
CREATE VIEW `tasks_with_users` AS
SELECT
  t.id         AS task_id,
  t.user_id,
  t.title,
  t.description,
  t.due_date,
  t.priority,
  t.completed,
  t.created_at,
  u.name      AS user_name
FROM tasks t
JOIN users u ON u.id = t.user_id;
SQL
        );
    }
}
