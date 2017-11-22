<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171122051659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE usr_action DROP FOREIGN KEY FK_F722FB385E237E06');
        $this->addSql('DROP INDEX IDX_F722FB385E237E06 ON usr_action');
        $this->addSql('ALTER TABLE usr_action CHANGE name `group` INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usr_action ADD CONSTRAINT FK_F722FB386DC044C5 FOREIGN KEY (`group`) REFERENCES templates (id)');
        $this->addSql('CREATE INDEX IDX_F722FB386DC044C5 ON usr_action (`group`)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE usr_action DROP FOREIGN KEY FK_F722FB386DC044C5');
        $this->addSql('DROP INDEX IDX_F722FB386DC044C5 ON usr_action');
        $this->addSql('ALTER TABLE usr_action CHANGE `group` name INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usr_action ADD CONSTRAINT FK_F722FB385E237E06 FOREIGN KEY (name) REFERENCES templates (id)');
        $this->addSql('CREATE INDEX IDX_F722FB385E237E06 ON usr_action (name)');
    }
}
