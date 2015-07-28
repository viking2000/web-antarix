<article>
    <?php
        if ( ! empty($previous) )
        {
            echo '<a class="button" style="float:left;" href="'.$previous.'" >&#8592; '.get_string('pages', 'prev').'</a>';
        }

        if ( ! empty($following) )
        {
            echo '<a class="button" style="float:right;" href="'.$following.'" > '.get_string('pages', 'next').' &#8594;</a>';
        }
        echo '<br class="clear" />';
    ?>
    <header>
        <h1>
            <?=$header;?>
        </h1>
        <div class="last-creation"><?=get_string('pages', 'last-creation'), $creation;?></div>

    </header>
    <?=$this->view(get_article_path($id, $type), array(), TRUE );?>
    <?php
        echo '<br class="clear" />';
        if ( ! empty($previous) )
        {
            echo '<a class="button" style="float:left;" href="'.$previous.'" >&#8592; '.get_string('pages', 'prev').'</a>';
        }

        if ( ! empty($following) )
        {
            echo '<a class="button" style="float:right;" href="'.$following.'" >'.get_string('pages', 'next').' &#8594;</a>';
        }
    ?>
</article>