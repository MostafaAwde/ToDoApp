<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903082419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create stored procedure sp_reorder_tasks to persist custom task order';
    }

    /**
     * Stored‐procedure DDL on MySQL must run outside a transaction.
     */
    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        // 1) Drop existing proc if it exists
        $this->addSql('DROP PROCEDURE IF EXISTS `sp_reorder_tasks`;');

        // 2) Define the reorder SP
        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_reorder_tasks`(
  IN p_user_id INT UNSIGNED,
  IN p_order JSON
)
BEGIN
  DECLARE idx INT DEFAULT 0;
  DECLARE cnt INT DEFAULT JSON_LENGTH(p_order);
  DECLARE t_id INT;

  WHILE idx < cnt DO
    SET t_id = JSON_UNQUOTE(JSON_EXTRACT(p_order, CONCAT('$[', idx, ']')));
    UPDATE `tasks`
      SET `position` = idx
      WHERE `id` = t_id
        AND `user_id` = p_user_id;
    SET idx = idx + 1;
  END WHILE;
END;
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP PROCEDURE IF EXISTS `sp_reorder_tasks`;');
    }
}
