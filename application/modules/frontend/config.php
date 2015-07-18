<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();

$config['default']['controller'] = 'home';
$config['default']['method'] = 'index';

$config['access']['zone'] = Z_PUBLIC; //Метка зоны доступа
//$config['access']['user'] = NULL;//Доступ только определённому типу пользователей
//$config['access']['group'] = NULL;//доступ только определённым группам
//$config['access']['ip'] = NULL; //доступ по ip
//$config['access']['permissions'] = P_NONE;//Права доступа на всю точку доступа
//$config['access']['base']['permissions'] = P_NONE;//права доступа на конкретный контроллер

//$config['controller']['_system']['zone'] = Z_CLOSED; //Описание доступа к конкретным контроллерам

$config['cache']['enabled']         = FALSE;//Включено или отключено кеширование страниц
$config['cache']['pages_lifetime']       = 28800;//Время жизни кеша
$config['cache']['page_header'] = FALSE; // компилировать ли стили страницы
$config['cache']['web_lifetime'] = 28800;//Время жизни сборки стилей

$config['web']['scripts']  = array('system/jquery-1.8.3');//список подключаемых js файлов
$config['web']['styles']  = array('system/import','system/reset', 'pages/frontend', 'pages/frontend-base_style' );//список подключаемх css файлов
$config['web']['noscript']  = array('noscript');//стили при отключённом js
$config['web']['constants'] = array();//Константы

$config['meta']['revisit-after'] = '7 days';
$config['meta']['robots'] = 'all';

