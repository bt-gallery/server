<?php

use Phinx\Migration\AbstractMigration;

class StairwayToModeration extends AbstractMigration
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
    public function change()
    {
        $table = $this->table('stairway_to_moderation');
        $table->addColumn('time', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
              ->addColumn('id_contribution', 'integer', array('limit' => 11, 'null' => true))
              ->addColumn('ticket', 'integer', array('limit' => 11, 'null' => true))
              ->create();
        $table->addIndex(array('id_contribution'))
              ->addForeignKey('id_contribution', 'contribution', 'id', array('delete'=> 'RESTRICT', 'update'=> 'RESTRICT'))
              ->save();
    }
}
