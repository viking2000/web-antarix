<?php  if ( ! defined('BASEPATH') ) exit('No direct script access allowed');/** * Created by JetBrains PhpStorm. * User: PHP Development * Date: 15.03.14 * Time: 14:30 * To change this template use File | Settings | File Templates. */class Controller {    static private $__model_list        = array();    static private $__controller_list   = array();    static private $__widget_call_list  = array();    private $__module                   = '';    public function __construct()    {    }    public function view($view_name, array $data = array(), $return = FALSE)    {        $global_view_path = APPPATH.'views/';        $view_name            .= '.php';        $view_file        = $global_view_path.$view_name;        if ( file_exists($view_file) )        {            $view_path = $global_view_path;        }        else        {            $ap         = Buffer::get(URL_AP);            $view_path  = APPPATH."modules/{$ap}/{$this->__module}/views/";        }        if ( ! file_exists($view_path.$view_name) )        {            throw new Exception_wx(4040005, $view_name);        }        ob_start();            extract($data, EXTR_OVERWRITE);            include $view_path.$view_name;            $buffer = ob_get_contents();        @ob_end_clean();        if ($return)        {            return $buffer;        }        Buffer::set_output($buffer);    }    public function controller($controller_name)    {        $controller = $this->__get_module('__controller_list', 'controllers', $controller_name);        if ( ! $controller)        {            return Loader::get_controller($controller_name);        }        $controller->set_module($this->__module);        return $controller;    }    public function set_module($name)    {        $this->__module = $name;    }    public function model($model_name)    {        $model = $this->__get_module('__model_list', 'models', $model_name);        if ( ! $model)        {            return Loader::get_model($model_name);        }        return $model;    }    public function widget($name, $options, $return = FALSE)    {        if ( empty($name) )        {            return '';        }        $widget_name = (string)str_replace('/', '__', $name);        $widget_path = $name;        if ( ! function_exists($widget_name) )        {            $path = APPPATH.'widgets/'.$widget_path.EXT;            include_once $path;            if ( ! function_exists($widget_name) )            {                Log::log_error("Widget does not exist. widgets:[{$widget_name}], path:[{$path}]");                return '';            }        }        if ( ! in_array($widget_name, self::$__widget_call_list) )        {            self::$__widget_call_list[] = $widget_name;            //$path = Builder::get_style_path("/widgets/{$widget_path}");            //Builder::set_head($path, 'css');            $widget_path = str_replace('/', '-', $widget_path);            Builder::add_css("widgets/{$widget_path}");            Builder::add_js("widgets/{$widget_path}");            //Builder::set_head($path.'_noscript', 'css');            //Builder::set_head("./widgets/{$widget_path}", 'js', TRUE);        }        $result = $widget_name($options);        if ($return)        {            return $result;        }        echo $result;        return '';    }    private function __get_module($storage_name, $place, $module_name)    {        $storage =& self::$$storage_name;        if ( ! isset($storage[$this->__module]) )        {            $storage[$this->__module] = array();        }        if ( array_key_exists($module_name, $storage[$this->__module]) )        {            return $storage[$this->__module][$module_name];        }        $ap_name = Buffer::get(URL_AP);        $path    = APPPATH."modules/{$ap_name}/{$this->__module}/{$place}/{$module_name}".EXT;        if ( ! file_exists($path) )        {            return NULL;        }        require_once $path;        $class_name = ucfirst( basename($module_name) );        if ( ! class_exists($class_name) )        {            throw new Exception_wx(5000001, $path, $class_name);        }        $storage[$this->__module][$module_name] = new $class_name();        $storage[$this->__module][$module_name]->set_module($this->__module);        return $storage[$this->__module][$module_name];    }}