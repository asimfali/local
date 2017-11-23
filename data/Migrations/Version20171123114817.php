<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171123114817 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv ADD department INT DEFAULT NULL');
        $this->addSql('ALTER TABLE izv ADD CONSTRAINT FK_D4F16CC8CD1DE18A FOREIGN KEY (department) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_D4F16CC8CD1DE18A ON izv (department)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv DROP FOREIGN KEY FK_D4F16CC8CD1DE18A');
        $this->addSql('DROP INDEX IDX_D4F16CC8CD1DE18A ON izv');
        $this->addSql('ALTER TABLE izv DROP department');
    }
}
