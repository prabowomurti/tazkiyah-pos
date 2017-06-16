<?php

use yii\db\Migration;

class m170616_040304_alter_column_order extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('order', 'delivery_time', $this->dateTime());
    }

    public function safeDown()
    {
        $this->alterColumn('order', 'delivery_time', $this->integer());
    }
}
