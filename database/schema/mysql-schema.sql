/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `academics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academics` (
  `academic_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('undergraduate','postgraduate','phd','erasmus','researcher','staff coupon','staff card application','staff entry') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `a_m` mediumint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`academic_id`),
  UNIQUE KEY `academics_email_unique` (`email`),
  UNIQUE KEY `academics_a_m_unique` (`a_m`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_id` bigint unsigned NOT NULL,
  `is_permanent` tinyint(1) NOT NULL DEFAULT '1',
  `location` char(99) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` char(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_academic_id_foreign` (`academic_id`),
  CONSTRAINT `addresses_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `card_applicants` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `card_applicants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_applicants` (
  `academic_id` bigint unsigned NOT NULL,
  `first_year` year NOT NULL,
  `cellphone` char(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`academic_id`),
  KEY `card_applicants_department_id_foreign` (`department_id`),
  CONSTRAINT `card_applicants_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `academics` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `card_applicants_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `card_application_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_application_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `card_application_id` bigint unsigned NOT NULL,
  `status` enum('submitted','accepted','rejected','incomplete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `file_name` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` char(27) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_application_documents_card_application_id_foreign` (`card_application_id`),
  CONSTRAINT `card_application_documents_card_application_id_foreign` FOREIGN KEY (`card_application_id`) REFERENCES `card_applications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `card_application_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_application_staff` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('undergraduate','postgraduate','phd','erasmus','researcher','staff coupon','staff card application','staff entry') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_application_staff_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `card_application_update`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_application_update` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `card_application_staff_id` tinyint unsigned DEFAULT NULL COMMENT 'null if it is the student otherwise staff',
  `card_application_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('temporary saved','submitted','checking','temporary checked','accepted','rejected','incomplete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'incomplete',
  PRIMARY KEY (`id`),
  KEY `card_application_checking_card_application_staff_id_foreign` (`card_application_staff_id`),
  KEY `card_application_checking_card_application_id_foreign` (`card_application_id`),
  CONSTRAINT `card_application_checking_card_application_id_foreign` FOREIGN KEY (`card_application_id`) REFERENCES `card_applications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `card_application_checking_card_application_staff_id_foreign` FOREIGN KEY (`card_application_staff_id`) REFERENCES `card_application_staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `card_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expiration_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `card_applications_academic_id_foreign` (`academic_id`),
  CONSTRAINT `card_applications_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `card_applicants` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `coupon_owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_owners` (
  `academic_id` bigint unsigned NOT NULL,
  `money` int unsigned NOT NULL DEFAULT '0',
  `BREAKFAST` int unsigned NOT NULL DEFAULT '0',
  `LUNCH` int unsigned NOT NULL DEFAULT '0',
  `DINNER` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`academic_id`),
  CONSTRAINT `coupon_owners_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `academics` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `coupon_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_staff` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('undergraduate','postgraduate','phd','erasmus','researcher','staff coupon','staff card application','staff entry') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupon_staff_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `coupon_transactions`;
/*!50001 DROP VIEW IF EXISTS `coupon_transactions`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `coupon_transactions` AS SELECT 
 1 AS `id`,
 1 AS `created_at`,
 1 AS `academic_id`,
 1 AS `transaction`,
 1 AS `other_person_id`,
 1 AS `money`,
 1 AS `BREAKFAST`,
 1 AS `LUNCH`,
 1 AS `DINNER`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `entry_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entry_staff` (
  `id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('undergraduate','postgraduate','phd','erasmus','researcher','staff coupon','staff card application','staff entry') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entry_staff_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `purchase_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_id` bigint unsigned NOT NULL,
  `coupon_staff_id` tinyint unsigned NOT NULL,
  `money` int unsigned NOT NULL DEFAULT '0',
  `BREAKFAST` tinyint unsigned NOT NULL DEFAULT '0',
  `LUNCH` tinyint unsigned NOT NULL DEFAULT '0',
  `DINNER` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `purchase_coupons_academic_id_foreign` (`academic_id`),
  KEY `purchase_coupons_coupon_staff_id_foreign` (`coupon_staff_id`),
  CONSTRAINT `purchase_coupons_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `coupon_owners` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_coupons_coupon_staff_id_foreign` FOREIGN KEY (`coupon_staff_id`) REFERENCES `coupon_staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transfer_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transfer_coupon` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `BREAKFAST` tinyint unsigned NOT NULL DEFAULT '0',
  `LUNCH` tinyint unsigned NOT NULL DEFAULT '0',
  `DINNER` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transfer_coupon_created_at_sender_id_receiver_id_unique` (`created_at`,`sender_id`,`receiver_id`),
  KEY `transfer_coupon_sender_id_foreign` (`sender_id`),
  KEY `transfer_coupon_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `transfer_coupon_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `coupon_owners` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transfer_coupon_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `coupon_owners` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `usage_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usage_cards` (
  `date` date NOT NULL,
  `academic_id` bigint unsigned NOT NULL,
  `period` enum('breakfast','lunch','dinner') COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` time NOT NULL,
  `entry_staff_id` tinyint unsigned NOT NULL,
  PRIMARY KEY (`date`,`academic_id`,`period`),
  KEY `usage_cards_academic_id_foreign` (`academic_id`),
  KEY `usage_cards_entry_staff_id_foreign` (`entry_staff_id`),
  CONSTRAINT `usage_cards_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `card_applicants` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usage_cards_entry_staff_id_foreign` FOREIGN KEY (`entry_staff_id`) REFERENCES `entry_staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `usage_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usage_coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_id` bigint unsigned NOT NULL,
  `entry_staff_id` tinyint unsigned NOT NULL,
  `period` enum('breakfast','lunch','dinner') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usage_coupons_academic_id_foreign` (`academic_id`),
  KEY `usage_coupons_entry_staff_id_foreign` (`entry_staff_id`),
  CONSTRAINT `usage_coupons_academic_id_foreign` FOREIGN KEY (`academic_id`) REFERENCES `coupon_owners` (`academic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usage_coupons_entry_staff_id_foreign` FOREIGN KEY (`entry_staff_id`) REFERENCES `entry_staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('undergraduate','postgraduate','phd','erasmus','researcher','staff coupon','staff card application','staff entry') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP VIEW IF EXISTS `coupon_transactions`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`food`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `coupon_transactions` AS select `transfer_coupon`.`id` AS `id`,`transfer_coupon`.`created_at` AS `created_at`,`transfer_coupon`.`sender_id` AS `academic_id`,'sending' AS `transaction`,`transfer_coupon`.`receiver_id` AS `other_person_id`,0 AS `money`,(cast(`transfer_coupon`.`BREAKFAST` as signed) * -(1)) AS `BREAKFAST`,(cast(`transfer_coupon`.`LUNCH` as signed) * -(1)) AS `LUNCH`,(cast(`transfer_coupon`.`DINNER` as signed) * -(1)) AS `DINNER` from `transfer_coupon` union select `transfer_coupon`.`id` AS `id`,`transfer_coupon`.`created_at` AS `created_at`,`transfer_coupon`.`receiver_id` AS `academic_id`,'receiving' AS `transaction`,`transfer_coupon`.`sender_id` AS `other_person_id`,0 AS `money`,`transfer_coupon`.`BREAKFAST` AS `BREAKFAST`,`transfer_coupon`.`LUNCH` AS `LUNCH`,`transfer_coupon`.`DINNER` AS `DINNER` from `transfer_coupon` union select `purchase_coupons`.`id` AS `id`,`purchase_coupons`.`created_at` AS `created_at`,`purchase_coupons`.`academic_id` AS `academic_id`,'buying' AS `transaction`,0 AS `other_person_id`,(`purchase_coupons`.`money` / 100) AS `money`,`purchase_coupons`.`BREAKFAST` AS `BREAKFAST`,`purchase_coupons`.`LUNCH` AS `LUNCH`,`purchase_coupons`.`DINNER` AS `DINNER` from `purchase_coupons` union select `usage_coupons`.`id` AS `id`,`usage_coupons`.`created_at` AS `created_at`,`usage_coupons`.`academic_id` AS `academic_id`,'using' AS `transaction`,0 AS `other_person_id`,0 AS `money`,(case when (`usage_coupons`.`period` = 'BREAKFAST') then -(1) else 0 end) AS `BREAKFAST`,(case when (`usage_coupons`.`period` = 'LUNCH') then -(1) else 0 end) AS `LUNCH`,(case when (`usage_coupons`.`period` = 'DINNER') then -(1) else 0 end) AS `DINNER` from `usage_coupons` order by `created_at` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2018_08_08_100000_create_telescope_entries_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2022_04_14_083353_create_academics_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2022_04_14_083400_create_departments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2022_04_14_083404_create_card_applicants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2022_04_14_083410_create_card_application_staff_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2022_04_14_083415_create_card_applications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2022_04_14_083426_create_card_application_documents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2022_04_14_083448_create_coupon_owners_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2022_04_14_083459_create_coupon_staff_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2022_04_14_083510_create_entry_staff_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2022_04_14_083545_create_purchase_coupons_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2022_04_14_083556_create_usage_cards_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2022_04_14_083608_create_usage_coupons_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2022_04_14_101359_create_transfer_coupons_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2022_05_06_113120_alter_card_applications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2022_05_06_121658_alter_card_applicants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2022_05_06_123043_create_addresses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2022_05_06_130407_create_card_application_checking_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2022_05_06_135934_create_has_card_applicant_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2024_03_13_171140_alter_card_application_documents_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2024_03_23_185226_alter_card_application_checking_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2024_03_23_185227_alter_has_card_applicant_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2024_03_23_185228_move_status_from_card__application_to_card_application_checking',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2024_11_12_092021_create_coupon_transactions_view',2);
