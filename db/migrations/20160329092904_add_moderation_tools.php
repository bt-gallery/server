<?php

use Phinx\Migration\AbstractMigration;

class AddModerationTools extends AbstractMigration
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
        $resultTextLength = 40;

        $rows = [
            [
                "text" => "одобрено"
            ],
            [
                "text" => "отклонено"
            ]
        ];
        $table = $this->table('moderation_result', array('id' => 'id_moderation_result'));
        $table->addColumn('text', 'string', array('limit' => $resultTextLength))
            ->insert($rows)
            ->create();

        $rows = [
            [
                "text" => "повторная"
            ],
            [
                "text" => "не соответсвует условиям"
            ],
            [
                "text" => "не соответсвует тематике"
            ],
            [
                "text" => "недостаточный размер изображения"
            ]
        ];
        $table = $this->table('moderation_notice', array('id' => 'id_moderation_notice'));
        $table->addColumn('text', 'string', array('limit' => 40))
            ->insert($rows)
            ->create();

        $table = $this->table('moderation_process', array('id' => false));
        $table->addColumn('id_competitive_work', 'integer', array('limit' => 11))
            ->addColumn('result', 'string', array('limit' => $resultTextLength))
            ->addColumn('notice', 'string', array('limit' => $resultTextLength))
            ->addIndex('id_competitive_work', ['type' => 'btree'])->create();
    }
}
