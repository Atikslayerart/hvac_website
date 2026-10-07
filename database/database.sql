-- ============================================================
-- This file creates the database and all required tables:
-- 1. users        - admin and client accounts
-- 2. appointments - service scheduling requests
-- 3. complaints   - customer complaints/support requests
-- ============================================================
CREATE DATABASE IF NOT EXISTS `hvac_company`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE `hvac_company`;

DROP TABLE IF EXISTS `complaints`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `users`;

-- ------------------------------------------------------------
-- Table: users
-- Stores login accounts for administrators and clients.
-- ------------------------------------------------------------
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'client') NOT NULL DEFAULT 'client',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Table: appointments
-- Stores HVAC service appointment requests from logged-in users.
-- ------------------------------------------------------------
CREATE TABLE `appointments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `service_type` VARCHAR(100) NOT NULL,
    `preferred_date` DATE NOT NULL,
    `preferred_time` TIME NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM(
        'pending',
        'under_process',
        'confirmed',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_appointments_user_id` (`user_id`),
    KEY `idx_appointments_status` (`status`),

    CONSTRAINT `fk_appointments_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Table: complaints
-- Stores customer complaints submitted from complaint.php.
-- ------------------------------------------------------------
CREATE TABLE `complaints` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `project_name` VARCHAR(150) DEFAULT NULL,
    `complaint_type` VARCHAR(100) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('Pending', 'In Progress', 'Resolved')
        NOT NULL DEFAULT 'Pending',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_complaints_email` (`email`),
    KEY `idx_complaints_status` (`status`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;