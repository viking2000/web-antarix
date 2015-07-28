<?php
if ( empty($type_list) )
{
    return;
}

foreach ($type_list as $item)
{
    $class = "top-type-item";
    if ($current == $item['type'])
    {
        $class = "top-type-item top-type-selected";
    }

    $link = base_url('articles/browse/'.$item['type']);
    echo "<a class=\"{$class}\" href=\"$link\"><img class=\"top-image\" src=\"{$item['image']}\" />{$item['header']}</a>";
}

echo '<br class="clear" />';
