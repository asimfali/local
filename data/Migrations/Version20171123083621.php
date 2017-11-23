<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171123083621 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv CHANGE reason reason INT DEFAULT NULL');
        $this->addSql('ALTER TABLE izv ADD CONSTRAINT FK_D4F16CC83BB8880C FOREIGN KEY (reason) REFERENCES fields (id)');
        $this->addSql('CREATE INDEX IDX_D4F16CC83BB8880C ON izv (reason)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv DROP FOREIGN KEY FK_D4F16CC83BB8880C');
        $this->addSql('DROP INDEX IDX_D4F16CC83BB8880C ON izv');
        $this->addSql('ALTER TABLE izv CHANGE reason reason VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
