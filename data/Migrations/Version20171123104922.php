<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171123104922 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv ADD zadel INT DEFAULT NULL, ADD impl INT DEFAULT NULL, DROP content_title');
        $this->addSql('ALTER TABLE izv ADD CONSTRAINT FK_D4F16CC8E58A4E78 FOREIGN KEY (zadel) REFERENCES fields (id)');
        $this->addSql('ALTER TABLE izv ADD CONSTRAINT FK_D4F16CC84C5E6B63 FOREIGN KEY (impl) REFERENCES fields (id)');
        $this->addSql('CREATE INDEX IDX_D4F16CC8E58A4E78 ON izv (zadel)');
        $this->addSql('CREATE INDEX IDX_D4F16CC84C5E6B63 ON izv (impl)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv DROP FOREIGN KEY FK_D4F16CC8E58A4E78');
        $this->addSql('ALTER TABLE izv DROP FOREIGN KEY FK_D4F16CC84C5E6B63');
        $this->addSql('DROP INDEX IDX_D4F16CC8E58A4E78 ON izv');
        $this->addSql('DROP INDEX IDX_D4F16CC84C5E6B63 ON izv');
        $this->addSql('ALTER TABLE izv ADD content_title VARCHAR(80) NOT NULL COLLATE utf8_unicode_ci, DROP zadel, DROP impl');
    }
}
