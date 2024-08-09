<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Html;

class Author extends ActiveRecord
{

    public static function tableName()
    {
        return '{{author}}';
    }

    public function rules()
    {
        return [
            [['first_name', 'second_name'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя автор',
            'second_name' => 'Фамилия автора',
            'middle_name' => 'Отчество автора',
        ];
    }

    public function getAuthorBooks() {
        return $this->hasMany(AuthorBook::class, ['author_id' => 'id']);
    }

    public function getSubscribe() {
        return $this->hasOne(Subscribe::class, ['author_id' => 'id'])->andOnCondition(['user_id' => Yii::$app->user->getId()]);
    }

    public function getSubscribers() {
        return $this->hasMany(Subscribe::class, ['author_id' => 'id']);
    }

    /**
     * integer $AuthorId
     * @return Author|null
     */
    public static function findAuthorById($authorId) :? Author
    {
        return Author::findOne(['id' => $authorId]);
    }

    public function notifySubscribers(Book $book = null) {
        foreach($this->subscribers as $subscriber) {
            $text = urlencode('У автора '.$this->first_name.' '.$this->second_name.' появилась новая книга: '.$book->title);
            $url = 'https://smspilot.ru/api.php?send='.$text.'&to='. $subscriber->user->phone.'&apikey='.Yii::$app->params['smspilot_apikey'].'&format=v';
            file_get_contents($url);
        }
    }



}