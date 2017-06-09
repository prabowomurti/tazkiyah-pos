<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_attribute`.
 */
class m170609_090955_create_product_attribute_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product_attribute', [
            'id'         => $this->primaryKey(),
            'product_id' => $this->integer(),
            'price'      => $this->bigInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-product_attribute-product_id', // => foreign key name
            'product_attribute', //               => current table
            'product_id', //                      => current table field
            'product', //                         => reference table
            'id', //                              => reference table field
            'SET NULL', //                        => ON DELETE
            'CASCADE' //                          => ON UPDATE
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-product_attribute-product_id', 'product_attribute');
        $this->dropTable('product_attribute');
    }
}
