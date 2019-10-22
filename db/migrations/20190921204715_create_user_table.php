<?php

use Phinx\Migration\AbstractMigration;

class CreateUserTable extends AbstractMigration
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
        $table = $this->table('users',[
            'id' => false,
            'primary_key' => 'id_user'
        ]);
        $table
            ->addColumn('id_user','integer',[
                'signed' => false,
                'identity' => true
            ])
            ->addColumn('username','string')
            ->addColumn('password','string')
            ->addColumn('name','string')
            ->addColumn('lastname','string')
            ->addColumn('email','string')
            ->addColumn('phone','biginteger',[
                'signed' => false,
                'null' => true,
                'default' => null
            ])
            ->addColumn('summary','text')
            ->addColumn('image','string',[
                'null' => true,
                'default' => null
            ])
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->addIndex(['username','email'],['unique' => true])
            ->create();
    }
}
