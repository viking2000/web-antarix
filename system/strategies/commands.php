<?php	if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Commands_strategy {

	const COMMAND_CLASS	= 'Command';
	const SUCCESS		= 'success';
	const ERROR			= 'error';
    const QUERY_PARAM   = 'command_data';


	public function __construct()
	{
		require_once BASEPATH.'internal/controller.php';
        require_once BASEPATH.'internal/model.php';
		require_once BASEPATH.'internal/command_exception.php';
		require_once BASEPATH.'internal/command.php';
	}

	public function execute()
	{
		$ap_name		 = Buffer::get(URL_AP);
		$controller_name = Buffer::get(URL_CONTROLLER);
		$query_param	 = Buffer::get(self::QUERY_PARAM, 'post');
		$command		 = Buffer::get('command',	 'post');
		$client_format	 = Buffer::get('format',	'post');
		$action			 = Buffer::get('action',		'post');
		$access_zone	 = config(URL_AP,'access','zone');
		$server_format	 = config('settings', 'web_format');

		if ($client_format != $server_format)
		{
			throw new Exception_wx(4060002, $client_format);
		}

		$command      = Security::sanitize_string($command);
		$action	      = Security::sanitize_string($action);
        $command_path =	APPPATH.'commands/'."{$ap_name}/{$command}".EXT;

        if ( ! file_exists($command_path) )
        {
            $command_path =	APPPATH."modules/{$ap_name}/{$controller_name}/commands/{$command}".EXT;
        }

		if ( ! file_exists($command_path) )
		{
			throw new Exception_wx(4040000, $command_path);
		}

		if ($access_zone != Z_PUBLIC AND ! Security::check_signature(Buffer::get_post('sig')) )
		{
			throw new Exception_wx(4030001);
		}

		include_once $command_path;

		if ( ! class_exists($action) )
		{
			throw new Exception_wx(4040002, $command_path, $action);
		}

		$query_param	= Format::converter($query_param, $server_format, TRUE);
		$command_object = new $action($query_param);
		if ( ! is_a($command_object, self::COMMAND_CLASS) )
		{
			throw new Exception_wx(5000002, $command_path, $command_object);
		}

        $command_object->set_module( Buffer::get(URL_CONTROLLER) );

		try 
		{
		 	$command_object->execute();
		}
		catch (Command_exception $exc)
		{
		 	Command::set_result(FALSE);
			$message =& $exc->client_message;
			if ( ! empty($message) )
			{
				$options = array(
					'message'	=> $message,
					'type'		=> 'error'
				);
				Command::set_client_command('show_message', $options);
			}
			Log::log_error($exc, 'command_exception');
		 	$this->_to_client();
		}

        if ( ! Command::isset_result() )
        {
		    Command::set_result(TRUE);
        }

		$this->_to_client();
	}
	
	protected function _to_client()
	{
		$web_format = config('settings', 'web_format');
		header('Content-type: application/'.$web_format);
		echo Format::converter( Command::get_report(), $web_format );
		exit();
	}
}