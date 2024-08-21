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

<hr/>

<h2>Количество сгруппированных по пользователям просмотров статей по дням за последний год - <?php echo date('Y'); ?></h2>
<ul>
    <?php foreach($artViewsGroup as $artViewGroup) :?>
        <li>
            Дата: <?php echo $artViewGroup->date; ?>&nbsp;
            ID пользователя: <?php echo $artViewGroup->user_id; ?>&nbsp;
            Кол-во просмотров: <strong><?php echo $artViewGroup->count_views; ?></strong>
        </li>
    <?php endforeach; ?>
</ul>