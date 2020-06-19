<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619122501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD column_kanban_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25AD5ECC8A FOREIGN KEY (column_kanban_id) REFERENCES column_kanban (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25AD5ECC8A ON task (column_kanban_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25AD5ECC8A');
        $this->addSql('DROP INDEX IDX_527EDB25AD5ECC8A ON task');
        $this->addSql('ALTER TABLE task DROP column_kanban_id');
    }
}
