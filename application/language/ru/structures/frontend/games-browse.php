<?php
$structure = array (
    'title' => 'Наши игры',
    'subtitle' => 'Секреты, описание, прохождение',
    'types' => array(
        array (
            'title' => 'Все игры',
            'description' => 'Отобразить все игры в порядке их даты создания.',
            'image' => base_url('modules/images/games/types/all_games.jpg'),
            'link' => base_url('games/browse/'),
            'button' => 'Отобразить'
        ),
        array (
            'title' => 'Логические игры',
            'description' => 'Отобразить только игры категории - логические. К таким играм относятся: тетрис, три в ряд, шашки, шахматы и т.д.',
            'image' => base_url('modules/images/games/types/puzzle.jpg'),
            'link' => base_url('games/browse/puzzle'),
            'button' => 'Отобразить'
        )
    ),
    'meta' => array(
        'keywords' => 'Игры студии Project-Antary',
        'description' => 'Список игр выпущенных студией Project-Antary'
    )
);