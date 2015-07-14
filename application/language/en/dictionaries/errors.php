<?php
$words = array();
// Клиенские ошибки
$words['captcha_error'] = 'Введён неправильный проверочный код!';
$word['text_length_error'] = 'Превышена допустимая длина сообщения! Одно сообщение может вмещать только 65000 символов. Для решения проблемы Вы можете - либо разбить сообщение на несколько которотких, либо отредактировать содержимое текущего сообщения.';
$words['empty_field'] = 'Одно из обязательных полей - пустое!';

//Серверные ошибки
//Внутренняя ошибка
$words['error-500'] = 'В данный момент на сайте ведутся технические работы!';
$words['5000000'] = 'Не найден объект стратегии. Путь к файлу: [?]';
$words['5000001'] = 'В системном файле отсутствует требуемый класс. Файл:[?], Класс:[?]';
$words['5000002'] = 'Неправильный объект комманды. Файл:[?], Объект:[?]';
$words['5000003'] = 'Отсутствует какой либо набор стилей страницы.';
$words['5000004'] = 'Файл или класс библиотеки [?] отуствстует!';

//Ошибка несуществующей страницы
$words['error-404'] = 'Страницы не существует!';
$words['4040000'] = 'Не найден объект модуля. Путь к файлу: [?]';
$words['4040001'] = 'В контроллере отсутствует вызываемый метод. Контроллер: [?], метод: [?]';
$words['4040002'] = 'В комманде отсутствует вызываемый метод. Комманда: [?], метод: [?]';
$words['4040003'] = 'Ошибка точки доступа. Параметры: [?]';
$words['4040004'] = 'Запрос на чтение документа без параметров, type[?], id[?]';
$words['4040005'] = 'Не существует объекта - view, view[?]';
$words['4040006'] = 'Запрос на отображение технической зоны сайта, модуль [?]';

//Отказ в доступе
$words['error-403'] = 'Отказано в доступе!';
$words['4030000'] = 'У пользователя нет прав на отображение страницы. Страница:[?]';
$words['4030001'] = 'Ошибка сигнатуры';
$words['4030002'] = 'Недостаточно прав на исполнение комманды:[?]';
$words['4030003'] = 'Доступ ip[?] запрещён, обнаружена попытка проникновения.';


//Ошибка переданных данных
$words['error-406'] = 'Неправильные данные!';
$words['4060000'] = 'Неправильный формат данных команде. Данные:[?]';
$words['4060001'] = 'В команду переданы не все обязательные параметры. Данные:[?]';
$words['4060002'] = 'Форматы данных клиент-сервер не соответствуют друг другу. Данные:[?]';