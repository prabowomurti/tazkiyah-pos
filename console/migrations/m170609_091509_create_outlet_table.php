<?php

use yii\db\Migration;

/**
 * Handles the creation of table `outlet`.
 */
class m170609_091509_create_outlet_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('outlet', [
            'id'        => $this->primaryKey(),
            'label'     => $this->string()->notNull(),
            'address'   => $this->text(),
            'latitude'  => $this->double(),
            'longitude' => $this->double(),
            'phone'     => $this->string(50),
            'status'    => $this->string(100),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('outlet');
    }
}
