-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 01:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crypto_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts_tbl`
--

CREATE TABLE `admin_accounts_tbl` (
  `admin_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `role` varchar(100) DEFAULT 'Administrator',
  `reset_password_token` varchar(255) DEFAULT NULL,
  `password_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts_tbl`
--

INSERT INTO `admin_accounts_tbl` (`admin_id`, `first_name`, `middle_name`, `last_name`, `email_address`, `admin_password`, `role`, `reset_password_token`, `password_token_expiry`, `created_at`, `updated_at`) VALUES
(1, 'Colin Maria', 'Castanares', 'Pampango', 'coca.pampango.ui@phinmaed.com', '$2y$10$rW9iqQEg2HM7A6QtO1c/rOs1mLFnjS.PbgGJuQEayVqfgB4tZ6Qhe', 'Administrator', NULL, NULL, '2025-08-13 03:11:38', '2025-08-13 03:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `admin_files_tbl`
--

CREATE TABLE `admin_files_tbl` (
  `file_id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `stored_file_name` varchar(255) NOT NULL,
  `processed_file_name` varchar(255) NOT NULL,
  `file_size` decimal(11,2) NOT NULL,
  `brgy_captain_download` enum('Yes','No') DEFAULT 'Yes',
  `brgy_secretary_download` enum('Yes','No') DEFAULT 'No',
  `brgy_treasurer_download` enum('Yes','No') DEFAULT 'No',
  `brgy_kagawad_download` enum('Yes','No') DEFAULT 'No',
  `sk_chairman_download` enum('Yes','No') DEFAULT 'Yes',
  `sk_kagawad_download` enum('Yes','No') DEFAULT 'No',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_files_tbl`
--

INSERT INTO `admin_files_tbl` (`file_id`, `folder_id`, `file_name`, `stored_file_name`, `processed_file_name`, `file_size`, `brgy_captain_download`, `brgy_secretary_download`, `brgy_treasurer_download`, `brgy_kagawad_download`, `sk_chairman_download`, `sk_kagawad_download`, `uploaded_at`, `modified_at`) VALUES
(10008, 1006, 'Agudines, Marvin M..txt', '68a091a903a47_original_Agudines, Marvin M..txt', '68a091a903a47__Agudines, Marvin M..txt', 0.00, 'Yes', 'Yes', 'No', 'No', 'Yes', 'No', '2025-08-16 14:11:53', '2025-08-16 14:11:53'),
(10009, 1006, 'Example Text.txt', '68a0b84f9df3f_original_Example Text.txt', '68a0b84f9df3f__Example Text.txt', 0.00, 'No', 'No', 'No', 'No', 'Yes', 'No', '2025-08-16 16:56:47', '2025-08-16 16:56:47');

-- --------------------------------------------------------

--
-- Table structure for table `admin_folder_tbl`
--

CREATE TABLE `admin_folder_tbl` (
  `folder_id` int(11) NOT NULL,
  `folder_name` varchar(150) NOT NULL,
  `brgy_captain_access` enum('Yes','No') DEFAULT 'Yes',
  `brgy_secretary_access` enum('Yes','No') DEFAULT 'No',
  `brgy_treasurer_access` enum('Yes','No') DEFAULT 'No',
  `brgy_kagawad_access` enum('Yes','No') DEFAULT 'No',
  `sk_chairman_access` enum('Yes','No') DEFAULT 'No',
  `sk_kagawad_access` enum('Yes','No') DEFAULT 'Yes',
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_folder_tbl`
--

INSERT INTO `admin_folder_tbl` (`folder_id`, `folder_name`, `brgy_captain_access`, `brgy_secretary_access`, `brgy_treasurer_access`, `brgy_kagawad_access`, `sk_chairman_access`, `sk_kagawad_access`, `modified_at`, `created_at`) VALUES
(1005, 'Private Files', 'Yes', 'No', 'Yes', 'No', 'Yes', 'No', '2025-08-16 17:08:46', '2025-08-15 15:11:22'),
(1006, 'Public Files', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-15 15:32:27', '2025-08-15 15:32:27'),
(1009, 'Colin', 'Yes', 'Yes', 'No', 'No', 'Yes', 'No', '2025-08-17 10:35:09', '2025-08-17 10:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `admin_info_tbl`
--

CREATE TABLE `admin_info_tbl` (
  `admin_id` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Others') DEFAULT 'Others',
  `phone_number` varchar(100) NOT NULL,
  `full_address` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_info_tbl`
--

INSERT INTO `admin_info_tbl` (`admin_id`, `profile_picture`, `date_of_birth`, `gender`, `phone_number`, `full_address`, `updated_at`) VALUES
(1, 'image_1_68a1b1313075e.jpg', '2004-05-13', 'Female', '09163500594', 'North San Jose, Molo, Iloilo City', '2025-08-17 10:38:41');

-- --------------------------------------------------------

--
-- Table structure for table `file_sharing_tbl`
--

CREATE TABLE `file_sharing_tbl` (
  `share_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `sender` int(11) DEFAULT NULL,
  `receiver` int(11) DEFAULT NULL,
  `shared_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_sharing_tbl`
--

INSERT INTO `file_sharing_tbl` (`share_id`, `file_id`, `sender`, `receiver`, `shared_at`) VALUES
(1001, 1002, 1011, 1015, '2025-08-16 19:39:20'),
(1002, 1004, 1015, 1011, '2025-08-16 19:53:18'),
(1003, 1005, 1015, 1011, '2025-08-16 20:28:56'),
(1004, 1006, 1011, 1015, '2025-08-16 20:30:44'),
(1005, 1007, 1015, 1011, '2025-08-16 20:35:35'),
(1006, 1008, 1015, 1016, '2025-08-16 20:36:03'),
(1007, 1009, 1015, 1016, '2025-08-16 20:36:53'),
(1008, 1010, 1011, 1016, '2025-08-17 02:54:38'),
(1009, 1011, 1011, 1016, '2025-08-17 03:53:23'),
(1010, 1012, 1011, 1015, '2025-08-17 04:37:47'),
(1011, 1013, 1011, 1015, '2025-08-17 05:07:32'),
(1012, 1016, 1015, 1011, '2025-08-17 06:32:18'),
(1013, 1017, 1011, 1016, '2025-08-17 08:05:46'),
(1014, 1018, 1011, 1015, '2025-08-17 09:54:08'),
(1015, 1019, 1015, 1011, '2025-08-17 10:08:53'),
(1016, 1020, 1011, 1015, '2025-08-17 10:20:19'),
(1017, 1021, 1015, 1011, '2025-08-17 10:46:32');

-- --------------------------------------------------------

--
-- Table structure for table `officials_accounts_tbl`
--

CREATE TABLE `officials_accounts_tbl` (
  `official_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `official_password` varchar(255) NOT NULL,
  `position` enum('Brgy. Captain','Brgy. Secretary','Brgy. Treasurer','Brgy. Kagawad','S.K. Chairman','S.K. Kagawad') NOT NULL,
  `is_online` enum('Yes','No') DEFAULT 'No',
  `reset_password_token` varchar(255) DEFAULT NULL,
  `password_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials_accounts_tbl`
--

INSERT INTO `officials_accounts_tbl` (`official_id`, `first_name`, `middle_name`, `last_name`, `email_address`, `official_password`, `position`, `is_online`, `reset_password_token`, `password_token_expiry`, `created_at`, `updated_at`, `last_login`) VALUES
(1011, 'Marvin', 'Morales', 'Agudines', 'stephmarvin30@gmail.com', '$2y$10$jvWxESEJ3zPOmNfOL7Dc3upiqoFXVM2euSDCEpK.SRNILj/GmvOve', 'Brgy. Captain', 'No', NULL, NULL, '2025-08-15 14:25:54', '2025-08-17 10:21:01', '2025-08-17 10:20:08'),
(1015, 'Colin Maria', 'Castanares', 'Pampango', 'coca.pampango.ui@phinmaed.com', '$2y$10$XwK4Cb14h6CE0lvFsopx7elHr9eb8mHAy/mJTAgqD8EwGeVbyE386', 'Brgy. Secretary', 'Yes', NULL, NULL, '2025-08-16 17:07:59', '2025-08-17 10:01:49', '2025-08-17 10:01:49'),
(1016, 'Joshua', 'Forro', 'Borra', 'bondlycommunity@gmail.com', '$2y$10$SBvEY9.WYCKRvRNnkVJiEOfpTywVnV2olizbyeXZ7KdWuKyfea1hG', 'Brgy. Treasurer', 'No', NULL, NULL, '2025-08-16 19:26:30', '2025-08-17 09:58:52', '2025-08-17 08:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `officials_files_tbl`
--

CREATE TABLE `officials_files_tbl` (
  `file_id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `stored_file_name` varchar(255) NOT NULL,
  `processed_file_name` varchar(255) NOT NULL,
  `file_size` decimal(11,2) NOT NULL,
  `brgy_captain_download` enum('Yes','No') DEFAULT 'Yes',
  `brgy_secretary_download` enum('Yes','No') DEFAULT 'No',
  `brgy_treasurer_download` enum('Yes','No') DEFAULT 'No',
  `brgy_kagawad_download` enum('Yes','No') DEFAULT 'No',
  `sk_chairman_download` enum('Yes','No') DEFAULT 'Yes',
  `sk_kagawad_download` enum('Yes','No') DEFAULT 'No',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uploaded_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials_files_tbl`
--

INSERT INTO `officials_files_tbl` (`file_id`, `folder_id`, `file_name`, `stored_file_name`, `processed_file_name`, `file_size`, `brgy_captain_download`, `brgy_secretary_download`, `brgy_treasurer_download`, `brgy_kagawad_download`, `sk_chairman_download`, `sk_kagawad_download`, `uploaded_at`, `modified_at`, `uploaded_by`) VALUES
(1002, 103, 'AcademiLink.docx', '68a0baa0e4be3_original_AcademiLink.docx', '68a0baa0e4be3__AcademiLink.docx', 0.38, 'Yes', 'Yes', 'No', 'No', 'Yes', 'No', '2025-08-16 17:06:40', '2025-08-16 17:06:40', 1011),
(1004, 109, 'Agudines, Marvin M..txt', '68a0c3c417eca_original_Agudines, Marvin M..txt', '68a0c3c417eca__Agudines, Marvin M..txt', 0.00, 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'Yes', '2025-08-16 17:45:40', '2025-08-16 17:45:40', 1015),
(1005, NULL, 'ITE 367 Activity - FitMyStyle (E-Commerce).docx', '68a0ea085ce57_original_ITE 367 Activity - FitMyStyle (E-Commerce).docx', '68a0ea085ce57__ITE 367 Activity - FitMyStyle (E-Commerce).docx', 0.01, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-16 20:28:56', '2025-08-16 20:28:56', 1015),
(1006, NULL, 'Example Text.txt', '68a0ea7474ef1_original_Example Text.txt', '68a0ea7474ef1__Example Text.txt', 0.00, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-16 20:30:44', '2025-08-16 20:30:44', 1011),
(1007, NULL, 'Pampango, Colin Maria C..txt', '68a0eb97b0c45_original_Pampango, Colin Maria C..txt', '68a0eb97b0c45__Pampango, Colin Maria C..txt', 0.00, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-16 20:35:35', '2025-08-16 20:35:35', 1015),
(1008, NULL, 'LTAX.txt', '68a0ebb3bca9d_original_LTAX.txt', '68a0ebb3bca9d__LTAX.txt', 0.00, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-16 20:36:03', '2025-08-16 20:36:03', 1015),
(1009, NULL, 'ITE 370.txt', '68a0ebe5753a3_original_ITE 370.txt', '68a0ebe5753a3__ITE 370.txt', 0.01, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-16 20:36:53', '2025-08-16 20:36:53', 1015),
(1010, NULL, 'stb_queries.txt', '68a1446e31bee_original_stb_queries.txt', '68a1446e31bee__stb_queries.txt', 0.01, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 02:54:38', '2025-08-17 02:54:38', 1011),
(1011, NULL, 'Agudines, Marvin M..txt', '68a15233d4f7c_original_Agudines, Marvin M..txt', '68a15233d4f7c__Agudines, Marvin M..txt', 0.00, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 03:53:23', '2025-08-17 03:53:23', 1011),
(1012, NULL, 'Agudines, Marvin M..txt', '68a15c9b74bf2_original_Agudines, Marvin M..txt', '68a15c9b74bf2__Agudines, Marvin M..txt', 0.00, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 04:37:47', '2025-08-17 04:37:47', 1011),
(1013, NULL, 'Kishia_Laubenia_2025_2026_1st_Semester_Observation_Summary (1).pdf', '68a16394a7ed9_original_Kishia_Laubenia_2025_2026_1st_Semester_Observation_Summary (1).pdf', '68a16394a7ed9__Kishia_Laubenia_2025_2026_1st_Semester_Observation_Summary (1).pdf', 31.34, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 05:07:32', '2025-08-17 05:07:32', 1011),
(1015, 103, 'Matrix-Work-life-Balance-and-academic-performance-of-BSHM-student-working-in-BPO (1).docx', '68a17399cef36_original_Matrix-Work-life-Balance-and-academic-performance-of-BSHM-student-working-in-BPO (1).docx', '68a17399cef36__Matrix-Work-life-Balance-and-academic-performance-of-BSHM-student-working-in-BPO (1).docx', 0.02, 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'No', '2025-08-17 06:15:53', '2025-08-17 06:15:53', 1011),
(1016, NULL, 'AcademiLink.docx', '68a17772b18b7_original_AcademiLink.docx', '68a17772b18b7__AcademiLink.docx', 0.38, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 06:32:18', '2025-08-17 06:32:18', 1015),
(1017, NULL, 'CERTIFICATE OF PROOFREADING [ARCO].pdf', '68a18d5a10931_original_CERTIFICATE OF PROOFREADING [ARCO].pdf', '68a18d5a10931__CERTIFICATE OF PROOFREADING [ARCO].pdf', 0.23, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 08:05:46', '2025-08-17 08:05:46', 1011),
(1018, NULL, 'Kishia_Laubenia_2025_2026_1st_Semester_Observation_Summary (1) (1).pdf', '68a1a6c0bafb5_original_Kishia_Laubenia_2025_2026_1st_Semester_Observation_Summary (1) (1).pdf', '68a1a6c0bafb5__Kishia_Laubenia_2025_2026_1st_Semester_Observation_Summary (1) (1).pdf', 31.34, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 09:54:08', '2025-08-17 09:54:08', 1011),
(1019, NULL, 'Documentation.docx', '68a1aa354ee88_original_Documentation.docx', '68a1aa354ee88__Documentation.docx', 0.85, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 10:08:53', '2025-08-17 10:08:53', 1015),
(1020, NULL, 'Example Text.txt', '68a1ace3cc598_original_Example Text.txt', '68a1ace3cc598__Example Text.txt', 0.00, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 10:20:19', '2025-08-17 10:20:19', 1011),
(1021, NULL, 'Example Text (2).txt', '68a1b30885eeb_original_Example Text (2).txt', '68a1b30885eeb__Example Text (2).txt', 0.00, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', '2025-08-17 10:46:32', '2025-08-17 10:46:32', 1015);

-- --------------------------------------------------------

--
-- Table structure for table `officials_folder_tbl`
--

CREATE TABLE `officials_folder_tbl` (
  `folder_id` int(11) NOT NULL,
  `folder_name` varchar(150) NOT NULL,
  `brgy_captain_access` enum('Yes','No') DEFAULT 'Yes',
  `brgy_secretary_access` enum('Yes','No') DEFAULT 'Yes',
  `brgy_treasurer_access` enum('Yes','No') DEFAULT 'Yes',
  `brgy_kagawad_access` enum('Yes','No') DEFAULT 'No',
  `sk_chairman_access` enum('Yes','No') DEFAULT 'Yes',
  `sk_kagawad_access` enum('Yes','No') DEFAULT 'No',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials_folder_tbl`
--

INSERT INTO `officials_folder_tbl` (`folder_id`, `folder_name`, `brgy_captain_access`, `brgy_secretary_access`, `brgy_treasurer_access`, `brgy_kagawad_access`, `sk_chairman_access`, `sk_kagawad_access`, `created_by`, `created_at`, `modified_at`) VALUES
(103, 'High Rank Files', 'Yes', 'No', 'Yes', 'No', 'Yes', 'No', 1011, '2025-08-16 16:47:17', '2025-08-16 17:07:04'),
(108, 'Secretary Files', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 1015, '2025-08-16 17:32:51', '2025-08-17 10:18:07'),
(109, 'Secretary Public', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 1015, '2025-08-16 17:34:47', '2025-08-17 10:18:14'),
(110, 'Blotter Reports', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'No', 1011, '2025-08-16 17:37:37', '2025-08-16 17:37:37');

-- --------------------------------------------------------

--
-- Table structure for table `officials_info_tbl`
--

CREATE TABLE `officials_info_tbl` (
  `official_id` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Others') DEFAULT 'Others',
  `phone_number` varchar(100) NOT NULL,
  `full_address` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials_info_tbl`
--

INSERT INTO `officials_info_tbl` (`official_id`, `profile_picture`, `date_of_birth`, `gender`, `phone_number`, `full_address`, `updated_at`) VALUES
(1011, 'image_1011_68a0a4e2144f9.jpg', '2000-04-09', 'Male', '09927415812', 'J.C. Zulueta Street, Oton, Iloilo', '2025-08-16 15:36:20'),
(1015, 'image_1015_68a1653501a2e.jpg', '2004-05-13', 'Female', '09163500594', 'Molo, Iloilo City', '2025-08-17 05:14:29'),
(1016, NULL, '2004-01-25', 'Male', '09123456789', 'Janiuay, Iloilo', '2025-08-16 19:26:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts_tbl`
--
ALTER TABLE `admin_accounts_tbl`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `admin_files_tbl`
--
ALTER TABLE `admin_files_tbl`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Indexes for table `admin_folder_tbl`
--
ALTER TABLE `admin_folder_tbl`
  ADD PRIMARY KEY (`folder_id`);

--
-- Indexes for table `admin_info_tbl`
--
ALTER TABLE `admin_info_tbl`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `file_sharing_tbl`
--
ALTER TABLE `file_sharing_tbl`
  ADD PRIMARY KEY (`share_id`),
  ADD KEY `sender` (`sender`),
  ADD KEY `receiver` (`receiver`),
  ADD KEY `file_sharing_tbl_ibfk_3` (`file_id`);

--
-- Indexes for table `officials_accounts_tbl`
--
ALTER TABLE `officials_accounts_tbl`
  ADD PRIMARY KEY (`official_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `officials_files_tbl`
--
ALTER TABLE `officials_files_tbl`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `folder_id` (`folder_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `officials_folder_tbl`
--
ALTER TABLE `officials_folder_tbl`
  ADD PRIMARY KEY (`folder_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `officials_info_tbl`
--
ALTER TABLE `officials_info_tbl`
  ADD PRIMARY KEY (`official_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts_tbl`
--
ALTER TABLE `admin_accounts_tbl`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_files_tbl`
--
ALTER TABLE `admin_files_tbl`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10010;

--
-- AUTO_INCREMENT for table `admin_folder_tbl`
--
ALTER TABLE `admin_folder_tbl`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1010;

--
-- AUTO_INCREMENT for table `file_sharing_tbl`
--
ALTER TABLE `file_sharing_tbl`
  MODIFY `share_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1018;

--
-- AUTO_INCREMENT for table `officials_accounts_tbl`
--
ALTER TABLE `officials_accounts_tbl`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1017;

--
-- AUTO_INCREMENT for table `officials_files_tbl`
--
ALTER TABLE `officials_files_tbl`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1022;

--
-- AUTO_INCREMENT for table `officials_folder_tbl`
--
ALTER TABLE `officials_folder_tbl`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_files_tbl`
--
ALTER TABLE `admin_files_tbl`
  ADD CONSTRAINT `admin_files_tbl_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `admin_folder_tbl` (`folder_id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_info_tbl`
--
ALTER TABLE `admin_info_tbl`
  ADD CONSTRAINT `admin_info_tbl_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_accounts_tbl` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `file_sharing_tbl`
--
ALTER TABLE `file_sharing_tbl`
  ADD CONSTRAINT `file_sharing_tbl_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `officials_accounts_tbl` (`official_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `file_sharing_tbl_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `officials_accounts_tbl` (`official_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `file_sharing_tbl_ibfk_3` FOREIGN KEY (`file_id`) REFERENCES `officials_files_tbl` (`file_id`) ON DELETE CASCADE;

--
-- Constraints for table `officials_files_tbl`
--
ALTER TABLE `officials_files_tbl`
  ADD CONSTRAINT `officials_files_tbl_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `officials_folder_tbl` (`folder_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `officials_files_tbl_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `officials_accounts_tbl` (`official_id`) ON DELETE SET NULL;

--
-- Constraints for table `officials_folder_tbl`
--
ALTER TABLE `officials_folder_tbl`
  ADD CONSTRAINT `officials_folder_tbl_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `officials_accounts_tbl` (`official_id`) ON DELETE SET NULL;

--
-- Constraints for table `officials_info_tbl`
--
ALTER TABLE `officials_info_tbl`
  ADD CONSTRAINT `officials_info_tbl_ibfk_1` FOREIGN KEY (`official_id`) REFERENCES `officials_accounts_tbl` (`official_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
