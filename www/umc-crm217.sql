-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 04 2016 г., 09:09
-- Версия сервера: 5.6.17
-- Версия PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `umc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `leb_cells`
--

CREATE TABLE IF NOT EXISTS `leb_cells` (
  `cell_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`cell_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_charts`
--

CREATE TABLE IF NOT EXISTS `leb_charts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL,
  `params` text,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_chat_messages`
--

CREATE TABLE IF NOT EXISTS `leb_chat_messages` (
  `user_id` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `message` text NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_ci_session`
--

CREATE TABLE IF NOT EXISTS `leb_ci_session` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`,`ip_address`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Триггеры `leb_ci_session`
--
DROP TRIGGER IF EXISTS `update_users_status`;
DELIMITER //
CREATE TRIGGER `update_users_status` BEFORE INSERT ON `leb_ci_session`
 FOR EACH ROW BEGIN 
	UPDATE `leb_users_online` SET `status` = "0" WHERE `last_active` < DATE_SUB(NOW(), INTERVAL 1 HOUR); 
    UPDATE `leb_users_online` SET `status` = "1" WHERE `last_active` < DATE_SUB(NOW(), INTERVAL 10 MINUTE) AND `last_active` > DATE_SUB(NOW(), INTERVAL 1 HOUR); 
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_comments`
--

CREATE TABLE IF NOT EXISTS `leb_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `cell_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_consultant`
--

CREATE TABLE IF NOT EXISTS `leb_consultant` (
  `cons_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_adress` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`cons_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_consultant_operator`
--

CREATE TABLE IF NOT EXISTS `leb_consultant_operator` (
  `cons_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_cons_messages`
--

CREATE TABLE IF NOT EXISTS `leb_cons_messages` (
  `cons_user_id` varchar(255) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `new_message` enum('0','1') NOT NULL DEFAULT '1',
  `from_to` enum('0','1') NOT NULL COMMENT 'fom user - 0, to user 1',
  `message` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_cons_users`
--

CREATE TABLE IF NOT EXISTS `leb_cons_users` (
  `cons_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `operator_data` text NOT NULL,
  `cons_user_id` varchar(255) NOT NULL,
  `cons_user_geo` varchar(255) NOT NULL,
  `url_history` text NOT NULL,
  `first_time` datetime NOT NULL,
  `last_time` datetime NOT NULL,
  `umc_cons_open` enum('0','1') NOT NULL,
  `cons_user_name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `date_creat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `HTTP_USER_AGENT` text NOT NULL,
  UNIQUE KEY `cons_user_id` (`cons_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_fields`
--

CREATE TABLE IF NOT EXISTS `leb_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `unique` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `data` text NOT NULL,
  `sort` int(11) NOT NULL,
  `in_cell` enum('0','1') NOT NULL,
  `required` enum('0','1') NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_fields_groups`
--

CREATE TABLE IF NOT EXISTS `leb_fields_groups` (
  `group_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_files`
--

CREATE TABLE IF NOT EXISTS `leb_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cell_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `orig_name` varchar(255) NOT NULL,
  `file_ext` varchar(10) NOT NULL,
  `file_size` varchar(20) NOT NULL,
  `is_image` enum('0','1') NOT NULL DEFAULT '0',
  `autor` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_formapi`
--

CREATE TABLE IF NOT EXISTS `leb_formapi` (
  `form_id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(255) NOT NULL,
  `form_title` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `fields` text NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_group_perms`
--

CREATE TABLE IF NOT EXISTS `leb_group_perms` (
  `perm_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `control_user` enum('0','1') NOT NULL,
  `control_cell` enum('0','1') NOT NULL,
  `control_export` enum('0','1') NOT NULL,
  `control_chat` enum('0','1') DEFAULT NULL,
  `control_chart` enum('0','1') NOT NULL,
  `admin` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`perm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `leb_group_perms`
--

INSERT INTO `leb_group_perms` (`perm_id`, `group_id`, `control_user`, `control_cell`, `control_export`, `control_chat`, `control_chart`, `admin`) VALUES
(1, 1, '1', '1', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `leb_menu`
--

CREATE TABLE IF NOT EXISTS `leb_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('0','1','2') NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `fields` text NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_users`
--

CREATE TABLE IF NOT EXISTS `leb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `is_admin` enum('0','1') CHARACTER SET utf8mb4 NOT NULL,
  `group_id` int(11) NOT NULL,
  `vk_id` int(11) NOT NULL,
  `date_creat` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `leb_users`
--

INSERT INTO `leb_users` (`id`, `username`, `password`, `is_admin`, `group_id`, `vk_id`, `date_creat`) VALUES
(1, 'superuser', '$2a$08$NWRkYmUwYTg4YWI4MDY2Z.3s9.YBl1cKnLOgf/yUB3qmcbjdhGfwu', '1', 1, 0, '2015-11-23');

-- --------------------------------------------------------

--
-- Структура таблицы `leb_users_data`
--

CREATE TABLE IF NOT EXISTS `leb_users_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cell_id` int(11) NOT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `leb_users_data`
--

INSERT INTO `leb_users_data` (`data_id`, `user_id`, `cell_id`) VALUES
(1, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `leb_users_online`
--

CREATE TABLE IF NOT EXISTS `leb_users_online` (
  `user_id` int(11) NOT NULL,
  `status` enum('0','1','2') NOT NULL,
  `last_active` datetime DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `leb_user_groups`
--

CREATE TABLE IF NOT EXISTS `leb_user_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `unique` varchar(255) NOT NULL,
  `postfix` varchar(255) NOT NULL,
  `workmans` enum('0','1') NOT NULL,
  `cell_name` text NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `leb_user_groups`
--

INSERT INTO `leb_user_groups` (`group_id`, `name`, `unique`, `postfix`, `workmans`, `cell_name`, `sort`) VALUES
(1, 'Admin', 'admin', '', '1', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
