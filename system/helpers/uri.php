<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */

if ( ! function_exists('is_ajax') )
{
    function is_ajax()
    {
        return ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
					AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

if ( ! function_exists('get_ip') )
{
    function get_ip()
    {
        if ( ! empty($_SERVER['HTTP_CLIENT_IP']) ) // Определение IP-адреса
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) // Проверка того, что IP идёт через прокси
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = ! empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0;
        }
        
        return $ip;
    }
}

if ( ! function_exists('get_full_url') )
{
    function get_full_url()
    {
        static $result;
        if ( ! empty($result) )
        {
            return $result;
        }

        $result = '';// Пока результат пуст
        $default_port = 80; // Порт по-умолчанию

        // А не в защищенном-ли мы соединении?
        if (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS']=='on'))
        {
            // В защищенном! Добавим протокол...
            $result .= 'https://';
            // ...и переназначим значение порта по-умолчанию
            $default_port = 443;
        } 
        else
        {
            // Обычное соединение, обычный протокол
            $result .= 'http://';
        }
        // Имя сервера, напр. site.com или www.site.com
        $result .= $_SERVER['SERVER_NAME'];

        // А порт у нас по-умолчанию?
        if ($_SERVER['SERVER_PORT'] != $default_port)
        {
            // Если нет, то добавим порт в URL
            $result .= ':'.$_SERVER['SERVER_PORT'];
        }
        // Последняя часть запроса (путь и GET-параметры).
        $result .= $_SERVER['REQUEST_URI'];
        return $result;
    }
}

if ( ! function_exists('get_article_path') )
{
    function get_article_path($article, $type = NULL, $with_root = FALSE)
    {
        if ( empty($type) )
        {
            $type = '_none-type';
        }

        $lang = '';
        if ( Buffer::get(URL_LANG)  )
        {
            $lang = Buffer::get(URL_LANG) . '/';
        }

        $path = "articles/{$lang}{$type}/{$article}";

        if ($with_root)
        {
            $path = APPPATH .'views/'. $path.'.php';
        }

        return $path;
    }
}

if ( ! function_exists('image_url') )
{
    function image_url($path)
    {
        if (strpos($path, './') !== FALSE)
        {
            $path = str_replace('./', '', $path);
        }

        $first_symbol = $path{0};
        if ($first_symbol == '/')
        {
            $path = substr($path, 1);
        }

        if ( ! file_exists('./modules/images/'.$path ) )
        {
            return base_url() . 'modules/images/not-found.gif';
        }

        return base_url() . 'modules/images/'.$path;
    }
}

if ( ! function_exists('get_path_url') )
{
    function get_path_url()
    {
        $full_url = get_full_url();
        $data_url = parse_url($full_url);
        $path = $data_url['path'];
        return $path;
    }
}

if ( ! function_exists('base_url') )
{
    function base_url($path = '', $is_add_ap = FALSE)
    {
        if (  empty($path) )
        {
            return config('settings', 'base_url');
        }

        if (strpos($path, './') !== FALSE)
        {
            $path = str_replace('./', '', $path);
        }

        $first_symbol = $path{0};
        if ($first_symbol == '/')
        {
            $path = substr($path, 1);
        }

        if ($is_add_ap)
        {
            $path = config('route', Buffer::get(URL_AP) ).'/'.$path ;
        }

        if ( config('settings', 'multilingualism') )
        {
            $path = Buffer::get(URL_LANG).'/'.$path ;
        }

        return config('settings', 'base_url').$path;
    }
}