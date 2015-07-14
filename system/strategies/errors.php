<?php  if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Errors_strategy {

    public function execute()
    {
        $error = func_get_arg(0); //Получение объекта исключения
        $code = intval($error->getCode() / 10000);

        if ( ! class_exists('Log') )
        {
            include_once BASEPATH.'global/log.php';
        }

        Log::log_error($error, 'exception');
        show_error($code);
    }
}