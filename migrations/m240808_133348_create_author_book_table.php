<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_book}}`.
 */
class m240808_133348_create_author_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_book}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer(10)->notNull(),
            'book_id' => $this->integer(10)->notNull(),
        ]);

        $this->createIndex(
            'idx-author_id',
            'author_book',
            'author_id'
        );
        $this->addForeignKey(
            'idx-author_id',
            'author_book',
            'author_id',
            'author',
            'id'
        );

        $this->createIndex(
            'idx-book_id',
            'author_book',
            'book_id'
        );
        $this->addForeignKey(
            'idx-book_id',
            'author_book',
            'book_id',
            'book',
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
            'idx-book_id',
            'author_book'
        );
        $this->dropIndex(
            'idx-book_id',
            'author_book'
        );

        $this->dropForeignKey(
            'idx-author_id',
            'author_book'
        );
        $this->dropIndex(
            'idx-author_id',
            'author_book'
        );

        $this->dropTable('{{%author_book}}');
    }
}
