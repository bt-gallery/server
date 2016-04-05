<?php

use Phinx\Migration\AbstractMigration;

class CreateFinalViews extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $queryPath = __DIR__ . "/final_child.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);

        $queryPath = __DIR__ . "/final_junior.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);

        $queryPath = __DIR__ . "/final_teen.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);
    }

    public function down()
    {
        $query = "DROP VIEW final_child";
        $this->query($query);

        $query = "DROP VIEW final_junior";
        $this->query($query);

        $query = "DROP VIEW final_teen";
        $this->query($query);
    }
}
