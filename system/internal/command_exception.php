<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Command_exception extends Exception
{
 	public $client_message = '';
	
    public function __construct($error_text, $client_message = '')
    {
	 	$this->client_message = $client_message;
        $args                 = func_get_args();

        unset($args[0]); //delete code
		unset($args[1]); //delete code
		
        //добавляем дополнительные параметры в тело текста ошибки за место '?', если остаются лишние параметры,
        //то добавляем их в конец сообщения
        if ( ! empty($args) AND ! empty($error_text) )
        {
            $additional_data = array();
            foreach ($args as &$value)
            {
                $pos = strpos($error_text, '?');
                if ($pos === FALSE)
                {
                    $additional_data[] =& $value;
                    continue;
                }

                $error_text = substr_replace($error_text, $value, $pos, 1);
            }

            if ( ! empty($additional_data) )
            {
                $error_text .= "\n\n".json_encode($additional_data);
            }
        }

       parent::__construct($error_text, 4000000);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */