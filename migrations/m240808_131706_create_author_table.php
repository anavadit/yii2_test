<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author}}`.
 */
class m240808_131706_create_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(100)->notNull(),
            'second_name' => $this->string(100)->notNull(),
            'middle_name' => $this->string(100),
        ]);

        $this->insert('{{%author}}', [
            'first_name' => 'Marta',
            'second_name' => 'Stuart',
        ]);

        $this->insert('{{%author}}', [
            'first_name' => 'Leo',
            'second_name' => 'Kavis',
        ]);

        $this->insert('{{%author}}', [
            'first_name' => 'Dora',
            'second_name' => 'Mill',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%author}}', ['id' => 3]);
        $this->delete('{{%author}}', ['id' => 2]);
        $this->delete('{{%author}}', ['id' => 1]);
        $this->dropTable('{{%author}}');
    }
}
