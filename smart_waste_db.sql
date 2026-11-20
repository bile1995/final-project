-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2026 at 01:38 PM
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
-- Database: `smart_waste_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assigned_bins`
--

CREATE TABLE `assigned_bins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_bins`
--

INSERT INTO `assigned_bins` (`id`, `user_id`, `bin_id`) VALUES
(3, 16, 21),
(4, 16, 22),
(6, 13, 20),
(8, 18, 23),
(12, 2, 24),
(14, 2, 19),
(15, 2, 2),
(17, 2, 26);

-- --------------------------------------------------------

--
-- Table structure for table `bins`
--

CREATE TABLE `bins` (
  `bin_id` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `waste_type` varchar(50) DEFAULT NULL,
  `capacity` double DEFAULT NULL,
  `current_level` double DEFAULT 0,
  `recyclability` varchar(50) DEFAULT NULL,
  `threshold` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bins`
--

INSERT INTO `bins` (`bin_id`, `location`, `waste_type`, `capacity`, `current_level`, `recyclability`, `threshold`) VALUES
(1, 'gerji', 'plastic', 100, 91, 'recyclable', 90),
(2, 'house: 02', 'metal', 100, 18.88671875, 'recyclable', 90),
(4, '2', 'organic', 100, 0, 'non-recyclable', 95),
(7, 'Apartment 20 - Unit 20', 'plastic', 100, 0, 'recyclable', 90),
(8, 'Apartment 19 - Unit 19', 'organic', 100, 0, 'non-recyclable', 90),
(9, 'Apartment 18 - Unit 18', 'plastic', 100, 0, 'recyclable', 90),
(10, 'Apartment 17 - Unit 17', 'organic', 100, 0, 'non-recyclable', 90),
(11, 'Apartment 16 - Unit 16', 'organic', 100, 0, 'non-recyclable', 90),
(12, 'Apartment 15 - Unit 15', 'organic', 100, 0, 'non-recyclable', 90),
(14, 'Apartment 21 - Unit 25', 'organic', 100, 0, 'non-recyclable', 90),
(15, 'Apartment 19 - Unit 19', 'mixed', 100, 0, 'non-recyclable', 90),
(16, 'Apartment 18 - Unit 18', 'hazardous', 100, 0, 'non-recyclable', 90),
(17, 'Apartment 17 - Unit 17', 'organic', 100, 0, 'non-recyclable', 90),
(18, 'Apartment 19 - Unit 19', 'organic', 90, 0, 'non-recyclable', 80),
(19, 'Apartment 21 - Unit 21', 'organic', 90, 60, 'non-recyclable', 80),
(20, 'Apartment 20 - Unit 20', 'organic', 90, 0, 'non-recyclable', 80),
(21, 'Apartment 16 - Unit 16', 'organic', 90, 0, 'non-recyclable', 80),
(22, 'Apartment 16 - Unit 16', 'organic', 90, 0, 'non-recyclable', 80),
(23, 'Apartment 12 - Unit 12', 'mixed', 100, 0, 'non-recyclable', 90),
(24, '02', 'organic', 100, 100, 'non-recyclable', 90),
(26, 'Apartment 10 - Unit 10', 'metal', 100, 0, 'recyclable', 90);

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `bin_id` int(11) DEFAULT NULL,
  `collection_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed') DEFAULT 'pending',
  `completion_time` datetime DEFAULT NULL,
  `emptied_percent` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`collection_id`, `collector_id`, `bin_id`, `collection_time`, `status`, `completion_time`, `emptied_percent`) VALUES
