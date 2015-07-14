<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */

class User {

    const T_ADMIN               = 'admin';
    const T_USER                = 'user';
    const T_SYSTEM              = 'system';
    const T_VISITOR             = 'visitor';
    const T_ROBOT               = 'robot';
    const T_ALL                 = 'all_users';

    const M_SECRET_KEY          = 'secret_key';

    protected $_internal_object = array();
    protected $_type;
    protected $_group_list;
    protected $_secret_key;
    protected $_id;

    const Q_GET_USER = 'SELECT * FROM `users` WHERE `id` = "%uid"';

    public function __construct($uid = NULL)
    {
        if ($uid === NULL)
        {
            $uid = Session::get_uid();
        }

        if ($uid)
        {
            $row = db::row( self::Q_GET_USER, array('%uid' => $uid) );

            //если пользователь есть в базе, то загружаем его
            if ( ! empty($row) )
            {
                foreach ($row as $field => $value)
                {
                    $this->_set_field($field, $value);
                }

                return;
            }
        }

        //проверка на роботов
        if ( ! isset($_SERVER['HTTP_USER_AGENT']) )
        {
            $this->_type = self::T_ROBOT;
            $this->id   = 0;
            $this->_group_list = array();
            return;
        }

        //если попали сюда, значит пользователь - посетитель
        $this->_type = self::T_VISITOR;
        $this->id   = 0;
        $this->_group_list = array();
    }

    public function get_type()
    {
        return $this->_type;
    }

    public function is_admin()
    {
        return $this->_type == self::T_ADMIN;
    }

    public function is_user()
    {
        return $this->_type == self::T_USER OR $this->_type == self::T_ADMIN;
    }

    public function is_robot()
    {
        return $this->_type == self::T_ROBOT;
    }

    public function is_visitor()
    {
        return $this->_type == self::T_VISITOR;
    }

    public function is_groups($groups)
    {
        $groups    = (array) $groups;
        $intersect = array_intersect($this->_group_list, $groups);
        $diff      = array_diff($groups, $intersect);
        return empty($diff);
    }

    public function check_permission($link)
    {
        //TODO:check_permission
        //001 - r
        //010 - w : 2
        //100 - x
        //111 : 7
        //000 : 0
        return 7;
    }

    public function reset($uid = NULL)
    {
        if ($uid === NULL)
        {
            $uid = Session::get_uid();
        }

        if ($uid)
        {
            $row = db::row( self::Q_GET_USER, array('%uid' => $uid) );

            //если пользователь есть в базе, то загружаем его
            if ( ! empty($row) )
            {
                foreach ($row as $field => $value)
                {
                    $this->_set_field($field, $value);
                }

                return;
            }
        }

        //если попали сюда, значит пользователь - посетитель
        $this->_type = self::T_VISITOR;
        $this->_id   = 0;
        $this->_group_list = array();
        $this->_secret_key = '';
    }

    public function get_id()
    {
        return $this->_id;
    }

    public function get_secret_key()
    {
         return $this->_secret_key;
    }

    public function get_module($name)
    {
        if ( ! isset($this->_internal_object[$name]) )
        {
            include_once BASEPATH."user/{$name}.php";
            $this->_internal_object[$name] = new $name();
        }

        return $this->_internal_object[$name];
    }

    protected function _set_field($field, $value)
    {
        $field = '_'.$field;

        if (strpos($field, '_list') != FALSE)
        {
            $this->$field = ( empty($value) ) ? array() : explode(',', $value);
        }
        else
        {
            $this->$field = $value;
        }
    }
}