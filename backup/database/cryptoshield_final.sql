-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 05:02 PM
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
-- Table structure for table `user_accounts_tbl`
--

CREATE TABLE `user_accounts_tbl` (
  `user_id` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `verified_account` enum('Yes','No') DEFAULT 'No',
  `reset_password_token` varchar(255) DEFAULT NULL,
  `password_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_accounts_tbl`
--

INSERT INTO `user_accounts_tbl` (`user_id`, `first_name`, `middle_name`, `last_name`, `email_address`, `user_password`, `verification_token`, `verified_account`, `reset_password_token`, `password_token_expiry`, `created_at`, `updated_at`) VALUES
('user@080406013327', 'Colin Maria', 'Castanares', 'Pampango', 'coca.pampango.ui@phinmaed.com', '$2y$10$csjzQliAP0ekJJxOafHLHuDjb74RD5Yl7Z6qE8EiVGM3sy1OMEa.y', NULL, 'Yes', NULL, NULL, '2025-08-02 14:47:07', '2025-08-02 14:50:36'),
('user@602797019682', 'Marvin', 'Morales', 'Agudines', 'stephmarvin30@gmail.com', '$2y$10$YwfNN2KIHcRfINw8ELvu3uFypInC3/lKKCV5ixRpIW2U8xjvYWyHy', NULL, 'Yes', NULL, NULL, '2025-08-02 13:39:13', '2025-08-02 17:52:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_info_tbl`
--

CREATE TABLE `user_info_tbl` (
  `tbl_row_id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Others') DEFAULT 'Others',
  `phone_number` varchar(100) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_info_tbl`
--

INSERT INTO `user_info_tbl` (`tbl_row_id`, `user_id`, `profile_picture`, `date_of_birth`, `gender`, `phone_number`, `user_address`, `updated_at`) VALUES
(6, 'user@602797019682', 'image_user@602797019682_688e4f46117f1.jpg', '2000-04-09', 'Male', '09927415812', 'J.C. Zulueta Street, Oton, Iloilo', '2025-08-02 17:52:08'),
(7, 'user@080406013327', '', '2004-05-13', 'Female', '09123456789', 'San Jose, Molo, Iloilo City', '2025-08-02 14:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs_tbl`
--

CREATE TABLE `user_logs_tbl` (
  `log_id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `algorithm_type` enum('AES','RSA') DEFAULT 'AES',
  `used_method` enum('Encrypt','Decrypt') DEFAULT 'Encrypt',
  `original_text` text NOT NULL,
  `output` text NOT NULL,
  `processed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs_tbl`
--

INSERT INTO `user_logs_tbl` (`log_id`, `user_id`, `algorithm_type`, `used_method`, `original_text`, `output`, `processed_at`) VALUES
(1001, 'user@602797019682', 'AES', 'Encrypt', 'Marvin', 'fDcQjhgCsEuN4i18hc9/TTlwbThJTUZ0ZldRVFpKWFYyZUNtQ2c9PQ==', '2025-08-02 16:42:54'),
(1002, 'user@602797019682', 'AES', 'Encrypt', 'Marvin', 'aTtpGXCl03NnRXIFWKhYz3pnTkdjeVVsbjhBRTRpZVJLUjdtQmc9PQ==', '2025-08-02 16:48:41'),
(1003, 'user@602797019682', 'AES', 'Decrypt', 'aTtpGXCl03NnRXIFWKhYz3pnTkdjeVVsbjhBRTRpZVJLUjdtQmc9PQ==', 'Marvin', '2025-08-02 16:48:44'),
(1004, 'user@602797019682', 'RSA', 'Encrypt', 'Colin', 'kXPzJv5gs5TCKz+CindbxA+s24pWuFwom+OB1Rkb7F9jrMpitJIL77orsfW2LSGiRCkqRvj8h0gBsaoRB91Nnb+18o9AtHMG1F8a5WmAUxf6uyJ+W0HizkgBN0zNqPLviiijguBG8f5U6Kh+zBQUJYMseIaoy1Z6maYQU6EkKcvG++DMm+3XyRsAxUZphWbgBk65cNYGJpT90TUi15SZTr5zgZDMHxqwvSy4IDpfX0ddLLAMoaJwjr9XdPAkiD9zwRmtDtXCaEekz5y3to9wFaPimFTLG6Afudx59PPyan4m6/qfkWea0TkDJawPxydt8XtASNO3qpYYbOcidT7Bcg==', '2025-08-02 16:48:49'),
(1005, 'user@602797019682', 'RSA', 'Decrypt', 'kXPzJv5gs5TCKz+CindbxA+s24pWuFwom+OB1Rkb7F9jrMpitJIL77orsfW2LSGiRCkqRvj8h0gBsaoRB91Nnb+18o9AtHMG1F8a5WmAUxf6uyJ+W0HizkgBN0zNqPLviiijguBG8f5U6Kh+zBQUJYMseIaoy1Z6maYQU6EkKcvG++DMm+3XyRsAxUZphWbgBk65cNYGJpT90TUi15SZTr5zgZDMHxqwvSy4IDpfX0ddLLAMoaJwjr9XdPAkiD9zwRmtDtXCaEekz5y3to9wFaPimFTLG6Afudx59PPyan4m6/qfkWea0TkDJawPxydt8XtASNO3qpYYbOcidT7Bcg==', 'Colin', '2025-08-02 16:48:52'),
(1006, 'user@602797019682', 'AES', 'Encrypt', 'I love you.', 'PVmh/WALP6ZUIWY33boteHJwSFdpbmNqUlBuUE5DR3hHQjJoMWc9PQ==', '2025-08-03 02:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_uploaded_files`
--

CREATE TABLE `user_uploaded_files` (
  `tbl_row_id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_original_name` varchar(255) NOT NULL,
  `stored_processed_name` varchar(255) NOT NULL,
  `algorithm_type` enum('AES') DEFAULT 'AES',
  `used_method` enum('Encrypt','Decrypt') DEFAULT 'Encrypt',
  `processed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_uploaded_files`
--

INSERT INTO `user_uploaded_files` (`tbl_row_id`, `user_id`, `original_name`, `stored_original_name`, `stored_processed_name`, `algorithm_type`, `used_method`, `processed_at`) VALUES
(1003, 'user@602797019682', 'ITE 367 Activity - FitMyStyle (E-Commerce).docx', '688e60ec4a1fa_original_ITE 367 Activity - FitMyStyle (E-Commerce).docx', '688e60ec4a1fa_Encrypt_ITE 367 Activity - FitMyStyle (E-Commerce).docx', 'AES', 'Encrypt', '2025-08-02 19:03:08'),
(1004, 'user@602797019682', 'Example Text.txt', '688e624cb0f00_original_Example Text.txt', '688e624cb0f00_Encrypt_Example Text.txt', 'AES', 'Encrypt', '2025-08-02 19:09:00'),
(1005, 'user@602797019682', 'Processed_File_Example Text.txt', '688e625d2b528_original_Processed_File_Example Text.txt', '688e625d2b528_Decrypt_Processed_File_Example Text.txt', 'AES', 'Decrypt', '2025-08-02 19:09:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_accounts_tbl`
--
ALTER TABLE `user_accounts_tbl`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `user_info_tbl`
--
ALTER TABLE `user_info_tbl`
  ADD PRIMARY KEY (`tbl_row_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_logs_tbl`
--
ALTER TABLE `user_logs_tbl`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_uploaded_files`
--
ALTER TABLE `user_uploaded_files`
  ADD PRIMARY KEY (`tbl_row_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_info_tbl`
--
ALTER TABLE `user_info_tbl`
  MODIFY `tbl_row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_logs_tbl`
--
ALTER TABLE `user_logs_tbl`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT for table `user_uploaded_files`
--
ALTER TABLE `user_uploaded_files`
  MODIFY `tbl_row_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1006;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_info_tbl`
--
ALTER TABLE `user_info_tbl`
  ADD CONSTRAINT `user_info_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_accounts_tbl` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_logs_tbl`
--
ALTER TABLE `user_logs_tbl`
  ADD CONSTRAINT `user_logs_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_accounts_tbl` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_uploaded_files`
--
ALTER TABLE `user_uploaded_files`
  ADD CONSTRAINT `user_uploaded_files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_accounts_tbl` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
