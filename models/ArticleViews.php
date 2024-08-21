<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Html;

class ArticleViews extends ActiveRecord
{

    public static function tableName()
    {
        return '{{article_views}}';
    }

    public static function primaryKey() {
        return ['article_id', 'user_id', 'date'];
    }

    public function rules()
    {
        return [
        ];
    }

    public function attributeLabels()
    {
        return [
            'article_id' => 'ID Статьи',
            'user_id' => 'ID пользователя',
            'date' => 'Дата прочтения',
            'count_views' => 'Кол-во просмотров',
        ];
    }

    public function beforeSave($insert) {
        if (!$this->user_id) {
            $this->user_id = 0;
        }
        return parent::beforeSave($insert);
    }

    
}