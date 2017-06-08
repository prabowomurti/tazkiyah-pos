<?php

use yii\db\Migration;

/**
 * Handles adding role to table `user`.
 */
class m170607_080933_add_role_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'role', 'VARCHAR(50) AFTER `email` ');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'role');
    }
}
