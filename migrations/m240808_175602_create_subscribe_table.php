<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscribe}}`.
 */
class m240808_175602_create_subscribe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscribe}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(10)->notNull(),
            'author_id' => $this->integer(10)->notNull()
        ]);

        $this->createIndex(
            'idx-user_id',
            'subscribe',
            'user_id'
        );
        $this->addForeignKey(
            'idx-user_id',
            'subscribe',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-uauthor_id',
            'subscribe',
            'author_id'
        );
        $this->addForeignKey(
            'idx-uauthor_id',
            'subscribe',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'idx-uauthor_id',
            'subscribe'
        );
        $this->dropIndex(
            'idx-uauthor_id',
            'subscribe'
        );

        $this->dropForeignKey(
            'idx-user_id',
            'subscribe'
        );
        $this->dropIndex(
            'idx-user_id',
            'subscribe'
        );
        $this->dropTable('{{%subscribe}}');
    }
}
