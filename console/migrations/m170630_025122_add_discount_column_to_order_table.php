<?php

use yii\db\Migration;

/**
 * Handles adding discount to table `order`.
 */
class m170630_025122_add_discount_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order', 'discount', $this->decimal(11,2)->defaultValue(0.00) . ' AFTER tax');
        $this->addColumn('order_item', 'discount', $this->decimal(11,2)->defaultValue(0.00) . ' AFTER quantity');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order', 'discount');
        $this->dropColumn('order_item', 'discount');
    }
}
