<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171213072412 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE passport (id INT AUTO_INCREMENT NOT NULL, tu INT DEFAULT NULL, creator INT DEFAULT NULL, status INT DEFAULT NULL, name VARCHAR(50) NOT NULL, type_curtain INT NOT NULL, seria VARCHAR(50) NOT NULL, date DATE NOT NULL, number VARCHAR(20) NOT NULL, password VARCHAR(20) NOT NULL, INDEX IDX_B5A26E082AE52BBE (tu), INDEX IDX_B5A26E08BC06EA63 (creator), INDEX IDX_B5A26E087B00651C (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tu (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, name VARCHAR(30) NOT NULL, `desc` VARCHAR(100) NOT NULL, alias VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE passport ADD CONSTRAINT FK_B5A26E082AE52BBE FOREIGN KEY (tu) REFERENCES tu (id)');
        $this->addSql('ALTER TABLE passport ADD CONSTRAINT FK_B5A26E08BC06EA63 FOREIGN KEY (creator) REFERENCES user (id)');
        $this->addSql('ALTER TABLE passport ADD CONSTRAINT FK_B5A26E087B00651C FOREIGN KEY (status) REFERENCES status (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE passport DROP FOREIGN KEY FK_B5A26E082AE52BBE');
        $this->addSql('DROP TABLE passport');
        $this->addSql('DROP TABLE tu');
    }
}
