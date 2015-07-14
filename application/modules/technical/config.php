<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();
$config['default']['controller'] = '';
$config['default']['method'] = 'index';

$config['access']['zone'] = Z_PUBLIC; //Метка зоны доступа
$config['access']['user'] = User::T_ALL;//Доступ только определённому типу пользователей
$config['access']['group'] = NULL;//доступ только определённым группам
$config['access']['ip'] = NULL; //доступ по ip

$config['cache']['enabled']         = FALSE;//Включено или отключено кеширование страниц
$config['cache']['lifetime']       = 300;//Время жизни кеша

$config['meta']['revisit-after'] = '3 days';
$config['meta']['robots'] = 'none';