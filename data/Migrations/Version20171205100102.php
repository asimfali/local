<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171205100102 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv DROP FOREIGN KEY FK_D4F16CC8A76ED395');
        $this->addSql('DROP INDEX IDX_D4F16CC8A76ED395 ON izv');
        $this->addSql('ALTER TABLE izv CHANGE user_id usr_first_name INT DEFAULT NULL');
        $this->addSql('ALTER TABLE izv ADD CONSTRAINT FK_D4F16CC8D1252B68 FOREIGN KEY (usr_first_name) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D4F16CC8D1252B68 ON izv (usr_first_name)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE izv DROP FOREIGN KEY FK_D4F16CC8D1252B68');
        $this->addSql('DROP INDEX IDX_D4F16CC8D1252B68 ON izv');
        $this->addSql('ALTER TABLE izv CHANGE usr_first_name user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE izv ADD CONSTRAINT FK_D4F16CC8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D4F16CC8A76ED395 ON izv (user_id)');
    }
}
