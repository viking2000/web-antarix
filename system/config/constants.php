<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: PHP Development
 * Date: 15.03.14
 * Time: 15:26
 * To change this template use File | Settings | File Templates.
 */

//access zone
define('Z_PRIVATE',         'private');
define('Z_PROTECTED',      'protected');
define('Z_PUBLIC',        'public');
define('Z_WIDGETS',         'widgets');
define('Z_CLOSED',         'closed');
define('Z_OPEN',            'open');

//point URL
define('URL_OPT',         'url_opt');
define('URL_CONTROLLER',      'url_controller');
define('URL_METHOD',      'url_method');
define('URL_AP',        'url_ap');
define('URL_LANG',        'url_lang');
define('ARTICLE_ID',    'article_id');

//define('REQUEST',         'request');
//define('SUCCESS',         'success');
//define('FAILURE',         'failure');
//define('COMMAND',         'command');
//define('ACTION',          'action');
//define('FORMAT',          'format');
//define('QUERY_PARAM',     'command_data');
//define('SECRET_KEY',      'secret_key');


//permission
define('P_NONE', 0);
define('P_WRITE', 1);
define('P_READ', 2);
define('P_EXECUTE', 4);
define('P_ALL', P_WRITE | P_READ | P_EXECUTE);

define('PATH_SYSTEM_JS',  './system/js/');
define('PATH_FONTS',      APPPATH.'fonts/');
define('PATH_MODULES',    APPPATH.'modules/');
define('PATH_MODELS',     APPPATH.'models/');
define('PATH_STRATEGIES', BASEPATH.'strategies/');
define('PATH_LIBRARY',    APPPATH.'library/');
define('PATH_CRON',       APPPATH.'cron/');