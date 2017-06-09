<?php

use yii\db\Migration;

/**
 * Handles the creation of table `employee`.
 */
class m170609_091844_create_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('employee', [
            'id'        => $this->primaryKey(),
            'user_id'   => $this->integer(),
            'outlet_id' => $this->integer(),
        ]);

        // add foreign key to user 
        $this->addForeignKey(
            'fk-employee-user_id', // => foreign key name
            'employee', //            => current table
            'user_id', //             => current table field
            'user', //                => reference table
            'id', //                  => reference table field
            'SET NULL', //            => ON DELETE
            'CASCADE' //              => ON UPDATE
        );

        // add foreign key to outlet 
        $this->addForeignKey(
            'fk-employee-outlet_id', // => foreign key name
            'employee', //              => current table
            'outlet_id', //             => current table field
            'outlet', //                => reference table
            'id', //                    => reference table field
            'SET NULL', //              => ON DELETE
            'CASCADE' //                => ON UPDATE
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-employee-user_id', 'employee');
        $this->dropForeignKey('fk-employee-outlet_id', 'employee');
        $this->dropTable('employee');
    }
}
