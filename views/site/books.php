<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('refactorData')) { ?>
        <p>
            <a href="/add-book"><?php echo Html::encode("Добавить книгу"); ?></a>
        </p>
    <?php } ?>
    <p>
        <a href="/authors"><?php echo Html::encode("Авторы"); ?></a>
    </p>

    <form action="/delete-books" method="post">
        <table>
            <thead>
                <tr style="border:1px solid black;">
                    <th>ISBN</th>
                    <th>Название</th>
                    <th>Авторы</th>
                    <th>Фото обложки</th>
                    <?php if ($books && Yii::$app->user->can('refactorData')) { ?>
                        <th>Удалить</th>
                    <?php } ?>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($books as $book): ?>
                    <tr style="border:1px solid black;">
                        <td><p><?php echo $book->isbn; ?></p></td>
                        <td><p><?php echo $book->title; ?></p></td>
                        <td>
                            <?php foreach ($book->authorBooks as $authorBook) : ?>
                                <p>
                                    <?php echo $authorBook->author->first_name; ?>&nbsp;<?php echo $authorBook->author->second_name; ?>
                                </p>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <p>
                                <a href="/uploads/<?php echo $book->photo; ?>" target="_blank">
                                    <img width="100" src="/uploads/<?php echo $book->photo; ?>" alt="<?php echo $book->title; ?>" />
                                </a>
                            </p>
                        </td>
                        <?php if ($books && Yii::$app->user->can('refactorData')) { ?>
                            <td>
                                <p>
                                    <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>" />
                                    <input type="checkbox" name="book_ids[<?php echo $book->id; ?>]" value="" />
                                </p>
                            </td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>

                <?php if ($books && Yii::$app->user->can('refactorData')) { ?>
                    <tr style="border:1px solid black;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
