<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171226115536 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE defect (id INT AUTO_INCREMENT NOT NULL, description INT DEFAULT NULL, action INT DEFAULT NULL, number INT NOT NULL, date DATE NOT NULL, drawing VARCHAR(20) DEFAULT NULL, type VARCHAR(20) NOT NULL, count INT NOT NULL, INDEX IDX_3A9C38876DE44026 (description), INDEX IDX_3A9C388747CC8C92 (action), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE listdef (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(20) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE defect ADD CONSTRAINT FK_3A9C38876DE44026 FOREIGN KEY (description) REFERENCES listdef (id)');
        $this->addSql('ALTER TABLE defect ADD CONSTRAINT FK_3A9C388747CC8C92 FOREIGN KEY (action) REFERENCES action (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE defect DROP FOREIGN KEY FK_3A9C388747CC8C92');
        $this->addSql('ALTER TABLE defect DROP FOREIGN KEY FK_3A9C38876DE44026');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE defect');
        $this->addSql('DROP TABLE listdef');
    }
}
