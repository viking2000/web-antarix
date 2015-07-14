<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 01.07.14
 * Time: 1:56
 * To change this template use File | Settings | File Templates.
 */

function menu__left( array $options)
{
    $header   = '';
    $body     = '';
    $selected = FALSE;

    if ( ! empty($options['logo']) )
    {
        $logo =& $options['logo'];
        $logo_img = base_url($logo['image']);
        $header = <<<EOT
        <header class="ml-logo-header">
        <img class="ml-logo" src="{$logo_img}" alt="logo" width="100%" /><h2 class="color-1">{$logo['title']}</h2><h5>{$logo['subtitle']}</h5>
        </header>
EOT;
    }

    $segment_url = Buffer::get(URL_CONTROLLER);
    $full_segment_url = Buffer::get(URL_CONTROLLER).'/'.Buffer::get(URL_METHOD);
    if ( ! isset($options['items']) )
    {
        return '';
    }

    foreach ($options['items'] as $section => $items)
    {
        $body .= "<section><header class=\"ml-section-header\">$section</header>";
        foreach ($items as $name => $link)
        {
            $class = 'ml-item';
            if ( empty($link) )
            {
                $link = $segment_url;
            }

            if ( ! $selected AND strpos($link, $segment_url) !== FALSE )
            {
                $selected = TRUE;
                $class = 'ml-selected';
            }

            if (strpos($link, $full_segment_url) !== FALSE)
            {
                $selected = TRUE;
                $body = str_replace('ml-selected', 'ml-item', $body);
                $class = 'ml-selected';
            }

            if ( isset($options['noindex']) AND in_array($name, $options['noindex']) )
            {
                $body .= '<noindex><a class="'.$class.'" href="'.base_url($link).'">'.$name.'</a></noindex>';
            }
            else
            {
                $body .= '<a class="'.$class.'" href="'.base_url($link).'">'.$name.'</a>';
            }
        }
        $body .= '</section>';
    }

    $html = <<<EOT
    <menu class="menu-left">
        <header>
            {$header}
        </header>
        <br />
    {$body}
    </menu>
EOT;
    return $html;
}