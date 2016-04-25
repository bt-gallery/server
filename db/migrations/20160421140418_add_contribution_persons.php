<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class AddContributionPersons extends AbstractMigration
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
        $table = $this->table('contribution');
        $table->addColumn('id_declarant', 'integer', array('after' => 'time','limit' => 11, 'null' => true))
              ->addColumn('persons', 'string', array('after' => 'description', 'null' => true))
              ->addIndex(array('id_declarant'))
              ->addForeignKey('id_declarant', 'declarant', 'id', array('delete'=> 'RESTRICT', 'update'=> 'RESTRICT'))
              ->update();

        $queryPath = __DIR__ . "/contribution_signed_persons.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);
    }
    public function down()
    {
        $table = $this->table('contribution');
        $table->dropForeignKey('id_declarant')
              ->removeIndex(array('id_declarant'))
              ->removeColumn('persons')
              ->removeColumn('id_declarant')
              ->update();

        $queryPath = __DIR__ . "/contribution_signed_thumb.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);
    }
}
