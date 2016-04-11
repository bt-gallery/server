SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `photo1945` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `photo1945`;

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `contribution`;
CREATE TABLE IF NOT EXISTS `contribution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_participant` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `store_path` varchar(255) DEFAULT NULL,
  `web_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `moderation` int(11) DEFAULT NULL,
  `rejection` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_participant` (`id_participant`),
  KEY `moderation` (`moderation`),
  KEY `rejection` (`rejection`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `declarant`;
CREATE TABLE IF NOT EXISTS `declarant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `patronymic` varchar(255) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `moderation` int(11) DEFAULT NULL,
  `rejection` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rejection` (`rejection`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `moderation_status`;
CREATE TABLE IF NOT EXISTS `moderation_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_declarant` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `patronymic` varchar(255) DEFAULT NULL,
  `description` text,
  `specification` int(11) DEFAULT NULL,
  `moderation` int(11) DEFAULT NULL,
  `rejection` int(11) DEFAULT NULL,
  `team` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_declarant` (`id_declarant`),
  KEY `rejection` (`rejection`),
  KEY `moderation` (`moderation`),
  KEY `specification` (`specification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `rejection`;
CREATE TABLE IF NOT EXISTS `rejection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `description` text,
  `correction_message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `specification`;
CREATE TABLE IF NOT EXISTS `specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `age` int(3) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(45) DEFAULT NULL,
  `agent` varchar(45) DEFAULT NULL,
  `id_contribution` int(11) DEFAULT NULL,
  `hash` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `id_contribution` (`id_contribution`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `contribution`
  ADD CONSTRAINT `contribution_ibfk_1` FOREIGN KEY (`id_participant`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `contribution_ibfk_2` FOREIGN KEY (`moderation`) REFERENCES `moderation_status` (`id`),
  ADD CONSTRAINT `contribution_ibfk_3` FOREIGN KEY (`rejection`) REFERENCES `rejection` (`id`);

ALTER TABLE `declarant`
  ADD CONSTRAINT `declarant_ibfk_1` FOREIGN KEY (`rejection`) REFERENCES `rejection` (`id`);

ALTER TABLE `participant`
  ADD CONSTRAINT `participant_ibfk_4` FOREIGN KEY (`rejection`) REFERENCES `rejection` (`id`),
  ADD CONSTRAINT `participant_ibfk_1` FOREIGN KEY (`id_declarant`) REFERENCES `declarant` (`id`),
  ADD CONSTRAINT `participant_ibfk_2` FOREIGN KEY (`specification`) REFERENCES `specification` (`id`),
  ADD CONSTRAINT `participant_ibfk_3` FOREIGN KEY (`moderation`) REFERENCES `moderation_status` (`id`);

ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`id_contribution`) REFERENCES `contribution` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
