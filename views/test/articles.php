
<h1>Все статьи</h1>
<p>
    <a href="/article-views">Количество просмотров статей</a>
</p>
<ul>
    <?php foreach($arts as $art) :?>
        <li>
            ID: <?php echo $art->id; ?>&nbsp;<a href="/article/<?php echo $art->id; ?>"><?php echo $art->content; ?></a>
        </li>
    <?php endforeach; ?>
</ul>