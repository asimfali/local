<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208065816 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv ADD status INT DEFAULT NULL');
        $this->addSql('ALTER TABLE izv ADD CONSTRAINT FK_D4F16CC87B00651C FOREIGN KEY (status) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_D4F16CC87B00651C ON izv (status)');
        $this->addSql('ALTER TABLE izv_content ADD link VARCHAR(256) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv DROP FOREIGN KEY FK_D4F16CC87B00651C');
        $this->addSql('DROP INDEX IDX_D4F16CC87B00651C ON izv');
        $this->addSql('ALTER TABLE izv DROP status');
        $this->addSql('ALTER TABLE izv_content DROP link');
    }
}
