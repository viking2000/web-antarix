<div class="article-menu-wrapper">
    <header>
        <?=get_string('pages', 'contents'), ':';?>
    </header>
    <div id="article-menu">
        <?php

        foreach ($list as $item)
        {
            $link = '<a href="'.$item['link'].'" class="left-menu-item" ';
            if ($current == $item['link'])
            {
                $link .= ' id="current-left-menu-item"';
            }

            $link .= '>';
            $link .= $item['header'];
            $link .= '</a><hr class="left-menu-hr"/>';
            echo $link;
        }
        ?>
    </div>
</div>
<script>
    $('#article-menu').slimScroll({
        position: 'right',
        height: '400px',
        railVisible: true,
        alwaysVisible: true
    });
    var position = $('#current-left-menu-item').position();
    $('#article-menu').slimScroll({ scrollBy: position.top + 'px'});
</script>