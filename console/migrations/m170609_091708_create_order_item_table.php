<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_item`.
 */
class m170609_091708_create_order_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_item', [
            'id'                   => $this->primaryKey(),
            'order_id'             => $this->integer(),
            'product_id'           => $this->integer(),
            'product_label'        => $this->string(),
            'product_attribute_id' => $this->integer(),
            'quantity'             => $this->integer()->defaultValue(0),
            'unit_price'           => $this->bigInteger()->defaultValue(0),
            'note'                 => $this->string(),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-order_item-order_id', // => foreign key name
            'order_item', //             => current table
            'order_id', //               => current table field
            'order', //                  => reference table
            'id', //                     => reference table field
            'SET NULL', //               => ON DELETE
            'CASCADE' //                 => ON UPDATE
        );

        $this->addForeignKey(
            'fk-order_item-product_id', // => foreign key name
            'order_item', //               => current table
            'product_id', //               => current table field
            'product', //                  => reference table
            'id', //                       => reference table field
            'SET NULL', //                 => ON DELETE
            'CASCADE' //                   => ON UPDATE
        );

        $this->addForeignKey(
            'fk-order_item-product_attribute_id', // => foreign key name
            'order_item', //                         => current table
            'product_attribute_id', //               => current table field
            'product_attribute', //                  => reference table
            'id', //                                 => reference table field
            'SET NULL', //                           => ON DELETE
            'CASCADE' //                             => ON UPDATE
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-order_item-order_id', 'order_item');
        $this->dropForeignKey('fk-order_item-product_id', 'order_item');
        $this->dropForeignKey('fk-order_item-product_attribute_id', 'order_item');
        $this->dropTable('order_item');
    }
}
