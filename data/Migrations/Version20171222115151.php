<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171222115151 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ta (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, l NUMERIC(1, 1) NOT NULL, w NUMERIC(1, 1) NOT NULL, h NUMERIC(1, 1) NOT NULL, v NUMERIC(1, 1) NOT NULL, row INT DEFAULT 2 NOT NULL, cu NUMERIC(2, 1) NOT NULL, stp NUMERIC(1, 1) NOT NULL, t NUMERIC(2, 1) DEFAULT \'.15\' NOT NULL, count INT NOT NULL, type VARCHAR(128) NOT NULL, conf VARCHAR(10) DEFAULT \'S22-10\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ta');
    }
}
