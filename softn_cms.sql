-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-08-2016 a las 16:17:19
-- Versión del servidor: 5.6.25
-- Versión de PHP: 5.5.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `softn_cms`
--
CREATE DATABASE IF NOT EXISTS `softn_cms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `softn_cms`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_categories`
--

DROP TABLE IF EXISTS `sn_categories`;
CREATE TABLE IF NOT EXISTS `sn_categories` (
  `ID` int(11) NOT NULL,
  `category_name` varchar(60) NOT NULL DEFAULT '',
  `category_description` text,
  `category_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_categories`
--

INSERT INTO `sn_categories` (`ID`, `category_name`, `category_description`, `category_count`) VALUES
(1, 'Uncategorized', NULL, 0),
(3, 'RLL34IIL1UM', 'natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', 0),
(5, 'UYT93MTW1TQ', 'convallis, ante lectus convallis est, vitae sodales nisi magna sed', 0),
(7, 'RKE66ICG7TI', 'vel arcu eu odio tristique pharetra. Quisque ac libero nec', 2),
(11, 'QOJ75BFV6IR', 'metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus', 1),
(15, 'XVQ44XKA9TX', 'a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc', 1),
(19, 'QXD05VZY3KK', 'orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique', 1),
(20, 'SLO99EUG6YX', 'et risus. Quisque libero lacus, varius et, euismod et, commodo', 0),
(24, 'QDZ75TPL9YN', 'sit amet ornare lectus justo eu arcu. Morbi sit amet', 1),
(25, 'RMF35SMI4OK', 'Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie', 1),
(26, 'UYC08KHL2YD', 'sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices', 1),
(30, 'WVF06ZJW2TI', 'scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit', 0),
(34, 'UXE84KKE7AV', 'et nunc. Quisque ornare tortor at risus. Nunc ac sem', 1),
(35, 'XXR91UEY9HH', 'lacinia orci, consectetuer euismod est arcu ac orci. Ut semper', 2),
(36, 'WQB41IBY4FX', 'condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing', 1),
(46, 'PPR24GMV1SO', 'amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus', 1),
(47, 'SKT73TTK2RE', 'elit, a feugiat tellus lorem eu metus. In lorem. Donec', 2),
(49, 'SRS31ZYP0ZW', 'orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna', 3),
(50, 'TFA13TOM0KO', 'sem, vitae aliquam eros turpis non enim. Mauris quis turpis', 3),
(51, 'SFZ25DNB1YR', 'neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_comments`
--

DROP TABLE IF EXISTS `sn_comments`;
CREATE TABLE IF NOT EXISTS `sn_comments` (
  `ID` int(11) NOT NULL,
  `comment_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Estado de aprovación del comentario. (1 = aprobado, 0 = sin aprobar)',
  `comment_autor` varchar(60) NOT NULL,
  `comment_author_email` varchar(100) NOT NULL,
  `comment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_contents` text NOT NULL COMMENT 'Contenido del comentario',
  `comment_user_ID` int(11) DEFAULT '0' COMMENT 'Si su valor es 0, el usuario no esta registrado en la pagina.',
  `post_ID` int(11) NOT NULL COMMENT 'Identificador del post'
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_comments`
--

INSERT INTO `sn_comments` (`ID`, `comment_status`, `comment_autor`, `comment_author_email`, `comment_date`, `comment_contents`, `comment_user_ID`, `post_ID`) VALUES
(1, 0, 'Veronica Santana', 'ut.dolor@Fusce.com', '2005-10-18 14:38:59', 'nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus', 0, 25),
(2, 1, 'Gemma Diaz', 'ante.ipsum@luctusCurabitur.edu', '2016-01-25 14:13:06', 'eget laoreet posuere, enim nisl elementum purus, accumsan interdum libero', 0, 85),
(3, 0, 'Reese Kline', 'fringilla@molestiepharetranibh.org', '2004-10-22 03:13:53', 'ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam', 0, 92),
(4, 1, 'Isaac Klein', 'urna.Nunc@ipsumportaelit.com', '2011-12-09 05:26:32', 'luctus lobortis. Class aptent taciti sociosqu ad litora torquent per', 0, 12),
(5, 0, 'Mallory Pace', 'dolor@lobortisnisinibh.co.uk', '2005-02-07 04:16:54', 'egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est', 0, 14),
(10, 0, 'Britanni Jones', 'Nullam.suscipit.est@acfacilisisfacilisis.net', '2011-01-24 18:35:58', 'id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor,', 0, 37),
(11, 0, 'Aidan Peterson', 'felis.Donec@Cumsociis.ca', '2016-02-29 19:19:04', 'Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor', 0, 94),
(12, 0, 'Wanda Russo', 'iaculis.quis.pede@vehiculaPellentesquetincidunt.ca', '2015-08-24 04:49:26', 'scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed', 0, 10),
(13, 1, 'Selma Duffy', 'amet@tellus.co.uk', '2005-08-01 17:19:20', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor,', 0, 60),
(14, 0, 'Laurel Underwood', 'odio.Aliquam@pretium.org', '2001-12-17 16:57:51', 'Nam nulla magna, malesuada vel, convallis in, cursus et, eros.', 0, 16),
(15, 0, 'Wang Rivas', 'tincidunt.nunc@Proin.org', '2014-02-01 05:54:36', 'nunc. In at pede. Cras vulputate velit eu sem. Pellentesque', 0, 62),
(16, 0, 'Julian Giles', 'interdum.feugiat@dis.com', '2008-10-12 08:48:11', 'dui. Fusce diam nunc, ullamcorper eu, euismod ac, fermentum vel,', 0, 68),
(17, 1, 'Lunea Terry', 'ultricies@nullaat.ca', '2002-05-22 19:19:20', 'consectetuer euismod est arcu ac orci. Ut semper pretium neque.', 0, 37),
(18, 0, 'Stewart Butler', 'Quisque.porttitor@maurisid.co.uk', '2000-12-11 23:52:40', 'mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor', 0, 9),
(19, 1, 'Linda Rogers', 'Duis@feugiatnec.org', '2006-09-29 16:02:41', 'sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra,', 0, 51),
(20, 0, 'Indira Lott', 'a@lacuspedesagittis.co.uk', '2011-03-10 12:38:29', 'magna a neque. Nullam ut nisi a odio semper cursus.', 0, 64),
(21, 0, 'Zephania Hewitt', 'ut.cursus@nisimagnased.ca', '2012-09-27 18:18:37', 'non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh.', 0, 69),
(22, 0, 'Sara Warner', 'sociosqu.ad.litora@nequevenenatislacus.com', '2008-10-12 03:20:45', 'commodo tincidunt nibh. Phasellus nulla. Integer vulputate, risus a ultricies', 0, 33),
(23, 0, 'Ronan Dixon', 'nisi@Fusce.edu', '2010-07-23 04:10:34', 'leo elementum sem, vitae aliquam eros turpis non enim. Mauris', 0, 52),
(24, 1, 'Grady Riggs', 'ultrices.Vivamus.rhoncus@nullaIn.net', '2008-02-05 19:39:07', 'Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus', 0, 9),
(25, 1, 'Odysseus Tate', 'a.enim@idliberoDonec.org', '2009-01-22 15:48:53', 'risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a', 0, 66),
(26, 0, 'Natalie Contreras', 'Duis.elementum.dui@malesuadafames.co.uk', '2008-05-12 08:09:52', 'vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac', 0, 79),
(27, 1, 'Anjolie Holloway', 'sem.magna@bibendum.edu', '2009-09-20 00:54:27', 'blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus', 0, 78),
(28, 0, 'Ulric Hebert', 'Nam.tempor.diam@faucibusid.org', '2011-02-26 02:31:12', 'et risus. Quisque libero lacus, varius et, euismod et, commodo', 0, 40),
(29, 0, 'Flavia Noble', 'lectus.sit@sapien.net', '2001-05-14 20:09:34', 'neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc', 0, 62),
(30, 1, 'Vladimir Salazar', 'pede.nec@ut.ca', '2013-05-20 13:06:44', 'id risus quis diam luctus lobortis. Class aptent taciti sociosqu', 0, 63),
(31, 0, 'Glenna Avery', 'orci.adipiscing.non@molestiepharetra.ca', '2007-11-21 23:26:27', 'orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero', 0, 24),
(32, 1, 'Eleanor Flowers', 'ullamcorper.eu.euismod@fringillaDonec.ca', '2015-10-14 02:41:55', 'adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor', 0, 52),
(33, 0, 'Cadman Greer', 'tempor@sit.net', '2002-03-14 04:38:40', 'Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu.', 0, 40),
(36, 1, 'Mollie Jones', 'lobortis@tinciduntorci.co.uk', '2005-04-10 18:53:23', 'adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque', 0, 9),
(37, 0, 'Carol Ratliff', 'gravida@eleifendnec.ca', '2010-08-28 12:28:57', 'amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor', 0, 79),
(38, 1, 'Dalton Mccullough', 'commodo@euturpisNulla.edu', '2007-05-12 05:42:28', 'lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc', 0, 79),
(39, 1, 'Josiah Hurst', 'eu@Vivamussit.ca', '2003-01-22 14:03:09', 'magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu', 0, 69),
(41, 0, 'Phyllis Sims', 'nonummy.ultricies@dui.ca', '2011-05-31 04:45:31', 'non nisi. Aenean eget metus. In nec orci. Donec nibh.', 0, 7),
(42, 1, 'Ruby Nicholson', 'velit.Aliquam@orci.co.uk', '2015-02-15 05:56:34', 'massa non ante bibendum ullamcorper. Duis cursus, diam at pretium', 0, 92),
(43, 1, 'Germane Lane', 'aliquet.nec@etmagnis.org', '2011-06-02 23:38:18', 'nulla at sem molestie sodales. Mauris blandit enim consequat purus.', 0, 51),
(44, 0, 'Tasha Baxter', 'quis.lectus@euismodin.org', '2012-09-07 16:09:04', 'Quisque ornare tortor at risus. Nunc ac sem ut dolor', 0, 19);

--
-- Disparadores `sn_comments`
--
DROP TRIGGER IF EXISTS `sn_comments_AFTER_INSERT`;
DELIMITER $$
CREATE TRIGGER `sn_comments_AFTER_INSERT` AFTER INSERT ON `sn_comments`
 FOR EACH ROW BEGIN
	DECLARE count INT;
    SELECT comment_count INTO count FROM sn_posts WHERE ID = NEW.post_ID;
    UPDATE sn_posts SET comment_count = count + 1 WHERE ID = NEW.post_ID;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `sn_comments_BEFORE_DELETE`;
DELIMITER $$
CREATE TRIGGER `sn_comments_BEFORE_DELETE` BEFORE DELETE ON `sn_comments`
 FOR EACH ROW BEGIN
	DECLARE count INT;
    SELECT comment_count INTO count FROM sn_posts WHERE ID = OLD.post_ID;
	UPDATE sn_posts SET comment_count = count - 1 WHERE ID = OLD.post_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_menus`
--

DROP TABLE IF EXISTS `sn_menus`;
CREATE TABLE IF NOT EXISTS `sn_menus` (
  `ID` int(11) NOT NULL,
  `menu_name` varchar(60) NOT NULL,
  `menu_url` varchar(100) DEFAULT NULL,
  `menu_sub` mediumint(9) NOT NULL DEFAULT '0' COMMENT 'Identificador del elemento padre, si se da el caso',
  `menu_position` int(11) NOT NULL DEFAULT '1' COMMENT 'Indica la posición del menu',
  `menu_title` varchar(60) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_menus`
--

INSERT INTO `sn_menus` (`ID`, `menu_name`, `menu_url`, `menu_sub`, `menu_position`, `menu_title`) VALUES
(93, 'menu1', '', 0, 1, 'menu'),
(99, '', '', 98, 1, ''),
(100, 'a1', '', 93, 1, 'a1'),
(101, 'a2', '', 93, 2, 'a2'),
(102, 'a3', '', 93, 3, 'a3'),
(103, 'a4', '', 93, 5, 'a4'),
(105, 'a7', '', 93, 4, 'a7');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_options`
--

DROP TABLE IF EXISTS `sn_options`;
CREATE TABLE IF NOT EXISTS `sn_options` (
  `ID` int(11) NOT NULL,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_options`
--

INSERT INTO `sn_options` (`ID`, `option_name`, `option_value`) VALUES
(1, 'optionTitle', 'SoftN CMS'),
(2, 'optionDescription', 'Sistema gestor de contenido.'),
(3, 'optionPaged', '9'),
(4, 'optionSiteUrl', 'http://localhost/ProyectosWeb/htdocsxampp/SoftN-CMS/'),
(5, 'optionTheme', 'default'),
(6, 'optionEmailAdmin', 'localhost@localhost.com'),
(7, 'optionMenu', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_posts`
--

DROP TABLE IF EXISTS `sn_posts`;
CREATE TABLE IF NOT EXISTS `sn_posts` (
  `ID` int(11) NOT NULL,
  `post_title` varchar(45) NOT NULL DEFAULT '' COMMENT 'Título del artículo',
  `post_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Estado de publicación. (1 = Publicado, 0 = Borrador)',
  `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de publicación',
  `post_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de la ultima actualización',
  `post_contents` longtext NOT NULL COMMENT 'Contenido del artículo',
  `comment_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Estado de comentarios, (1 = habilitado, 0 = deshabilitado)',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Total de comentarios',
  `user_ID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_posts`
--

INSERT INTO `sn_posts` (`ID`, `post_title`, `post_status`, `post_date`, `post_update`, `post_contents`, `comment_status`, `comment_count`, `user_ID`) VALUES
(5, 'entrada actualizada test5', 0, '2014-10-04 04:15:23', '2016-07-24 01:55:26', '<p>Fusce diam nunc, ullamcorper eu, euismod ac, fermentum vel, mauris.</p>', 1, 0, 5),
(7, 'actualizando titulo test2', 1, '2012-02-14 02:00:11', '2016-07-24 01:13:42', '<p>tempor lorem, eget mollis lectus pede et risus. Quisque libero</p>', 1, 1, 39),
(8, 'augue. Sed molestie. Sed id risus quis diam l', 1, '2013-09-07 19:47:44', '2016-06-10 00:26:41', 'at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit', 1, 0, 38),
(9, 'rutrum magna. Cras convallis convallis dolor.', 0, '2006-07-26 10:04:13', '2016-06-10 00:26:41', 'tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh', 0, 3, 24),
(10, 'ridiculus mus. Aenean eget', 1, '2005-03-03 22:39:54', '2016-06-10 00:26:41', 'magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed,', 0, 1, 7),
(12, 'diam. Pellentesque habitant morbi tristique s', 1, '2012-08-21 16:53:34', '2016-06-10 00:26:41', 'blandit enim consequat purus. Maecenas libero est, congue a, aliquet', 1, 1, 23),
(13, 'litora torquent per conubia nostra, per incep', 1, '2009-02-21 13:14:34', '2016-06-10 00:26:41', 'faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare.', 1, 0, 14),
(14, 'blandit at, nisi. Cum sociis natoque penatibu', 0, '2009-11-28 11:20:13', '2016-06-10 00:26:41', 'cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet', 0, 1, 13),
(16, 'mauris. Morbi non sapien molestie orci tincid', 1, '2015-09-15 12:27:53', '2016-06-10 00:26:41', 'sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis,', 0, 1, 13),
(17, 'Ut sagittis lobortis mauris. Suspendisse aliq', 1, '2009-02-02 13:11:22', '2016-06-10 00:26:41', 'urna justo faucibus lectus, a sollicitudin orci sem eget massa.', 1, 0, 1),
(18, 'ipsum non arcu. Vivamus sit amet risus. Donec', 1, '2011-08-05 20:08:35', '2016-06-10 00:26:41', 'cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis', 1, 0, 38),
(19, 'nec metus facilisis lorem tristique aliquet. ', 0, '2005-03-29 18:57:26', '2016-06-10 00:26:41', 'dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus', 1, 1, 27),
(20, 'Donec vitae erat vel pede', 0, '2016-03-06 02:17:38', '2016-06-10 00:26:41', 'sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales.', 0, 0, 24),
(21, 'mauris blandit mattis. Cras eget nisi dictum ', 1, '2004-09-08 15:44:42', '2016-06-10 00:26:41', 'neque. Nullam ut nisi a odio semper cursus. Integer mollis.', 1, 0, 39),
(22, 'vitae purus gravida sagittis. Duis gravida. P', 1, '2013-10-12 22:01:06', '2016-06-10 00:26:41', 'ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit', 1, 0, 24),
(23, 'eu, placerat eget, venenatis a, magna. Lorem ', 0, '2009-04-05 02:37:52', '2016-06-10 00:26:41', 'ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum', 0, 0, 35),
(24, 'interdum enim non nisi. Aenean eget metus. In', 1, '2012-11-07 12:20:55', '2016-06-10 00:26:41', 'a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis', 0, 1, 33),
(25, 'lorem, auctor quis, tristique ac, eleifend vi', 0, '2013-03-05 16:41:18', '2016-06-10 00:26:41', 'enim diam vel arcu. Curabitur ut odio vel est tempor', 0, 1, 32),
(26, 'justo eu arcu. Morbi sit amet massa. Quisque ', 1, '2007-07-05 15:11:26', '2016-06-10 00:26:41', 'dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue,', 0, 0, 1),
(27, 'et, magna. Praesent interdum ligula eu enim. ', 1, '2009-07-28 02:47:23', '2016-06-10 00:26:41', 'malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas,', 0, 0, 40),
(29, 'non leo. Vivamus nibh dolor, nonummy ac, feug', 0, '2002-09-09 00:11:57', '2016-06-10 00:26:41', 'id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus', 1, 0, 12),
(30, 'at,', 1, '2002-06-09 01:25:03', '2016-06-10 00:26:41', 'est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed', 0, 0, 41),
(31, 'nulla at sem molestie sodales. Mauris blandit', 0, '2015-05-17 04:18:32', '2016-06-10 00:26:41', 'risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed', 0, 0, 24),
(32, 'blandit congue. In scelerisque scelerisque du', 0, '2004-02-02 04:43:14', '2016-06-10 00:26:41', 'ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor, velit', 0, 0, 4),
(33, 'sit amet diam eu dolor egestas rhoncus. Proin', 0, '2002-05-31 02:40:04', '2016-06-10 00:26:41', 'Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum', 0, 1, 40),
(34, 'ac tellus. Suspendisse sed dolor. Fusce mi lo', 0, '2007-10-05 15:14:35', '2016-06-10 00:26:41', 'fringilla, porttitor vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin', 0, 0, 4),
(36, 'interdum. Nunc sollicitudin commodo ipsum. Su', 0, '2013-09-28 11:23:54', '2016-06-10 00:26:41', 'fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque', 1, 0, 37),
(37, 'eu, odio. Phasellus at augue id', 1, '2012-12-29 07:03:00', '2016-06-10 00:26:41', 'volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean', 1, 2, 1),
(38, 'nec tempus scelerisque, lorem ipsum sodales p', 0, '2002-05-14 13:15:53', '2016-06-10 00:26:41', 'orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec', 1, 0, 4),
(39, 'sed pede. Cum sociis natoque penatibus et mag', 1, '2011-12-24 15:37:03', '2016-06-10 00:26:41', 'feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam nunc,', 0, 0, 22),
(40, 'dictum eu, eleifend nec, malesuada ut, sem. N', 1, '2006-04-21 08:00:28', '2016-06-10 00:26:41', 'malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit.', 1, 2, 33),
(41, 'Donec tempus, lorem fringilla ornare placerat', 1, '2015-02-27 03:18:08', '2016-06-10 00:26:41', 'ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus', 1, 0, 34),
(42, 'penatibus et magnis dis parturient montes, na', 1, '2002-12-02 19:52:54', '2016-06-10 00:26:41', 'Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum', 1, 0, 17),
(43, 'a, aliquet vel, vulputate eu, odio. Phasellus', 1, '2002-12-28 12:51:33', '2016-06-10 00:26:41', 'diam. Pellentesque habitant morbi tristique senectus et netus et malesuada', 0, 0, 7),
(44, 'vitae risus. Duis a mi', 0, '2008-05-21 08:17:33', '2016-06-10 00:26:41', 'odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a,', 1, 0, 41),
(51, 'gravida sagittis. Duis gravida. Praesent eu n', 1, '2004-08-06 18:26:12', '2016-06-10 00:26:41', 'elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris', 1, 2, 30),
(52, 'tellus. Suspendisse sed dolor. Fusce mi lorem', 0, '2012-09-29 04:32:12', '2016-06-10 00:26:41', 'neque sed sem egestas blandit. Nam nulla magna, malesuada vel,', 1, 2, 21),
(53, 'molestie tellus. Aenean egestas hendrerit neq', 0, '2013-02-27 20:42:19', '2016-06-10 00:26:41', 'amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor', 1, 0, 34),
(54, 'ante ipsum', 0, '2005-05-24 16:18:51', '2016-06-10 00:26:41', 'sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo.', 0, 0, 31),
(55, 'pede blandit congue. In scelerisque scelerisq', 0, '2014-10-04 04:15:23', '2016-06-10 00:26:41', 'Fusce diam nunc, ullamcorper eu, euismod ac, fermentum vel, mauris.', 1, 0, 1),
(57, 'non ante bibendum ullamcorper. Duis cursus, d', 0, '2012-02-14 02:00:11', '2016-06-10 00:26:41', 'tempor lorem, eget mollis lectus pede et risus. Quisque libero', 0, 0, 3),
(59, 'rutrum magna. Cras convallis convallis dolor.', 0, '2006-07-26 10:04:13', '2016-06-10 00:26:41', 'tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh', 0, 0, 30),
(60, 'ridiculus mus. Aenean eget', 1, '2005-03-03 22:39:54', '2016-06-10 00:26:41', 'magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed,', 0, 1, 36),
(61, 'mauris sagittis placerat. Cras dictum ultrici', 1, '2000-08-08 19:43:44', '2016-06-10 00:26:41', 'mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent', 1, 0, 23),
(62, 'diam. Pellentesque habitant morbi tristique s', 1, '2012-08-21 16:53:34', '2016-06-10 00:26:41', 'blandit enim consequat purus. Maecenas libero est, congue a, aliquet', 1, 2, 24),
(63, 'litora torquent per conubia nostra, per incep', 1, '2009-02-21 13:14:34', '2016-06-10 00:26:41', 'faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare.', 1, 1, 38),
(64, 'blandit at, nisi. Cum sociis natoque penatibu', 0, '2009-11-28 11:20:13', '2016-06-10 00:26:41', 'cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet', 0, 1, 41),
(65, 'Proin ultrices. Duis volutpat nunc sit amet m', 0, '2004-07-19 19:30:50', '2016-06-10 00:26:41', 'lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam', 1, 0, 39),
(66, 'mauris. Morbi non sapien molestie orci tincid', 1, '2015-09-15 12:27:53', '2016-06-10 00:26:41', 'sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis,', 0, 1, 32),
(67, 'Ut sagittis lobortis mauris. Suspendisse aliq', 1, '2009-02-02 13:11:22', '2016-06-10 00:26:41', 'urna justo faucibus lectus, a sollicitudin orci sem eget massa.', 1, 0, 35),
(68, 'ipsum non arcu. Vivamus sit amet risus. Donec', 1, '2011-08-05 20:08:35', '2016-06-10 00:26:41', 'cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis', 1, 1, 21),
(69, 'nec metus facilisis lorem tristique aliquet. ', 0, '2005-03-29 18:57:26', '2016-06-10 00:26:41', 'dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus', 1, 2, 6),
(70, 'Donec vitae erat vel pede', 0, '2016-03-06 02:17:38', '2016-06-10 00:26:41', 'sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales.', 0, 0, 12),
(71, 'mauris blandit mattis. Cras eget nisi dictum ', 1, '2004-09-08 15:44:42', '2016-06-10 00:26:41', 'neque. Nullam ut nisi a odio semper cursus. Integer mollis.', 1, 0, 4),
(72, 'vitae purus gravida sagittis. Duis gravida. P', 1, '2013-10-12 22:01:06', '2016-06-10 00:26:41', 'ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit', 1, 0, 31),
(73, 'eu, placerat eget, venenatis a, magna. Lorem ', 0, '2009-04-05 02:37:52', '2016-06-10 00:26:41', 'ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum', 0, 0, 4),
(74, 'interdum enim non nisi. Aenean eget metus. In', 1, '2012-11-07 12:20:55', '2016-06-10 00:26:41', 'a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis', 0, 0, 3),
(75, 'lorem, auctor quis, tristique ac, eleifend vi', 0, '2013-03-05 16:41:18', '2016-06-10 00:26:41', 'enim diam vel arcu. Curabitur ut odio vel est tempor', 0, 0, 4),
(76, 'justo eu arcu. Morbi sit amet massa. Quisque ', 1, '2007-07-05 15:11:26', '2016-06-10 00:26:41', 'dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue,', 0, 0, 33),
(77, 'et, magna. Praesent interdum ligula eu enim. ', 1, '2009-07-28 02:47:23', '2016-06-10 00:26:41', 'malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas,', 0, 0, 41),
(78, 'gravida. Aliquam tincidunt, nunc ac mattis or', 0, '2015-08-31 23:20:16', '2016-06-10 00:26:41', 'sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis', 0, 1, 11),
(79, 'non leo. Vivamus nibh dolor, nonummy ac, feug', 0, '2002-09-09 00:11:57', '2016-06-10 00:26:41', 'id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus', 1, 3, 13),
(80, 'at,', 1, '2002-06-09 01:25:03', '2016-06-10 00:26:41', 'est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed', 0, 0, 40),
(81, 'nulla at sem molestie sodales. Mauris blandit', 0, '2015-05-17 04:18:32', '2016-06-10 00:26:41', 'risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed', 0, 0, 5),
(82, 'blandit congue. In scelerisque scelerisque du', 0, '2004-02-02 04:43:14', '2016-06-10 00:26:41', 'ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor, velit', 0, 0, 38),
(83, 'sit amet diam eu dolor egestas rhoncus. Proin', 0, '2002-05-31 02:40:04', '2016-06-10 00:26:41', 'Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum', 0, 0, 2),
(84, 'ac tellus. Suspendisse sed dolor. Fusce mi lo', 0, '2007-10-05 15:14:35', '2016-06-10 00:26:41', 'fringilla, porttitor vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin', 0, 0, 5),
(85, 'et, magna. Praesent interdum ligula eu enim. ', 1, '2002-03-03 00:16:04', '2016-06-10 00:26:41', 'elementum sem, vitae aliquam eros turpis non enim. Mauris quis', 1, 1, 43),
(86, 'interdum. Nunc sollicitudin commodo ipsum. Su', 0, '2013-09-28 11:23:54', '2016-06-10 00:26:41', 'fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque', 1, 0, 16),
(87, 'eu, odio. Phasellus at augue id', 1, '2012-12-29 07:03:00', '2016-06-10 00:26:41', 'volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean', 1, 0, 45),
(88, 'nec tempus scelerisque, lorem ipsum sodales p', 0, '2002-05-14 13:15:53', '2016-06-10 00:26:41', 'orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec', 1, 0, 34),
(89, 'sed pede. Cum sociis natoque penatibus et mag', 1, '2011-12-24 15:37:03', '2016-06-10 00:26:41', 'feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam nunc,', 0, 0, 11),
(90, 'dictum eu, eleifend nec, malesuada ut, sem. N', 1, '2006-04-21 08:00:28', '2016-06-10 00:26:41', 'malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit.', 1, 0, 24),
(92, 'penatibus et magnis dis parturient montes, na', 1, '2002-12-02 19:52:54', '2016-06-10 00:26:41', 'Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum', 1, 2, 44),
(93, 'a, aliquet vel, vulputate eu, odio. Phasellus', 1, '2002-12-28 12:51:33', '2016-06-10 00:26:41', 'diam. Pellentesque habitant morbi tristique senectus et netus et malesuada', 0, 0, 19),
(94, 'vitae risus. Duis a mi', 0, '2008-05-21 08:17:33', '2016-06-10 00:26:41', 'odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a,', 1, 1, 10),
(95, 'sollicitudin commodo ipsum. Suspendisse non l', 0, '2014-06-21 00:24:55', '2016-06-10 00:26:41', 'amet ornare lectus justo eu arcu. Morbi sit amet massa.', 0, 0, 45);

--
-- Disparadores `sn_posts`
--
DROP TRIGGER IF EXISTS `sn_posts_BEFORE_DELETE`;
DELIMITER $$
CREATE TRIGGER `sn_posts_BEFORE_DELETE` BEFORE DELETE ON `sn_posts`
 FOR EACH ROW BEGIN
	DECLARE exist INT;
	DECLARE count INT;
    DECLARE category_ID INT;
    DECLARE term_ID INT;
    DECLARE cursor_category_ID CURSOR FOR SELECT relationships_category_ID FROM sn_posts_categories WHERE relationships_post_ID = OLD.ID;
    DECLARE cursor_term_ID CURSOR FOR SELECT relationships_term_ID FROM sn_posts_terms WHERE relationships_post_ID = OLD.ID;
    
    SELECT count(*) INTO exist FROM sn_posts_categories WHERE relationships_post_ID = OLD.ID;
    OPEN cursor_category_ID;
		loop_category: LOOP
			IF exist = 0 THEN
				LEAVE loop_category;
            END IF;
			FETCH cursor_category_ID INTO category_ID;
            SELECT category_count INTO count FROM sn_categories WHERE ID = category_ID;
			UPDATE sn_categories SET category_count = count - 1 WHERE ID = category_ID;
            SET exist = exist - 1;
        END LOOP;
    CLOSE cursor_category_ID;
    
    SELECT count(*) INTO exist FROM sn_posts_terms WHERE relationships_post_ID = OLD.ID;
    OPEN cursor_term_ID;
		loop_term: LOOP
			IF exist = 0 THEN
				LEAVE loop_term;
            END IF;
			FETCH cursor_term_ID INTO term_ID;
			SELECT term_count INTO count FROM sn_terms WHERE ID = term_ID;
			UPDATE sn_terms SET term_count = count - 1 WHERE ID = term_ID;
            SET exist = exist - 1;
        END LOOP loop_term;
    CLOSE cursor_term_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_posts_categories`
--

DROP TABLE IF EXISTS `sn_posts_categories`;
CREATE TABLE IF NOT EXISTS `sn_posts_categories` (
  `relationships_post_ID` int(11) NOT NULL,
  `relationships_category_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relaciones entre post y categorias';

--
-- Volcado de datos para la tabla `sn_posts_categories`
--

INSERT INTO `sn_posts_categories` (`relationships_post_ID`, `relationships_category_ID`) VALUES
(12, 7),
(16, 7),
(39, 11),
(29, 15),
(18, 19),
(43, 24),
(7, 25),
(13, 26),
(32, 34),
(25, 35),
(30, 35),
(38, 36),
(31, 46),
(9, 47),
(17, 47);

--
-- Disparadores `sn_posts_categories`
--
DROP TRIGGER IF EXISTS `sn_posts_categories_AFTER_INSERT`;
DELIMITER $$
CREATE TRIGGER `sn_posts_categories_AFTER_INSERT` AFTER INSERT ON `sn_posts_categories`
 FOR EACH ROW BEGIN
	DECLARE count INT;
    SELECT category_count INTO count FROM sn_categories WHERE ID = NEW.relationships_category_ID;
	UPDATE sn_categories SET category_count = count + 1 WHERE ID = NEW.relationships_category_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_posts_terms`
--

DROP TABLE IF EXISTS `sn_posts_terms`;
CREATE TABLE IF NOT EXISTS `sn_posts_terms` (
  `relationships_post_ID` int(11) NOT NULL,
  `relationships_term_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relaciones entre posts y etiquetas';

--
-- Volcado de datos para la tabla `sn_posts_terms`
--

INSERT INTO `sn_posts_terms` (`relationships_post_ID`, `relationships_term_ID`) VALUES
(42, 1),
(14, 7),
(10, 10),
(29, 14),
(23, 19),
(37, 19),
(32, 23),
(36, 23),
(9, 25),
(17, 25),
(41, 26),
(39, 27),
(40, 28),
(19, 33),
(22, 33),
(24, 34),
(33, 34),
(43, 39),
(13, 40),
(25, 41),
(18, 42),
(8, 45),
(21, 46),
(27, 46),
(16, 50);

--
-- Disparadores `sn_posts_terms`
--
DROP TRIGGER IF EXISTS `sn_posts_terms_AFTER_INSERT`;
DELIMITER $$
CREATE TRIGGER `sn_posts_terms_AFTER_INSERT` AFTER INSERT ON `sn_posts_terms`
 FOR EACH ROW BEGIN
	DECLARE count INT;
    SELECT term_count INTO count FROM sn_terms WHERE ID = NEW.relationships_term_ID;
	UPDATE sn_terms SET term_count = count + 1 WHERE ID = NEW.relationships_term_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_sidebars`
--

DROP TABLE IF EXISTS `sn_sidebars`;
CREATE TABLE IF NOT EXISTS `sn_sidebars` (
  `ID` int(11) NOT NULL,
  `sidebar_title` varchar(60) NOT NULL DEFAULT '',
  `sidebar_contents` longtext,
  `sidebar_position` smallint(2) NOT NULL DEFAULT '1' COMMENT 'Establece la posición en la que se mostrara el contenido'
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_sidebars`
--

INSERT INTO `sn_sidebars` (`ID`, `sidebar_title`, `sidebar_contents`, `sidebar_position`) VALUES
(42, 'a3', '', 3),
(43, 'a1', '', 2),
(44, 'a ---', 'a ---', 1),
(45, 'asdasd', 'asdasd', 1100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_terms`
--

DROP TABLE IF EXISTS `sn_terms`;
CREATE TABLE IF NOT EXISTS `sn_terms` (
  `ID` int(11) NOT NULL,
  `term_name` varchar(60) NOT NULL DEFAULT '',
  `term_description` text,
  `term_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_terms`
--

INSERT INTO `sn_terms` (`ID`, `term_name`, `term_description`, `term_count`) VALUES
(1, 'YXO69IHL4MH', 'pede, ultrices a, auctor non, feugiat nec, diam. Duis mi', 1),
(2, 'RLL34IIL1UM', 'natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', 0),
(4, 'UYT93MTW1TQ', 'convallis, ante lectus convallis est, vitae sodales nisi magna sed', 0),
(5, 'LRZ14OVJ7UO', 'dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse', 0),
(6, 'RKE66ICG7TI', 'vel arcu eu odio tristique pharetra. Quisque ac libero nec', 0),
(7, 'MNK92ZAU3RX', 'rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at,', 1),
(10, 'QOJ75BFV6IR', 'metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus', 1),
(14, 'XVQ44XKA9TX', 'a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc', 1),
(16, 'OMT61VXI3WA', 'ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero', 0),
(17, 'LUV08SAL9ER', 'dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus', 0),
(18, 'QXD05VZY3KK', 'orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique', 0),
(19, 'SLO99EUG6YX', 'et risus. Quisque libero lacus, varius et, euismod et, commodo', 2),
(23, 'QDZ75TPL9YN', 'sit amet ornare lectus justo eu arcu. Morbi sit amet', 2),
(24, 'RMF35SMI4OK', 'Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie', 0),
(25, 'UYC08KHL2YD', 'sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices', 2),
(26, 'YWB93EAY0DU', 'et magnis dis parturient montes, nascetur ridiculus mus. Proin vel', 1),
(27, 'OCD09XWC1NP', 'libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus', 1),
(28, 'ONF06IZX8OB', 'risus, at fringilla purus mauris a nunc. In at pede.', 1),
(29, 'WVF06ZJW2TI', 'scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit', 0),
(30, 'NJJ41UOU9SS', 'Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non,', 0),
(31, 'PKF10WST4KR', 'egestas nunc sed libero. Proin sed turpis nec mauris blandit', 0),
(33, 'UXE84KKE7AV', 'et nunc. Quisque ornare tortor at risus. Nunc ac sem', 2),
(34, 'XXR91UEY9HH', 'lacinia orci, consectetuer euismod est arcu ac orci. Ut semper', 2),
(35, 'WQB41IBY4FX', 'condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing', 0),
(39, 'NQV08VOH8TX', 'euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas', 1),
(40, 'ZDB95KQN2KR', 'ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris', 1),
(41, 'OTA99KIR5KC', 'posuere vulputate, lacus. Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse', 1),
(42, 'OFO72CUO6PM', 'ac nulla. In tincidunt congue turpis. In condimentum. Donec at', 1),
(45, 'PPR24GMV1SO', 'amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus', 1),
(46, 'SKT73TTK2RE', 'elit, a feugiat tellus lorem eu metus. In lorem. Donec', 2),
(48, 'SRS31ZYP0ZW', 'orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna', 3),
(49, 'TFA13TOM0KO', 'sem, vitae aliquam eros turpis non enim. Mauris quis turpis', 2),
(50, 'SFZ25DNB1YR', 'neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sn_users`
--

DROP TABLE IF EXISTS `sn_users`;
CREATE TABLE IF NOT EXISTS `sn_users` (
  `ID` int(11) NOT NULL,
  `user_login` varchar(60) NOT NULL COMMENT 'nombre usado para acceder',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT 'Nombre del usuario',
  `user_email` varchar(100) NOT NULL COMMENT 'Correo electronico',
  `user_pass` varchar(64) NOT NULL COMMENT 'Contraseña',
  `user_rol` int(11) NOT NULL DEFAULT '0' COMMENT 'Determinara el rango del usuario y los privilegios dentro del panel de administración. Por defecto 0: Tiene solo acceso para editar su información.',
  `user_registred` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `user_url` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sn_users`
--

INSERT INTO `sn_users` (`ID`, `user_login`, `user_name`, `user_email`, `user_pass`, `user_rol`, `user_registred`, `user_url`) VALUES
(1, 'admin', 'admin', 'admin@localhost', 'bedb4f9ee8eebc062920f6a7c565ceb687e1bca97282e2ce6407aed35b4ae0dc', 3, '2016-06-10 00:24:12', NULL),
(2, 'Deirdre', 'Stephen', 'In@elitEtiam.edu', 'TWZ12FJA3NV', 3, '2014-12-03 06:00:54', 'http://Deirdre.com'),
(3, 'Blair', 'Patrick', 'aliquam@eleifendCras.edu', 'YYD60QDO6SG', 3, '2007-12-17 12:48:41', 'http://Blair.com'),
(4, 'Tashya', 'Gisela', 'aliquam.enim@feugiatnonlobortis.edu', 'HZW89MJE3JH', 3, '2012-07-08 01:22:43', 'http://Tashya.com'),
(5, 'Honorato', 'Addison', 'a@turpisnon.ca', 'XZR11ARJ8HD', 0, '2011-06-02 05:47:43', 'http://Honorato.com'),
(6, 'Fletcher', 'Chancellor', 'arcu@ac.edu', 'BSV58VFW8XQ', 3, '2001-01-12 19:20:53', 'http://Fletcher.com'),
(7, 'Germane', 'Victor', 'Donec@Aliquamnisl.net', 'YBW84SGR2JN', 0, '2013-09-03 00:50:06', 'http://Germane.com'),
(8, 'Lance', 'Stella', 'laoreet.lectus.quis@rutrumeu.org', 'QPL09WKY3UT', 3, '2002-10-31 04:13:37', 'http://Lance.com'),
(10, 'Buffy', 'Leah', 'et@velitdui.co.uk', 'GXA61PSO0AR', 3, '2014-06-30 12:21:05', 'http://Buffy.com'),
(11, 'Cassidy', 'Arthur', 'leo@Maurismagna.org', 'XBB53HFN9WX', 0, '2002-05-28 23:47:31', 'http://Cassidy.com'),
(12, 'Brandon', 'Indigo', 'hendrerit@iaculisaliquetdiam.ca', 'DZE00SIZ9FK', 3, '2012-10-16 04:14:12', 'http://Brandon.com'),
(13, 'Jaime', 'Ronan', 'lectus.Cum@maurisanunc.co.uk', 'YSY92ZOX0HK', 3, '2014-07-09 08:30:35', 'http://Jaime.com'),
(14, 'Dawn', 'Guy', 'Nunc@tinciduntnibhPhasellus.org', 'VYE59EKR4IS', 1, '2015-05-11 01:42:21', 'http://Dawn.com'),
(15, 'Martha', 'Geoffrey', 'Curabitur.sed@ornare.co.uk', 'OBK01YXO5RN', 3, '2002-10-31 08:33:59', 'http://Martha.com'),
(16, 'Avye', 'Camilla', 'Phasellus.ornare@lacinia.ca', 'OIZ19NYD7FX', 2, '2013-10-12 02:36:03', 'http://Avye.com'),
(17, 'Harlan', 'Kyle', 'neque.non.quam@nonfeugiat.com', 'UAQ08GTH3BT', 1, '2001-07-29 18:30:14', 'http://Harlan.com'),
(18, 'Caryn', 'Desiree', 'elit.Etiam@bibendumsedest.org', 'AVC52YQD8XM', 3, '2001-12-08 13:28:03', 'http://Caryn.com'),
(19, 'Maxwell', 'Jamal', 'et.magna.Praesent@volutpat.ca', 'FTT40MHI4VH', 1, '2010-07-15 21:30:41', 'http://Maxwell.com'),
(20, 'Vladimir', 'Ora', 'varius.et@nibh.net', 'GMZ24IOO4UR', 3, '2012-08-17 20:32:39', 'http://Vladimir.com'),
(21, 'MacKensie', 'India', 'Suspendisse@QuisquevariusNam.ca', 'EKI70WLI2DT', 2, '2015-03-31 05:12:03', 'http://MacKensie.com'),
(22, 'Rhoda', 'Jael', 'non.sollicitudin.a@vellectusCum.co.uk', 'KLZ99MKI0HE', 2, '2008-10-11 13:22:43', 'http://Rhoda.com'),
(23, 'Drake', 'Tobias', 'nunc.ullamcorper@Donec.com', 'QIS07BKF9ZM', 1, '2002-03-26 18:59:55', 'http://Drake.com'),
(24, 'Tana', 'Stella', 'egestas.lacinia.Sed@quisturpis.co.uk', 'AZH40LEW1DT', 3, '2001-03-24 03:35:37', 'http://Tana.com'),
(25, 'Allen', 'Colleen', 'luctus@lectusCum.ca', 'EBN25MFZ3KK', 2, '2011-08-04 20:24:56', 'http://Allen.com'),
(26, 'Aimee', 'Brendan', 'scelerisque.sed@idblanditat.edu', 'TRR17WSE5TA', 2, '2014-03-21 05:45:23', 'http://Aimee.com'),
(27, 'Phyllis', 'Ross', 'hymenaeos@Cumsociis.edu', 'TKL55ZWI2GQ', 3, '2003-04-07 10:53:39', 'http://Phyllis.com'),
(30, 'Jamal', 'Blossom', 'Cum@enimEtiam.org', 'WDJ37ZYL3AM', 1, '2009-04-11 22:47:48', 'http://Jamal.com'),
(31, 'Julie', 'Abbot', 'elit.a@aliquameuaccumsan.ca', 'ZCH87MRI2UH', 1, '2009-09-01 09:51:45', 'http://Julie.com'),
(32, 'Sacha', 'Herrod', 'ligula.eu@Sed.ca', 'ORW40TTI7ZI', 0, '2015-12-30 19:04:18', 'http://Sacha.com'),
(33, 'Jena', 'Hayley', 'pharetra.nibh@tempordiamdictum.org', 'KFG35HJZ7ZM', 1, '2015-01-29 01:16:08', 'http://Jena.com'),
(34, 'Kelly', 'Belle', 'amet.orci.Ut@Nunc.ca', 'KLR44GTC8YG', 0, '2004-11-23 01:12:11', 'http://Kelly.com'),
(35, 'Abigail', 'Tanek', 'malesuada.vel@mauris.com', 'GCH46CIK4VB', 0, '2011-08-21 08:30:36', 'http://Abigail.com'),
(36, 'Ross', 'Noah', 'tellus@luctusaliquet.org', 'QHL50AXX7JI', 2, '2006-10-22 19:22:56', 'http://Ross.com'),
(37, 'Quyn', 'Evan', 'erat@suscipit.edu', 'VNC70GWT5RE', 0, '2012-07-10 01:25:15', 'http://Quyn.com'),
(38, 'Lareina', 'Thor', 'In@tortorIntegeraliquam.net', 'UZM40URM6FU', 0, '2004-04-22 12:52:45', 'http://Lareina.com'),
(39, 'Elijah', 'Shaeleigh', 'ante.blandit@magnisdis.co.uk', 'XXG19YEM8JO', 3, '2015-03-16 01:23:32', 'http://Elijah.com'),
(40, 'Althea', 'Owen', 'vulputate.ullamcorper@at.edu', 'VRC85URL0PJ', 1, '2002-04-08 02:55:09', 'http://Althea.com'),
(41, 'Chester', 'Drew', 'id.sapien@semNullainterdum.ca', 'XIG96RZB3JV', 3, '2014-02-01 03:50:40', 'http://Chester.com'),
(42, 'Lani', 'Dara', 'dignissim@molestieintempus.ca', 'ACA38PTC8NB', 3, '2002-07-19 13:45:34', 'http://Lani.com'),
(43, 'Dexter', 'Giselle', 'hymenaeos.Mauris.ut@Phasellus.com', 'TBS39RDH0SF', 3, '2011-08-21 22:11:44', 'http://Dexter.com'),
(44, 'Madonna', 'Hilel', 'velit.Pellentesque.ultricies@suscipitnonummyFusce.net', 'INO35TBX5XQ', 0, '2009-05-22 08:38:04', 'http://Madonna.com'),
(45, 'Vera', 'Barry', 'nisi@auctorquis.edu', 'MSE09XMQ8RN', 0, '2005-06-22 13:32:58', 'http://Vera.com'),
(46, 'Rana', 'Aiko', 'at@Morbi.ca', 'OOG11UMD6GL', 3, '2012-10-08 00:35:19', 'http://Rana.com'),
(47, 'dana', 'Gretchen', 'odio@dolorquam.org', '2530a24f31ded117d283698e549299e996f4a554f09f099e5f6a82d0aebcc2e6', 0, '2003-02-21 22:08:32', 'http://Dana.com'),
(49, 'nicolas', 'Nicolás Marulanda P.', 'nicolas@n.com', 'e6e520d442f871214941035628a45a0781bfbcddd11caff52212c1c2a02b9695', 0, '2016-06-12 23:30:54', 'n.com');

--
-- Disparadores `sn_users`
--
DROP TRIGGER IF EXISTS `sn_users_BEFORE_DELETE`;
DELIMITER $$
CREATE TRIGGER `sn_users_BEFORE_DELETE` BEFORE DELETE ON `sn_users`
 FOR EACH ROW BEGIN
	DECLARE exist INT;
    DECLARE post_ID INT;
	DECLARE cursor_posts_ID CURSOR FOR SELECT ID FROM sn_posts WHERE user_ID = OLD.ID;

	SELECT count(*) INTO exist FROM sn_posts WHERE user_ID = OLD.ID;
    OPEN cursor_posts_ID;
		loop_posts: LOOP
			IF exist = 0 THEN
				LEAVE loop_posts;
            END IF;
			FETCH cursor_posts_ID INTO post_ID;
            DELETE FROM sn_posts WHERE ID = post_ID;
            SET exist = exist - 1;
        END LOOP;
    CLOSE cursor_posts_ID;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sn_categories`
--
ALTER TABLE `sn_categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `category_name_UNIQUE` (`category_name`);

--
-- Indices de la tabla `sn_comments`
--
ALTER TABLE `sn_comments`
  ADD PRIMARY KEY (`ID`,`post_ID`),
  ADD KEY `fk_comments_post_id` (`post_ID`);

--
-- Indices de la tabla `sn_menus`
--
ALTER TABLE `sn_menus`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `menu_name_UNIQUE` (`menu_name`);

--
-- Indices de la tabla `sn_options`
--
ALTER TABLE `sn_options`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `option_name_UNIQUE` (`option_name`);

--
-- Indices de la tabla `sn_posts`
--
ALTER TABLE `sn_posts`
  ADD PRIMARY KEY (`ID`,`user_ID`),
  ADD KEY `fk_post_user_id` (`user_ID`);

--
-- Indices de la tabla `sn_posts_categories`
--
ALTER TABLE `sn_posts_categories`
  ADD PRIMARY KEY (`relationships_post_ID`,`relationships_category_ID`),
  ADD KEY `fk_posts_categories_id` (`relationships_category_ID`),
  ADD KEY `fk_categories_posts_id` (`relationships_post_ID`);

--
-- Indices de la tabla `sn_posts_terms`
--
ALTER TABLE `sn_posts_terms`
  ADD PRIMARY KEY (`relationships_post_ID`,`relationships_term_ID`),
  ADD KEY `fk_posts_terms_id` (`relationships_term_ID`),
  ADD KEY `fk_terms_posts_id` (`relationships_post_ID`);

--
-- Indices de la tabla `sn_sidebars`
--
ALTER TABLE `sn_sidebars`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `sn_terms`
--
ALTER TABLE `sn_terms`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `term_name_UNIQUE` (`term_name`);

--
-- Indices de la tabla `sn_users`
--
ALTER TABLE `sn_users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `user_email_UNIQUE` (`user_email`),
  ADD UNIQUE KEY `user_login_UNIQUE` (`user_login`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sn_categories`
--
ALTER TABLE `sn_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT de la tabla `sn_comments`
--
ALTER TABLE `sn_comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `sn_menus`
--
ALTER TABLE `sn_menus`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT de la tabla `sn_options`
--
ALTER TABLE `sn_options`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `sn_posts`
--
ALTER TABLE `sn_posts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT de la tabla `sn_sidebars`
--
ALTER TABLE `sn_sidebars`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT de la tabla `sn_terms`
--
ALTER TABLE `sn_terms`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT de la tabla `sn_users`
--
ALTER TABLE `sn_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sn_comments`
--
ALTER TABLE `sn_comments`
  ADD CONSTRAINT `fk_comments_post_id` FOREIGN KEY (`post_ID`) REFERENCES `sn_posts` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sn_posts`
--
ALTER TABLE `sn_posts`
  ADD CONSTRAINT `fk_post_user_id` FOREIGN KEY (`user_ID`) REFERENCES `sn_users` (`ID`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sn_posts_categories`
--
ALTER TABLE `sn_posts_categories`
  ADD CONSTRAINT `fk_categories_post_id` FOREIGN KEY (`relationships_post_ID`) REFERENCES `sn_posts` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_categories_id` FOREIGN KEY (`relationships_category_ID`) REFERENCES `sn_categories` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sn_posts_terms`
--
ALTER TABLE `sn_posts_terms`
  ADD CONSTRAINT `fk_post_terms_id` FOREIGN KEY (`relationships_term_ID`) REFERENCES `sn_terms` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_terms_post_id` FOREIGN KEY (`relationships_post_ID`) REFERENCES `sn_posts` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
