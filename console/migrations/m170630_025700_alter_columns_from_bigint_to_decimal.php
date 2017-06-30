<?php

use yii\db\Migration;

class m170630_025700_alter_columns_from_bigint_to_decimal extends Migration
{
    public function safeUp()
    {
        // change all big integer columns to decimal
        $this->alterColumn('order', 'tax', $this->decimal(11,2)->defaultValue(0.00));
        $this->alterColumn('order', 'total_price', $this->decimal(11,2)->defaultValue(0.00));
        $this->alterColumn('product', 'price', $this->decimal(11,2)->notNull()->defaultValue(0.00));
        $this->alterColumn('product_attribute', 'price', $this->decimal(11,2)->defaultValue(0.00));
        $this->alterColumn('order_item', 'unit_price', $this->decimal(11,2)->notNull()->defaultValue(0.00));

        // quantity can be a float number
        $this->alterColumn('order_item', 'quantity', $this->decimal(11,2)->notNull()->defaultValue(1.00));
    }

    public function safeDown()
    {
        $this->alterColumn('order', 'tax', $this->bigInteger()->notNull()->defaultValue(0));
        $this->alterColumn('order', 'total_price', $this->bigInteger()->notNull()->defaultValue(0));
        $this->alterColumn('product', 'price', $this->bigInteger()->notNull()->defaultValue(0));
        $this->alterColumn('product_attribute', 'price', $this->bigInteger()->notNull()->defaultValue(0));
        $this->alterColumn('order_item', 'unit_price', $this->bigInteger()->notNull()->defaultValue(0));

        $this->alterColumn('order_item', 'quantity', $this->bigInteger()->notNull()->defaultValue(1));
    }
}
