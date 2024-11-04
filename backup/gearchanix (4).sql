-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2024 at 06:17 PM
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
-- Database: `gearchanix`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_reservation`
--

CREATE TABLE `client_reservation` (
  `reservation_ID` int(5) NOT NULL,
  `vehicle_ID` int(5) NOT NULL,
  `vehicle_type` enum('Bus','Car','Motor','Mini Bus','Van') NOT NULL,
  `date` date NOT NULL DEFAULT curdate(),
  `reservation_date` date NOT NULL,
  `location` varchar(100) NOT NULL,
  `duration` int(5) NOT NULL,
  `time_departure` time(6) NOT NULL,
  `purpose` text NOT NULL,
  `no_passengers` int(5) NOT NULL,
  `office_dept` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_no` int(15) NOT NULL,
  `passenger_manifest` blob DEFAULT NULL,
  `service_type` enum('Internal Service','External Service','Others') DEFAULT NULL,
  `reservation_status` enum('Approved','Rejected','Pending') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `client_reservation`
--

INSERT INTO `client_reservation` (`reservation_ID`, `vehicle_ID`, `vehicle_type`, `date`, `reservation_date`, `location`, `duration`, `time_departure`, `purpose`, `no_passengers`, `office_dept`, `email`, `contact_no`, `passenger_manifest`, `service_type`, `reservation_status`) VALUES
(52, 55, 'Bus', '2024-10-27', '2024-10-29', 'Laguna', 11, '19:31:00.000000', 'dsdsa', 11, '11', 'darryljhan76@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(53, 55, 'Bus', '2024-10-27', '2024-10-30', 'Boracay', 2, '19:32:00.000000', 'fdsfsfsd', 22, 'TTPD', 'darryljhan76@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(54, 55, 'Bus', '2024-10-27', '2024-10-31', 'Philippines', 3, '19:33:00.000000', 'fdfdsfs', 33, 'Darryl\'s Office', 'darryljhan76@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(55, 55, 'Bus', '2024-10-27', '2024-11-01', 'PUP Sta. Mesa', 11, '19:39:00.000000', 'dfdsfds', 11, 'ABCD', 'darryljhan76@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(56, 56, 'Car', '2024-08-01', '2024-09-18', '50fdfdsf', 50, '23:41:00.000000', 'gfgfrgfdfdffd', 20, 'hala', 'umsworld16@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(57, 55, 'Car', '2024-09-03', '2024-09-10', 'gfgref', 10, '23:47:00.000000', 'thtrdgfgfdgs', 15, 'fgfdvfdv', 'ken011603@gmail.com', 957382912, NULL, 'Internal Service', 'Approved'),
(58, 56, 'Car', '2024-10-27', '2024-10-30', 'dfrdrfref', 62, '11:44:00.000000', 'fdgresfdf', 15, 'freeafdf', 'quennie1603@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(59, 56, 'Car', '2024-10-27', '2024-11-01', 'fdsafaf', 10, '11:45:00.000000', 'gfgdsfgds', 15, 'fdfea', 'quennie1603@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(60, 56, 'Car', '2024-10-27', '2024-11-01', 'fdfsad', 54, '11:46:00.000000', 'fdsz', 41, 'fvdfvaef', 'ken011603@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(61, 57, 'Motor', '2024-10-27', '2024-11-01', 'frdgresgt', 51, '12:23:00.000000', 'dfa', 15, 'fdfefd', 'ken011603@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(62, 57, 'Motor', '2024-10-27', '2024-11-06', 'fdfdsa', 41, '00:25:00.000000', 'fdfregea', 12, 'fdreafed', 'umsworld16@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(63, 57, 'Motor', '2024-10-27', '2024-11-04', 'grefdre', 12, '12:27:00.000000', 'motoro3', 14, 'jythgfhs', 'darryljhan76@gmail.com', 957382912, NULL, 'Internal Service', 'Approved'),
(64, 57, 'Motor', '2024-10-27', '2024-11-15', 'dsadsa', 20, '03:25:00.000000', 'motor4', 12, 'freafrdsf', 'darryljhan76@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(65, 57, 'Motor', '2024-10-27', '2024-11-28', 'fdfeaf', 30, '15:26:00.000000', 'motor5', 15, 'fdreaf', 'umsworld16@gmail.com', 945236874, NULL, 'Internal Service', 'Approved'),
(66, 57, 'Motor', '2024-10-27', '2024-12-11', 'fdfafe', 45, '17:30:00.000000', 'motor6', 13, 'fdf', 'ken011603@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved'),
(67, 57, 'Motor', '2024-10-27', '2024-11-29', 'gretger1', 21, '00:34:00.000000', 'motor1', 12, '`efefeaw', 'ken011603@gmail.com', 957382912, NULL, 'Internal Service', 'Approved'),
(68, 57, 'Motor', '2024-10-27', '2024-10-29', 'hhjbjk', 45, '00:44:00.000000', 'motor8', 4, 'dfdfeda', 'darryljhan76@gmail.com', 2147483647, NULL, 'Internal Service', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `ems`
--

CREATE TABLE `ems` (
  `ems_ID` int(5) NOT NULL,
  `vehicle_ID` int(5) NOT NULL,
  `acquisition_date` date NOT NULL,
  `ems_date` date NOT NULL,
  `workorder_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_triptix`
--

CREATE TABLE `history_triptix` (
  `trip_ticketID` int(5) NOT NULL,
  `trip_ticket_date` date NOT NULL,
  `gas_tank` float DEFAULT NULL,
  `purchased_gas` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `start_odometer` int(10) DEFAULT NULL,
  `end_odometer` int(10) DEFAULT NULL,
  `KM_used` int(10) DEFAULT NULL,
  `RFID_Easy` float DEFAULT NULL,
  `RFID_Auto` float DEFAULT NULL,
  `oil_used` varchar(15) DEFAULT NULL,
  `vehicle_type` enum('Bus','Car','Motor','Mini Bus','Van') NOT NULL,
  `plate_num` varchar(10) DEFAULT NULL,
  `reservation_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_triptix`
--

INSERT INTO `history_triptix` (`trip_ticketID`, `trip_ticket_date`, `gas_tank`, `purchased_gas`, `total`, `start_odometer`, `end_odometer`, `KM_used`, `RFID_Easy`, `RFID_Auto`, `oil_used`, `vehicle_type`, `plate_num`, `reservation_ID`) VALUES
(100, '2024-11-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Bus', 'XYZ123', 55),
(101, '2024-09-18', NULL, NULL, NULL, 0, 1500, 1500, 0.01, NULL, NULL, 'Car', 'HaC10a', 56),
(102, '2024-09-10', NULL, NULL, NULL, 1500, 3000, 1500, NULL, NULL, NULL, 'Car', 'HaC10a', 57),
(103, '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Car', 'HaC10a', 58),
(106, '2024-09-12', NULL, NULL, NULL, 0, 500, 500, NULL, NULL, NULL, 'Motor', 'HAL123', 61),
(107, '2024-09-20', NULL, NULL, NULL, 500, 1500, 1000, NULL, NULL, NULL, 'Motor', 'HAL123', 62),
(108, '2024-09-28', NULL, NULL, NULL, 1500, 3000, 1500, NULL, NULL, NULL, 'Motor', 'HAL123', 63),
(112, '2024-09-16', NULL, NULL, NULL, 0, 500, 500, NULL, NULL, NULL, 'Motor', 'HAL123', 67);

--
-- Triggers `history_triptix`
--
DELIMITER $$
CREATE TRIGGER `calculate_aupd_after_delete` AFTER DELETE ON `history_triptix` FOR EACH ROW BEGIN
    -- Calculate the start and end dates of the previous month
    SET @prev_month_start = DATE_SUB(LAST_DAY(NOW() - INTERVAL 1 MONTH), INTERVAL DAY(LAST_DAY(NOW() - INTERVAL 1 MONTH)) - 1 DAY);
    SET @prev_month_end = LAST_DAY(NOW() - INTERVAL 1 MONTH);

    -- Calculate AUPD for the specific vehicle in the previous month
    SELECT 
        (MAX(end_odometer) - MIN(end_odometer)) / COUNT(end_odometer)
    INTO 
        @avg_usage_per_day
    FROM 
        history_triptix
    WHERE 
        plate_num = OLD.plate_num
        AND trip_ticket_date BETWEEN @prev_month_start AND @prev_month_end;

    -- Insert or update the AUPD value in the vehicle_trip_summary table
    INSERT INTO vehicle_trip_summary (vehicle_type, plate_num, AUPD)
    VALUES (OLD.vehicle_type, OLD.plate_num, @avg_usage_per_day)
    ON DUPLICATE KEY UPDATE
        AUPD = @avg_usage_per_day;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_aupd_after_insert` AFTER INSERT ON `history_triptix` FOR EACH ROW BEGIN
    -- Calculate the start and end dates of the previous month
    SET @prev_month_start = DATE_SUB(LAST_DAY(NOW() - INTERVAL 1 MONTH), INTERVAL DAY(LAST_DAY(NOW() - INTERVAL 1 MONTH)) - 1 DAY);
    SET @prev_month_end = LAST_DAY(NOW() - INTERVAL 1 MONTH);

    -- Calculate AUPD for the specific vehicle in the previous month
    SELECT 
        (MAX(end_odometer) - MIN(end_odometer)) / COUNT(end_odometer)
    INTO 
        @avg_usage_per_day
    FROM 
        history_triptix
    WHERE 
        plate_num = NEW.plate_num
        AND trip_ticket_date BETWEEN @prev_month_start AND @prev_month_end;

    -- Insert or update the AUPD value in the vehicle_trip_summary table
    INSERT INTO vehicle_trip_summary (vehicle_type, plate_num, AUPD)
    VALUES (NEW.vehicle_type, NEW.plate_num, @avg_usage_per_day)
    ON DUPLICATE KEY UPDATE
        AUPD = @avg_usage_per_day;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_aupd_after_update` AFTER UPDATE ON `history_triptix` FOR EACH ROW BEGIN
    -- Calculate the start and end dates of the previous month
    SET @prev_month_start = DATE_SUB(LAST_DAY(NOW() - INTERVAL 1 MONTH), INTERVAL DAY(LAST_DAY(NOW() - INTERVAL 1 MONTH)) - 1 DAY);
    SET @prev_month_end = LAST_DAY(NOW() - INTERVAL 1 MONTH);

    -- Calculate AUPD for the specific vehicle in the previous month
    SELECT 
        (MAX(end_odometer) - MIN(end_odometer)) / COUNT(end_odometer)
    INTO 
        @avg_usage_per_day
    FROM 
        history_triptix
    WHERE 
        plate_num = NEW.plate_num
        AND trip_ticket_date BETWEEN @prev_month_start AND @prev_month_end;

    -- Insert or update the AUPD value in the vehicle_trip_summary table
    INSERT INTO vehicle_trip_summary (vehicle_type, plate_num, AUPD)
    VALUES (NEW.vehicle_type, NEW.plate_num, @avg_usage_per_day)
    ON DUPLICATE KEY UPDATE
        AUPD = @avg_usage_per_day;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `log_ID` int(5) NOT NULL,
  `task_ID` int(5) NOT NULL,
  `vehicle_ID` int(5) NOT NULL,
  `vehicle_type` enum('Bus','Mini Bus','Car','Motor','Van') NOT NULL,
  `plate_num` varchar(10) NOT NULL,
  `odometer_last_service` int(10) NOT NULL DEFAULT 0,
  `date_last_service` date NOT NULL DEFAULT curdate(),
  `task_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_logs`
--

INSERT INTO `maintenance_logs` (`log_ID`, `task_ID`, `vehicle_ID`, `vehicle_type`, `plate_num`, `odometer_last_service`, `date_last_service`, `task_name`) VALUES
(78, 3, 55, 'Bus', 'XYZ123', 0, '2024-10-27', 'Semi-Synthetic Oil Change'),
(79, 6, 55, 'Bus', 'XYZ123', 0, '2024-10-27', 'Fully Synthetic Oil Change'),
(80, 7, 55, 'Bus', 'XYZ123', 0, '2024-10-27', 'Preventive Maintenance'),
(81, 9, 55, 'Bus', 'XYZ123', 0, '2024-10-27', 'nnn'),
(82, 3, 56, 'Car', 'HaC10a', 0, '2024-10-27', 'Semi-Synthetic Oil Change'),
(83, 6, 56, 'Car', 'HaC10a', 0, '2024-10-27', 'Fully Synthetic Oil Change'),
(84, 7, 56, 'Car', 'HaC10a', 0, '2024-10-27', 'Preventive Maintenance'),
(85, 9, 56, 'Car', 'HaC10a', 0, '2024-10-27', 'nnn'),
(86, 3, 57, 'Motor', 'HAL123', 0, '2024-10-28', 'Semi-Synthetic Oil Change'),
(87, 6, 57, 'Motor', 'HAL123', 0, '2024-10-28', 'Fully Synthetic Oil Change'),
(88, 7, 57, 'Motor', 'HAL123', 0, '2024-10-28', 'Preventive Maintenance'),
(89, 9, 57, 'Motor', 'HAL123', 0, '2024-10-28', 'nnn');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_request`
--

CREATE TABLE `maintenance_request` (
  `queue_num` int(5) NOT NULL,
  `reminder_ID` int(5) NOT NULL,
  `vehicle_name` varchar(100) NOT NULL,
  `driver` varchar(50) NOT NULL,
  `nature_of_repair` text NOT NULL,
  `start_date` date NOT NULL,
  `target_date` date DEFAULT NULL,
  `receiver` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `noted_by` varchar(100) NOT NULL,
  `approval_chief` enum('Approved','Rejected','Pending') NOT NULL DEFAULT 'Pending',
  `approval_office` enum('Approved','Rejected','Pending') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pdi_form`
--

CREATE TABLE `pdi_form` (
  `pdi_ID` int(5) NOT NULL,
  `pdi_date` date DEFAULT NULL,
  `battery` tinyint(1) DEFAULT NULL,
  `lights` tinyint(1) DEFAULT NULL,
  `oil_level` tinyint(1) DEFAULT NULL,
  `water_level` tinyint(1) DEFAULT NULL,
  `brakes` tinyint(1) DEFAULT NULL,
  `air_pressure` tinyint(1) DEFAULT NULL,
  `gas` tinyint(1) DEFAULT NULL,
  `electronic` tinyint(1) DEFAULT NULL,
  `tools` tinyint(1) DEFAULT NULL,
  `self` tinyint(1) DEFAULT NULL,
  `driver` varchar(50) DEFAULT NULL,
  `mechanic` varchar(50) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `trip_ticketID` int(5) NOT NULL,
  `plate_num` varchar(10) DEFAULT NULL,
  `status` enum('approved','unavailable','pending') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pdi_form`
--

INSERT INTO `pdi_form` (`pdi_ID`, `pdi_date`, `battery`, `lights`, `oil_level`, `water_level`, `brakes`, `air_pressure`, `gas`, `electronic`, `tools`, `self`, `driver`, `mechanic`, `remarks`, `trip_ticketID`, `plate_num`, `status`) VALUES
(62, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, 'Good Condition', 97, 'XYZ123', 'approved'),
(63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 98, 'XYZ123', 'pending'),
(64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 99, 'XYZ123', 'pending'),
(65, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, 'Good Condition', 100, 'XYZ123', 'approved'),
(67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 104, 'HaC10a', 'pending'),
(68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 105, 'HaC10a', 'pending'),
(72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 109, 'HAL123', 'pending'),
(73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 110, 'HAL123', 'pending'),
(74, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 111, 'HAL123', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `pms`
--

CREATE TABLE `pms` (
  `pms_ID` int(5) NOT NULL,
  `vehicle_ID` int(5) NOT NULL,
  `queue_num` int(5) NOT NULL,
  `acquisition_date` date NOT NULL,
  `pms_date` date NOT NULL,
  `workorder_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_ID` int(5) NOT NULL,
  `user_ID` int(5) NOT NULL,
  `user_role` enum('CHIEF-TMPS','DISPATCHER','ADMIN CLERK','MECHANIC','SET ROLE') NOT NULL DEFAULT 'SET ROLE',
  `role_status` enum('Approve','Reject','Pending') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_ID`, `user_ID`, `user_role`, `role_status`) VALUES
(12, 15, 'DISPATCHER', 'Approve'),
(16, 20, 'SET ROLE', 'Pending'),
(27, 38, 'SET ROLE', 'Pending'),
(40, 56, 'SET ROLE', 'Pending');

--
-- Triggers `roles`
--
DELIMITER $$
CREATE TRIGGER `update_user_role` AFTER UPDATE ON `roles` FOR EACH ROW BEGIN
  UPDATE users
  SET user_role = NEW.user_role
  WHERE user_role = OLD.user_role;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `service_reminder`
--

CREATE TABLE `service_reminder` (
  `reminder_ID` int(5) NOT NULL,
  `task_ID` int(5) NOT NULL,
  `vehicle_type` enum('Bus','Mini Bus','Car','Van','Motor') NOT NULL,
  `plate_num` varchar(10) NOT NULL,
  `service_task` varchar(100) DEFAULT NULL,
  `status` enum('Due Soon','Overdue') DEFAULT NULL,
  `next_due` int(10) DEFAULT NULL,
  `Meter_until_due` int(10) DEFAULT NULL,
  `est_days` int(10) DEFAULT NULL,
  `pms_date` date DEFAULT NULL,
  `latest_odometer` int(10) DEFAULT NULL,
  `parts` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `service_reminder`
--

INSERT INTO `service_reminder` (`reminder_ID`, `task_ID`, `vehicle_type`, `plate_num`, `service_task`, `status`, `next_due`, `Meter_until_due`, `est_days`, `pms_date`, `latest_odometer`, `parts`) VALUES
(67, 7, 'Bus', 'XYZ123', 'Preventive Maintenance', 'Overdue', 5000, -2500, NULL, NULL, 7500, ''),
(68, 9, 'Bus', 'XYZ123', 'nnn', 'Overdue', 5000, -2500, NULL, NULL, 7500, ''),
(69, 3, 'Bus', 'XYZ123', 'Semi-Synthetic Oil Change', 'Overdue', 5000, -2500, NULL, NULL, 7500, 'Battery, Lights'),
(70, 3, 'Car', 'HaC10a', 'Semi-Synthetic Oil Change', 'Due Soon', 7500, 0, 0, '2024-10-27', 7500, 'Air FIlter'),
(71, 7, 'Car', 'HaC10a', 'Preventive Maintenance', 'Overdue', 5000, -2500, -3, '2024-10-30', 7500, 'Air FIlter'),
(72, 9, 'Car', 'HaC10a', 'nnn', 'Overdue', 2000, -5500, -7, '2024-11-03', 7500, 'Air FIlter'),
(73, 7, 'Motor', 'HAL123', 'Preventive Maintenance', 'Due Soon', 5000, -325, -1, '2024-10-27', 5325, 'Engine2'),
(74, 9, 'Motor', 'HAL123', 'nnn', 'Due Soon', 5000, -325, -1, '2024-10-31', 5325, 'Engine2');

-- --------------------------------------------------------

--
-- Table structure for table `service_tasks`
--

CREATE TABLE `service_tasks` (
  `task_ID` int(5) NOT NULL,
  `task_name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `MTBF` int(10) NOT NULL,
  `parts_involved` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `service_tasks`
--

INSERT INTO `service_tasks` (`task_ID`, `task_name`, `description`, `MTBF`, `parts_involved`) VALUES
(3, 'Semi-Synthetic Oil Change', 'Change Oil', 7500, 'Oil FIlter'),
(6, 'Fully Synthetic Oil Change', 'Change Oil', 10000, 'Oil Filter'),
(7, 'Preventive Maintenance', 'Regular Maintenance Check Up', 5000, ''),
(9, 'nnn', 'sdsadsa', 2000, '');

-- --------------------------------------------------------

--
-- Table structure for table `trip_ticket`
--

CREATE TABLE `trip_ticket` (
  `trip_ticketID` int(5) NOT NULL,
  `trip_ticket_date` date NOT NULL,
  `gas_tank` float DEFAULT NULL,
  `purchased_gas` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `start_odometer` int(10) DEFAULT NULL,
  `end_odometer` int(10) DEFAULT NULL,
  `KM_used` int(10) DEFAULT NULL,
  `RFID_Easy` float DEFAULT NULL,
  `RFID_Auto` float DEFAULT NULL,
  `oil_used` varchar(15) DEFAULT NULL,
  `vehicle_type` enum('Bus','Car','Motor','Mini Bus','Van') NOT NULL,
  `plate_num` varchar(10) DEFAULT NULL,
  `reservation_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `trip_ticket`
--

INSERT INTO `trip_ticket` (`trip_ticketID`, `trip_ticket_date`, `gas_tank`, `purchased_gas`, `total`, `start_odometer`, `end_odometer`, `KM_used`, `RFID_Easy`, `RFID_Auto`, `oil_used`, `vehicle_type`, `plate_num`, `reservation_ID`) VALUES
(97, '2024-10-29', NULL, NULL, NULL, 0, 300, 300, NULL, NULL, NULL, 'Bus', 'XYZ123', 52),
(98, '2024-10-30', NULL, NULL, NULL, 300, 2000, 1700, NULL, NULL, NULL, 'Bus', 'XYZ123', 53),
(99, '2024-10-31', NULL, NULL, NULL, 2000, 7500, 5500, NULL, NULL, NULL, 'Bus', 'XYZ123', 54),
(100, '2024-11-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Bus', 'XYZ123', 55),
(104, '2024-11-01', NULL, NULL, NULL, 3000, 3500, 500, NULL, NULL, NULL, 'Car', 'HaC10a', 59),
(105, '2024-11-01', NULL, NULL, NULL, 3500, 7500, 4000, NULL, NULL, NULL, 'Car', 'HaC10a', 60),
(109, '2024-11-15', NULL, NULL, NULL, 3000, 3500, 500, NULL, NULL, NULL, 'Motor', 'HAL123', 64),
(110, '2024-11-28', NULL, NULL, NULL, 3500, 4300, 800, NULL, NULL, NULL, 'Motor', 'HAL123', 65),
(111, '2024-12-11', NULL, NULL, NULL, 4300, 4700, 400, NULL, NULL, NULL, 'Motor', 'HAL123', 66),
(113, '2024-10-29', NULL, NULL, NULL, 4700, 5325, 625, NULL, NULL, NULL, 'Motor', 'HAL123', 68);

--
-- Triggers `trip_ticket`
--
DELIMITER $$
CREATE TRIGGER `update_plate_num_in_pdi_form` AFTER UPDATE ON `trip_ticket` FOR EACH ROW BEGIN
    UPDATE pdi_form
    SET plate_num = NEW.plate_num
    WHERE trip_ticketID = NEW.trip_ticketID;  -- Assuming trip_ticketID is the foreign key
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_vehicle_trip_summary_after_delete` AFTER DELETE ON `trip_ticket` FOR EACH ROW BEGIN
    -- Update only the total_km_used for the old plate_num and vehicle_ID (the deleted row)
    INSERT INTO vehicle_trip_summary (vehicle_ID, vehicle_type, plate_num, total_km_used)
    SELECT 
        v.vehicle_ID, 
        v.vehicle_type, 
        v.plate_num, 
        IFNULL(SUM(t.KM_used), 0) AS total_km_used
    FROM 
        vehicles v
    LEFT JOIN 
        trip_ticket t ON v.plate_num = t.plate_num
    WHERE 
        v.plate_num = OLD.plate_num
    GROUP BY 
        v.vehicle_ID, v.vehicle_type, v.plate_num
    ON DUPLICATE KEY UPDATE 
        total_km_used = VALUES(total_km_used);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_vehicle_trip_summary_after_insert` AFTER INSERT ON `trip_ticket` FOR EACH ROW BEGIN
    -- Insert or update the summary for the new plate_num and vehicle_ID
    INSERT INTO vehicle_trip_summary (vehicle_ID, vehicle_type, plate_num, oil_used, total_km_used, latest_odometer)
    SELECT 
        v.vehicle_ID, 
        v.vehicle_type, 
        v.plate_num, 
        NEW.oil_used, -- Get the oil_used from the trip_ticket table
        SUM(t.KM_used) AS total_km_used,
        MAX(t.end_odometer) AS latest_odometer
    FROM 
        vehicles v
    JOIN 
        trip_ticket t ON v.plate_num = t.plate_num
    WHERE 
        v.plate_num = NEW.plate_num
    GROUP BY 
        v.vehicle_ID, v.vehicle_type, v.plate_num
    ON DUPLICATE KEY UPDATE 
        oil_used = VALUES(oil_used), -- Update the oil_used
        total_km_used = VALUES(total_km_used),
        latest_odometer = VALUES(latest_odometer);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_vehicle_trip_summary_after_update` AFTER UPDATE ON `trip_ticket` FOR EACH ROW BEGIN
    -- Insert or update the summary for the new plate_num and vehicle_ID
    INSERT INTO vehicle_trip_summary (vehicle_ID, vehicle_type, plate_num, oil_used, total_km_used, latest_odometer)
    SELECT 
        v.vehicle_ID, 
        v.vehicle_type, 
        v.plate_num, 
        NEW.oil_used,  -- Get the oil_used from the updated trip_ticket row
        SUM(t.KM_used) AS total_km_used,
        MAX(t.end_odometer) AS latest_odometer
    FROM 
        vehicles v
    JOIN 
        trip_ticket t ON v.plate_num = t.plate_num
    WHERE 
        v.plate_num = NEW.plate_num
    GROUP BY 
        v.vehicle_ID, v.vehicle_type, v.plate_num
    ON DUPLICATE KEY UPDATE 
        oil_used = VALUES(oil_used),  -- Update the oil_used
        total_km_used = VALUES(total_km_used),
        latest_odometer = VALUES(latest_odometer);

    -- If the plate_num has changed, update the summary for the old plate_num and vehicle_ID
    IF OLD.plate_num != NEW.plate_num THEN
        INSERT INTO vehicle_trip_summary (vehicle_ID, vehicle_type, plate_num, oil_used, total_km_used, latest_odometer)
        SELECT 
            v.vehicle_ID, 
            v.vehicle_type, 
            v.plate_num, 
            NEW.oil_used,  -- Retain the oil_used from the updated record (or you can choose OLD.oil_used if that is relevant)
            SUM(t.KM_used) AS total_km_used,
            MAX(t.end_odometer) AS latest_odometer
        FROM 
            vehicles v
        JOIN 
            trip_ticket t ON v.plate_num = t.plate_num
        WHERE 
            v.plate_num = OLD.plate_num
        GROUP BY 
            v.vehicle_ID, v.vehicle_type, v.plate_num
        ON DUPLICATE KEY UPDATE 
            oil_used = VALUES(oil_used),  -- Update the oil_used for the old plate_num
            total_km_used = VALUES(total_km_used),
            latest_odometer = VALUES(latest_odometer);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_ID` int(5) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `contact_num` varchar(15) DEFAULT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_role` enum('CHIEF-TMPS','DISPATCHER','ADMIN CLERK','MECHANIC','SET ROLE') NOT NULL DEFAULT 'SET ROLE',
  `username` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_idpic` blob DEFAULT NULL,
  `user_profile` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_ID`, `first_name`, `last_name`, `middle_name`, `contact_num`, `user_email`, `user_role`, `username`, `user_password`, `user_idpic`, `user_profile`) VALUES
(15, 'Olivia', 'Duterte', 'Rodrigo', '09765432898', 'umsworld16@gmail.com', 'CHIEF-TMPS', 'umsworld16@gmail.com', '$2y$10$J31uK33dsIPmRZs5vm5gdenX7yElEXyyu8HrA4AoDhb6JZwG9snLW', NULL, NULL),
(20, 'Darryl', 'Oro', 'Aguylo', '096661117980', 'darryljhan76@gmail.com', 'CHIEF-TMPS', 'darrylhan76@gmail.com', '$2y$10$jCRSianld6XPsRtol84A0uypef97GuzH8wa52nVJ1tAsZhy9fvm02', NULL, NULL),
(38, 'Queen', 'Fhukerat', 'Dura', '09827354912', 'ken011603@gmail.com', 'DISPATCHER', 'quenni234@gmail.com', '$2y$10$3tdHem2MPUKN3uu8WUaNqe5yleUbXh7FL9JED6UHlfzQIjFdS1NeS', NULL, NULL),
(56, 'Lucas', 'Fhukerat', 'Dura', '09827384921', 'gearchanix@gmail.com', 'ADMIN CLERK', 'gearchanix@gmail.com', '$2y$10$4JKLq1.cBnF3Cnd1DDtZDO/Xwdz86Sm23sUZKbDEuQXRyc4cDkDve', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_ID` int(5) NOT NULL,
  `vehicle_name` varchar(100) NOT NULL,
  `vehicle_model` varchar(50) NOT NULL,
  `vehicle_year` int(4) NOT NULL,
  `vehicle_vin` varchar(25) DEFAULT NULL,
  `vehicle_type` enum('Bus','Car','Motor','Mini Bus','Van') NOT NULL,
  `plate_num` varchar(10) NOT NULL,
  `lifespan` int(10) NOT NULL,
  `current_meter` int(10) NOT NULL,
  `pms_date` date DEFAULT NULL,
  `ems_date` date DEFAULT NULL,
  `vehicle_remarks` text DEFAULT NULL,
  `vehicle_status` enum('Available','Scheduled','Under Maintenance','Inactive') NOT NULL,
  `odometer_last_pms` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_ID`, `vehicle_name`, `vehicle_model`, `vehicle_year`, `vehicle_vin`, `vehicle_type`, `plate_num`, `lifespan`, `current_meter`, `pms_date`, `ems_date`, `vehicle_remarks`, `vehicle_status`, `odometer_last_pms`) VALUES
(55, 'Honda Civic', '2022', 2022, 'dsgsd123432sd11', 'Bus', 'XYZ123', 1, 0, NULL, NULL, NULL, 'Available', 0),
(56, 'Kawasaki wikiwik', 'Corolla', 2022, '1HGBH41JXMN1078', 'Car', 'HaC10a', 10, 0, NULL, NULL, NULL, 'Available', 0),
(57, 'Victory', 'CommuterYes', 2021, 'dsgsd123432sdrlk', 'Motor', 'HAL123', 10, 0, NULL, NULL, NULL, 'Available', 0);

--
-- Triggers `vehicles`
--
DELIMITER $$
CREATE TRIGGER `update_vehicle_trip_summary_olp` AFTER UPDATE ON `vehicles` FOR EACH ROW BEGIN
    -- Check if the odometer_last_pms column is being updated
    IF NEW.odometer_last_pms <> OLD.odometer_last_pms THEN
        -- Update the vehicle_trip_summary table with the new odometer_last_pms based on plate_num
        UPDATE vehicle_trip_summary
        SET odometer_last_pms = NEW.odometer_last_pms
        WHERE plate_num = NEW.plate_num;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_parts`
--

CREATE TABLE `vehicle_parts` (
  `vehicleparts_ID` int(5) NOT NULL,
  `vehicle_ID` int(5) NOT NULL,
  `part_name` varchar(50) NOT NULL,
  `part_mtbf` int(6) NOT NULL,
  `part_ornum` varchar(5) NOT NULL,
  `part_date_procurred` date NOT NULL,
  `part_date_inspected` date NOT NULL,
  `part_date_accomplished` date NOT NULL,
  `part_remarks` varchar(100) DEFAULT NULL,
  `part_num_of_days` int(5) NOT NULL,
  `part_description` text DEFAULT NULL,
  `odometer_last_service` int(10) NOT NULL DEFAULT 0,
  `date_last_service` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `vehicle_parts`
--

INSERT INTO `vehicle_parts` (`vehicleparts_ID`, `vehicle_ID`, `part_name`, `part_mtbf`, `part_ornum`, `part_date_procurred`, `part_date_inspected`, `part_date_accomplished`, `part_remarks`, `part_num_of_days`, `part_description`, `odometer_last_service`, `date_last_service`) VALUES
(128, 55, 'Battery', 4500, '2345', '2024-09-30', '2024-10-06', '2024-10-14', '', 10, '', 0, '2024-10-27'),
(129, 55, 'Lights', 3000, '1243', '2024-10-14', '2024-10-15', '2024-10-16', '', 11, '', 0, '2024-10-27'),
(130, 56, 'Air FIlter', 2000, '453', '2024-10-28', '2024-10-28', '2024-10-28', '', 3, '', 0, '2024-10-28'),
(131, 57, 'Engine2', 4000, '127', '2024-10-28', '2024-10-23', '0000-00-00', '', 12, '', 0, '2024-10-28');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_trip_summary`
--

CREATE TABLE `vehicle_trip_summary` (
  `vehicle_type` enum('Bus','Car','Motor','Mini Bus','Van') NOT NULL,
  `plate_num` varchar(10) NOT NULL,
  `vehicle_ID` int(5) NOT NULL,
  `total_km_used` int(10) DEFAULT NULL,
  `latest_odometer` int(10) DEFAULT NULL,
  `oil_used` varchar(15) DEFAULT NULL,
  `AUPD` int(10) DEFAULT 0,
  `odometer_last_pms` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_trip_summary`
--

INSERT INTO `vehicle_trip_summary` (`vehicle_type`, `plate_num`, `vehicle_ID`, `total_km_used`, `latest_odometer`, `oil_used`, `AUPD`, `odometer_last_pms`) VALUES
('Car', 'HaC10a', 56, 4500, 7500, NULL, 750, 0),
('Motor', 'HAL123', 57, 2325, 5325, NULL, 625, 0),
('Bus', 'XYZ123', 55, 7500, 7500, NULL, NULL, 0);

--
-- Triggers `vehicle_trip_summary`
--
DELIMITER $$
CREATE TRIGGER `update_service_reminder_after_vehicle_update` AFTER UPDATE ON `vehicle_trip_summary` FOR EACH ROW BEGIN
    DECLARE next_due INT;
    DECLARE meter_until_due INT;
    DECLARE est_days INT;
    DECLARE mtbf_value INT;

    -- Fetch the MTBF from the service_tasks table based on the vehicle's usage
    SET mtbf_value = (SELECT MTBF FROM service_tasks s 
                      JOIN service_reminder sr ON sr.task_ID = s.task_ID
                      WHERE sr.plate_num = NEW.plate_num 
                      LIMIT 1);

    -- Calculate new next due and meter until due values based on updated data
    SET next_due = NEW.odometer_last_pms + mtbf_value;
    SET meter_until_due = next_due - NEW.latest_odometer;
    SET est_days = IF(NEW.AUPD > 0, FLOOR(meter_until_due / NEW.AUPD), NULL);

    -- Update the service_reminder table if the reminder already exists for the vehicle
    UPDATE service_reminder
    SET 
        next_due = next_due,
        Meter_until_due = meter_until_due,
        est_days = est_days,
        latest_odometer = NEW.latest_odometer,
        status = CASE 
                    WHEN NEW.total_km_used <= mtbf_value
                    THEN 'Due Soon' 
                    ELSE 'Overdue' 
                 END
    WHERE 
        plate_num = NEW.plate_num;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `work_order`
--

CREATE TABLE `work_order` (
  `workorder_ID` int(5) NOT NULL,
  `assigned_to` varchar(100) NOT NULL,
  `replaced_parts` varchar(100) NOT NULL,
  `activity` varchar(100) NOT NULL,
  `remarks` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client_reservation`
--
ALTER TABLE `client_reservation`
  ADD PRIMARY KEY (`reservation_ID`),
  ADD KEY `vehicle_ID` (`vehicle_ID`);

--
-- Indexes for table `ems`
--
ALTER TABLE `ems`
  ADD PRIMARY KEY (`ems_ID`),
  ADD KEY `vehicle_ID` (`vehicle_ID`),
  ADD KEY `workorder_ID` (`workorder_ID`);

--
-- Indexes for table `history_triptix`
--
ALTER TABLE `history_triptix`
  ADD PRIMARY KEY (`trip_ticketID`);

--
-- Indexes for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`log_ID`),
  ADD KEY `vehicle_ID` (`vehicle_ID`),
  ADD KEY `maintenance_logs_ibfk_1` (`task_ID`);

--
-- Indexes for table `maintenance_request`
--
ALTER TABLE `maintenance_request`
  ADD PRIMARY KEY (`queue_num`),
  ADD KEY `reminder_ID` (`reminder_ID`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pdi_form`
--
ALTER TABLE `pdi_form`
  ADD PRIMARY KEY (`pdi_ID`),
  ADD KEY `fk_trip_ticket` (`trip_ticketID`);

--
-- Indexes for table `pms`
--
ALTER TABLE `pms`
  ADD PRIMARY KEY (`pms_ID`),
  ADD KEY `vehicle_ID` (`vehicle_ID`),
  ADD KEY `queue_num` (`queue_num`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `service_reminder`
--
ALTER TABLE `service_reminder`
  ADD PRIMARY KEY (`reminder_ID`),
  ADD KEY `task_ID` (`task_ID`);

--
-- Indexes for table `service_tasks`
--
ALTER TABLE `service_tasks`
  ADD PRIMARY KEY (`task_ID`);

--
-- Indexes for table `trip_ticket`
--
ALTER TABLE `trip_ticket`
  ADD PRIMARY KEY (`trip_ticketID`),
  ADD KEY `fk_reservation` (`reservation_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_ID`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `contact_num` (`contact_num`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_ID`),
  ADD UNIQUE KEY `plate_num` (`plate_num`),
  ADD UNIQUE KEY `vehicle_vin` (`vehicle_vin`);

--
-- Indexes for table `vehicle_parts`
--
ALTER TABLE `vehicle_parts`
  ADD PRIMARY KEY (`vehicleparts_ID`),
  ADD KEY `vehicle_parts_ibfk_1` (`vehicle_ID`);

--
-- Indexes for table `vehicle_trip_summary`
--
ALTER TABLE `vehicle_trip_summary`
  ADD PRIMARY KEY (`plate_num`),
  ADD UNIQUE KEY `vehicle_ID` (`vehicle_ID`);

--
-- Indexes for table `work_order`
--
ALTER TABLE `work_order`
  ADD PRIMARY KEY (`workorder_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client_reservation`
--
ALTER TABLE `client_reservation`
  MODIFY `reservation_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `ems`
--
ALTER TABLE `ems`
  MODIFY `ems_ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `log_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `maintenance_request`
--
ALTER TABLE `maintenance_request`
  MODIFY `queue_num` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pdi_form`
--
ALTER TABLE `pdi_form`
  MODIFY `pdi_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `pms`
--
ALTER TABLE `pms`
  MODIFY `pms_ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `service_reminder`
--
ALTER TABLE `service_reminder`
  MODIFY `reminder_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `service_tasks`
--
ALTER TABLE `service_tasks`
  MODIFY `task_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trip_ticket`
--
ALTER TABLE `trip_ticket`
  MODIFY `trip_ticketID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `vehicle_parts`
--
ALTER TABLE `vehicle_parts`
  MODIFY `vehicleparts_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `work_order`
--
ALTER TABLE `work_order`
  MODIFY `workorder_ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_reservation`
--
ALTER TABLE `client_reservation`
  ADD CONSTRAINT `client_reservation_ibfk_1` FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles` (`vehicle_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ems`
--
ALTER TABLE `ems`
  ADD CONSTRAINT `ems_ibfk_1` FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles` (`vehicle_ID`),
  ADD CONSTRAINT `ems_ibfk_2` FOREIGN KEY (`workorder_ID`) REFERENCES `work_order` (`workorder_ID`);

--
-- Constraints for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_ibfk_1` FOREIGN KEY (`task_ID`) REFERENCES `service_tasks` (`task_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `maintenance_logs_ibfk_2` FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles` (`vehicle_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maintenance_request`
--
ALTER TABLE `maintenance_request`
  ADD CONSTRAINT `maintenance_request_ibfk_1` FOREIGN KEY (`reminder_ID`) REFERENCES `service_reminder` (`reminder_ID`);

--
-- Constraints for table `pdi_form`
--
ALTER TABLE `pdi_form`
  ADD CONSTRAINT `fk_trip_ticket` FOREIGN KEY (`trip_ticketID`) REFERENCES `trip_ticket` (`trip_ticketID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pms`
--
ALTER TABLE `pms`
  ADD CONSTRAINT `pms_ibfk_1` FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles` (`vehicle_ID`),
  ADD CONSTRAINT `pms_ibfk_2` FOREIGN KEY (`queue_num`) REFERENCES `maintenance_request` (`queue_num`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`user_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_reminder`
--
ALTER TABLE `service_reminder`
  ADD CONSTRAINT `service_reminder_ibfk_2` FOREIGN KEY (`task_ID`) REFERENCES `service_tasks` (`task_ID`);

--
-- Constraints for table `trip_ticket`
--
ALTER TABLE `trip_ticket`
  ADD CONSTRAINT `fk_reservation` FOREIGN KEY (`reservation_ID`) REFERENCES `client_reservation` (`reservation_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vehicle_trip_summary`
--
ALTER TABLE `vehicle_trip_summary`
  ADD CONSTRAINT `vehicle_trip_summary_ibfk_1` FOREIGN KEY (`vehicle_ID`) REFERENCES `vehicles` (`vehicle_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
