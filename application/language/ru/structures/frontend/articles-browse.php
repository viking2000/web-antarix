<?php
$structure = array (
    'title' => 'Статьи',
    'subtitle' => 'Технологии, уроки, примеры',
    'types' => array(
        array (
            'title' => 'Все статьи',
            'description' => 'Отобразить все статьи в порядке даты создания.',
            'image' => base_url('modules/images/articles/types/all.jpg'),
            'link' => base_url('articles/browse/'),
            'button' => 'Отобразить'
        ),
        array (
            'title' => 'Cocos2D-x и C++',
            'description' => 'Уроки по программированию на Cocos2D-x C++ под мобильные платформы',
            'image' => base_url('modules/images/articles/types/cocos2d-x.jpg'),
            'link' => base_url('articles/browse/cocos2d-x'),
            'button' => 'Отобразить'
        )
    ),
    'meta' => array()
);