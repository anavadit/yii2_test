<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Html;

class Articles extends ActiveRecord
{

    public static function tableName()
    {
        return '{{articles}}';
    }

    public function rules()
    {
        return [
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID Статьи',
            'content' => 'Содержание',
        ];
    }
    
}