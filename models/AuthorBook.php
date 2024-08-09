<?php

namespace app\models;

use yii\db\ActiveRecord;

class AuthorBook extends ActiveRecord
{
    public static function tableName()
    {
        return '{{author_book}}';
    }

    public function rules()
    {
        return [
            [['book_id', 'author_id'], 'unique', 'targetAttribute' => ['book_id', 'author_id']],
        ];
    }

    public function getBook() {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    public function getAuthor() {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }


}