<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 01.07.14
 * Time: 1:56
 * To change this template use File | Settings | File Templates.
 */

//{'type' => '2block, 1block, poly, micro', 'items' => [{1}, {}, {}, {}] }
//1 -> 'footer', 'header', 'content', 'background', 'link'
function list__poly_block( array $options = array() )
{
    Builder::add_css("widgets/list/poly_block");
    if (empty($options) )
    {
        return '';
    }

    $body   = '';
    foreach ($options as $item)
    {
        $img_link = '#';
        $total_link = '#';
        $header = '';
        $content = '';
        if ( ! empty($item['content']) )
        {
            $content = $item['content'];
        }

        if ( ! empty($item['header']) )
        {
            $header = $item['header'];
        }

        if ( ! empty($item['img_title']) )
        {
            $img_link = base_url($item['img_title']);
        }

        if ( ! empty($item['link']) )
        {
            $total_link = base_url($item['link']);
        }

        $body .= <<<EOT
            <div class="view view-tenth">
                    <img src="{$img_link}" />
                    <div class="mask">
                        <h2>{$header}</h2>
                        <p>{$content}</p>
                        <a href="{$total_link}" class="info">Подробнее</a>
                    </div>
                </div>
EOT;
    }

    return "<article class=\"list-poly_block\">{$body}<div class=\"clear\"></div></article>";
}