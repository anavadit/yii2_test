<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Добавить автора';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        $form = ActiveForm::begin([
            'id' => 'add_author_form',
            'options' => [
                'class' => 'form-horizontal', 
            ],
            'action' => '/add-author',
            'method' => 'post'
        ]);
    ?>

        <?php echo $form->field($authorModel, 'first_name')->input('text'); ?>
        <?php echo $form->field($authorModel, 'second_name')->input('text'); ?>
        <?php echo $form->field($authorModel, 'middle_name')->input('text'); ?>

        <?= Html::submitButton( Html::encode('Сохранить'), ['class' => 'btn btn-success']); ?>
        <?= Html::a( Html::encode('Назад'), Url::to('/authors'), ['class' => 'btn btn-primary'] ); ?>

    <?php ActiveForm::end(); ?>

</div>

