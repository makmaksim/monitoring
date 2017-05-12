/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>cells`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>cells` (
  `cell_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`cell_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>charts`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>charts` (
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
-- Структура таблицы `<?=$db_prefix?>chat_messages`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>chat_messages` (
  `user_id` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `message` text NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>ci_session`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>ci_session` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`,`ip_address`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>comments`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>comments` (
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
-- Структура таблицы `<?=$db_prefix?>consultant`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>consultant` (
  `cons_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_adress` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`cons_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>consultant_operator`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>consultant_operator` (
  `cons_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>cons_messages`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>cons_messages` (
  `cons_user_id` varchar(255) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `new_message` enum('0','1') NOT NULL DEFAULT '1',
  `from_to` enum('0','1') NOT NULL COMMENT 'fom user - 0, to user 1',
  `message` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>cons_users`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>cons_users` (
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
-- Структура таблицы `<?=$db_prefix?>fields`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>fields` (
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
-- Структура таблицы `<?=$db_prefix?>fields_groups`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>fields_groups` (
  `group_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>files`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>files` (
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
-- Структура таблицы `<?=$db_prefix?>formapi`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>formapi` (
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
-- Структура таблицы `<?=$db_prefix?>group_perms`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>group_perms` (
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
-- Дамп данных таблицы `<?=$db_prefix?>group_perms`
--

INSERT INTO `<?=$db_prefix?>group_perms` (`perm_id`, `group_id`, `control_user`, `control_cell`, `control_export`, `control_chat`, `control_chart`, `admin`) VALUES
(1, 1, '1', '1', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>menu`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>menu` (
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
-- Структура таблицы `<?=$db_prefix?>users`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>users` (
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
-- Дамп данных таблицы `<?=$db_prefix?>users`
--

INSERT INTO `<?=$db_prefix?>users` (`id`, `username`, `password`, `is_admin`, `group_id`, `vk_id`, `date_creat`) VALUES
(1, '<?=$username?>', '<?=$password?>', '1', 1, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>users_data`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>users_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cell_id` int(11) NOT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `<?=$db_prefix?>users_data`
--

INSERT INTO `<?=$db_prefix?>users_data` (`data_id`, `user_id`, `cell_id`) VALUES
(1, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>users_online`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>users_online` (
  `user_id` int(11) NOT NULL,
  `status` enum('0','1','2') NOT NULL,
  `last_active` datetime DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `<?=$db_prefix?>user_groups`
--

CREATE TABLE IF NOT EXISTS `<?=$db_prefix?>user_groups` (
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
-- Дамп данных таблицы `<?=$db_prefix?>user_groups`
--

INSERT INTO `<?=$db_prefix?>user_groups` (`group_id`, `name`, `unique`, `postfix`, `workmans`, `cell_name`, `sort`) VALUES
(1, 'Admin', 'admin', '', '1', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
