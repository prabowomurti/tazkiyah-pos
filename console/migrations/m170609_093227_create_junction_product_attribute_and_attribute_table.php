<?php

use yii\db\Migration;

/**
 * Handles the creation of table `attribute_combination`.
 * Has foreign keys to the tables:
 *
 * - `product_attribute`
 * - `attribute`
 */
class m170609_093227_create_junction_product_attribute_and_attribute_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('attribute_combination', [
            'product_attribute_id' => $this->integer(),
            'attribute_id' => $this->integer(),
            'PRIMARY KEY(product_attribute_id, attribute_id)',
        ]);

        // creates index for column `product_attribute_id`
        $this->createIndex(
            'idx-attribute_combination-product_attribute_id',
            'attribute_combination',
            'product_attribute_id'
        );

        // add foreign key for table `product_attribute`
        $this->addForeignKey(
            'fk-attribute_combination-product_attribute_id',
            'attribute_combination',
            'product_attribute_id',
            'product_attribute',
            'id',
            'CASCADE'
        );

        // creates index for column `attribute_id`
        $this->createIndex(
            'idx-attribute_combination-attribute_id',
            'attribute_combination',
            'attribute_id'
        );

        // add foreign key for table `attribute`
        $this->addForeignKey(
            'fk-attribute_combination-attribute_id',
            'attribute_combination',
            'attribute_id',
            'attribute',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `product_attribute`
        $this->dropForeignKey(
            'fk-attribute_combination-product_attribute_id',
            'attribute_combination'
        );

        // drops index for column `product_attribute_id`
        $this->dropIndex(
            'idx-attribute_combination-product_attribute_id',
            'attribute_combination'
        );

        // drops foreign key for table `attribute`
        $this->dropForeignKey(
            'fk-attribute_combination-attribute_id',
            'attribute_combination'
        );

        // drops index for column `attribute_id`
        $this->dropIndex(
            'idx-attribute_combination-attribute_id',
            'attribute_combination'
        );

        $this->dropTable('attribute_combination');
    }
}
