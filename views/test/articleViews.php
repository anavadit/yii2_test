<h1>Количество просмотров статей</h1>
<p>
    <a href="/articles">Статьи</a>
</p>
<ul>
    <?php foreach($artViews as $artView) :?>
        <li>
            ID статьи: <?php echo $artView->article_id; ?>&nbsp;
            ID пользователя: <?php echo $artView->user_id; ?>&nbsp;
            Дата: <?php echo $artView->date; ?>&nbsp;
            Кол-во просмотров: <strong><?php echo $artView->count_views; ?></strong>
        </li>
    <?php endforeach; ?>
</ul>