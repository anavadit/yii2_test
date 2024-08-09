<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Author;

$this->title = 'Добавить книгу';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        $form = ActiveForm::begin([
            'id' => 'add_book_form',
            'options' => [
                'class' => 'form-horizontal', 
                'enctype' => 'multipart/form-data'
            ],
            'action' => '/add-book',
            'method' => 'post'
        ]);
    ?>

    <?php echo $form->field($bookModel, 'isbn')->input('text'); ?>
    <?php echo $form->field($bookModel, 'title')->input('text'); ?>
    <?php echo $form->field($bookModel, 'year')->input('text'); ?>

    <?php echo $form->field($bookModel, 'author_ids[]')->dropdownList(
        Author::find()->select(['second_name', 'id'])->indexBy('id')->column(),
        [ 
            'multiple' => 'multiple',
            'prompt' => Html::encode('Выберите авторов') 
        ]
    );?>

    <?php echo $form->field($bookModel, 'photo')->fileInput(); ?>

    <?= Html::submitButton( Html::encode('Сохранить'), ['class' => 'btn btn-success']); ?>
    <?= Html::a( Html::encode('Назад'), Url::to('/books'), ['class' => 'btn btn-primary'] ); ?>

    <?php ActiveForm::end(); ?>

</div>

