<?php

use yii\db\Migration;

/**
 * Handles the creation of table `client`.
 */
class m170729_065236_create_client_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('client', [
            'id'           => $this->primaryKey(),
            'client_name'  => $this->string(100)->notNull()->unique(),
            'api_key'      => $this->string(255)->notNull(),
            'label'        => $this->string(50)->notNull(),
            'db_name'      => $this->string(30)->notNull()->unique(),
            'db_username'  => $this->string(30)->notNull(),
            'db_password'  => $this->string(255)->notNull(),
            'client_token' => $this->string(255),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('client');
    }
}
