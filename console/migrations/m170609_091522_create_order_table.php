<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170609_091522_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id'            => $this->primaryKey(),
            'customer_id'   => $this->integer(),
            'outlet_id'     => $this->integer(),
            'code'          => $this->string(20),
            'tax'           => $this->bigInteger()->defaultValue(0),
            'total_price'   => $this->bigInteger()->defaultValue(0),
            'status'        => $this->string(50),
            'delivery_time' => $this->integer(),
            'note'          => $this->string(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'fk-order-customer_id', // => foreign key name
            'order', //                => current table
            'customer_id', //          => current table field
            'customer', //             => reference table
            'id', //                   => reference table field
            'SET NULL', //             => ON DELETE
            'CASCADE' //               => ON UPDATE
        );

        $this->addForeignKey(
            'fk-order-outlet_id', // => foreign key name
            'order', //              => current table
            'outlet_id', //          => current table field
            'outlet', //             => reference table
            'id', //                 => reference table field
            'SET NULL', //           => ON DELETE
            'CASCADE' //             => ON UPDATE
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        $this->dropForeignKey('fk-order-customer_id', 'order');
        $this->dropForeignKey('fk-order-outlet_id', 'order');
        $this->dropTable('order');
    }
}
