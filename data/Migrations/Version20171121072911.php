<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171121072911 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE templates (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, UNIQUE INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usr_action ADD name INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usr_action ADD CONSTRAINT FK_F722FB385E237E06 FOREIGN KEY (name) REFERENCES templates (id)');
        $this->addSql('CREATE INDEX IDX_F722FB385E237E06 ON usr_action (name)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE usr_action DROP FOREIGN KEY FK_F722FB385E237E06');
        $this->addSql('DROP TABLE templates');
        $this->addSql('DROP INDEX IDX_F722FB385E237E06 ON usr_action');
        $this->addSql('ALTER TABLE usr_action DROP name');
    }
}
