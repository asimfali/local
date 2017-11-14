<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171114123129 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
// izv_content table
        $table = $schema->createTable('izv');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('date', 'date', ['notnull' => true]);
        $table->addColumn('number', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('reason', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('appendix', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('content_id', 'integer', ['notnull' => false]);
        $table->addColumn('zadel', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('impl', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('creator', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('department_id', 'integer', ['notnull' => false]);
        $table->addColumn('templates_id', 'integer' , ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['date'], 'date_idx');
        $table->addOption('engine', 'InnoDB');

        // department
        $table = $schema->createTable('department');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string',['notnull' => true, 'length' =>128]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'name_idx');
        $table->addForeignKeyConstraint('izv', ['id'], ['department_id'],
            ['onDelete' => 'RESTRICT', 'onUpdate' => 'RESTRICT'], 'izv_department_id_fk');
        $table->addOption('engine', 'InnoDB');

        // templates
        $table = $schema->createTable('templates');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('check', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('TechControl', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('HeadTech', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('approved', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('NZG', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('NUG', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('NUVent', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('NUManufaction', 'string',['notnull' => true, 'length' =>128]);
        $table->addColumn('OKK', 'string',['notnull' => true, 'length' =>128]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('izv', ['id'], ['templates_id'],
            ['onDelete' => 'RESTRICT', 'onUpdate' => 'RESTRICT'], 'izv_templates_id_fk');
        $table->addUniqueIndex(['name'], 'name_idx');
        $table->addOption('engine', 'InnoDB');

        // izv_content table
        $table = $schema->createTable('izv_content');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('content', 'string', ['notnull' => true, 'length' =>1024]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('izv', ['id'], ['content_id'],
            ['onDelete' => 'RESTRICT', 'onUpdate' => 'RESTRICT'], 'izv_content_id_fk');
        $table->addUniqueIndex(['name'], 'name_idx');
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('izv');
        $schema->dropTable('templates');
        $schema->dropTable('department');
        $schema->dropTable('izv_content');
    }
}
