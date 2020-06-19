<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619122239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE column_kanban ADD table_kanban_id INT NOT NULL');
        $this->addSql('ALTER TABLE column_kanban ADD CONSTRAINT FK_91D22CCFDA239F0E FOREIGN KEY (table_kanban_id) REFERENCES table_kanban (id)');
        $this->addSql('CREATE INDEX IDX_91D22CCFDA239F0E ON column_kanban (table_kanban_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE column_kanban DROP FOREIGN KEY FK_91D22CCFDA239F0E');
        $this->addSql('DROP INDEX IDX_91D22CCFDA239F0E ON column_kanban');
        $this->addSql('ALTER TABLE column_kanban DROP table_kanban_id');
    }
}
