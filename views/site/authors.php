<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('refactorData')) { ?>
        <p>
            <a href="/add-author"><?php echo Html::encode("Добавить автора"); ?></a>
        </p>
    <?php } ?>
    <p>
        <a href="/books"><?php echo Html::encode("Книги"); ?></a>
    </p>

    <form action="/delete-authors" method="post">
        <table>
            <thead>
                <tr style="border:1px solid black;">
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Отчетсво</th>
                    <th>Книги автора</th>
                    <?php if ($authors && Yii::$app->user->can('subscribeAuthor')) { ?>
                        <th>Подписаться</th>
                    <?php } ?>
                    <?php if ($authors && Yii::$app->user->can('refactorData')) { ?>
                        <th>Удалить</th>
                    <?php } ?>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($authors as $author): ?>
                    <tr style="border:1px solid black;">
                        <td><p><?php echo $author->first_name; ?></p></td>
                        <td><p><?php echo $author->second_name; ?></p></td>
                        <td><p><?php echo $author->middle_name; ?></p></td>
                        <td>
                            <?php foreach ($author->authorBooks as $authorBook) : ?>
                                <p>
                                    <?php echo $authorBook->book->isbn; ?>&nbsp;-&nbsp;<?php echo $authorBook->book->title; ?>
                                </p>
                            <?php endforeach; ?>
                        </td>
                        <?php if ($authors && Yii::$app->user->can('subscribeAuthor')) { 
                            $doSubscribe = $author->subscribe ? Html::encode("Отписаться") : Html::encode("Подписаться"); 
                        ?>
                            <td>
                                <form action="/subscribe" method="post">
                                    <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>" />
                                    <input type="hidden" name="author_id" value="<?php echo $author->id; ?>" />
                                    <input type="submit" value="<?php echo $doSubscribe; ?>" />
                                </form>
                            </td>
                        <?php } ?>
                        <?php if ($authors && Yii::$app->user->can('refactorData')) { ?>
                            <td>
                                <p>
                                    <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>" />
                                    <input type="checkbox" name="author_ids[<?php echo $author->id; ?>]" value="" />
                                </p>
                            </td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>

                <?php if ($authors && Yii::$app->user->can('refactorData')) { ?>
                    <tr style="border:1px solid black;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php if ($authors && Yii::$app->user->can('subscribeAuthor')) { ?>
                            <td></td>
                        <?php } ?>
                        <td>
                            <p>
                                <input type="submit" value="<?php echo Html::encode("Удалить"); ?>" />
                            </p>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </form>

    <code><?= __FILE__ ?></code>
</div>

