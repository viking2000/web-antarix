<?php

    foreach ($data_list as $item)
    {
        $article = '<a class="article-list-item" href="'.$item['link'].'">';
        $article .= '<img class="article-list-image-item" src="'.$item['image'] .'" />';
        $article .= '<header>'.$item['header'] .'</header>';
        $article .= '<div class="description">'.$item['description'] .'</div>';
        $article .= '<footer>&#160;'.$item['footer'] .'</footer>';
        $article .= '</a>';
        echo $article;
    }

$this->widget('calculation/interval', $interval);
?>
