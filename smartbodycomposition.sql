-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 06:49 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartbodycomposition`
--

-- --------------------------------------------------------

--
-- Table structure for table `body_compositions`
--

CREATE TABLE `body_compositions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `measurement_date` date NOT NULL,
  `measurement_time` time DEFAULT NULL,
  `weight_kg` double DEFAULT NULL,
  `body_fat_percent` double DEFAULT NULL,
  `body_fat_kg` double DEFAULT NULL,
  `body_water_percent` double DEFAULT NULL,
  `muscle_mass` double DEFAULT NULL,
  `physical_rating` double DEFAULT NULL,
  `bone_mass` double DEFAULT NULL,
  `kcal` double DEFAULT NULL,
  `body_age` double DEFAULT NULL,
  `visceral_fat` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `body_compositions`
--

INSERT INTO `body_compositions` (`id`, `user_id`, `measurement_date`, `measurement_time`, `weight_kg`, `body_fat_percent`, `body_fat_kg`, `body_water_percent`, `muscle_mass`, `physical_rating`, `bone_mass`, `kcal`, `body_age`, `visceral_fat`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-04-03', '08:00:00', 67.4, 27.8, 18.7, 46.5, 24.9, 4, 2.7, 1875, 32, 9.8, '2026-04-12 08:24:53', '2026-04-12 08:24:53'),
(2, 2, '2026-04-10', '08:00:00', 67, 27.2, 18.2, 45.9, 24.7, 4, 2.7, 1860, 32, 9.4, '2026-04-12 08:24:53', '2026-04-12 08:24:53'),
(5, 4, '2026-03-15', '09:10:00', 61.8, 24.6, 15.2, 50.7, 25.8, 6, 2.6, 1760, 32, 7.8, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(6, 4, '2026-04-10', '09:10:00', 61.2, 23.8, 14.6, 51.4, 26.1, 7, 2.7, 1748, 32, 7.2, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(7, 5, '2026-01-06', '07:30:00', 88.2, 28.4, 25.1, 48.2, 33.5, 3, 3.1, 2520, 32, 11, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(8, 5, '2026-01-20', '07:30:00', 87, 27.9, 24.3, 48.5, 33.8, 3, 3.1, 2495, 32, 11, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(9, 5, '2026-02-03', '07:30:00', 86.1, 27.2, 23.4, 49.1, 34.3, 4, 3.1, 2473, 32, 10, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(10, 5, '2026-02-17', '07:30:00', 85.3, 26.5, 22.6, 49.7, 34.7, 4, 3.2, 2455, 32, 10, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(11, 5, '2026-03-03', '07:30:00', 84.5, 25.8, 21.8, 50.4, 35.1, 5, 3.2, 2437, 32, 9, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(12, 5, '2026-03-17', '07:30:00', 83.8, 25, 21, 51, 35.5, 5, 3.2, 2420, 32, 9, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(13, 5, '2026-03-31', '07:30:00', 83.1, 24.2, 20.1, 51.7, 35.8, 6, 3.2, 2403, 32, 8, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(14, 5, '2026-04-10', '07:30:00', 82.4, 23.5, 19.4, 52.3, 36.1, 6, 3.2, 2387, 32, 8, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(17, 7, '2026-03-03', '17:37:00', 72, 18.5, 13.5, 58, 44, 4, 3.1, 1900, 25, 5, '2026-04-13 00:38:04', '2026-04-13 00:38:04'),
(18, 7, '2026-02-16', '17:45:00', 70, 17, 12, 59, 42, 5, 3, 1879, 25, 5, '2026-04-13 00:46:07', '2026-04-13 00:46:35'),
(19, 7, '2026-02-09', '19:46:00', 69.9, 16.8, 12.8, 57.9, 41.7, 5, 2.9, 1855, 25, 5, '2026-04-13 00:47:41', '2026-04-13 00:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-login:alltemplates.demo@example.com|127.0.0.1', 'i:2;', 1776342887),
('laravel-cache-login:alltemplates.demo@example.com|127.0.0.1:timer', 'i:1776342887;', 1776342887),
('laravel-cache-login:nathanaeld012@gmai.com|127.0.0.1', 'i:1;', 1776063016),
('laravel-cache-login:nathanaeld012@gmai.com|127.0.0.1:timer', 'i:1776063016;', 1776063016),
('laravel-cache-login:nathanaeld0121@gmai.com|127.0.0.1', 'i:1;', 1776062767),
('laravel-cache-login:nathanaeld0121@gmai.com|127.0.0.1:timer', 'i:1776062767;', 1776062767);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `metric` enum('weight_kg','body_fat_percent','muscle_mass','bmi','visceral_fat','body_water_percent') NOT NULL,
  `target_value` double NOT NULL,
  `start_value` double DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','achieved','abandoned') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `metric`, `target_value`, `start_value`, `deadline`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'weight_kg', 78, 88.2, '2026-07-31', 'Target healthy BMI range (< 25) by end of July through caloric deficit and consistent training.', 'active', '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(2, 5, 'body_fat_percent', 18, 28.4, '2026-09-30', 'Reach athletic body fat range. Currently on track — losing ~0.5% per fortnight.', 'active', '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(3, 5, 'muscle_mass', 40, 33.5, '2026-12-31', 'Progressive overload programme — 3 days strength, 2 days cardio per week.', 'active', '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(4, 5, 'visceral_fat', 6, 11, '2026-10-31', 'Reduce metabolic risk. Avoiding processed food and sugary drinks.', 'active', '2026-04-12 08:24:54', '2026-04-12 08:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `health_recommendations`
--

CREATE TABLE `health_recommendations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recommendation_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `health_recommendations`
--

INSERT INTO `health_recommendations` (`id`, `user_id`, `recommendation_text`, `created_at`, `updated_at`) VALUES
(1, 2, '{\"template_id\":\"steady-progress\",\"template_code\":\"TMPL-REC-001\",\"recommendation_type\":\"Recovery\",\"title\":\"Maintain Your Current Wellness Habits\",\"summary\":\"Your latest body-composition markers are broadly within the personalized wellness ranges used by the system.\",\"details\":[\"Keep routines consistent before making large changes to training or nutrition.\",\"Continue tracking measurements regularly so future guidance stays relevant.\",\"Use gradual habit adjustments instead of chasing short-term fluctuations.\"],\"metric_basis\":[{\"label\":\"Profile context\",\"value\":\"Moderate activity\",\"insight\":\"Personalized ranges were applied using available gender, age, and inferred activity level data.\"}],\"priority\":\"low\",\"confidence\":\"medium\",\"icon\":\"heart\",\"status\":\"pending\",\"measurement_snapshot\":{\"measurement_date\":\"2026-04-10\",\"weight_kg\":67,\"body_fat_percent\":27.2,\"body_water_percent\":45.9,\"muscle_mass\":24.7,\"visceral_fat\":9.4,\"physical_rating\":4,\"bmi\":24.9,\"activity_level\":\"moderate\"},\"profile_snapshot\":{\"gender\":\"Female\",\"gender_key\":\"female\",\"age\":26,\"activity_level\":\"moderate\",\"height_cm\":164},\"engine_version\":\"1.1.0\",\"guidance_scope\":\"general-wellness\",\"disclaimer\":\"These recommendations support general wellness only and are not medical advice.\",\"last_synced_at\":\"2026-04-12T16:24:53+00:00\",\"version\":1}', '2026-04-12 08:24:53', '2026-04-12 08:24:53'),
(7, 4, '{\"template_id\":\"steady-progress\",\"template_code\":\"TMPL-REC-001\",\"recommendation_type\":\"Recovery\",\"title\":\"Maintain Your Current Wellness Habits\",\"summary\":\"Your latest body-composition markers are broadly within the personalised wellness ranges used by the system.\",\"details\":[\"Keep routines consistent before making large changes to training or nutrition.\",\"Continue tracking measurements regularly so future guidance stays relevant.\",\"Use gradual habit adjustments instead of chasing short-term fluctuations.\"],\"metric_basis\":[{\"label\":\"Profile context\",\"value\":\"High activity\",\"insight\":\"Personalized ranges were applied using available gender, age, and inferred activity level data.\"}],\"priority\":\"low\",\"confidence\":\"medium\",\"icon\":\"heart\",\"status\":\"completed\",\"measurement_snapshot\":{\"measurement_date\":\"2026-04-10\",\"weight_kg\":61.2,\"body_fat_percent\":23.8,\"body_water_percent\":51.4,\"muscle_mass\":26.1,\"visceral_fat\":7.2,\"physical_rating\":7,\"bmi\":21.7,\"activity_level\":\"high\"},\"profile_snapshot\":{\"gender\":\"Female\",\"gender_key\":\"female\",\"age\":29,\"activity_level\":\"high\",\"height_cm\":168},\"engine_version\":\"1.1.0\",\"guidance_scope\":\"general-wellness\",\"disclaimer\":\"These recommendations support general wellness only and are not medical advice.\",\"last_synced_at\":\"2026-04-17T06:49:02+00:00\",\"version\":1}', '2026-04-12 08:24:54', '2026-04-16 22:49:02'),
(8, 5, '{\"template_id\":\"body-fat-reduction\",\"template_code\":\"TMPL-NUT-001\",\"recommendation_type\":\"Nutrition\",\"title\":\"Rebalance Body Fat Through Sustainable Habits\",\"summary\":\"Body fat percentage is above the personalized wellness range, so nutrition quality and consistency should be prioritized.\",\"details\":[\"Build meals around protein, fiber, and minimally processed foods.\",\"Use gradual, sustainable calorie control instead of aggressive restriction.\",\"Track changes across several weeks rather than reacting to a single measurement.\"],\"metric_basis\":[{\"label\":\"Body fat\",\"value\":\"23.5%\",\"insight\":\"Your body fat is above 21%, which is the healthy upper limit for your gender and age. Excess body fat, especially over time, can increase health risks.\"},{\"label\":\"BMI\",\"value\":\"27.5\",\"insight\":\"Your BMI is shown here for extra context. It helps give a fuller picture alongside your body fat reading.\"}],\"priority\":\"medium\",\"confidence\":\"high\",\"icon\":\"apple\",\"status\":\"pending\",\"measurement_snapshot\":{\"measurement_date\":\"2026-04-10\",\"weight_kg\":82.4,\"body_fat_percent\":23.5,\"body_water_percent\":52.3,\"muscle_mass\":36.1,\"visceral_fat\":8,\"physical_rating\":6,\"bmi\":27.5,\"activity_level\":\"high\"},\"profile_snapshot\":{\"gender\":\"Male\",\"gender_key\":\"male\",\"age\":24,\"activity_level\":\"high\",\"height_cm\":173},\"engine_version\":\"1.1.0\",\"guidance_scope\":\"general-wellness\",\"disclaimer\":\"These recommendations support general wellness only and are not medical advice.\",\"last_synced_at\":\"2026-04-12T16:24:54+00:00\",\"version\":1}', '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(11, 7, '{\"template_id\":\"steady-progress\",\"template_code\":\"TMPL-REC-001\",\"recommendation_type\":\"Recovery\",\"title\":\"Maintain Your Current Wellness Habits\",\"summary\":\"Your latest body-composition markers are broadly within the personalised wellness ranges used by the system.\",\"details\":[\"Keep routines consistent before making large changes to training or nutrition.\",\"Continue tracking measurements regularly so future guidance stays relevant.\",\"Use gradual habit adjustments instead of chasing short-term fluctuations.\"],\"metric_basis\":[{\"label\":\"Profile context\",\"value\":\"Moderate activity\",\"insight\":\"Personalized ranges were applied using available gender, age, and inferred activity level data.\"}],\"priority\":\"low\",\"confidence\":\"medium\",\"icon\":\"heart\",\"status\":\"pending\",\"measurement_snapshot\":{\"measurement_date\":\"2026-03-03\",\"weight_kg\":72,\"body_fat_percent\":18.5,\"body_water_percent\":58,\"muscle_mass\":44,\"visceral_fat\":5,\"physical_rating\":4,\"bmi\":24.1,\"activity_level\":\"moderate\"},\"profile_snapshot\":{\"gender\":\"Male\",\"gender_key\":\"male\",\"age\":25,\"activity_level\":\"moderate\",\"height_cm\":173},\"engine_version\":\"1.1.0\",\"guidance_scope\":\"general-wellness\",\"disclaimer\":\"These recommendations support general wellness only and are not medical advice.\",\"last_synced_at\":\"2026-04-15T07:38:07+00:00\",\"version\":1}', '2026-04-13 00:38:04', '2026-04-14 23:38:07');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_26_144841_create_body_compositions_table', 1),
(5, '2026_03_26_144841_create_health_recommendations_table', 1),
(6, '2026_03_26_144842_create_system_settings_table', 1),
(7, '2026_03_26_151801_create_personal_access_tokens_table', 1),
(8, '2026_03_31_000000_add_profile_fields_to_users_table', 1),
(9, '2026_04_09_000001_add_height_to_users_table', 1),
(10, '2026_04_11_000001_create_goals_table', 1),
(11, '2026_04_12_000001_rename_bmr_to_body_age_in_body_compositions_table', 1),
(12, '2026_04_12_000002_create_recommendation_templates_table', 1),
(13, '2026_04_12_000003_add_account_status_to_users_table', 1),
(14, '2026_04_12_000004_create_notifications_table', 1),
(15, '2026_04_12_000005_add_notification_preferences_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0732cedb-4fc8-46f1-b569-84f989c13689', 'App\\Notifications\\MeasurementReminderNotification', 'App\\Models\\User', 6, '{\"kind\":\"measurement_reminder\",\"title\":\"Reminder to log a new measurement\",\"message\":\"Add your first entry to start seeing trends and recommendations.\",\"action_url\":\"\\/body-composition\",\"dedupe_key\":\"measurement-reminder:2026-04-06\",\"priority\":\"medium\"}', NULL, '2026-04-12 08:30:44', '2026-04-12 08:30:44'),
('1f78c2e1-ee7c-499b-8b69-7af10d0d2a99', 'App\\Notifications\\RecommendationGeneratedNotification', 'App\\Models\\User', 6, '{\"kind\":\"recommendation_generated\",\"title\":\"New recommendations are ready\",\"message\":\"A new recommendation was generated from your latest measurement.\",\"action_url\":\"\\/ai-tips\",\"dedupe_key\":\"recommendations:measurement-16\",\"priority\":\"medium\"}', NULL, '2026-04-12 22:47:35', '2026-04-12 22:47:35'),
('24803899-418d-44f7-9e63-9eddc36a26fb', 'App\\Notifications\\MeasurementReminderNotification', 'App\\Models\\User', 1, '{\"kind\":\"measurement_reminder\",\"title\":\"Reminder to log a new measurement\",\"message\":\"Add your first entry to start seeing trends and recommendations.\",\"action_url\":\"\\/body-composition\",\"dedupe_key\":\"measurement-reminder:2026-04-06\",\"priority\":\"medium\"}', NULL, '2026-04-12 08:30:43', '2026-04-12 08:30:43'),
('759dbccc-29a4-494e-82dd-a72225527878', 'App\\Notifications\\RecommendationGeneratedNotification', 'App\\Models\\User', 7, '{\"kind\":\"recommendation_generated\",\"title\":\"New recommendations are ready\",\"message\":\"A new recommendation was generated from your latest measurement.\",\"action_url\":\"\\/ai-tips\",\"dedupe_key\":\"recommendations:measurement-17\",\"priority\":\"medium\"}', NULL, '2026-04-13 00:38:04', '2026-04-13 00:38:04'),
('9b80354e-a752-4fee-8adf-f326321876a6', 'App\\Notifications\\RecommendationGeneratedNotification', 'App\\Models\\User', 6, '{\"kind\":\"recommendation_generated\",\"title\":\"New recommendations are ready\",\"message\":\"A new recommendation was generated from your latest measurement.\",\"action_url\":\"\\/ai-tips\",\"dedupe_key\":\"recommendations:measurement-15\",\"priority\":\"medium\"}', NULL, '2026-04-12 22:43:07', '2026-04-12 22:43:07'),
('9f710bd9-8bc6-4c0f-915d-b81e6f2e8595', 'App\\Notifications\\RecommendationGeneratedNotification', 'App\\Models\\User', 7, '{\"kind\":\"recommendation_generated\",\"title\":\"New recommendations are ready\",\"message\":\"A new recommendation was generated from your latest measurement.\",\"action_url\":\"\\/ai-tips\",\"dedupe_key\":\"recommendations:measurement-19\",\"priority\":\"medium\"}', NULL, '2026-04-13 00:47:41', '2026-04-13 00:47:41'),
('a2752b7b-3572-454f-818a-4c902fa3bea6', 'App\\Notifications\\RecommendationGeneratedNotification', 'App\\Models\\User', 7, '{\"kind\":\"recommendation_generated\",\"title\":\"New recommendations are ready\",\"message\":\"A new recommendation was generated from your latest measurement.\",\"action_url\":\"\\/ai-tips\",\"dedupe_key\":\"recommendations:measurement-18\",\"priority\":\"medium\"}', NULL, '2026-04-13 00:46:07', '2026-04-13 00:46:07'),
('f04a04de-2a3f-436e-9388-98e21c00db29', 'App\\Notifications\\RecommendationGeneratedNotification', 'App\\Models\\User', 3, '{\"kind\":\"recommendation_generated\",\"title\":\"New recommendations are ready\",\"message\":\"3 new recommendations were generated from your latest measurement.\",\"action_url\":\"\\/ai-tips\",\"dedupe_key\":\"recommendations:measurement-20\",\"priority\":\"medium\"}', NULL, '2026-04-13 01:17:33', '2026-04-13 01:17:33');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('nathanaeld012@gmail.com', 'ad9388736c2f9b255b66fab1e17978e736d0a6ba1e78923e2a084b235805efcc', '2026-04-14 23:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(34, 'App\\Models\\User', 4, 'api-token', '1f41df131e285b0a639ba1fc1aba0164c3789155fe97b9ee61bdef56756aedaf', '[\"*\"]', '2026-04-16 22:49:05', NULL, '2026-04-16 22:49:00', '2026-04-16 22:49:05');

-- --------------------------------------------------------

--
-- Table structure for table `recommendation_templates`
--

CREATE TABLE `recommendation_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `template_id` varchar(255) NOT NULL,
  `template_code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`details`)),
  `priority` varchar(255) NOT NULL DEFAULT 'medium',
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `icon` varchar(255) NOT NULL DEFAULT 'heart',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recommendation_templates`
--

INSERT INTO `recommendation_templates` (`id`, `template_id`, `template_code`, `type`, `title`, `summary`, `details`, `priority`, `status`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'hydration-foundation', 'TMPL-HYD-001', 'Hydration', 'Improve Daily Hydration Consistency', 'Your body water percentage is below the personalised general wellness range for your profile and activity level.', '[\"Spread fluid intake more evenly across the day instead of catching up late.\",\"Increase hydration attention around exercise, hot weather, and long gaps between meals.\",\"Use water-rich foods and a consistent routine to make hydration easier to maintain.\"]', 'high', 'Active', 'droplet', '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(2, 'body-fat-reduction', 'TMPL-NUT-001', 'Nutrition', 'Rebalance Body Fat Through Sustainable Habits', 'Body fat percentage is above the personalised wellness range, so nutrition quality and consistency should be prioritised.', '[\"Build meals around protein, fibre, and minimally processed foods.\",\"Use gradual, sustainable calorie control instead of aggressive restriction.\",\"Track changes across several weeks rather than reacting to a single measurement.\"]', 'medium', 'Active', 'apple', '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(3, 'cardio-conditioning', 'TMPL-EXE-001', 'Exercise', 'Improve Cardiovascular Conditioning', 'Visceral fat and overall composition suggest adding or strengthening regular cardio work to support general wellness.', '[\"Accumulate moderate cardio across the week in a way you can sustain consistently.\",\"Choose walking, cycling, rowing, or similar lower-barrier activities if adherence is the main issue.\",\"Keep strength training in place so cardio complements overall body composition goals.\"]', 'medium', 'Active', 'activity', '2026-04-12 08:24:55', '2026-04-12 08:24:55'),
(4, 'muscle-preservation', 'TMPL-EXE-002', 'Exercise', 'Protect Lean Muscle Mass', 'Muscle mass ratio is below the personalised reference point, so resistance training quality and protein consistency should remain central.', '[\"Keep resistance training focused on major muscle groups at least a few times per week.\",\"Progress training gradually instead of changing volume and intensity at the same time.\",\"Distribute protein intake through the day to better support lean mass retention.\"]', 'medium', 'Active', 'trending-up', '2026-04-12 08:24:55', '2026-04-12 08:24:55'),
(5, 'weight-gain-trend', 'TMPL-NUT-002', 'Nutrition', 'Review Your Dietary Intake', 'Your weight has increased noticeably since your last measurement, which may indicate a caloric surplus worth reviewing.', '[\"Review portion sizes and total daily calorie intake relative to your activity level.\",\"Prioritise whole foods and reduce intake of highly processed, calorie-dense options.\",\"Focus on consistent eating patterns rather than making dramatic short-term cuts.\",\"Consider tracking meals for 1\\u20132 weeks to identify key patterns.\"]', 'medium', 'Active', 'trending-up', '2026-04-12 08:24:55', '2026-04-12 08:24:55'),
(6, 'fitness-foundation', 'TMPL-FIT-001', 'Exercise', 'Build a Structured Fitness Foundation', 'Your physical rating indicates a low current fitness level. Starting with consistent, low-intensity exercise is the most effective first step.', '[\"Begin with 20\\u201330 minute sessions of low-intensity cardio 3\\u20134 times per week.\",\"Introduce light resistance exercises targeting major muscle groups twice per week.\",\"Prioritise consistency over intensity \\u2014 showing up regularly matters more than training hard early on.\",\"Progress gradually by adding 5\\u201310% volume or intensity every two weeks.\"]', 'high', 'Active', 'activity', '2026-04-12 08:24:55', '2026-04-12 08:24:55'),
(7, 'bone-health', 'TMPL-NUT-003', 'Nutrition', 'Support Bone Density Through Diet', 'Your bone mass is below the reference level for your body weight. Nutritional support for bone density is beneficial at every age.', '[\"Ensure adequate calcium intake from dairy, leafy greens, or fortified foods.\",\"Maintain sufficient vitamin D levels through sunlight exposure and dietary sources.\",\"Include weight-bearing exercise such as walking, jogging, or resistance training regularly.\",\"Limit excessive sodium and caffeine, which can impair calcium absorption over time.\"]', 'medium', 'Active', 'apple', '2026-04-12 08:24:55', '2026-04-12 08:24:55'),
(8, 'underweight-nutrition', 'TMPL-NUT-004', 'Nutrition', 'Increase Caloric and Nutritional Intake', 'Your BMI is below the general wellness threshold, which may indicate insufficient caloric intake or underlying nutritional needs.', '[\"Increase total daily calorie intake through nutrient-dense foods rather than processed snacks.\",\"Prioritise protein, healthy fats, and complex carbohydrates at every meal.\",\"Eat more frequently \\u2014 4\\u20135 smaller meals per day can help increase overall intake.\",\"Consider consulting a dietitian if healthy weight gain proves difficult over several weeks.\"]', 'high', 'Active', 'apple', '2026-04-12 08:24:55', '2026-04-12 08:24:55'),
(9, 'steady-progress', 'TMPL-REC-001', 'Recovery', 'Maintain Your Current Wellness Habits', 'Your latest body-composition markers are broadly within the personalised wellness ranges used by the system.', '[\"Keep routines consistent before making large changes to training or nutrition.\",\"Continue tracking measurements regularly so future guidance stays relevant.\",\"Use gradual habit adjustments instead of chasing short-term fluctuations.\"]', 'low', 'Active', 'heart', '2026-04-12 08:24:55', '2026-04-12 08:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bCdNf9dFHyacO7IjUvPRUj0usFI4etbeAk7idnvs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlA5dWxWZlBmRXR1OHdTZ2picnRJU0FUUzN0M1RyUTF4ckRVbXhLNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776408517),
('Bt3EaIYFP8jM8JvKrwaifLP7mUmskMLVOuOB88WU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidk5NT0Ntd2tWbVlwVGdSNWZxbGhjSDhWaEVPNkNDUGE0OFlkNTN6eiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776069861),
('e4ljo1HTgkgfKUYPdyx4nDbWUkCmHK11cP6dHsWc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiamlGNnJ4eEk5SzlSNlQwYWlOQXZkTEhMeUduWlVweHlVcFdsWFhaaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib2R5LWNvbXBvc2l0aW9uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776062458),
('EH3QQ4FFfKrXb9RKB53x77G2IVHy3hxGhW9Yjush', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiclRqMUEzRDlacTNFVjF0RTlxazAxNXpwV2Z2cTF1NTYyTFBnaHczRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776341974),
('GmUDjp2l8W8VA8DtHf0l1IUPH7CSZR4thaxosOWR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicFNtRGQzU0lFUDIyWEo5QkJGQ1pxeWJQWGtRWTBEN1dkU3VHd2xJNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTM5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcmVzZXQtcGFzc3dvcmQ/ZW1haWw9bmF0aGFuYWVsZDAxMiU0MGdtYWlsLmNvbSZ0b2tlbj1zdFJZdWd6VWNHeXZoR2tGbEZKOVk1d0M2WlByb1JIVzJqMW5pN01sU0c2UlphajduQ2JJcUVwTXlTbGs2OTBNIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776239084),
('vGB8hVUvqgeySlc3wjTyE9oTQMRsfckKQwZEgr9J', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVU9iTlpTUEdnWnNadTVDdG5VQTM0UG81RXlXRTNqb1VOamZNOXdWdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776012208),
('xSMoZlcwKWQrmaKfk8mI1UXkJvtNJ9gIXygQLBZB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieUNBR3ZTU1BGR0F1d3Q0TWJjdlNSYTY0Qjk1SGV4eWFhekdZT01EMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776261585);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_name`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'smtp_settings', '{\"host\":\"smtp-relay.brevo.com\",\"port\":587,\"username\":\"a7e24e001@smtp-brevo.com\",\"password\":\"eyJpdiI6IllnT0t3ZklhcHhPeGJRRXQ1VE91b2c9PSIsInZhbHVlIjoib3dJaVNFNGVQc2VybDVaOURCVlgzYndlSTJQdXVUWUY5YnA5WldvK1V6QWk2Umg3ZTcwSmt3VHVtbVNHZHZoYmdLK0NvZDFXVGV3V2pQK01LWm15c2VrTG9ORjVkN3Y4d3VWaEFiU1dRU1ZGajdwVDBpdTVVM3JjeWliaFRyZVIiLCJtYWMiOiI3Y2Q4ZjE0YmI0MWFkZGY5OTllZWYzNDhhZDBjMmYzMmQ5MDY4ZjQwMzE5YzY1ZDA5N2Q4NDRlMzkyNzZiYzU4IiwidGFnIjoiIn0=\",\"from\":\"nathanaeldouglas1924@gmail.com\",\"from_name\":\"Smartbodycomposition\",\"encryption\":\"tls\"}', '2026-04-12 08:26:25', '2026-04-12 08:35:56'),
(2, 'admin_notification_settings', '{\"emailOnRegister\":true,\"emailOnGoalAchieved\":true,\"emailOnInactivity\":false,\"weeklyDigest\":false}', '2026-04-12 08:26:25', '2026-04-12 08:26:25'),
(3, 'admin_security_settings', '{\"sessionTimeout\":120,\"maxLoginAttempts\":5,\"maintenanceMode\":false}', '2026-04-12 08:26:25', '2026-04-12 08:26:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `height_cm` double DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `account_status` varchar(255) NOT NULL DEFAULT 'Active',
  `notifications_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `email_alerts_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `weekly_reports_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `measurement_reminders_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `height_cm`, `email_verified_at`, `password`, `age`, `gender`, `role`, `account_status`, `notifications_enabled`, `email_alerts_enabled`, `weekly_reports_enabled`, `measurement_reminders_enabled`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Demo Admin', 'admin.demo@example.com', 172, '2026-04-12 08:24:53', '$2y$12$3rbLUyXchysuthUDOkJjCuuoykfpGpYx5kiOwLezhHSRgWF4qxbce', 34, 'Other', 'admin', 'Active', 1, 1, 1, 1, NULL, '2026-04-12 08:24:53', '2026-04-12 08:24:54'),
(2, 'Hydration Test User', 'hydration.demo@example.com', 164, '2026-04-12 08:24:53', '$2y$12$agd.jEybLTowtac2/Yi1VeiwWu/3hX8kDLuKflVoXbh67MCzZywcq', 26, 'Female', 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-12 08:24:53', '2026-04-12 08:24:53'),
(4, 'Balanced Test User', 'balanced.demo@example.com', 168, '2026-04-12 08:24:54', '$2y$12$RbNbOvHq4SeTONykNZdy1OC.SijwZ6vYxZMKwvvqoq7Ig03XwO3VG', 29, 'Female', 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(5, 'Ahmad Fadhil', 'ahmad.fadhil@example.com', 173, '2026-04-12 08:24:54', '$2y$12$AlwZuMyBsrYJkRmeWSNbTubHAGwdkvOLLBrsAEdM5/Kw1mx2FXC56', 24, 'Male', 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-12 08:24:54', '2026-04-12 08:24:54'),
(7, 'NATHANAEL ANAK CHULIF@DOUGLAS CHULIP', 'nathanaeld012@gmail.com', 173, NULL, '$2y$12$W7jyGOosIIINmclyftqcuuchqKUPouaUOmkrWQwhPGioH.drvyTkS', 25, 'Male', 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-13 00:18:36', '2026-04-13 01:44:52'),
(8, 'matt', 'matt@gmail.com', 232, NULL, '$2y$12$NO2aZjAIKNnLc8DdMLtZH.jTIXSOTHG.hRKxnGavmO8HITEkAzMW6', 24, 'Male', 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-13 00:26:50', '2026-04-13 00:31:46'),
(9, 'nathan', 'user@test.com', NULL, NULL, '$2y$12$ewoQDYAPNTE0W9duLS4TXe8ks3zhx3IwEM4F35yR9TBTdF1e9u6l2', NULL, NULL, 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-13 03:05:15', '2026-04-13 03:05:15'),
(10, 'wew@gmail.com', 'wew@gmail.com', NULL, NULL, '$2y$12$pUT1IK9RdRzJm5YSgTMhUu76YOX2n1hmwT7c1amLBZ.tqwpyrd0Sy', NULL, NULL, 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-13 03:22:36', '2026-04-13 03:22:36'),
(11, 'matt2', 'matt2@gmail.com', 213, NULL, '$2y$12$aFimZotdCkJnH/GOiioy.OtZwkfVwKxdlmVFka99x5kQeT1.NPOw.', 23, 'Male', 'user', 'Active', 1, 1, 1, 1, NULL, '2026-04-15 02:07:28', '2026-04-15 02:08:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `body_compositions`
--
ALTER TABLE `body_compositions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `body_compositions_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goals_user_id_foreign` (`user_id`);

--
-- Indexes for table `health_recommendations`
--
ALTER TABLE `health_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `health_recommendations_user_id_foreign` (`user_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `recommendation_templates`
--
ALTER TABLE `recommendation_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recommendation_templates_template_id_unique` (`template_id`),
  ADD UNIQUE KEY `recommendation_templates_template_code_unique` (`template_code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `body_compositions`
--
ALTER TABLE `body_compositions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `health_recommendations`
--
ALTER TABLE `health_recommendations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `recommendation_templates`
--
ALTER TABLE `recommendation_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `body_compositions`
--
ALTER TABLE `body_compositions`
  ADD CONSTRAINT `body_compositions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `health_recommendations`
--
ALTER TABLE `health_recommendations`
  ADD CONSTRAINT `health_recommendations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
