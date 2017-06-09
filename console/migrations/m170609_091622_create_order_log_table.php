<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_log`.
 */
class m170609_091622_create_order_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_log', [
            'id'         => $this->primaryKey(),
            'order_id'   => $this->integer(),
            'status'     => $this->string(50),
            'user_id'    => $this->integer(),
            'note'       => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-order_log-order_id', // => foreign key name
            'order_log', //             => current table
            'order_id', //              => current table field
            'order', //                 => reference table
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
        $this->dropForeignKey('fk-order_log-order_id', 'order_log');
        $this->dropTable('order_log');
    }
}
