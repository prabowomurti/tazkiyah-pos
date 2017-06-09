<?php

use yii\db\Migration;

/**
 * Handles the creation of table `setting`.
 */
class m170609_091719_create_setting_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('setting', [
            'id'    => $this->primaryKey(),
            'key'   => $this->string(50)->notNull(),
            'value' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('setting');
    }
}
