-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июл 11 2015 г., 00:47
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `example_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `log_error`
--

CREATE TABLE IF NOT EXISTS `log_error` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` int(3) unsigned NOT NULL,
  `line` int(10) unsigned NOT NULL,
  `uid` varchar(100) COLLATE utf8_bin NOT NULL,
  `tag` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `file` text COLLATE utf8_bin,
  `message` text COLLATE utf8_bin,
  `stack` text COLLATE utf8_bin,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `create` (`creation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sys_blocked_ip`
--

CREATE TABLE IF NOT EXISTS `sys_blocked_ip` (
  `ip` int(10) unsigned NOT NULL,
  `blocked_date` datetime NOT NULL,
  `violations` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `sys_session`
--

CREATE TABLE IF NOT EXISTS `sys_session` (
  `sid` char(32) COLLATE utf8_bin NOT NULL,
  `uid` int(10) unsigned DEFAULT NULL,
  `ip_address` int(10) unsigned NOT NULL,
  `user_agent` varchar(120) COLLATE utf8_bin NOT NULL,
  `user_data` text COLLATE utf8_bin,
  `last_activity` datetime DEFAULT NULL,
  `life_time` datetime NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `session_id` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(64) COLLATE utf8_bin NOT NULL,
  `group_list` text COLLATE utf8_bin NOT NULL,
  `creation` datetime NOT NULL,
  `secret_key` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `type` enum('admin','user','system') COLLATE utf8_bin DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`login`),
  KEY `name` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Структура таблицы `log_cron`
--

CREATE TABLE IF NOT EXISTS `log_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `report` text COLLATE utf8_bin NOT NULL,
  `result` varchar(10) COLLATE utf8_bin NOT NULL,
  `creation` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `group_list`, `creation`, `secret_key`, `type`) VALUES
(1, 'admin', '$2a$10$67d829f868b90258eb0e1umkx6rNFvER3UzcaqtR6Gh9fc9F8UHsG', '', '2014-03-16 00:00:00', '9a|e270cd]}|=5{fD+8#4$1%F6EFF<3^', 'admin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
