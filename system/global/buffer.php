<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 16:53
 * To change this template use File | Settings | File Templates.
 */

class Buffer {

    static private $__final_output     = '';
    static private $__internal_data    = array();

    static public function set($key, $data, $zone = 'buffer')
    {
        switch ($zone)
        {
            case 'buffer':
                self::$__internal_data[$key] = $data;
                break;
            case 'post':
                $_POST[$key] = $data;
                break;
            case 'cache':

                break;
        }
    }

    static public function get($key, $zone = 'buffer')
    {
        switch ($zone)
        {
            case 'buffer':
                if ( array_key_exists($key, self::$__internal_data) )
                {
                    return self::$__internal_data[$key];
                }

                return NULL;
            case 'post':
                if ($key)
                {
                    if ( isset($_POST[$key]) )
                    {
                        return $_POST[$key];
                    }

                    return NULL;
                }

                return $_POST;
        }

        return NULL;
    }

    static public function set_output($data, $to_start = FALSE)
    {
        if ($to_start)
        {
            self::$__final_output = $data.self::$__final_output;
            return;
        }

        self::$__final_output .= $data;
    }

    static public function get_output()
    {
        return self::$__final_output;
    }
	
    static public function get_post($key = NULL) //Если NULL то вернуть всё
    {
       // var_dump(file_get_contents('php://input')); exit();
       // return file_get_contents('php://input');
        if ($key)
        {
            if ( isset($_POST[$key]) )
            {
                return $_POST[$key];
            }

            return NULL;
        }

        return $_POST;
    }
}