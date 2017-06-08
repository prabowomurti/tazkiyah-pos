<?php

use yii\db\Migration;

class m170607_180043_remove_username_index_from_user_table extends Migration
{
    public function up()
    {
        $this->dropIndex('username', 'user');
    }

    public function down()
    {
        $this->createIndex('username', 'user', 'username', $unique = true);
    }
}
