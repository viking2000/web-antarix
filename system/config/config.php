<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');/** * Created by JetBrains PhpStorm. * User: PHP Development * Date: 15.03.14 * Time: 14:42 * To change this template use File | Settings | File Templates. *///deprecate$config = array();//new format$config['settings']['protocol'] = ( isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS']=='on') ) ? 'https://' : 'http://';$config['settings']['site'] = 'example.ru';$config['settings']['base_url'] = $config['settings']['protocol'].$config['settings']['site'].'/';$config['settings']['web_format'] = 'json'; //Устанавливает форматирование данных, для передачи по сети$config['settings']['db_format'] = 'json'; //Устанавливает форматирование данных, для хранения в db$config['settings']['compress_output'] = TRUE; //Сжатие выходных данных$config['settings']['multilingualism'] = TRUE; //Многоязычность$config['settings']['default_lang'] = 'ru'; //язык по умолчанию/*| -------------------------------------------------------------------| DATABASE CONNECTIVITY SETTINGS| -------------------------------------------------------------------*/$config['db']['hostname'] = 'p:localhost';$config['db']['username'] = 'root';$config['db']['password'] = '';$config['db']['database'] = 'example_db';$config['db']['cache_on'] = FALSE;$config['db']['cachedir'] = '';$config['db']['char_set'] = 'utf8';$config['db']['dbcollat'] = 'utf8_general_ci';/*| -------------------------------------------------------------------| ROUTING SETTINGS| -------------------------------------------------------------------*/$config['route']['backend'] = 'wx-editpanel';$config['route']['technical'] = 'technical';$config['route']['frontend'] = '';/*| -------------------------------------------------------------------| SESSIONS SETTINGS| -------------------------------------------------------------------*/$config['session']['lifetime'] = 2678400;$config['session']['short_lifetime'] = 3600;$config['session']['web_format'] = 'json';//формат хранения данных в куках пользователя/*| -------------------------------------------------------------------| WEB ELEMENTS| -------------------------------------------------------------------*/$config['web']['is_mobile']      = FALSE;//is_mobile();$config['web']['sig_params']     = array(Session::COOKIE_ID, 'command_data', 'time', 'format', 'location');//Список обязательных параметров для проверки сигнатуры$config['web']['doctype_pc']     = '<!DOCTYPE html>';$config['web']['doctype_mobile'] = '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">';/* End of file database.php *//* Location: ./application/config/database.php */