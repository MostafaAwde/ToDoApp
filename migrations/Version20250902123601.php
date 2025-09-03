<?php
// src/Migration/Version20250902123601.php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250902123601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users & tasks tables, tasks_with_users view and CRUD stored procedures';
    }

    public function up(Schema $schema): void
    {

        // 1) users table
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
        );

        // 2) tasks table
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `due_date` DATE NULL,
  `priority` ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
  `completed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_tasks_user`
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
        );

        // 3) tasks_with_users view
        $this->addSql(<<<'SQL'
CREATE OR REPLACE VIEW `tasks_with_users` AS
SELECT
  t.`id`          AS task_id,
  t.`title`,
  t.`description`,
  t.`due_date`,
  t.`priority`,
  t.`completed`,
  t.`created_at`  AS task_created,
  u.`id`          AS user_id,
  u.`name`        AS user_name,
  u.`email`       AS user_email,
  u.`created_at`  AS user_created
FROM `tasks` AS t
JOIN `users` AS u
  ON t.`user_id` = u.`id`;
SQL
        );

        // 4) user stored procedures
        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_insert_user`(
  IN p_name VARCHAR(100),
  IN p_email VARCHAR(150),
  IN p_password_hash VARCHAR(255)
)
BEGIN
  INSERT INTO `users` (`name`,`email`,`password_hash`)
  VALUES (p_name, p_email, p_password_hash);
  SELECT LAST_INSERT_ID() AS user_id;
END;
SQL
        );

        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_update_user`(
  IN p_id INT UNSIGNED,
  IN p_name VARCHAR(100),
  IN p_email VARCHAR(150)
)
BEGIN
  UPDATE `users`
    SET `name`  = p_name,
        `email` = p_email
  WHERE `id` = p_id;
END;
SQL
        );

        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_delete_user`(
  IN p_id INT UNSIGNED
)
BEGIN
  DELETE FROM `users`
  WHERE `id` = p_id;
END;
SQL
        );

        // 5) task stored procedures
        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_insert_task`(
  IN p_user_id INT UNSIGNED,
  IN p_title VARCHAR(255),
  IN p_description TEXT,
  IN p_due_date DATE,
  IN p_priority ENUM('low','medium','high')
)
BEGIN
  INSERT INTO `tasks`
    (`user_id`,`title`,`description`,`due_date`,`priority`,`completed`)
  VALUES
    (p_user_id, p_title, p_description, p_due_date, p_priority, 0);
  SELECT LAST_INSERT_ID() AS task_id;
END;
SQL
        );

        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_update_task`(
  IN p_id INT UNSIGNED,
  IN p_title VARCHAR(255),
  IN p_description TEXT,
  IN p_due_date DATE,
  IN p_priority ENUM('low','medium','high')
)
BEGIN
  UPDATE `tasks`
    SET `title`       = p_title,
        `description` = p_description,
        `due_date`    = p_due_date,
        `priority`    = p_priority
  WHERE `id` = p_id;
END;
SQL
        );

        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_delete_task`(
  IN p_id INT UNSIGNED
)
BEGIN
  DELETE FROM `tasks`
  WHERE `id` = p_id;
END;
SQL
        );

        $this->addSql(<<<'SQL'
CREATE PROCEDURE `sp_toggle_task`(
  IN p_id INT UNSIGNED,
  IN p_completed TINYINT(1)
)
BEGIN
  UPDATE `tasks`
    SET `completed` = p_completed
  WHERE `id` = p_id;
END;
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // no-op
    }
}
