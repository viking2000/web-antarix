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
function list__2block( array $options = array() )
{
    if (empty($options) )
    {
        return '';
    }

    $left  = '';
    $right = '';
    $parity = 0;
    foreach ($options as $item)
    {
        ++$parity;
        $body       = '';
        $header     = '';
        $footer     = '';
        $image      = '';

        if ( isset($item['header']) )
        {
            $key = rand(0, 2);
            $header .= "<header class=\"lb-bite{$key}\"><h1>{$item['header']}</h1></header>";
        }

        if ( isset($item['image']) )
        {
            $body .= '<img width="100%" src="'. img_link($item['image']). '" /><br />';
        }

        if ( isset($item['content']) )
        {
            $body .= "<p>{$item['content']}</p>";
        }

        if ( isset($item['footer']) )
        {
            $footer .= $item['footer'];
        }

        if ( isset($item['link']) )
        {
            $link = base_url($item['link']);
            $footer .= '<a class="lb-button right" href="'.$link.'">'.get_string('widgets', 'read more').'</a>';
        }

        $body .= "<footer>{$footer}</footer>";
        if ($parity % 2 != 0)
        {
            $left .= "<section class=\"lb-wrapper_body\">{$header}<div class=\"lb-cbm_wrap\">{$body}</div></section>";
        }
        else
        {
            $right .= "<section class=\"lb-wrapper_body\">{$header}<div class=\"lb-cbm_wrap\">{$body}</div></section>";
        }
    }

    return "<div class=\"list-2block\"><div class=\"lb-2block-left\">{$left}</div><div class=\"lb-2block-right\">{$right}</div><div class=\"lb-end\"></div></div><script>if (window.screen.width < 1200) {\$(\".lb-2block-left, .lb-2block-right\").css({\"display\":\"block\", \"width\":\"100%\"});}</script>";
}