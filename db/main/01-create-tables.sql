/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(512) NOT NULL,
  `verified` tinyint unsigned NOT NULL DEFAULT '0',
  `active` tinyint unsigned NOT NULL DEFAULT '0',
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `phone_number` varchar(150) DEFAULT NULL,
  `mobile_number` varchar(150) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `deleted` tinyint unsigned NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created_by` int unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `table_01`;
CREATE TABLE IF NOT EXISTS `table_01` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `deleted` tinyint unsigned NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created_by` int unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `table_02`;
CREATE TABLE IF NOT EXISTS `table_02` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `table_01_id` varchar(200) NOT NULL,
  `text` varchar(255) NOT NULL,
  `deleted` tinyint unsigned NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created_by` int unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `table_03`;
CREATE TABLE IF NOT EXISTS `table_03` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `table_01_id` varchar(200) NOT NULL,
  `table_02_id` varchar(200) NOT NULL,
  `text` varchar(255) NOT NULL,
  `deleted` tinyint unsigned NOT NULL DEFAULT '0',
  `deleted_date` datetime DEFAULT NULL,
  `created_by` int unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
