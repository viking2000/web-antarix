<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */

class db {
    const ER_NOCONNECT = 101;
    
    static protected $_mysqli  = NULL;

    static public function connect()
    {
        if ( ! empty(self::$_mysqli) )
        {
            return;
        }

        $options = config('db');
        self::$_mysqli = new mysqli($options['hostname'], $options['username'], $options['password'] , $options['database']);

        if ( ! self::$_mysqli->set_charset($options['char_set']) )
        {
            show_error(500);
        }

        if (self::$_mysqli->connect_errno)
        {
            show_error(500);
        }
    }

    static public function simple_query($sql_query, array $data = array(), $escape = FALSE)
    {
        $result = self::_get_data($sql_query, $data, $escape);
        if ( is_object($result) )
        {
            $result->close();
            return TRUE;
        }

        return $result;
    }

    static public function row($sql_query, array $data = array(), $escape = FALSE)
    {
        $result = self::_get_data($sql_query, $data, $escape);
        if ( ! $result)
        {
            return NULL;
        }

        $row = $result->fetch_assoc();
        $result->close();
        return $row;
    }

    static public function sql($sql_query, array $data = array(), $escape = FALSE)
    {
        foreach ($data as $key => $value)
        {
            if ($escape)
            {
                $value = self::$_mysqli->escape_string($value);
            }

            $sql_query = str_replace($key, $value, $sql_query);
        }

        return $sql_query;
    }

    static public function rows($sql_query, array $data = array(), $escape = FALSE)
    {
        $result = self::_get_data($sql_query, $data, $escape);
        if ( ! $result)
        {
            return NULL;
        }
		$rows = array();
		while ($row = $result->fetch_assoc())
		{
			$rows[] = $row;
		}
        //$rows = $result->fetch_all(MYSQLI_ASSOC);
        $result->close();
        return $rows;
    }

    static public function scalar($sql_query, array $data = array(), $escape = FALSE, $auto_convert = FALSE, $replace_constants = FALSE)
    {
        $result = self::_get_data($sql_query, $data, $escape);
        if ( ! $result)
        {
            return NULL;
        }

        $scalar = $result->fetch_row();

        if ($scalar)
        {
            $scalar = $scalar[0];

            if ($replace_constants)
            {
                replace_constants($scalar);
            }

            if ($auto_convert)
            {
                $scalar =  Format::converter($scalar, config('settings', 'db_format'), TRUE);
            }
        }

        $result->close();
        return $scalar;
    }

    static public function scalar_list($sql_query, array $data = array(), $escape = FALSE, $auto_convert = FALSE, $replace_constants = FALSE)
    {
        $result = self::_get_data($sql_query, $data, $escape);
        if ( ! $result)
        {
            return NULL;
        }

        $scalar_list = array();
        while($scalar = $result->fetch_row() )
        {
            $item =& $scalar[0];
            if ($replace_constants)
            {
                replace_constants($item);
            }

            if ($auto_convert)
            {
                $item =  Format::converter($item, config('settings', 'db_format'), TRUE);
            }

            $scalar_list[] = $item;
        }

        $result->close();
        return $scalar_list;
    }

    static public function close()
    {
        if ( empty(self::$_mysqli) )
        {
            return;
        }
        
        self::$_mysqli->close();
    }

    static public function escape_string($str)
    {
        return self::$_mysqli->escape_string($str);
    }

    static protected function _get_data($sql_query, $data, $escape)
    {
        if ( empty(self::$_mysqli) )
        {
            show_error(500);
        }
        
        if ( ! self::$_mysqli->ping() )
        {
            self::$_mysqli->close();
            self::connect();
        }
        
        //call_user_func_array('sprintf', $args);
        foreach ($data as $key => $value)
        {
            if ($escape)
            {
                $value = self::$_mysqli->escape_string($value);
            }

            $sql_query = str_replace($key, $value, $sql_query);
        }
		
        return self::$_mysqli->query($sql_query);
    }
}