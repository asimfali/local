<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171227045537 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Part (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, number VARCHAR(20) NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_E93DDFF8727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Part ADD CONSTRAINT FK_E93DDFF8727ACA70 FOREIGN KEY (parent_id) REFERENCES Part (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Part DROP FOREIGN KEY FK_E93DDFF8727ACA70');
        $this->addSql('DROP TABLE Part');
    }
}
