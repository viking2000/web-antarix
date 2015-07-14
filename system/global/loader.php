<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */

class Loader {

    static protected $_model_list      = array();
    static protected $_cron_list       = array();
    static protected $_controller_list = array();
    static protected $_library_list    = array();

    static public function get_user()
    {
        static $user;
        if ( ! $user)
        {
            $uid = Session::get_uid();
            $user = new User($uid);

            return $user;
        }

        return $user;
    }

    static public function &get_controller($name)
    {
        $class_name = ucfirst($name);
        if ( array_key_exists($class_name, self::$_controller_list) )
        {
            return self::$_controller_list[$class_name];
        }

        $ap_name = Buffer::get(URL_AP);
        $path = PATH_MODULES."{$ap_name}/{$name}/controllers/{$name}".EXT;
        if ( ! file_exists($path) )
        {
            throw new Exception_wx(4040000, $path);
        }

        include_once $path;
        
        if ( ! class_exists($class_name) )
        {
            throw new Exception_wx(5000001, $path, $class_name);
        }
        
        self::$_controller_list[$class_name] = new $class_name();
        self::$_controller_list[$class_name]->set_module($name);
        return self::$_controller_list[$class_name];
    }

    static public function &get_model($model)
    {
        if ( array_key_exists($model, self::$_model_list) )
        {
            return self::$_model_list[$model];
        }

        $path = PATH_MODELS.$model.EXT;
        require_once $path;

        $class_name = ucfirst( basename($model) );
        if ( ! class_exists($class_name) )
        {
            throw new Exception_wx(5000001, $path, $class_name);
        }

        self::$_model_list[$model] = new $class_name();
        return self::$_model_list[$model];
    }

    static public function &get_library($library)
    {
        if ( array_key_exists($library, self::$_library_list) )
        {
            return self::$_library_list[$library];
        }

        $path = PATH_LIBRARY.$library.EXT;
        if ( ! file_exists($path) )
        {
            $path = PATH_LIBRARY."{$library}/{$library}.".EXT;
            if ( ! file_exists($path) )
            {
                throw new Exception_wx(5000004, $library);
            }
        }

        require_once $path;

        $class_name = ucfirst($library);
        if ( ! class_exists($class_name) )
        {
            throw new Exception_wx(5000004, $class_name);
        }

        self::$_library_list[$library] = new $class_name();
        return self::$_library_list[$library];
    }
}