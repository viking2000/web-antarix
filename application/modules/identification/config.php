<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();
$config['default']['controller'] = 'identification';
$config['default']['method'] = 'login';

$config['access']['zone'] = Z_PUBLIC; //Метка зоны доступа
$config['access']['user'] = User::T_VISITOR;//Доступ только определённому типу пользователей
$config['access']['group'] = NULL;//доступ только определённым группам
$config['access']['ip'] = NULL; //доступ по ip

$config['cache']['enabled']         = TRUE;//Включено или отключено кеширование страниц
$config['cache']['pages_lifetime']       = 28800;//Время жизни кеша
$config['cache']['page_header'] = TRUE; // компилировать ли стили страницы
$config['cache']['web_lifetime'] = 28800;//Время жизни сборки стилей

$config['web']['system_js']  = array('jquery-1.8.3');
$config['web']['system_css'] = array('reset');
$config['web']['total_js']   = array();
$config['web']['total_css']  = array();
$config['web']['noscript']  = array('noscript');
$config['web']['constants'] = array();

$config['meta']['revisit-after'] = '3 days';
$config['meta']['robots'] = 'none';