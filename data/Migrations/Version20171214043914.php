<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171214043914 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seria CHANGE perfomance performance VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE tu CHANGE `desc` description VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE type_curtain CHANGE code alias VARCHAR(2) NOT NULL, CHANGE `desc` description VARCHAR(500) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seria CHANGE performance perfomance VARCHAR(30) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE tu CHANGE description `desc` VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE type_curtain CHANGE alias code VARCHAR(2) NOT NULL COLLATE utf8_unicode_ci, CHANGE description `desc` VARCHAR(500) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
