<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer`.
 */
class m170609_090646_create_customer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('customer', [
            'id'         => $this->primaryKey(),
            'username'   => $this->string()->notNull(),
            'phone'      => $this->string(),
            'address'    => $this->text(),
            'gender'     => $this->string(20),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('customer');
    }
}
