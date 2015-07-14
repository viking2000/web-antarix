<?php  if ( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 02.10.14
 * Time: 23:41
 * To change this template use File | Settings | File Templates.
 */

class Cron {

    const SUCCESS = 'success';
    const FAILURE = 'failure';
    const ERROR   = 'error';

    public function get_report()
    {
        return 'Cron complete';
    }
}