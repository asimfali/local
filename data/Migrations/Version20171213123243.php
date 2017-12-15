<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171213123243 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE seria (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, perfomance VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_curtain (id INT AUTO_INCREMENT NOT NULL, alias VARCHAR(2) NOT NULL, name VARCHAR(30) NOT NULL, `desc` VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE passport ADD typeCurtain INT DEFAULT NULL, DROP type_curtain, CHANGE seria seria INT DEFAULT NULL');
        $this->addSql('ALTER TABLE passport ADD CONSTRAINT FK_B5A26E0859EB1991 FOREIGN KEY (typeCurtain) REFERENCES type_curtain (id)');
        $this->addSql('ALTER TABLE passport ADD CONSTRAINT FK_B5A26E08AD57572D FOREIGN KEY (seria) REFERENCES seria (id)');
        $this->addSql('CREATE INDEX IDX_B5A26E0859EB1991 ON passport (typeCurtain)');
        $this->addSql('CREATE INDEX IDX_B5A26E08AD57572D ON passport (seria)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE passport DROP FOREIGN KEY FK_B5A26E08AD57572D');
        $this->addSql('ALTER TABLE passport DROP FOREIGN KEY FK_B5A26E0859EB1991');
        $this->addSql('DROP TABLE seria');
        $this->addSql('DROP TABLE type_curtain');
        $this->addSql('DROP INDEX IDX_B5A26E0859EB1991 ON passport');
        $this->addSql('DROP INDEX IDX_B5A26E08AD57572D ON passport');
        $this->addSql('ALTER TABLE passport ADD type_curtain INT NOT NULL, DROP typeCurtain, CHANGE seria seria VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci');
    }
}
