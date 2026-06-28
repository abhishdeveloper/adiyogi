-- Create Tables

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` enum('superadmin','clinic','patient') NOT NULL DEFAULT 'patient',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `clinics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `unique_code` varchar(50) NOT NULL,
  `url_slug` varchar(100) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`unique_code`),
  UNIQUE KEY `url_slug` (`url_slug`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_clinic_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `patient_clinic_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_user_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `linked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_link` (`patient_user_id`,`clinic_id`),
  KEY `clinic_id` (`clinic_id`),
  CONSTRAINT `fk_link_patient` FOREIGN KEY (`patient_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_link_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default superadmin
INSERT INTO `users` (`role`, `email`, `password`, `first_name`, `last_name`, `is_active`) VALUES
('superadmin', 'admin@medclinicpro.com', '$2y$10$0X53a/LyrxJ6MVB7BoKGAObILZYBMGAhU99c0QG9esJNHT2WFa6s2', 'Super', 'Admin', 1);

-- Insert placeholder settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('google_client_id', ''),
('google_client_secret', '');
