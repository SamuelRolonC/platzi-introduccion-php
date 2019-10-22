<?php

use Phinx\Migration\AbstractMigration;

class CreateMessageTable extends AbstractMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('messages',[
            'id' => false,
            'primary_key' => 'id_message'
        ]);
        $table
            ->addColumn('id_message','integer',[
                'signed' => false,
                'identity' => true
            ])
            ->addColumn('name','string')
            ->addColumn('email','string')
            ->addColumn('body','text')
            ->addColumn('sent','boolean')
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->addColumn('id_user','integer',['signed' => false])
            ->addForeignKey('id_user','users','id_user',[
                'delete' => 'NO_ACTION',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}
