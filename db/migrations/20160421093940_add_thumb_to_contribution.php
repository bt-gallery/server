<?php

use Phinx\Migration\AbstractMigration;

class AddThumbToContribution extends AbstractMigration
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
        $table->addColumn('thumb_store_path', 'string', array('limit' => 255, 'null' => true))
              ->addColumn('thumb_web_path', 'string', array('limit' => 255, 'null' => true))
              ->update();

        $queryPath = __DIR__ . "/contribution_signed_thumb.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);

    }
    public function down()
    {
        $queryPath = __DIR__ . "/contribution_signed.sql";
        $query = file_get_contents($queryPath);
        $this->query($query);

        $table = $this->table('contribution');
        $table->removeColumn('thumb_store_path')
              ->removeColumn('thumb_web_path')
              ->update();
    }
}
