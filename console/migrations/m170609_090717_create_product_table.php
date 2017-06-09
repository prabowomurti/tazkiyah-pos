<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m170609_090717_create_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product', [
            'id'          => $this->primaryKey(),
            'label'       => $this->string()->notNull(),
            'description' => $this->string(),
            'price'       => $this->bigInteger()->notNull()->defaultValue(0),
            'visible'     => $this->boolean()->defaultValue(1),
            'position'    => $this->integer()->defaultValue(0),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product');
    }
}
