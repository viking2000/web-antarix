<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 14:26
 * To change this template use File | Settings | File Templates.
 */

class Cron_strategy {

    const Q_REPORT = 'INSERT INTO `log_cron` (`name`, `start`, `end`, `report`, `result`, `creation`)
                      VALUES("%name", FROM_UNIXTIME("%start"), NOW(),"%report", "%result", NOW())';

    public function __construct()
    {
        require_once BASEPATH.'internal/cron.php';
    }

    public function execute()
    {
        if ( ! file_exists(PATH_CRON.CRON_NAME.'.php') )
        {
            throw new Exception_wx('6000000', CRON_NAME);
        }

        require_once PATH_CRON.CRON_NAME.'.php';
        $class_name = ucfirst( basename(CRON_NAME) );
        $cron = new $class_name();

        $query = array();
        $query['%start']  = time();
        $query['%result'] = $cron->execute();
        $query['%report'] = $cron->get_report();
        $query['%name']   = CRON_NAME;

        db::simple_query(self::Q_REPORT, $query);
    }
}


/* End of file engine.php */
/* Location: ./system/engine.php */