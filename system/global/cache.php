<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */

class Cache {

    static public function get($key, $position = 'pages')
    {
        switch ($position)
        {
            case 'pages':
                $cache_file = APPPATH."cache/{$key}.cch";
                if ( file_exists($cache_file) AND (filemtime($cache_file) >= get_time() ) )
                {
                    return file_get_contents($cache_file);
                }

                break;
            case 'styles':
                $cache_file = FCPATH."modules/styles/cache/{$key}.css";
                if ( file_exists($cache_file) AND (filemtime($cache_file) >= get_time() ) )
                {
                    return file_get_contents("./modules/styles/cache/{$key}.css");
                }

                break;
            case 'scripts':
                $cache_file = FCPATH."modules/scripts/cache/{$key}.js";
                if ( file_exists($cache_file) AND (filemtime($cache_file) >= get_time() ) )
                {
                    return file_get_contents("./modules/scripts/cache/{$key}.js");
                }

                break;
        }

        return NULL;
    }

    static public function get_link($key, $position = 'pages')
    {
        switch ($position)
        {
            case 'pages':
                $cache_file = APPPATH."cache/{$key}.cch";
                if ( file_exists($cache_file) AND (filemtime($cache_file) >= get_time() ) )
                {
                    return $cache_file;
                }

                break;
            case 'styles':
                $cache_file = FCPATH."modules/styles/cache/{$key}.css";
                if ( file_exists($cache_file) AND (filemtime($cache_file) >= get_time() ) )
                {
                    return "./modules/styles/cache/{$key}.css";
                }

                break;
            case 'scripts':
                $cache_file = FCPATH."modules/scripts/cache/{$key}.js";
                if ( file_exists($cache_file) AND (filemtime($cache_file) >= get_time() ) )
                {
                    return "./modules/scripts/cache/{$key}.js";
                }

                break;
        }

        return NULL;
    }

    static public function set($key, $content, $time, $position = 'pages')
    {
        switch ($position)
        {
            case 'pages':
                $cache_file = APPPATH."cache/{$key}.cch";
                break;
            case 'styles':
                $cache_file = FCPATH."modules/styles/cache/{$key}.css";
                break;
            case 'scripts':
                $cache_file = FCPATH."modules/scripts/cache/{$key}.js";
                break;
            default:
                return NULL;
        }

        $h          = fopen($cache_file,'w');
        fwrite($h, $content);
        fclose($h);
        touch($cache_file, get_time() + $time);

        return TRUE;
    }

    static public function reset($key = '', $position = 'all')
    {
        $patterns = array();
        switch ($position)
        {
            case 'pages':
                $patterns[] = APPPATH."cache/{$key}*.cch";
                break;
            case 'styles':
                $patterns[] = FCPATH."modules/styles/cache/{$key}*.css";
                break;
            case 'scripts':
                $patterns[] = FCPATH."modules/scripts/cache/{$key}*.js";
                break;
            case 'all':
                $patterns[] = FCPATH."modules/scripts/cache/{$key}*.js";
                $patterns[] = FCPATH."modules/styles/cache/{$key}*.css";
                $patterns[] = APPPATH."cache/{$key}*.cch";
                break;
            default:
                return NULL;
        }

        foreach ($patterns as $pattern)
        {
            $file_list = glob($pattern);
            foreach ($file_list as $file_name)
            {
                unlink($file_name);
            }
        }
    }

    static public function generate_key()
    {
        //if ($short)
        //{
            return crc32( get_path_url() );
        //}

        //$style_suite = config(URL_AP, 'default', 'style_suite');
        //$postfix = ( config('web','is_mobile') ) ? "{$style_suite}_mobile" : $style_suite;
        //$get_string = config('settings', 'get_string');
        //return crc32( get_path_url() ).'__page_'.$get_string.'-'.$postfix;
    }
}