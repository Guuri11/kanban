<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200620102027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_kanban ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE table_kanban ADD CONSTRAINT FK_6F378CC3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6F378CC3A76ED395 ON table_kanban (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_kanban DROP FOREIGN KEY FK_6F378CC3A76ED395');
        $this->addSql('DROP INDEX IDX_6F378CC3A76ED395 ON table_kanban');
        $this->addSql('ALTER TABLE table_kanban DROP user_id');
    }
}
