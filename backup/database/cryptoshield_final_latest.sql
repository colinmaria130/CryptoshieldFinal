-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 07:54 PM
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
-- Database: `cryptoshield_final`
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
(1, 'Colin Maria', 'Castanares', 'Pampango', 'coca.pampango.ui@phinmaed.com', '$2y$10$FtNKEgdWv4mTC5LmXl9nkeQHeyhByrcRNjQaCkrYRtWa8FSeu4rrq', 'Administrator', NULL, NULL, '2025-11-14 11:11:38', '2025-11-14 11:11:38');

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
  `is_archive` enum('Yes','No') DEFAULT 'No',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `is_archive` enum('Yes','No') DEFAULT 'No',
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1035, 1093, 1025, 1027, '2026-02-25 07:36:14'),
(1036, 1094, 1025, 1027, '2025-12-12 13:14:24'),
(1037, 1095, 1025, 1027, '2026-03-25 09:09:33');

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
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials_accounts_tbl`
--

INSERT INTO `officials_accounts_tbl` (`official_id`, `first_name`, `middle_name`, `last_name`, `email_address`, `official_password`, `position`, `is_online`, `reset_password_token`, `password_token_expiry`, `created_at`, `updated_at`, `last_login`) VALUES
(1025, 'Kenneth John', 'Castanares', 'Pampango', 'kpampango@gmail.com', '$2y$10$xBfPk5SjBjYmCCogUiCzAuZveCxKCDhtKnxS/We/z51qd7.MNBQfC', 'S.K. Chairman', 'No', NULL, NULL, '2025-11-14 11:11:19', '2026-04-26 12:09:45', '2026-04-26 12:09:38'),
(1026, 'Ana Liza', '', 'Ladeza', 'analiza.ladeza10@gmail.com', '$2y$10$SkS9tTjL75w8KEJnKULOv.3a5c9tdYgmA4MrR.uitLlB0GdnRlW.2', 'Brgy. Secretary', 'Yes', NULL, NULL, '2025-11-14 11:16:38', '2026-04-26 12:10:02', '2026-04-26 12:10:02'),
(1027, 'Teresita', '', 'Abong', 'tr.abong@gmail.com', '$2y$10$SznvOyxx8dXfq/dwlk4QYOU2P/K9W4Hr/HHHgsAHdJoKiH87cWrFy', 'Brgy. Captain', 'No', NULL, NULL, '2025-11-14 11:05:26', '2026-04-13 05:11:13', '2026-04-13 05:11:10');

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
  `is_archive` enum('Yes','No') DEFAULT 'No',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uploaded_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials_files_tbl`
--

