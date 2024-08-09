<?php

namespace app\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{

    public $author_ids;
    public $bookPhotoPath = '/app/web/uploads/';

    public static function tableName()
    {
        return '{{book}}';
    }

    public function rules()
    {
        return [
            [['isbn', 'title', 'year', 'author_ids'], 'required'],
            [['isbn'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название книги',
            'year' => 'Год выпуска',
            'photo' => 'Фото обложки',
            'author_ids' => 'Авторы',
        ];
    }

    public function getAuthorBooks() {
        return $this->hasMany(AuthorBook::class, ['book_id' => 'id']);
    }

    /**
     * integer $bookId
     * @return Book|null
     */
    public static function findBookById($bookId) :? Book
    {
        return Book::findOne(['id' => $bookId]);
    }

    

}