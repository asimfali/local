<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171103085046 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription()
    {
        $description = 'Migration to role';
        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // role table
        $table = $schema->createTable('role');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('description', 'string', ['notnull' => true, 'length' =>1024]);
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'name_idx');
        $table->addOption('engine', 'InnoDB');
        // role_hierarchy
        $table = $schema->createTable('role_hierarchy');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('parent_role_id', 'integer', ['notnull' => true]);
        $table->addColumn('child_role_id', 'integer', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('role', ['parent_role_id'],['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE'], 'role_role_parent_role_id_fk');
        $table->addForeignKeyConstraint('role', ['child_role_id'],['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE'], 'role_role_child_role_id_fk');
        $table->addOption('engine', 'InnoDB');
        // permission
        $table = $schema->createTable('permission');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('description', 'string', ['notnull' => true, 'length' =>1024]);
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name'], 'name_idx');
        $table->addOption('engine', 'InnoDB');
        // role_permission
        $table = $schema->createTable('role_permission');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('role_id', 'integer', ['notnull' => true]);
        $table->addColumn('permission_id', 'integer', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('role', ['role_id'],['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE'], 'role_permission_role_id_fk');
        $table->addForeignKeyConstraint('permission', ['permission_id'],['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE'], 'role_permission_permission_id_fk');
        $table->addOption('engine', 'InnoDB');
        // user_role
        $table = $schema->createTable('user_role');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('user_id', 'integer', ['notnull' => true]);
        $table->addColumn('role_id', 'integer', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('user', ['user_id'],['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE'], 'user_role_user_id_fk');
        $table->addForeignKeyConstraint('role', ['role_id'],['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE'], 'user_role_role_id_fk');
        $table->addOption('engine', 'InnoDB');
        // izv_content table
        $table = $schema->createTable('izv_content');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' =>128]);
        $table->addColumn('content', 'string', ['notnull' => true, 'length' =>1024]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('izv', ['content_id'],['id'],
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
        $schema->dropTable('user_role');
        $schema->dropTable('role_permission');
        $schema->dropTable('permission');
        $schema->dropTable('role');
    }
}
