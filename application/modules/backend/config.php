<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();

$config['default']['controller'] = 'menu';
$config['default']['method'] = 'main';
$config['default']['style_suite'] = 'default';

$config['access']['zone'] = Z_PRIVATE; //Метка зоны доступа
$config['access']['user'] = User::T_ADMIN;//Доступ только определённому типу пользователей
$config['access']['group'] = NULL;//доступ только определённым группам
$config['access']['ip'] = NULL; //доступ по ip
$config['access']['check_blocked_ip'] = TRUE; //Проверять блокировку ip адреса
$config['access']['permissions'] = P_NONE;//Права доступа на всю точку доступа
$config['access']['base']['permissions'] = P_NONE;//права доступа на конкретный контроллер

//$config['controller']['_system']['zone'] = Z_CLOSED; //Описание доступа к конкретным контроллерам

$config['cache']['enabled']         = FALSE;//Включено или отключено кеширование страниц
$config['cache']['lifetime']       = 300;//Время жизни кеша
$config['cache']['page_header'] = FALSE; // компилировать ли стили страницы
$config['cache']['web_lifetime'] = 28800;//Время жизни сборки стилей

$config['web']['styles']  = array('system/import','system/reset', 'pages/backend' );
$config['web']['scripts']  = array('system/jquery-1.8.3', 'system/wx');
$config['web']['noscript']  = array('noscript');
$config['web']['constants'] = array();

$config['meta']['revisit-after'] = '3 days';
$config['meta']['robots'] = 'all';


