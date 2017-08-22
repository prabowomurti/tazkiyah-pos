<?php

use yii\db\Migration;

/**
 * Handles adding status to table `order_item`.
 */
class m170822_090809_add_status_column_to_order_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order_item', 'status', $this->string(50) . ' AFTER `unit_price`');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order_item', 'status');
    }
}
