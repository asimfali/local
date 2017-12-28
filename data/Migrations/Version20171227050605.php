<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171227050605 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE parts (part_id INT NOT NULL, parts_id INT NOT NULL, INDEX IDX_6940A7FE4CE34BEC (part_id), INDEX IDX_6940A7FE4E81F03D (parts_id), PRIMARY KEY(part_id, parts_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parts ADD CONSTRAINT FK_6940A7FE4CE34BEC FOREIGN KEY (part_id) REFERENCES Part (id)');
        $this->addSql('ALTER TABLE parts ADD CONSTRAINT FK_6940A7FE4E81F03D FOREIGN KEY (parts_id) REFERENCES Part (id)');
        $this->addSql('ALTER TABLE part DROP FOREIGN KEY FK_E93DDFF8727ACA70');
        $this->addSql('DROP INDEX IDX_E93DDFF8727ACA70 ON part');
        $this->addSql('ALTER TABLE part DROP parent_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE parts');
        $this->addSql('ALTER TABLE Part ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Part ADD CONSTRAINT FK_E93DDFF8727ACA70 FOREIGN KEY (parent_id) REFERENCES part (id)');
        $this->addSql('CREATE INDEX IDX_E93DDFF8727ACA70 ON Part (parent_id)');
    }
}
