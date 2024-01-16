# ************************************************************
# Sequel Ace SQL dump
# Version 20059
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 11.1.2-MariaDB-1:11.1.2+maria~ubu2204)
# Database: perpustakaan
# Generation Time: 2024-01-15 03:59:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table authors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `authors`;

CREATE TABLE `authors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone_number` char(14) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;

INSERT INTO `authors` (`id`, `name`, `email`, `phone_number`, `address`, `created_at`, `updated_at`)
VALUES
	(2,'Tere Liye','tereliye@gmail.com','0823289989','-','2024-01-13 14:34:42','2024-01-13 14:34:42'),
	(3,'Boy Candra','boycandra@email.com','082328342','-','2024-01-15 01:45:46','2024-01-15 01:45:46'),
	(4,'Raditya Dika','radit@email.com','082328342324','-','2024-01-15 01:46:35','2024-01-15 01:46:35');

/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table books
# ------------------------------------------------------------

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `isbn` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `publisher_id` bigint(20) unsigned NOT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `books_publisher_id_foreign` (`publisher_id`),
  KEY `books_author_id_foreign` (`author_id`),
  KEY `books_category_id_foreign` (`category_id`),
  CONSTRAINT `books_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  CONSTRAINT `books_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `books_publisher_id_foreign` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;

INSERT INTO `books` (`id`, `isbn`, `title`, `cover`, `year`, `publisher_id`, `author_id`, `category_id`, `qty`, `price`, `created_at`, `updated_at`)
VALUES
	(3,'978-602-03-8373-6','Negeri Para Bedebah','books/w7wat3EMS9T7DPyrwXLGDSRrD1QAGaHlvNW5MEeM.jpg',2012,1,2,1,4,5000,'2024-01-13 15:54:31','2024-01-14 14:10:20'),
	(4,'67576123','Pulang','books/zooAmmkajsI3ypAlLHozk2zg8uAWbDVwrnvYvRaz.jpg',2015,1,2,1,7,66000,'2024-01-14 14:29:52','2024-01-15 01:32:56'),
	(5,'786876237842','Test Book','books/AHCN7YMOa3IeKMO100A98QPE7DnhwTVZAWim1G5G.jpg',2024,1,2,3,3,50000,'2024-01-15 01:31:48','2024-01-15 01:43:25'),
	(6,'5726573','Kambing Jantan','books/a8t42uIDSh6KDcyHOAmgfU7jcdoRXfWD66EsHPiU.jpg',2017,1,4,3,2,61000,'2024-01-15 01:49:16','2024-01-15 03:31:04');

/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'Action','2024-01-13 15:17:19','2024-01-14 10:02:11'),
	(2,'Thriller','2024-01-15 01:27:41','2024-01-15 01:27:41'),
	(3,'Comedy','2024-01-15 01:30:58','2024-01-15 01:30:58'),
	(4,'Romance','2024-01-15 03:29:33','2024-01-15 03:29:33');

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `gender` char(1) NOT NULL,
  `phone_number` char(14) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;

INSERT INTO `members` (`id`, `name`, `gender`, `phone_number`, `address`, `email`, `created_at`, `updated_at`)
VALUES
	(2,'Fadia','P','083423','-','fadia@gmail.com','2024-01-14 09:27:13','2024-01-14 09:27:13'),
	(3,'Test123','L','982398423','-','test@gmail.com','2024-01-14 13:16:21','2024-01-14 13:17:57'),
	(4,'Test3','P','746923794','-','test3@gmail.com','2024-01-15 01:25:55','2024-01-15 01:25:55'),
	(5,'test4','P','083434232','-','test4@gmail.com','2024-01-15 01:30:44','2024-01-15 01:30:44'),
	(6,'Test9','P','073433242','Jln. Kenangan','test7@gmail.com','2024-01-15 03:29:06','2024-01-15 03:29:20');

/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2024_01_07_111538_create_users_table',1),
	(2,'2024_01_12_144605_create_members_table',1),
	(3,'2024_01_12_144708_create_publishers_table',1),
	(4,'2024_01_12_144748_create_authors_table',1),
	(5,'2024_01_12_145009_create_categories_table',1),
	(6,'2024_01_12_145045_create_books_table',1),
	(7,'2024_01_12_145159_create_transactions_table',1),
	(8,'2024_01_12_145209_create_transaction_details_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table publishers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `publishers`;

CREATE TABLE `publishers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone_number` char(14) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `publishers` WRITE;
/*!40000 ALTER TABLE `publishers` DISABLE KEYS */;

INSERT INTO `publishers` (`id`, `name`, `email`, `phone_number`, `address`, `created_at`, `updated_at`)
VALUES
	(1,'Gramedia','gramedia@email.com','082322','-','2024-01-13 14:50:37','2024-01-13 14:50:37');

/*!40000 ALTER TABLE `publishers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transaction_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transaction_details`;

CREATE TABLE `transaction_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned NOT NULL,
  `book_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_details_book_id_foreign` (`book_id`),
  CONSTRAINT `transaction_details_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `transaction_details` WRITE;
/*!40000 ALTER TABLE `transaction_details` DISABLE KEYS */;

INSERT INTO `transaction_details` (`id`, `transaction_id`, `book_id`, `qty`, `created_at`, `updated_at`)
VALUES
	(9,9,6,1,'2024-01-15 03:30:33','2024-01-15 03:30:33');

/*!40000 ALTER TABLE `transaction_details` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) unsigned NOT NULL,
  `status` enum('true','false') NOT NULL DEFAULT 'false',
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_member_id_foreign` (`member_id`),
  CONSTRAINT `transactions_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;

INSERT INTO `transactions` (`id`, `member_id`, `status`, `date_start`, `date_end`, `created_at`, `updated_at`)
VALUES
	(9,2,'true','2024-01-15','2024-01-18','2024-01-15 03:30:33','2024-01-15 03:31:04');

/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `created_at`, `updated_at`)
VALUES
	(1,'admin','admin@gmail.com','$2y$12$HAv5UpJKv7NVnYIMeRNy5OvPyjheTFnGXeEH869r/2WhxR9m4TUDO','2024-01-13 13:44:07','2024-01-13 13:44:07'),
	(2,'admin2','admin2@gmail.com','$2y$12$eLVO3I5LJGScapzi1KNKleadXTm6XqMHMxZJ4xuXArRxHwh9YMDgK','2024-01-15 01:01:47','2024-01-15 01:01:47'),
	(3,'admin3','admin3@gmail.com','$2y$12$.vbu8WjHifRG1dYB4sj2v.SqoWoMipHMmAYmz4momgAX0jegeMS/2','2024-01-15 01:24:46','2024-01-15 01:24:46'),
	(4,'admin4','admin4@gmail.com','$2y$12$OnVQgStb.KRvbmycqKw1N.p8h1hwVYF6JSNlzWpXnMKl.tww.SaRi','2024-01-15 01:29:45','2024-01-15 01:29:45'),
	(5,'admin5','admin5@gmail.com','$2y$12$/pz2wbaSFEB5a9c4/Rh6H.QHC7WpHUvYdloChrZWXrAhE0ds.Lgbq','2024-01-15 03:26:52','2024-01-15 03:26:52');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
