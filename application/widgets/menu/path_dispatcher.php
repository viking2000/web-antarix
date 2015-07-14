<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 01.07.14
 * Time: 1:56
 * To change this template use File | Settings | File Templates.
 */

function menu__path_dispatcher( array $options = array() )
{
    if ( empty($options) )
    {
        return '';
    }

    $body = "";
    $after = '<div class="path_dispatcher-item-first"></div>';
    foreach ($options as $name => $link)
    {
        $format_link = base_url($link);
        $body .= "<a class=\"path_dispatcher-item\" href=\"{$format_link}\">{$after}<div class=\"path_dispatcher-item-center\">{$name}</div><div  class=\"path_dispatcher-item-after\"></div></a>";
        $after = '<div class="path_dispatcher-item-before"></div>';
    }

    return "<nav class=\"path_dispatcher\">{$body}</nav>";
}