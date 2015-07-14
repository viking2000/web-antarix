<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 01.07.14
 * Time: 1:56
 * To change this template use File | Settings | File Templates.
 */

function menu__block( array $options = array() )
{
    $body = '';
    $count_list = array();
    $item_count = count($options);

    for (; $item_count > 0; )
    {
        $number = rand(2, 4);
        if ($item_count - $number < 0)
        {
            $count_list[] = $item_count;
            break;
        }

        $item_count  -= $number;
        $count_list[] = $number;
    }

    reset($options);
    foreach ($count_list as $count)
    {
        $body .= '<ul table-layout="fixet" class="mb-line">';
        for ($i = 0; $i < $count; $i++)
        {
            $img_link = '#';
            $total_link = '#';
            $title = '';
            $description = '';
            $item = current($options);

            if ($item === FALSE)
            {
                break;
            }

            next($options);
            if ( ! empty($item['description']) )
            {
                $description = $item['description'];
            }

            if ( ! empty($item['title']) )
            {
                $title = $item['title'];
            }

            if ( ! empty($item['image']) )
            {
                $img_link = $item['image'];
            }

            if ( ! empty($item['link']) )
            {
                $total_link = $item['link'];
            }

            $style = "background-image:url('{$img_link}');background-position: 50%; background-size: cover;";
            $element = <<<EOT
            <a class="info" href="{$total_link}">
                <div class="mask">
                    <h2>{$title}</h2>
                    <p>{$description}</p>
                 </div>
                 <footer>{$title}</footer>
            </a>
EOT;

            $body .='<li class="view view-first mb-item'.$count.'" style="'.$style.'">'.$element.'</li>';
        }

        $body .= '</ul>';
    }

    return '<menu class="menu-block">'.$body.'</menu>';
}