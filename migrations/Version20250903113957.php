<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250903113957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed initial categories';
    }

    public function up(Schema $schema): void
    {
        // Insert 10 default categories
        $this->addSql(<<<'SQL'
INSERT INTO categories (`name`) VALUES
  ('Work'),
  ('Personal'),
  ('Fitness'),
  ('Learning'),
  ('Shopping'),
  ('Health'),
  ('Travel'),
  ('Finance'),
  ('Errands'),
  ('Hobby');
SQL
        );
    }

    public function down(Schema $schema): void
    {
        // Remove the seeded categories
        $this->addSql(<<<'SQL'
DELETE FROM categories
 WHERE `name` IN (
   'Work',
   'Personal',
   'Fitness',
   'Learning',
   'Shopping',
   'Health',
   'Travel',
   'Finance',
   'Errands',
   'Hobby'
 );
SQL
        );
    }
}
