<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m240807_202711_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull(),
            'password' => $this->string(100)->notNull(),
            'authKey' => $this->string(100),
            'accessToken' => $this->string(100),
            'phone' => $this->bigInteger(100),
        ]);

        $this->insert('{{%user}}', [
            'username' => 'user',
            'password' => md5('user'),
            'authKey' => 'test100key',
            'accessToken' => '100-token',
            'phone' => '79956668844'
        ]);

        $this->insert('{{%user}}', [
            'username' => 'guest',
            'password' => md5('guest'),
            'authKey' => 'test101key',
            'accessToken' => '101-token',
            'phone' => '79955556633'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['id' => 2]);
        $this->delete('{{%user}}', ['id' => 1]);
        $this->dropTable('{{%user}}');
    }
}
