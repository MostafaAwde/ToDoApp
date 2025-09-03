<?php
// src/Migration/Version20250903120000.php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250903120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add categories table and category_id foreign key to tasks';
    }

    public function up(Schema $schema): void
    {
        // 1) Create categories table
        $this->addSql(<<<'SQL'
CREATE TABLE categories (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
        );

        // 2) Add category_id to tasks
        $this->addSql('ALTER TABLE tasks ADD COLUMN category_id INT NULL AFTER user_id;');

        // 3) Add FK constraint
        $this->addSql(<<<'SQL'
ALTER TABLE tasks
  ADD CONSTRAINT FK_tasks_category
    FOREIGN KEY (category_id)
    REFERENCES categories(id)
    ON DELETE SET NULL;
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // Remove FK and column
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_tasks_category;');
        $this->addSql('ALTER TABLE tasks DROP COLUMN category_id;');

        // Drop categories table
        $this->addSql('DROP TABLE IF EXISTS categories;');
    }
}
