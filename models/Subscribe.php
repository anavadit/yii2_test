<?php

namespace app\models;

use yii\db\ActiveRecord;

class Subscribe extends ActiveRecord
{
    public static function tableName()
    {
        return '{{subscribe}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'author_id'], 'unique', 'targetAttribute' => ['user_id', 'author_id']],
        ];
    }

    public function getAuthor() {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}