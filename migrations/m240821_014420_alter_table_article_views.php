<?php

use yii\db\Migration;

/**
 * Class m240821_014420_alter_table_article_views
 */
class m240821_014420_alter_table_article_views extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_id', '{{%article_views}}', ['date', 'user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_id', '{{%article_views}}');
    }

}
