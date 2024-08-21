<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Articles;
use app\models\ArticleViews;
use app\models\ContactForm;
use yii\web\UploadedFile;
use yii\base\UserException;
use yii\web\HttpException;

class TestController extends Controller
{

    public function actionArticles() 
    {
        $arts = Articles::find()->all();
        return $this->render('articles', ['arts' => $arts]);
    }


    public function actionArticle($id) 
    {
        $art = Articles::findOne(['id' => $id]);
        $user = Yii::$app->user;

        if ($art) {
            $userId = $user->getId() ?  $user->getId() : 0;
            $artView = ArticleViews::findOne([
                'user_id' => $userId,
                'article_id' => $art->id,
                'date' => date('Y-m-d')
            ]);
            if (!$artView) {
                $artView = new ArticleViews();
                $artView->user_id = $userId;
                $artView->article_id = $art->id;
                $artView->date = date('Y-m-d');
            }
            $artView->count_views += 1;

            try {
                if ($userId) {
                    $artView->update();
                } else {
                    $artView->save();
                }
            } catch (\Exception $e) {
                throw new HttpException(404, "Ошибка сохранения просмотра.");
            }
        }

        return $this->render('article', ['art' => $art]);
    }

    public function actionArticleViews() {
        $artViews = ArticleViews::find()->all();
        return $this->render('articleViews', ['artViews' => $artViews]);
    }
}