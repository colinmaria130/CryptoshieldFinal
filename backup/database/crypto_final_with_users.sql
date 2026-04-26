-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 06:07 PM
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
(1019, 'Teresita', '', 'Abong', 'teresita@gmail.com', '$2y$10$lGNzXElVDofrtayV.secbe.Q7TNHjBKh7NPBYQCgayce47FFKlBeC', 'Brgy. Captain', 'No', NULL, NULL, '2025-08-17 16:02:51', '2025-08-17 16:02:51', NULL),
(1020, 'Mary Rose', '', 'Tacorda', 'maryrose@gmail.com', '$2y$10$OpYftc838G3j6Zr9.N0SGu.GVRiwT2EpH/b3jfQ/dMhz0RDp.t3XG', 'Brgy. Treasurer', 'No', NULL, NULL, '2025-08-17 16:04:10', '2025-08-17 16:04:10', NULL),
(1021, 'Ana Liza', '', 'Ladeza', 'analiza@gmail.com', '$2y$10$EY2XhkboamlIb3lTGs/qE.Nlr37R0I.pPEHOlNLniomKOm.syZrcW', 'Brgy. Secretary', 'No', NULL, NULL, '2025-08-17 16:04:56', '2025-08-17 16:04:56', NULL),
(1022, 'Kenneth John', '', 'Pampango', 'kenneth@gmail.com', '$2y$10$.vmIM1nMrHD3wfZI.tHHmevByjZV6p2amN7kG6eerURPVgvOAR2bO', 'S.K. Chairman', 'No', NULL, NULL, '2025-08-17 16:06:09', '2025-08-17 16:06:09', NULL),
(1023, 'Frincess Joy', '', 'Patenio', 'frincess@gmail.com', '$2y$10$sqeUcEy/v5ea/vhMOX8gtuZRntgiCuB1v.X8O9hqXBzQS8CvR1Zy2', 'Brgy. Kagawad', 'No', NULL, NULL, '2025-08-17 16:06:55', '2025-08-17 16:06:55', NULL),
(1024, 'Melesa', '', 'Gabayeron', 'melesa@gmail.com', '$2y$10$DBWKCSrY0NIN3qjk/rQlu.zjTqiFEVTTGaHkVS1zuCydQ64mJpn3O', 'S.K. Kagawad', 'No', NULL, NULL, '2025-08-17 16:07:18', '2025-08-17 16:07:18', NULL);

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
(1019, NULL, '1960-01-01', 'Female', '09123456789', 'Molo, Iloilo City', '2025-08-17 16:02:51'),
(1020, NULL, '1990-01-01', 'Female', '09123456789', 'Molo, Iloilo City', '2025-08-17 16:04:10'),
(1021, NULL, '2000-01-01', 'Female', '09123456789', 'Molo, Iloilo City', '2025-08-17 16:04:56'),
(1022, NULL, '2002-01-01', 'Male', '09123456789', 'Molo, Iloilo City', '2025-08-17 16:06:09'),
(1023, NULL, '2004-01-01', 'Female', '09123456789', 'Lapaz, Iloilo City', '2025-08-17 16:06:55'),
(1024, NULL, '2004-01-01', 'Female', '09123456789', 'Iloilo City', '2025-08-17 16:07:18');

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
  MODIFY `share_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1019;

--
-- AUTO_INCREMENT for table `officials_accounts_tbl`
--
ALTER TABLE `officials_accounts_tbl`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1025;

--
-- AUTO_INCREMENT for table `officials_files_tbl`
--
ALTER TABLE `officials_files_tbl`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1033;

--
-- AUTO_INCREMENT for table `officials_folder_tbl`
--
ALTER TABLE `officials_folder_tbl`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

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
  ADD CONSTRAINT `officials_files_tbl_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `officials_folder_tbl` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
