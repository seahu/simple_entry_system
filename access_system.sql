-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 01, 2019 at 03:20 PM
-- Server version: 5.5.62-0+deb8u1
-- PHP Version: 5.6.40-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `access_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE IF NOT EXISTS `card` (
`id` int(11) NOT NULL,
  `Pk` varchar(15) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL COMMENT 'Identifikator karty (uzivatele) pouzivany externim systemem',
  `card_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL COMMENT 'Kod karty',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL COMMENT 'Prijmeni + jmeno uzivatele karty',
  `access` tinyint(4) NOT NULL COMMENT '1-povolen pristup pro uzivatele s danou kartou, 0-zakazany pristup',
  `status` tinyint(4) NOT NULL COMMENT '1-uzivatel s danou kartou je v objektu pritomen, 0-nepritomen',
  `type_status` tinyint(4) NOT NULL COMMENT 'odkaz do cisellniku status_type - dodatecna informace k pruchodu, napr. odchod k lekari,..',
  `last_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Cas posledni aktivity uzivatele s dotycnou kartou'
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`id`, `Pk`, `card_code`, `name`, `access`, `status`, `type_status`, `last_change`) VALUES
(6, '', '15154654', 'Dolezal', 1, 1, 0, '2019-02-22 23:27:58'),
(7, '', '46546523', 'Petr novotny', 0, 1, 0, '0000-00-00 00:00:00'),
(8, '', '112331', 'Ales Brychta', 0, 1, 0, '0000-00-00 00:00:00'),
(10, '', '465465', 'pvel', 0, 1, 0, '2019-02-08 08:10:18'),
(11, '', 'fsdfsdf', 'beny', 0, 1, 0, '2019-01-24 22:22:19'),
(12, '', '16546465', 'Jarda', 0, 1, 0, '2019-02-22 22:10:49'),
(13, '', '', 'Živný Filip', 0, 0, 0, '2019-02-01 10:53:56'),
(14, '1187', '0f0448887d', 'Ambrožová Renata', 0, 0, 0, '2019-02-01 19:08:24'),
(18, '1197', '028ce08da6', 'Hlásenský Marek', 1, 0, 0, '2019-02-07 23:37:58'),
(19, '', '0260C3E5B9', 'Ondrej Lycka', 1, 0, 0, '2019-02-28 17:11:49'),
(20, '1215', '028993e077', 'Borek Daniel', 1, 1, 0, '2019-02-28 13:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `card_reader`
--

CREATE TABLE IF NOT EXISTS `card_reader` (
`id` int(11) NOT NULL,
  `comment` varchar(150) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `address` varchar(150) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL COMMENT 'adresa ctecky muze byt i lokalni, napr. /mnt/1-wite/25-454646',
  `direction` tinyint(4) NOT NULL COMMENT '1-prichod, 0-odchod'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `card_reader`
--

INSERT INTO `card_reader` (`id`, `comment`, `address`, `direction`) VALUES
(4, 'doma', '4564646', 1),
(6, 'dfdsfsdewrw', 'b5a6ffdd', 1),
(8, 'dvere2', '4564646', 0),
(9, 'ruční příchod', 'ruční příchod', 1),
(10, 'ruční odchod', 'ruční odchod', 0),
(11, 'Pokusna ctecka1', '2917e7d6', 1),
(12, 'Pokusna ctecka2', '3e063836', 0);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
`id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `value` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`, `description`) VALUES
(1, 'EDOOKIT_URL', 'https://spss.edookit.net', 'EDOOKIT URL adresa edookitu pro žáky a rodiče (včetně protokolu http:// nebo https://)'),
(2, 'EDOOKITschoolSign', 'apiuser1', 'EDOOKIT Přihlašovací jméno (API přístupové údaje školy)'),
(3, 'EDOOKITschoolPass', 'limp7irawl61knh9soxaucbg8cbjp595fhfuc4qd', 'EDOOKIT Heslo (API přístupové údaje školy)'),
(4, 'HandEnterReaderID', '9', 'ID čtečky karet používané pro ruční zápis příchodu (např. při strátě karty)'),
(5, 'HandExitReaderID', '10', 'ID čtečky karet používané pro ruční zápis odchodu (např. při strátě karty)');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
`id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `name`) VALUES
(28, 'DV2'),
(29, 'DV3'),
(30, 'DV4'),
(31, 'G1'),
(32, 'G2'),
(33, 'G3'),
(34, 'G4'),
(35, 'R1'),
(36, 'R2'),
(37, 'R3'),
(38, 'R4'),
(39, 'S1A'),
(40, 'S1B'),
(41, 'S2A'),
(42, 'S2B'),
(43, 'S3A'),
(44, 'S3B'),
(46, 'S4B'),
(49, 'S4A'),
(52, 'DV1');