(3, 3, 1, '2025-07-02 11:40:34', 'completed', '2025-07-15 13:20:18', 100),
(4, 3, 1, '2025-07-02 12:00:15', 'completed', '2025-07-15 13:20:18', 100),
(5, 3, 1, '2025-07-03 20:33:00', 'completed', '2025-07-15 13:20:18', 100),
(6, 3, 22, '2025-07-07 23:42:30', 'completed', NULL, 100),
(7, 3, 2, '2025-07-14 11:34:19', 'completed', '2025-07-18 11:47:44', 50),
(8, 3, 2, '2025-07-14 11:36:24', 'completed', '2025-07-18 11:47:44', 50),
(9, 3, 2, '2025-07-14 11:37:49', 'completed', '2025-07-18 11:47:44', 50),
(10, 3, 23, '2025-07-14 11:38:08', 'completed', '2025-07-14 14:38:08', 100),
(11, 3, 2, '2025-07-14 11:38:52', 'completed', '2025-07-18 11:47:44', 50),
(13, 3, 1, '2025-07-15 10:19:03', 'completed', '2025-07-15 13:20:18', 100),
(14, 3, 1, '2025-07-15 10:20:31', 'completed', '2025-07-15 13:20:31', 100),
(15, 3, 2, '2025-07-15 10:37:22', 'completed', '2025-07-18 11:47:44', 50),
(16, 3, 2, '2025-07-15 10:37:44', 'completed', '2025-07-18 11:47:44', 50),
(17, 3, 2, '2025-07-15 10:38:45', 'completed', '2025-07-18 11:47:44', 50),
(18, 3, 26, '2025-07-15 15:04:03', 'completed', '2025-07-15 22:36:10', 100),
(19, 3, 26, '2025-07-15 19:34:37', 'completed', '2025-07-15 22:36:10', 100),
(20, 3, 2, '2025-07-18 08:45:10', 'completed', '2025-07-18 11:47:44', 50),
(21, 3, 2, '2025-07-18 08:47:00', 'completed', '2025-07-18 11:47:44', 50),
(22, 9, 24, '2025-07-18 08:56:17', 'pending', NULL, 100),
(23, 3, 19, '2025-07-24 12:59:41', 'completed', '2025-07-24 15:59:41', 25);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `timestamp`, `is_read`) VALUES
(1, 2, 'Bin ID 1 is almost full.', '2025-07-02 11:11:35', 1),
(2, 2, '✅ Bin ID 1 has been emptied.', '2025-07-02 11:13:44', 1),
(3, 2, 'Bin ID 1 is full.', '2025-07-02 11:39:13', 1),
(4, 3, '✅ Bin ID 1 has been emptied.', '2025-07-02 11:40:34', 1),
(5, 8, 'Bin ID 1 is almost full.', '2025-07-05 22:05:59', 0),
(6, 8, 'Bin ID 1 is almost full.', '2025-07-05 22:18:28', 0),
(7, 2, '⚠️ Bin #19 at \'Apartment 21 - Unit 21\' is almost full (80kg).', '2025-07-15 14:20:28', 1),
(8, 2, '⚠️ Bin #1 at \'gerji\' is almost full (91kg).', '2025-07-15 14:25:07', 1),
(9, 2, '⚠️ Bin #26 at \'Apartment 10 - Unit 10\' is almost full (91kg).', '2025-07-15 15:01:48', 1),
(10, 3, '📌 New collection task assigned for Bin #26 at \'Apartment 10 - Unit 10\'.', '2025-07-15 19:34:37', 1),
(11, 3, '📌 New collection task assigned for Bin #2 at \'house: 02\'.', '2025-07-18 08:47:00', 1),
(12, 2, '⚠️ Bin #24 at \'02\' is almost full (90kg).', '2025-07-18 08:51:29', 1),
(13, 2, '⚠️ Bin #24 at \'02\' is almost full (100kg).', '2025-07-18 08:52:20', 1),
(14, 9, '📌 New collection task assigned for Bin #24 at \'02\'.', '2025-07-18 08:56:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('resident','collector','admin') DEFAULT NULL,
  `building_number` varchar(50) DEFAULT NULL,
  `unit_number` varchar(50) DEFAULT NULL,
  `residence_type` enum('apartment','household') DEFAULT NULL,
  `house_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `building_number`, `unit_number`, `residence_type`, `house_address`) VALUES
(2, 'bile', 'bilemohammedabde@gmail.com', '$2y$10$iLNc/uIbPMv1qfdD/oYjceEHg1lxw7wtbNWtcO7KfMg.jOB95fm9y', 'resident', NULL, NULL, NULL, '2'),
(3, 'bole', 'bole.mohammed@gmail.com', '$2y$10$bc9vXcpL3d0/G511NpilAeIhdnRqVdtRDJ4XXUsrFxdPJH9bcKMj6', 'collector', NULL, NULL, NULL, NULL),
(6, 'logics', 'logicnfthemusic@gmail.com', '$2y$10$uKIKa5W4OcWP17RK9hSGTuNrOpgaEOBNzSR6FCGSkBISeSPQG/2E2', 'admin', NULL, NULL, NULL, NULL),
(7, 'Admin', 'admin@smartwaste.com', '$2y$10$U4IW3Rw.vkx62Xpz7wrzaOqsZ3q/DvJ4Abj/YngEaO9xtP5Dsj8zq', 'admin', NULL, NULL, NULL, NULL),
(8, 'abdu', 'abdu@gmail.com', '$2y$10$Rt0RBt1zDehLH0895thYIOkT1T7m3ja7NpCpL3AEivzkWtm2ZqoN6', 'resident', '21', '25', NULL, NULL),
(9, 'hoden', 'hoden@gmail.com', '$2y$10$V95gPEMDf6fGqReG7jZigOuz4yTvXxqkGWbYHfU2VRInQsDBflwhe', 'collector', '', '', NULL, NULL),
(10, 'nur', 'nur@gmail.com', '$2y$10$FA7Hb8LtfTUkBdTHW.aoEueutLnACgAW9ppH58A9/GkQ3EL4pX7Ay', 'collector', '', '', NULL, NULL),
(11, 'Girum', 'girum@gmail.com', '$2y$10$50/5sPiJmAjhYfuF0EYl4eWROgfZhSlMxBkTkXnUcWZsTaC/Bzzfy', 'resident', NULL, NULL, '', '01'),
(12, 'resid', 'resid@gmail.com', '$2y$10$C/BY1zGpr3gGEpnAgmrYF.onDLKgvOQ3Ufzb0SHOgnZ49bUL2YIoy', 'resident', '20', '20', 'apartment', ''),
(13, 'resi', 'resi@gmail.com', '$2y$10$85MQmbV7CISYUsG2O3WU6.LLC9J9DkNKp6jUfQwrqi0CRTQ/bDFQ.', 'resident', '19', '19', 'apartment', ''),
(14, 'bires', 'bires@gmail.com', '$2y$10$CspfQrpos.75pS5z3czOdulFIJkMaN09Iw4NTpumJTeL1KTscj2Ty', 'resident', '18', '18', 'apartment', ''),
(15, 'abres', 'abres@gmail.com', '$2y$10$brhNWvvx7kEwS2MPJTegYegSUH5ElmfqW0JxiZwG8FPM9Unpcb8JO', 'resident', '17', '17', 'apartment', ''),
(16, 'jah', 'jah@gmail.com', '$2y$10$flAtQ/6fFwy9VbiHgjbukOV.mINP4B2REC2UE5fAMU12lz69JyraW', 'resident', '16', '16', 'apartment', ''),
(17, 'haj', 'haj@gmail.com', '$2y$10$WmjWpaxPK/evy4.kBXuVcuQmjG5w6ubIpXl.wiwt.J31vnFHu/MIO', 'resident', '15', '15', 'apartment', ''),
(18, 'wond', 'wond@gmail.com', '$2y$10$oG/YVQnssIhaGE7gZp2dkOnu3T9AnrCTZzTu8kauEE7Xrl9jF/Mxq', 'resident', '12', '12', 'apartment', '');

-- --------------------------------------------------------

--
-- Table structure for table `waste_disposal`
--

CREATE TABLE `waste_disposal` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bin_id` int(11) DEFAULT NULL,
  `waste_type` varchar(50) DEFAULT NULL,
  `quantity_kg` double DEFAULT NULL,
  `disposal_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` enum('Recyclable','Non-Recyclable') NOT NULL DEFAULT 'Non-Recyclable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_disposal`
--

INSERT INTO `waste_disposal` (`id`, `user_id`, `bin_id`, `waste_type`, `quantity_kg`, `disposal_time`, `category`) VALUES
(1, 2, 1, 'Organic', 3, '2025-07-02 11:11:35', 'Non-Recyclable'),
(2, 2, 1, 'Organic', 5, '2025-07-02 11:39:13', 'Non-Recyclable'),
(4, 8, 1, 'organic', 4, '2025-07-05 22:05:58', 'Non-Recyclable'),
(5, 8, 1, 'metal', 0.3, '2025-07-05 22:18:28', 'Non-Recyclable'),
(6, 16, 22, 'organic', 15, '2025-07-07 23:41:34', 'Non-Recyclable'),
(7, 18, 2, 'organic', 12, '2025-07-08 12:19:12', 'Non-Recyclable'),
(8, 18, 23, 'mixed', 84, '2025-07-08 12:40:52', 'Non-Recyclable'),
(9, 18, 23, 'hazardous', 7, '2025-07-08 12:42:07', 'Non-Recyclable'),
(10, 18, 23, 'organic', 9, '2025-07-08 12:43:25', 'Non-Recyclable'),
(11, 2, 1, 'mixed', 15, '2025-07-15 10:18:32', 'Non-Recyclable'),
(12, 2, 2, 'mixed', 50, '2025-07-15 10:35:00', 'Non-Recyclable'),
(13, 2, 19, 'organic', 80, '2025-07-15 14:20:28', 'Non-Recyclable'),
(14, 2, 1, 'plastic', 91, '2025-07-15 14:25:07', 'Recyclable'),
(15, 2, 26, 'metal', 91, '2025-07-15 15:01:47', 'Recyclable'),
(16, 2, 2, 'metal', 65, '2025-07-15 20:00:02', 'Recyclable'),
(17, 2, 24, 'organic', 75, '2025-07-18 08:51:00', 'Non-Recyclable'),
(18, 2, 24, 'organic', 15, '2025-07-18 08:51:29', 'Non-Recyclable'),
(19, 2, 24, 'organic', 10, '2025-07-18 08:52:19', 'Non-Recyclable');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assigned_bins`
--
ALTER TABLE `assigned_bins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assigned_bin` (`bin_id`),
  ADD KEY `fk_assigned_user` (`user_id`);

--
-- Indexes for table `bins`
--
ALTER TABLE `bins`
  ADD PRIMARY KEY (`bin_id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD KEY `collector_id` (`collector_id`),
  ADD KEY `bin_id` (`bin_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `waste_disposal`
--
ALTER TABLE `waste_disposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `waste_disposal_ibfk_2` (`bin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assigned_bins`
--
ALTER TABLE `assigned_bins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `bins`
--
ALTER TABLE `bins`
  MODIFY `bin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `waste_disposal`
--
ALTER TABLE `waste_disposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_bins`
--
ALTER TABLE `assigned_bins`
  ADD CONSTRAINT `assigned_bins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assigned_bins_ibfk_2` FOREIGN KEY (`bin_id`) REFERENCES `bins` (`bin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assigned_bin` FOREIGN KEY (`bin_id`) REFERENCES `bins` (`bin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assigned_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`collector_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `collections_ibfk_2` FOREIGN KEY (`bin_id`) REFERENCES `bins` (`bin_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `waste_disposal`
--
ALTER TABLE `waste_disposal`
  ADD CONSTRAINT `waste_disposal_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `waste_disposal_ibfk_2` FOREIGN KEY (`bin_id`) REFERENCES `bins` (`bin_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
