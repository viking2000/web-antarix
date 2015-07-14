<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 01.07.14
 * Time: 1:56
 * To change this template use File | Settings | File Templates.
 */

function calculation__interval( array $options = array() )
{
    if ( empty($options['link']) )
    {
        return '';
    }

    if ( empty($options['selected']) )
    {
        $options['selected'] = 1;
    }
    else
    {
        $options['selected'] = intval($options['selected']);
    }

    $body        = '';
    $interval    = empty($options['interval']) ? 1 : $options['interval'];
    $link        = $options['link'];
    $last_symbol = substr($link, -1);
    if ($last_symbol != '/')
    {
        $link .= '/';
    }

    for ($i = 1; $i <= $interval; $i++)
    {
        $class = ($options['selected'] == $i) ? 'cl-selected' : 'cl-button';
        $body .= '<a class="'.$class.'" href="'.$link.$i.'">'.$i.'</a>';
    }

    return "<div class=\"calculation-interval\">{$body}</div>";
}