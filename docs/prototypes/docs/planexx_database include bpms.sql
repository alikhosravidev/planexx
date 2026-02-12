-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: planexx_db:3306
-- Generation Time: Nov 06, 2025 at 02:06 PM
-- Server version: 8.4.4
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `planexx`
--

-- --------------------------------------------------------

--
-- Table structure for table `bpms_follow_ups`
--

CREATE TABLE `bpms_follow_ups` (
  `id` int UNSIGNED NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `type` enum('follow_up','state_transition','user_action','watcher_review') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_by` int UNSIGNED NOT NULL,
  `previous_assignee_id` int UNSIGNED DEFAULT NULL,
  `new_assignee_id` int UNSIGNED DEFAULT NULL,
  `previous_state_id` int UNSIGNED DEFAULT NULL,
  `new_state_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bpms_tasks`
--

CREATE TABLE `bpms_tasks` (
  `id` int UNSIGNED NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL ,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL ,
  `description` text COLLATE utf8mb4_unicode_ci ,
  `workflow_id` int UNSIGNED NOT NULL ,
  `current_state_id` int UNSIGNED NOT NULL ,
  `assignee_id` int UNSIGNED NOT NULL ,
  `created_by` int UNSIGNED NOT NULL ,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium' ,
  `estimated_hours` decimal(5,2) DEFAULT NULL ,
  `due_date` datetime DEFAULT NULL ,
  `next_follow_up_date` datetime DEFAULT NULL ,
  `completed_at` timestamp NULL DEFAULT NULL ,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `bpms_watchlist`
--

CREATE TABLE `bpms_watchlist` (
  `id` int UNSIGNED NOT NULL,
  `task_id` int UNSIGNED NOT NULL ,
  `watcher_id` int UNSIGNED NOT NULL ,
  `watch_status` enum('open','reviewed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open' ,
  `watch_reason` text COLLATE utf8mb4_unicode_ci ,
  `comment` text COLLATE utf8mb4_unicode_ci ,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `bpms_workflows`
--

CREATE TABLE `bpms_workflows` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL ,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL ,
  `description` text COLLATE utf8mb4_unicode_ci ,
  `department_id` int UNSIGNED DEFAULT NULL ,
  `workflow_owner_id` int UNSIGNED DEFAULT NULL ,
  `allowed_roles` json DEFAULT NULL ,
  `created_by` int UNSIGNED DEFAULT NULL ,
  `is_active` tinyint(1) DEFAULT '1' ,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `bpms_workflow_states`
--

CREATE TABLE `bpms_workflow_states` (
  `id` int UNSIGNED NOT NULL,
  `workflow_id` int UNSIGNED NOT NULL ,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL ,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL ,
  `description` text COLLATE utf8mb4_unicode_ci ,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL ,
  `order` smallint UNSIGNED NOT NULL DEFAULT '1' ,
  `position` enum('start','middle','final-success','final-failed','final-closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'middle' ,
  `default_assignee_id` int UNSIGNED DEFAULT NULL ,
  `is_active` tinyint(1) DEFAULT '1' ,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tier` tinyint UNSIGNED DEFAULT NULL COMMENT '0:investor, 1:senior_management, 2:middle_management, 3:staff',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_addresses`
--

CREATE TABLE `location_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `province_id` bigint UNSIGNED DEFAULT NULL,
  `city_id` bigint UNSIGNED DEFAULT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_mobile` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_cities`
--

CREATE TABLE `location_cities` (
  `id` bigint UNSIGNED NOT NULL,
  `province_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `location_countries`
--

CREATE TABLE `location_countries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `location_provinces`
--

CREATE TABLE `location_provinces` (
  `id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `ip` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fingerprint` char(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `logout_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temporary_codes`
--

CREATE TABLE `temporary_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `direct_manager_id` bigint UNSIGNED DEFAULT NULL,
  `full_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `customer_type` tinyint UNSIGNED DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `national_code` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint UNSIGNED DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `birth_date` timestamp NULL DEFAULT NULL,
  `mobile_verified_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `employee_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `job_position_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_departments`
--

CREATE TABLE `user_departments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bpms_follow_ups`
--
ALTER TABLE `bpms_follow_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `previous_state_id` (`previous_state_id`),
  ADD KEY `new_state_id` (`new_state_id`),
  ADD KEY `idx_task` (`task_id`),
  ADD KEY `idx_user` (`created_by`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `bpms_tasks`
--
ALTER TABLE `bpms_tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_workflow` (`workflow_id`),
  ADD KEY `idx_current_state` (`current_state_id`),
  ADD KEY `idx_assignee` (`assignee_id`),
  ADD KEY `idx_creator` (`created_by`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_next_follow_up` (`next_follow_up_date`),
  ADD KEY `idx_due_date` (`due_date`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indexes for table `bpms_watchlist`
--
ALTER TABLE `bpms_watchlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_task_watcher` (`task_id`,`watcher_id`),
  ADD KEY `idx_task` (`task_id`),
  ADD KEY `idx_watcher` (`watcher_id`),
  ADD KEY `idx_status` (`watch_status`);

--
-- Indexes for table `bpms_workflows`
--
ALTER TABLE `bpms_workflows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_department` (`department_id`),
  ADD KEY `idx_process_manager` (`workflow_owner_id`),
  ADD KEY `idx_creator` (`created_by`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indexes for table `bpms_workflow_states`
--
ALTER TABLE `bpms_workflow_states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workflow` (`workflow_id`),
  ADD KEY `idx_order` (`workflow_id`,`order`),
  ADD KEY `idx_position` (`position`),
  ADD KEY `idx_assignee` (`default_assignee_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_deleted` (`deleted_at`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_code_unique` (`code`),
  ADD KEY `departments_parent_id_index` (`parent_id`),
  ADD KEY `departments_manager_id_index` (`manager_id`),
  ADD KEY `departments_is_active_index` (`is_active`),
  ADD KEY `departments_deleted_at_index` (`deleted_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_positions_code_unique` (`code`),
  ADD KEY `job_positions_is_active_index` (`is_active`),
  ADD KEY `job_positions_deleted_at_index` (`deleted_at`);

--
-- Indexes for table `location_addresses`
--
ALTER TABLE `location_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_addresses_user_id_foreign` (`user_id`),
  ADD KEY `location_addresses_country_id_foreign` (`country_id`),
  ADD KEY `location_addresses_province_id_foreign` (`province_id`),
  ADD KEY `location_addresses_city_id_foreign` (`city_id`);

--
-- Indexes for table `location_cities`
--
ALTER TABLE `location_cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_cities_province_id_foreign` (`province_id`);

--
-- Indexes for table `location_countries`
--
ALTER TABLE `location_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_provinces`
--
ALTER TABLE `location_provinces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_provinces_country_id_foreign` (`country_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `temporary_codes`
--
ALTER TABLE `temporary_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temporary_codes_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_national_code_unique` (`national_code`),
  ADD UNIQUE KEY `users_employee_code_unique` (`employee_code`),
  ADD KEY `users_user_type_index` (`user_type`),
  ADD KEY `users_customer_type_index` (`customer_type`),
  ADD KEY `users_direct_manager_id_index` (`direct_manager_id`),
  ADD KEY `users_is_active_index` (`is_active`),
  ADD KEY `users_deleted_at_index` (`deleted_at`),
  ADD KEY `users_job_position_id_index` (`job_position_id`);

--
-- Indexes for table `user_departments`
--
ALTER TABLE `user_departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_departments_user_id_department_id_unique` (`user_id`,`department_id`),
  ADD KEY `user_departments_user_id_index` (`user_id`),
  ADD KEY `user_departments_department_id_index` (`department_id`),
  ADD KEY `user_departments_is_primary_index` (`is_primary`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bpms_follow_ups`
--
ALTER TABLE `bpms_follow_ups`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bpms_tasks`
--
ALTER TABLE `bpms_tasks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bpms_watchlist`
--
ALTER TABLE `bpms_watchlist`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bpms_workflows`
--
ALTER TABLE `bpms_workflows`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bpms_workflow_states`
--
ALTER TABLE `bpms_workflow_states`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_addresses`
--
ALTER TABLE `location_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_cities`
--
ALTER TABLE `location_cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=473;

--
-- AUTO_INCREMENT for table `location_countries`
--
ALTER TABLE `location_countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `location_provinces`
--
ALTER TABLE `location_provinces`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temporary_codes`
--
ALTER TABLE `temporary_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_departments`
--
ALTER TABLE `user_departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bpms_follow_ups`
--
ALTER TABLE `bpms_follow_ups`
  ADD CONSTRAINT `bpms_follow_ups_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `bpms_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bpms_follow_ups_ibfk_2` FOREIGN KEY (`previous_state_id`) REFERENCES `bpms_workflow_states` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `bpms_follow_ups_ibfk_3` FOREIGN KEY (`new_state_id`) REFERENCES `bpms_workflow_states` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `bpms_tasks`
--
ALTER TABLE `bpms_tasks`
  ADD CONSTRAINT `bpms_tasks_ibfk_1` FOREIGN KEY (`workflow_id`) REFERENCES `bpms_workflows` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `bpms_tasks_ibfk_2` FOREIGN KEY (`current_state_id`) REFERENCES `bpms_workflow_states` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `bpms_watchlist`
--
ALTER TABLE `bpms_watchlist`
  ADD CONSTRAINT `bpms_watchlist_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `bpms_tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bpms_workflow_states`
--
ALTER TABLE `bpms_workflow_states`
  ADD CONSTRAINT `bpms_workflow_states_ibfk_1` FOREIGN KEY (`workflow_id`) REFERENCES `bpms_workflows` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `location_addresses`
--
ALTER TABLE `location_addresses`
  ADD CONSTRAINT `location_addresses_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `location_cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `location_addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `location_countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `location_addresses_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `location_provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `location_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `location_cities`
--
ALTER TABLE `location_cities`
  ADD CONSTRAINT `location_cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `location_provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `location_provinces`
--
ALTER TABLE `location_provinces`
  ADD CONSTRAINT `location_provinces_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `location_countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temporary_codes`
--
ALTER TABLE `temporary_codes`
  ADD CONSTRAINT `temporary_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_direct_manager_id_foreign` FOREIGN KEY (`direct_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_job_position_id_foreign` FOREIGN KEY (`job_position_id`) REFERENCES `job_positions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_departments`
--
ALTER TABLE `user_departments`
  ADD CONSTRAINT `user_departments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_departments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
