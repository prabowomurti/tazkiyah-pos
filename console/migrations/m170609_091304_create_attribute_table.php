<?php

use yii\db\Migration;

/**
 * Handles the creation of table `attribute`.
 */
class m170609_091304_create_attribute_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('attribute', [
            'id'       => $this->primaryKey(),
            'label'    => $this->string()->notNull(),
            'tree'     => $this->integer(),
            'lft'      => $this->integer()->notNull(),
            'rgt'      => $this->integer()->notNull(),
            'depth'    => $this->integer()->notNull(),
            'position' => $this->integer()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('attribute');
    }
}
