<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171222124109 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ta CHANGE l l NUMERIC(5, 1) NOT NULL, CHANGE w w NUMERIC(5, 1) NOT NULL, CHANGE h h NUMERIC(5, 1) NOT NULL, CHANGE v v NUMERIC(5, 1) NOT NULL, CHANGE stp stp NUMERIC(3, 2) DEFAULT \'2.5\' NOT NULL, CHANGE t t NUMERIC(3, 2) DEFAULT \'.15\' NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ta CHANGE l l NUMERIC(1, 1) NOT NULL, CHANGE w w NUMERIC(1, 1) NOT NULL, CHANGE h h NUMERIC(1, 1) NOT NULL, CHANGE v v NUMERIC(1, 1) NOT NULL, CHANGE stp stp NUMERIC(3, 2) DEFAULT \'0.15\' NOT NULL, CHANGE t t NUMERIC(3, 2) DEFAULT \'2.50\' NOT NULL');
    }
}
