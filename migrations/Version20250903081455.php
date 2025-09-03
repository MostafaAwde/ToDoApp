<?php
declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250903121500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add position column to tasks for custom ordering';
    }

    // Default isTransactional() = true, so we omit override here.

    public function up(Schema $schema): void
    {
        // 1) Add the position column
        $this->addSql(<<<'SQL'
ALTER TABLE `tasks`
  ADD COLUMN `position` INT NOT NULL DEFAULT 0 AFTER `completed`;
SQL
        );

        // 2) Initialize existing rows by created_at order
        $this->addSql(<<<'SQL'
SET @rownum = -1;
UPDATE `tasks` t
JOIN (
  SELECT id, (@rownum := @rownum + 1) AS pos
  FROM `tasks`
  ORDER BY created_at ASC
) x ON t.id = x.id
SET t.position = x.pos;
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `tasks` DROP COLUMN `position`;');
    }
}
