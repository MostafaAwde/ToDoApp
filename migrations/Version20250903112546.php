<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250903112546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add stored procedures for category CRUD and a view for categories';
    }

    public function up(Schema $schema): void
    {
        // 1) Create stored procedure sp_insert_category
        $this->addSql(
            <<<'SQL'
DROP PROCEDURE IF EXISTS `sp_insert_category`;
SQL
        );
        $this->addSql(
            <<<'SQL'
CREATE PROCEDURE `sp_insert_category`(
  IN p_name VARCHAR(100)
)
BEGIN
  INSERT INTO `categories` (`name`)
    VALUES (p_name);
  SELECT LAST_INSERT_ID() AS category_id;
END;
SQL
        );

        // 2) Create stored procedure sp_update_category
        $this->addSql(
            <<<'SQL'
DROP PROCEDURE IF EXISTS `sp_update_category`;
SQL
        );
        $this->addSql(
            <<<'SQL'
CREATE PROCEDURE `sp_update_category`(
  IN p_id   INT,
  IN p_name VARCHAR(100)
)
BEGIN
  UPDATE `categories`
    SET `name` = p_name
  WHERE `id` = p_id;
END;
SQL
        );

        // 3) Create stored procedure sp_delete_category
        $this->addSql(
            <<<'SQL'
DROP PROCEDURE IF EXISTS `sp_delete_category`;
SQL
        );
        $this->addSql(
            <<<'SQL'
CREATE PROCEDURE `sp_delete_category`(
  IN p_id INT
)
BEGIN
  DELETE FROM `categories`
    WHERE `id` = p_id;
END;
SQL
        );

        // 4) Create or replace the categories view
        $this->addSql(
            <<<'SQL'
DROP VIEW IF EXISTS `categories_view`;
SQL
        );
        $this->addSql(
            <<<'SQL'
CREATE VIEW `categories_view` AS
SELECT
  `id`   AS category_id,
  `name` AS category_name
FROM `categories`
ORDER BY `name`;
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // Drop the categories view
        $this->addSql('DROP VIEW IF EXISTS `categories_view`;');

        // Drop each stored procedure
        $this->addSql('DROP PROCEDURE IF EXISTS `sp_delete_category`;');
        $this->addSql('DROP PROCEDURE IF EXISTS `sp_update_category`;');
        $this->addSql('DROP PROCEDURE IF EXISTS `sp_insert_category`;');
    }
}
