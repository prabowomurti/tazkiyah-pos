<?php

use yii\db\Migration;

/**
 * Handles adding barcode to table `product`.
 */
class m170807_232419_add_barcode_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product', 'barcode', $this->string(255) . ' AFTER `label`');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product', 'barcode');
    }
}
