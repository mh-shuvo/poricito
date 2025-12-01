-- Migration: create senior_citizen table
CREATE TABLE IF NOT EXISTS `senior_citizen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `district` varchar(255) DEFAULT NULL,
  `upazila` varchar(255) DEFAULT NULL,
  `union` varchar(255) DEFAULT NULL,
  `address` text,
  `details` text,
  `images` text,
  `status` enum('pending','approved','canceled') NOT NULL DEFAULT 'pending',
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `createdById` int DEFAULT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  PRIMARY KEY (`id`),
  KEY `IDX_CREATED_BY` (`createdById`),
  CONSTRAINT `FK_SENIOR_CREATED_BY` FOREIGN KEY (`createdById`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