INSERT INTO `officials_files_tbl` (`file_id`, `folder_id`, `file_name`, `stored_file_name`, `processed_file_name`, `file_size`, `brgy_captain_download`, `brgy_secretary_download`, `brgy_treasurer_download`, `brgy_kagawad_download`, `sk_chairman_download`, `sk_kagawad_download`, `is_archive`, `uploaded_at`, `modified_at`, `uploaded_by`) VALUES
(1075, 133, 'august.docx', '69d7d6d607e84_original_august.docx', '69d7d6d607e84_processed_august.docx', 0.11, 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2025-12-20 13:24:54', '2026-04-26 12:11:32', 1025),
(1076, 133, 'BASKET.docx', '69d7d6dd9e18c_original_BASKET.docx', '69d7d6dd9e18c_processed_BASKET.docx', 0.09, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2025-12-08 04:31:05', '2025-12-08 04:31:05', 1025),
(1077, 133, 'CERTIFICATE OF ASSUMPTION TO DUTY.docx', '69d7d6feed905_original_CERTIFICATE OF ASSUMPTION TO DUTY.docx', '69d7d6feed905_processed_CERTIFICATE OF ASSUMPTION TO DUTY.docx', 0.12, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2026-02-12 13:19:12', '2026-02-12 13:19:12', 1025),
(1078, 133, 'Date.docx', '69d7d70620704_original_Date.docx', '69d7d70620704_processed_Date.docx', 0.11, 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2026-02-17 04:02:46', '2026-04-26 12:08:22', 1025),
(1079, 133, 'PERMIT.docx', '69d7d70d122ac_original_PERMIT.docx', '69d7d70d122ac_processed_PERMIT.docx', 1.03, 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2026-01-09 06:40:06', '2026-04-26 12:08:55', 1025),
(1080, 133, 'PROJECT DESIGN.docx', '69d7d717cb303_original_PROJECT DESIGN.docx', '69d7d717cb303_processed_PROJECT DESIGN.docx', 0.17, 'Yes', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2026-03-25 07:07:08', '2026-04-26 12:06:20', 1025),
(1081, 133, 'PROJECT.docx', '69d7d71f4c95f_original_PROJECT.docx', '69d7d71f4c95f_processed_PROJECT.docx', 4.76, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2025-11-21 13:03:13', '2025-11-21 13:03:13', 1025),
(1082, 133, 'REPUBLIC OF THE PHILIPPINES.docx', '69d7d726a7c05_original_REPUBLIC OF THE PHILIPPINES.docx', '69d7d726a7c05_processed_REPUBLIC OF THE PHILIPPINES.docx', 0.17, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2025-11-21 12:42:10', '2025-11-21 12:42:10', 1025),
(1083, 134, 'ABYIP MIN & RESO 2025.docx', '69d7d9abf00b0_original_ABYIP MIN & RESO 2025.docx', '69d7d9abf00b0_processed_ABYIP MIN & RESO 2025.docx', 0.13, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2026-03-25 09:06:03', '2026-03-25 09:06:03', 1025),
(1084, 134, 'ABYIP.docx', '69d7d9b475101_original_ABYIP.docx', '69d7d9b475101_processed_ABYIP.docx', 0.16, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2026-03-25 09:07:14', '2026-03-25 09:07:14', 1025),
(1085, 134, 'ABYIP-2.docx', '69d7d9baed1aa_original_ABYIP-2.docx', '69d7d9baed1aa_processed_ABYIP-2.docx', 0.16, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2026-03-25 09:09:23', '2026-03-25 09:09:23', 1025),
(1086, 134, 'ANNUAL BUDGET(SK MANSAYA) (2).docx', '69d7d9c176119_original_ANNUAL BUDGET(SK MANSAYA) (2).docx', '69d7d9c176119_processed_ANNUAL BUDGET(SK MANSAYA) (2).docx', 0.13, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2026-03-25 10:35:36', '2026-03-25 10:35:36', 1025),
(1087, 134, 'ANNUAL.docx', '69d7d9c893567_original_ANNUAL.docx', '69d7d9c893567_processed_ANNUAL.docx', 0.11, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2025-12-02 05:56:22', '2025-12-02 05:56:22', 1025),
(1088, 134, 'CBYDP.docx', '69d7d9d3d1305_original_CBYDP.docx', '69d7d9d3d1305_processed_CBYDP.docx', 0.16, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2025-12-17 09:20:43', '2025-12-17 09:20:43', 1025),
(1089, 134, 'CHANGE-SIGNA.docx', '69d7d9df0dfeb_original_CHANGE-SIGNA.docx', '69d7d9df0dfeb_processed_CHANGE-SIGNA.docx', 0.13, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2025-12-17 09:22:20', '2025-12-17 09:22:20', 1025),
(1090, 134, 'Copy of SKC.xlsx', '69d7d9e841043_original_Copy of SKC.xlsx', '69d7d9e841043_processed_Copy of SKC.xlsx', 0.02, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2025-12-12 07:26:04', '2025-12-12 07:26:04', 1025),
(1091, 134, 'PAYROLL.pdf', '69d7d9f30a738_original_PAYROLL.pdf', '69d7d9f30a738_processed_PAYROLL.pdf', 0.13, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2025-12-15 06:25:15', '2025-12-15 06:25:15', 1025),
(1092, 134, 'PD 2025.docx', '69d7d9faaaaeb_original_PD 2025.docx', '69d7d9faaaaeb_processed_PD 2025.docx', 0.14, 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', '2026-03-25 11:01:06', '2026-03-25 11:01:06', 1025),
(1093, NULL, '6 SAMPLE ABYIP with PROJECT DESIGNS.docx', '69d7dc133a68e_original_6 SAMPLE ABYIP with PROJECT DESIGNS.docx', '69d7dc133a68e__6 SAMPLE ABYIP with PROJECT DESIGNS.docx', 0.23, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2026-02-25 07:36:14', '2026-02-25 07:36:14', 1025),
(1094, NULL, 'CERTIFICATE OF ASSUMPTION TO DUTY.docx', '69d7dc18e293b_original_CERTIFICATE OF ASSUMPTION TO DUTY.docx', '69d7dc18e293b__CERTIFICATE OF ASSUMPTION TO DUTY.docx', 0.12, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2025-12-12 13:14:24', '2025-12-12 13:14:24', 1025),
(1095, NULL, 'PROJECT DESIGN.docx', '69d7dc1f40af1_original_PROJECT DESIGN.docx', '69d7dc1f40af1__PROJECT DESIGN.docx', 0.17, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', '2026-03-25 09:09:33', '2026-03-25 09:09:33', 1025);

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
  `is_archive` enum('Yes','No') DEFAULT 'No',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials_folder_tbl`
--

INSERT INTO `officials_folder_tbl` (`folder_id`, `folder_name`, `brgy_captain_access`, `brgy_secretary_access`, `brgy_treasurer_access`, `brgy_kagawad_access`, `sk_chairman_access`, `sk_kagawad_access`, `is_archive`, `created_by`, `created_at`, `modified_at`) VALUES
(133, 'S.K. Files - 1', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 1025, '2025-11-18 07:22:02', '2025-11-18 07:22:02'),
(134, 'S.K. Files - 2', 'Yes', 'No', 'No', 'No', 'Yes', 'Yes', 'No', 1025, '2025-12-01 05:26:11', '2025-12-01 05:26:11');

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
(1025, NULL, '2002-09-10', 'Male', '09637658406', 'Brgy. North San Jose, Molo, Iloilo City', '2026-04-09 16:00:05'),
(1026, NULL, '1974-10-26', 'Female', '09123456789', 'Brgy. North San Jose, Molo, Iloilo City', '2026-04-09 15:55:19'),
(1027, NULL, '1975-10-22', 'Female', '09123456789', 'Brgy. North San Jose, Molo, Iloilo City', '2026-04-09 17:11:57');

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
  ADD KEY `admin_files_tbl_ibfk_1` (`folder_id`);

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
  ADD KEY `uploaded_by` (`uploaded_by`),
  ADD KEY `officials_files_tbl_ibfk_1` (`folder_id`);

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
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10024;

--
-- AUTO_INCREMENT for table `admin_folder_tbl`
--
ALTER TABLE `admin_folder_tbl`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1020;

--
-- AUTO_INCREMENT for table `file_sharing_tbl`
--
ALTER TABLE `file_sharing_tbl`
  MODIFY `share_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1038;

--
-- AUTO_INCREMENT for table `officials_accounts_tbl`
--
ALTER TABLE `officials_accounts_tbl`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1028;

--
-- AUTO_INCREMENT for table `officials_files_tbl`
--
ALTER TABLE `officials_files_tbl`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1096;

--
-- AUTO_INCREMENT for table `officials_folder_tbl`
--
ALTER TABLE `officials_folder_tbl`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_files_tbl`
--
ALTER TABLE `admin_files_tbl`
  ADD CONSTRAINT `admin_files_tbl_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `admin_folder_tbl` (`folder_id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `officials_files_tbl_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `officials_folder_tbl` (`folder_id`) ON DELETE SET NULL ON UPDATE CASCADE,
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
