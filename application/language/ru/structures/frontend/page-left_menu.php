<?php
$structure = array();

$structure['logo'] = array(
    'image' => 'modules/styles/images/pages/logo.jpg',
    'title' => 'Project-Antary',
    'subtitle' => 'Студия игр'
);

$structure['items']['Главное меню'] = array(
    'Новости' => 'home/news',
    'Наши игры' => 'games/browse',
    'F.A.Q.' => 'faq/browse',
    'Статьи' => 'articles/browse'
);

//$structure['items']['Творчество'] = array(
 //   'Статьи' => 'articles/browse',
    //'Комиксы' => 'pictures/section'
//);

$structure['noindex'] = array('Главная');