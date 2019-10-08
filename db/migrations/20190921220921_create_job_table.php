<?php

use Phinx\Migration\AbstractMigration;

class CreateJobTable extends AbstractMigration
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
        $table = $this->table('jobs',[
            'id' => false,
            'primary_key' => 'id_job'
        ]);
        $table
            ->addColumn('id_job','integer',[
                'signed' => false,
                'identity' => true
            ])
            ->addColumn('id_user','integer',[ 'signed' => false ])
            ->addColumn('title','string')
            ->addColumn('description','text')
            ->addColumn('visible','boolean')
            ->addColumn('months','integer')
            ->addColumn('image','string')
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->addColumn('deleted_at','datetime',[
                'null' => true,
                'default' => null
            ])
            ->addForeignKey('id_user','users','id_user',[
                'delete' => 'NO_ACTION',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}
