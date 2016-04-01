<?php

use Phinx\Migration\AbstractMigration;

class AddPriority extends AbstractMigration
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
        $table = $this->table('competitive_work');
        $table->addColumn('priority', 'integer', array('null' => true, ))
              ->update();
              
        $queryPath = __DIR__ . "/moderation_stack_filtered_with_priority.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);

        // Update dependent
        $queryPath = __DIR__ . "/moderation_stack_grouped.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);
    }

    public function down()
    {
        $queryPath = __DIR__ . "/moderation_stack_filtered.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);

        // Update dependent
        $queryPath = __DIR__ . "/moderation_stack_grouped.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);

        $table = $this->table('competitive_work');
        $table->removeColumn('priority')
              ->update();
    }
}
