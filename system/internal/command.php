<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 11.06.14
 * Time: 0:07
 * To change this template use File | Settings | File Templates.
 */

abstract class Command extends Controller{

//    const R_RESULT      = 'result';
//    const R_SERVER_DATA = 'server_data';
    const REQUIRED      = TRUE;

    static private $__report   = array();

    function __construct($command_data)
    {
        if ( config(URL_AP, 'access', 'check_blocked_ip') )
        {
            Security::check_access_ip();
        }

        if ( ! is_array($command_data) )
        {
            throw new Exception_wx(4060000, print_r($command_data, TRUE) );
        }

        //Проверка прав выполнения команды
        $user        = Loader::get_user();
        $path_url    = get_path_url();
        $action_name = get_class();
        if ( property_exists($this, '__permission')
            AND ! ($user->check_permission("{$path_url}>{$action_name}") & (int)$this->__permission)
        )
        {
            throw new Exception_wx(4030002, "{$path_url}>{$action_name}" );
        }

        foreach ($this as $parameter => $required)
        {
            if ( ! empty($command_data[$parameter]) )
            {
                $this->$parameter = $command_data[$parameter];
            }
            elseif ($required)
            {
                throw new Exception_wx(4060001, $parameter, print_r($command_data, TRUE) );
            }
        }
    }

    abstract public function execute();

	static public function set_result($success)
	{
		if ( ! isset(self::$__report['result']) OR ! is_array(self::$__report['result']) )
        {
            self::$__report['result'] = '';
        }
		
        self::$__report['result'] = (bool) $success ? 'success' : 'error';
	}

    static public function isset_result()
    {
        return isset(self::$__report['result']);
    }
	
	static public function set_client_data($key, $options)
	{
	    if ( ! isset(self::$__report['result']) OR ! is_array(self::$__report['client_data']) )
        {
            self::$__report['client_data'] = array();
        }
		
        self::$__report['client_data'][$key] = $options;
	}
	
    static public function set_client_command($name, $options)
    {
        if ( ! isset(self::$__report['commands']) OR ! is_array(self::$__report['commands']) )
        {
            self::$__report['commands'] = array();
        }

        self::$__report['commands'][] = array('name' => $name, 'options' => $options);
    }

    static public function get_report()
    {
        return self::$__report;
    }
}