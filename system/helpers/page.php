<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');/** * Created by JetBrains PhpStorm. * User: PHP Development * Date: 15.03.14 * Time: 14:48 * To change this template use File | Settings | File Templates. */if ( ! function_exists('attach_js') ){    function attach_js($file, $options = '')    {        if ( ! file_exists($file) )        {            return '';        }        $path = str_replace('./', config('settings','base_url'), $file);        return "<script src=\"{$path}\" type=\"text/javascript\" {$options}></script>";    }}if ( ! function_exists('attach_css') ){    function attach_css($file, $option = 'rel="stylesheet"')    {        if ( ! file_exists($file) )        {            return '';        }        $path = str_replace('./', config('settings','base_url'), $file);        return "<link {$option} type=\"text/css\" media=\"screen\" href=\"{$path}\"/>";    }}//Deprecatedif ( ! function_exists('img_link') ){    function img_link($link_segment)    {        return base_url("/modules/images/{$link_segment}");    }}//Deprecatedif ( ! function_exists('img') ){    function img($link, $class = '', $alt = '', $auto_path = FALSE)    {        $base = '/modules/images/';        if ($auto_path)        {            $base .= Buffer::get(URL_CONTROLLER) . '/';        }        $link = base_url($base . $link);        if ($class)        {            $class = 'class="'.$class.'"';        }        if ($alt)        {            $alt = 'alt="'.$alt.'"';        }        return "<img {$alt} src=\"{$link}\" {$class} />";    }}//Deprecatedif ( ! function_exists('article_img') ){    function article_img($img_name, $class = '', $alt = '')    {        $base = '/modules/images/';        $base .= Buffer::get(URL_CONTROLLER) . '/articles_data/' . Buffer::get(ARTICLE_ID) . '/';        $link = base_url($base . $img_name);        if ($class)        {            $class = 'class="'.$class.'"';        }        if ($alt)        {            $alt = 'alt="'.$alt.'"';        }        return "<img {$alt} src=\"{$link}\" {$class} />";    }}if ( ! function_exists('replace_constants') ){    function replace_constants(&$value)    {        if (strpos($value, '{base_url}') !== FALSE)        {            $value = str_replace('{base_url}', base_url(), $value);        }    }}