-- --------------------------------------------------------

--
-- Table structure for table `group_list`
--

CREATE TABLE IF NOT EXISTS `group_list` (
`id` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_card` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_list`
--

INSERT INTO `group_list` (`id`, `id_group`, `id_card`) VALUES
(22, 49, 14),
(25, 49, 18),
(26, 46, 20);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
`id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `card_reader_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sync` tinyint(1) NOT NULL COMMENT '1-zaznam je jiz zesynchronizovan s externim systemem, 0- \\zazna ceka na synchronizaci'
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `card_id`, `card_reader_id`, `timestamp`, `sync`) VALUES
(2, 8, 4, '2016-06-14 13:56:21', 0),
(6, 14, 4, '2019-02-05 17:19:36', 1),
(7, 13, 4, '2019-02-02 22:38:15', 0),
(8, 12, 4, '2019-02-04 23:52:27', 0),
(9, 12, 4, '2019-02-04 23:53:43', 0),
(10, 12, 4, '2019-02-05 00:00:27', 0),
(11, 12, 4, '2019-02-05 00:10:29', 0),
(12, 12, 4, '2019-02-05 00:13:04', 0),
(13, 12, 10, '2019-02-05 00:16:47', 0),
(14, 7, 9, '2019-02-05 00:18:45', 0),
(15, 6, 9, '2019-02-05 11:25:53', 0),
(16, 10, 9, '2019-02-08 08:10:18', 0),
(17, 6, 4, '2019-02-22 22:01:02', 0),
(18, 6, 4, '2019-02-22 22:02:22', 0),
(19, 12, 9, '2019-02-22 22:10:49', 0),
(20, 6, 4, '2019-02-22 23:28:03', 0),
(21, 6, 4, '2019-02-23 08:48:44', 0),
(22, 6, 4, '2019-02-23 08:52:05', 0),
(23, 6, 4, '2019-02-23 08:52:53', 0),
(24, 6, 4, '2019-02-23 08:52:54', 0),
(25, 6, 4, '2019-02-23 08:52:55', 0),
(26, 6, 4, '2019-02-23 08:52:57', 0),
(27, 6, 4, '2019-02-23 08:52:58', 0),
(28, 6, 4, '2019-02-23 08:52:59', 0),
(29, 6, 4, '2019-02-23 08:53:50', 0),
(30, 8, 9, '2019-02-25 09:27:45', 0),
(31, 19, 11, '2019-02-25 14:21:24', 0),
(32, 19, 11, '2019-02-25 14:21:29', 0),
(33, 19, 11, '2019-02-25 14:28:12', 0),
(34, 19, 11, '2019-02-25 14:28:24', 0),
(35, 19, 11, '2019-02-25 14:28:31', 0),
(36, 19, 11, '2019-02-25 14:28:35', 0),
(37, 19, 11, '2019-02-25 14:28:39', 0),
(38, 19, 11, '2019-02-25 14:28:44', 0),
(39, 19, 11, '2019-02-25 14:28:45', 0),
(40, 19, 11, '2019-02-25 14:28:46', 0),
(41, 19, 11, '2019-02-25 14:28:48', 0),
(42, 19, 11, '2019-02-25 14:28:49', 0),
(43, 19, 11, '2019-02-25 14:28:50', 0),
(44, 19, 11, '2019-02-25 14:28:52', 0),
(45, 19, 11, '2019-02-25 14:28:53', 0),
(46, 19, 11, '2019-02-25 14:30:03', 0),
(47, 19, 11, '2019-02-25 14:30:27', 0),
(48, 19, 11, '2019-02-25 14:30:32', 0),
(49, 19, 11, '2019-02-25 14:30:40', 0),
(50, 19, 11, '2019-02-25 15:06:24', 0),
(51, 19, 11, '2019-02-25 15:08:52', 0),
(52, 19, 11, '2019-02-25 15:09:00', 0),
(53, 19, 11, '2019-02-25 15:09:07', 0),
(54, 19, 11, '2019-02-25 15:09:13', 0),
(55, 19, 11, '2019-02-25 15:09:48', 0),
(56, 19, 11, '2019-02-25 15:10:00', 0),
(57, 19, 11, '2019-02-25 15:10:16', 0),
(58, 19, 11, '2019-02-25 15:12:45', 0),
(59, 19, 11, '2019-02-25 15:13:01', 0),
(60, 19, 11, '2019-02-25 15:13:08', 0),
(61, 19, 11, '2019-02-25 15:13:49', 0),
(62, 19, 11, '2019-02-25 15:14:50', 0),
(63, 19, 11, '2019-02-25 15:15:07', 0),
(64, 19, 11, '2019-02-25 15:15:21', 0),
(65, 19, 11, '2019-02-25 15:17:09', 0),
(66, 19, 11, '2019-02-25 15:17:21', 0),
(67, 19, 11, '2019-02-25 15:28:32', 0),
(68, 19, 11, '2019-02-25 16:14:41', 0),
(69, 19, 11, '2019-02-25 16:14:47', 0),
(70, 19, 11, '2019-02-25 16:15:03', 0),
(71, 19, 11, '2019-02-25 16:15:08', 0),
(72, 19, 11, '2019-02-26 07:21:02', 0),
(73, 19, 9, '2019-02-26 07:21:34', 0),
(74, 19, 11, '2019-02-26 08:53:23', 0),
(75, 19, 12, '2019-02-26 12:14:24', 0),
(76, 19, 11, '2019-02-26 12:14:29', 0),
(77, 19, 12, '2019-02-26 12:14:33', 0),
(78, 19, 12, '2019-02-26 12:15:18', 0),
(79, 19, 11, '2019-02-26 12:15:23', 0),
(80, 19, 11, '2019-02-26 12:16:41', 0),
(81, 19, 12, '2019-02-26 12:16:45', 0),
(82, 19, 12, '2019-02-26 13:56:44', 0),
(83, 19, 12, '2019-02-26 13:56:51', 0),
(84, 19, 12, '2019-02-26 13:56:56', 0),
(85, 19, 11, '2019-02-26 13:56:57', 0),
(86, 19, 12, '2019-02-26 13:56:59', 0),
(87, 19, 11, '2019-02-26 13:57:00', 0),
(88, 19, 12, '2019-02-26 13:57:01', 0),
(89, 19, 11, '2019-02-26 13:57:02', 0),
(90, 19, 12, '2019-02-26 13:57:03', 0),
(91, 19, 11, '2019-02-26 13:57:05', 0),
(92, 19, 12, '2019-02-26 13:57:05', 0),
(93, 19, 11, '2019-02-26 13:57:06', 0),
(94, 19, 12, '2019-02-26 13:57:07', 0),
(95, 19, 11, '2019-02-26 13:57:08', 0),
(96, 19, 12, '2019-02-26 13:57:09', 0),
(97, 19, 11, '2019-02-26 13:57:10', 0),
(98, 19, 12, '2019-02-26 13:57:11', 0),
(99, 19, 11, '2019-02-26 13:57:12', 0),
(100, 19, 12, '2019-02-26 13:57:12', 0),
(101, 19, 11, '2019-02-26 13:57:17', 0),
(102, 19, 12, '2019-02-26 13:57:18', 0),
(103, 19, 11, '2019-02-26 13:57:18', 0),
(104, 19, 12, '2019-02-26 13:57:19', 0),
(105, 19, 11, '2019-02-26 13:57:20', 0),
(106, 19, 12, '2019-02-26 13:57:21', 0),
(107, 19, 11, '2019-02-26 13:57:22', 0),
(108, 19, 12, '2019-02-26 13:57:22', 0),
(109, 19, 11, '2019-02-26 13:57:23', 0),
(110, 19, 12, '2019-02-26 13:57:24', 0),
(111, 19, 11, '2019-02-26 13:57:25', 0),
(112, 19, 12, '2019-02-26 13:57:26', 0),
(113, 19, 11, '2019-02-26 13:57:27', 0),
(114, 19, 11, '2019-02-26 13:57:27', 0),
(115, 19, 11, '2019-02-26 13:57:29', 0),
(116, 19, 12, '2019-02-26 13:57:30', 0),
(117, 19, 11, '2019-02-26 13:57:32', 0),
(118, 19, 12, '2019-02-26 13:57:33', 0),
(119, 19, 11, '2019-02-26 13:57:34', 0),
(120, 19, 12, '2019-02-26 13:57:35', 0),
(121, 19, 11, '2019-02-26 13:57:35', 0),
(122, 19, 12, '2019-02-26 13:57:36', 0),
(123, 19, 11, '2019-02-26 13:57:37', 0),
(124, 19, 12, '2019-02-26 13:57:38', 0),
(125, 19, 11, '2019-02-26 13:57:39', 0),
(126, 19, 12, '2019-02-26 13:57:40', 0),
(127, 19, 11, '2019-02-26 13:57:40', 0),
(128, 19, 12, '2019-02-26 13:57:41', 0),
(129, 19, 11, '2019-02-26 13:57:42', 0),
(130, 19, 12, '2019-02-26 13:57:43', 0),
(131, 19, 11, '2019-02-26 13:57:43', 0),
(132, 19, 12, '2019-02-26 13:57:44', 0),
(133, 19, 11, '2019-02-26 16:04:45', 0),
(134, 19, 12, '2019-02-26 16:04:52', 0),
(135, 19, 12, '2019-02-26 16:06:01', 0),
(136, 19, 12, '2019-02-26 16:06:23', 0),
(137, 19, 12, '2019-02-26 16:06:29', 0),
(138, 19, 12, '2019-02-26 16:06:35', 0),
(139, 19, 12, '2019-02-26 16:06:43', 0),
(140, 19, 12, '2019-02-26 16:06:47', 0),
(141, 19, 12, '2019-02-26 16:06:52', 0),
(142, 19, 12, '2019-02-26 16:07:51', 0),
(143, 19, 12, '2019-02-26 16:07:52', 0),
(144, 19, 12, '2019-02-26 16:07:54', 0),
(145, 19, 12, '2019-02-26 16:07:55', 0),
(146, 19, 12, '2019-02-26 16:07:56', 0),
(147, 19, 12, '2019-02-26 16:07:58', 0),
(148, 19, 12, '2019-02-26 16:07:59', 0),
(149, 19, 12, '2019-02-26 16:08:00', 0),
(150, 19, 12, '2019-02-26 16:08:05', 0),
(151, 19, 12, '2019-02-27 08:16:41', 0),
(152, 19, 12, '2019-02-27 08:16:43', 0),
(153, 19, 12, '2019-02-27 08:16:44', 0),
(154, 20, 9, '2019-02-28 13:29:13', 1),
(155, 19, 11, '2019-02-28 15:47:52', 0),
(156, 19, 12, '2019-02-28 16:37:09', 0),
(157, 19, 12, '2019-02-28 16:48:18', 0),
(158, 19, 12, '2019-02-28 16:48:26', 0),
(159, 19, 11, '2019-02-28 16:57:28', 0),
(160, 19, 12, '2019-02-28 17:11:49', 0),
(161, 19, 12, '2019-02-28 17:13:51', 0),
(162, 19, 12, '2019-03-01 14:12:58', 0),
(163, 19, 12, '2019-03-01 14:19:03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'pokus', 'pokusny text', '2016-04-11 09:27:42'),
(2, 'pokus2', 'pokusny text2', '2016-04-11 09:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `status_type`
--

CREATE TABLE IF NOT EXISTS `status_type` (
`id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card`
--
ALTER TABLE `card`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `card_reader`
--
ALTER TABLE `card_reader`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_list`
--
ALTER TABLE `group_list`
 ADD PRIMARY KEY (`id`), ADD KEY `id_group` (`id_group`), ADD KEY `id_card` (`id_card`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
 ADD PRIMARY KEY (`id`), ADD KEY `card_id` (`card_id`), ADD KEY `card_reader_id` (`card_reader_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_type`
--
ALTER TABLE `status_type`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card`
--
ALTER TABLE `card`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `card_reader`
--
ALTER TABLE `card_reader`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `group_list`
--
ALTER TABLE `group_list`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=164;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `status_type`
--
ALTER TABLE `status_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_list`
--
ALTER TABLE `group_list`
ADD CONSTRAINT `group_list_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `group` (`id`),
ADD CONSTRAINT `group_list_ibfk_2` FOREIGN KEY (`id_card`) REFERENCES `card` (`id`);

--
-- Constraints for table `log`
--
ALTER TABLE `log`
ADD CONSTRAINT `log_ibfk_3` FOREIGN KEY (`card_id`) REFERENCES `card` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `log_ibfk_4` FOREIGN KEY (`card_reader_id`) REFERENCES `card_reader` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
