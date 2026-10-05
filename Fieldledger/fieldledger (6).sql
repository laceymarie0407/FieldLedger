-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 05, 2026 at 01:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fieldledger`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_reports`
--

CREATE TABLE `daily_reports` (
  `report_id` int(11) NOT NULL COMMENT 'Unique Daily Report',
  `job_id` int(11) NOT NULL COMMENT 'Job worked that day',
  `foreman_id` int(11) NOT NULL COMMENT 'Foreman submitting report',
  `report_date` date NOT NULL COMMENT 'Date work occured',
  `weather` varchar(50) DEFAULT NULL COMMENT 'Basic weather conditions',
  `notes` text DEFAULT NULL COMMENT 'jobsite notes/issues',
  `status` varchar(20) NOT NULL DEFAULT 'In Progress',
  `reviewed_by` varchar(50) DEFAULT NULL,
  `reviewed_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Submission timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_reports`
--

INSERT INTO `daily_reports` (`report_id`, `job_id`, `foreman_id`, `report_date`, `weather`, `notes`, `status`, `reviewed_by`, `reviewed_date`, `created_at`) VALUES
(400001, 400, 100, '2026-05-05', 'Clear, 68F', 'Clearing underway. No issues.', 'Reviewed', 'LeeA', '2026-05-06 13:00:00', '2026-09-10 22:51:15'),
(400002, 400, 100, '2026-05-12', 'Partly Cloudy, 72F', 'Demolition progressing as planned.', 'Reviewed', 'LeeA', '2026-05-13 13:00:00', '2026-09-10 22:51:15'),
(400003, 400, 100, '2026-05-20', 'Clear, 75F', 'Building pad grading.', 'Reviewed', 'LeeA', '2026-05-21 13:00:00', '2026-09-10 22:51:15'),
(400004, 400, 100, '2026-05-29', 'Cloudy, 70F', 'Water service installation.', 'Reviewed', 'LeeA', '2026-05-30 13:00:00', '2026-09-10 22:51:15'),
(400005, 400, 100, '2026-06-08', 'Clear, 80F', 'Asphalt restoration completed.', 'Reviewed', 'LeeA', '2026-06-09 13:00:00', '2026-09-10 22:51:15'),
(401001, 401, 101, '2026-05-19', 'Clear, 70F', 'Sediment controls installed.', 'Reviewed', 'LeeA', '2026-05-20 13:00:00', '2026-09-10 22:51:15'),
(401002, 401, 101, '2026-05-27', 'Clear, 73F', 'Topsoil stripping in progress.', 'Reviewed', 'LeeA', '2026-05-28 13:00:00', '2026-09-10 22:51:15'),
(401003, 401, 101, '2026-06-08', 'Hot, 84F', 'Earthwork continuing.', 'Reviewed', 'LeeA', '2026-06-09 13:00:00', '2026-09-10 22:51:15'),
(401004, 401, 101, '2026-06-22', 'Clear, 81F', 'Storm drain installation.', 'Reviewed', 'LeeA', '2026-06-23 13:00:00', '2026-09-10 22:51:15'),
(401005, 401, 101, '2026-07-06', 'Hot, 88F', 'Curb and sidewalk finishing work.', 'Reviewed', 'LeeA', '2026-07-07 13:00:00', '2026-09-10 22:51:15'),
(402001, 402, 100, '2026-09-01', 'Clear, 78F', 'Mass grading south pad.', 'Reviewed', 'LeeA', '2026-09-02 13:00:00', '2026-09-10 22:51:15'),
(402002, 402, 100, '2026-09-02', 'Clear, 80F', 'Continued mass grading.', 'Reviewed', 'LeeA', '2026-09-03 13:00:00', '2026-09-10 22:51:15'),
(402003, 402, 100, '2026-09-03', 'Cloudy, 75F', 'Worked central fill area.', 'Reviewed', 'LeeA', '2026-09-04 13:00:00', '2026-09-10 22:51:15'),
(402004, 402, 100, '2026-09-04', 'Clear, 77F', 'Mass grading remains on schedule.', 'Submitted', NULL, NULL, '2026-09-10 22:51:15'),
(403001, 403, 101, '2026-09-01', 'Clear, 79F', 'Storm line installation near north entrance.', 'Reviewed', 'LeeA', '2026-09-02 13:00:00', '2026-09-10 22:51:15'),
(403002, 403, 101, '2026-09-02', 'Cloudy, 76F', 'Slow production around existing utilities.', 'Reviewed', 'LeeA', '2026-09-03 13:00:00', '2026-09-10 22:51:15'),
(403003, 403, 101, '2026-09-03', 'Light Rain, 71F', 'Hand work required near utility crossing.', 'Reviewed', 'LeeA', '2026-09-04 13:00:00', '2026-09-10 22:51:15'),
(403004, 403, 101, '2026-09-04', 'Clear, 75F', 'Storm installation continued.', 'Submitted', NULL, NULL, '2026-09-10 22:51:15'),
(404001, 404, 102, '2026-09-01', 'Clear, 80F', 'Good haul conditions.', 'Reviewed', 'LeeA', '2026-09-02 13:00:00', '2026-09-10 22:51:15'),
(404002, 404, 102, '2026-09-02', 'Clear, 82F', 'Mass grading production ahead of plan.', 'Reviewed', 'LeeA', '2026-09-03 13:00:00', '2026-09-10 22:51:15'),
(404003, 404, 102, '2026-09-03', 'Clear, 81F', 'Continued favorable production.', 'Submitted', NULL, NULL, '2026-09-10 22:51:15'),
(405001, 405, 100, '2026-09-01', 'Clear, 77F', 'Storm pipe installation.', 'Reviewed', 'LeeA', '2026-09-02 13:00:00', '2026-09-10 22:51:15'),
(405002, 405, 100, '2026-09-02', 'Cloudy, 74F', 'Additional stone required due to wet subgrade.', 'Reviewed', 'LeeA', '2026-09-03 13:00:00', '2026-09-10 22:51:15'),
(405003, 405, 100, '2026-09-03', 'Clear, 76F', 'Material usage higher than estimate.', 'Submitted', NULL, NULL, '2026-09-10 22:51:15'),
(405004, 404, 102, '2026-09-08', 'Clear, 78F', 'Sediment controls completed and inspected.', 'Reviewed', NULL, NULL, '2026-10-03 13:06:35'),
(405005, 404, 102, '2026-09-15', 'Clear, 76F', 'Mass grading completed.', 'Reviewed', NULL, NULL, '2026-10-03 13:06:35'),
(405006, 404, 102, '2026-09-22', 'Cloudy, 72F', 'Storm drainage installation completed.', 'Reviewed', NULL, NULL, '2026-10-03 13:06:35'),
(405007, 404, 102, '2026-09-29', 'Clear, 74F', 'Roadway subgrade completed.', 'Reviewed', NULL, NULL, '2026-10-03 13:06:36'),
(405008, 405, 101, '2026-09-10', 'Clear, 77F', 'Site work progressing through major production items.', 'Reviewed', NULL, NULL, '2026-10-03 13:27:48'),
(405009, 408, 100, '2026-09-18', 'Partly Cloudy, 73F', 'Initial site preparation and demolition underway.', 'Reviewed', NULL, NULL, '2026-10-03 13:28:06'),
(405010, 409, 101, '2026-08-12', 'Clear, 78F', 'Sediment controls installed and site preparation underway.', 'Reviewed', 'RoofL', '2026-08-13 13:00:00', '2026-10-03 14:27:12'),
(405011, 409, 101, '2026-08-24', 'Clear, 82F', 'Mass grading completed. Good soil and haul conditions.', 'Reviewed', 'RoofL', '2026-08-25 13:15:00', '2026-10-03 14:27:12'),
(405012, 409, 101, '2026-09-08', 'Cloudy, 74F', 'Storm drainage completed. Minor utility conflict resolved.', 'Reviewed', 'RoofL', '2026-09-09 12:45:00', '2026-10-03 14:27:12'),
(405013, 409, 101, '2026-09-22', 'Clear, 70F', 'Water service installation completed and tested.', 'Reviewed', 'RoofL', '2026-09-23 13:10:00', '2026-10-03 14:27:12'),
(405014, 410, 102, '2026-09-24', 'Clear, 72F', 'Final production quantities entered. Project exceeded several estimated quantities.', 'Reviewed', 'RoofL', '2026-09-25 13:00:00', '2026-10-03 14:29:34'),
(405015, 411, 100, '2026-09-28', 'Clear, 75F', 'Four major scopes completed. Several quantities exceeded estimate. Paving has not started.', 'Reviewed', 'RoofL', '2026-09-29 12:30:00', '2026-10-03 14:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `daily_task_entries`
--

CREATE TABLE `daily_task_entries` (
  `daily_task_entry_id` int(11) NOT NULL COMMENT 'Unit task activity record',
  `report_id` int(11) NOT NULL COMMENT 'Daily report',
  `task_id` int(11) NOT NULL COMMENT 'Task performed',
  `hours_spent` decimal(5,2) DEFAULT NULL COMMENT 'Elapsed task time for the day',
  `equipment_used` varchar(255) DEFAULT NULL COMMENT 'Equipment used that day',
  `equipment_hours` decimal(10,2) DEFAULT NULL COMMENT 'Total equipment hours',
  `equipment_cost` decimal(12,2) DEFAULT NULL,
  `materials_used` varchar(255) DEFAULT NULL COMMENT 'Materials used that day',
  `material_quantity` decimal(10,2) DEFAULT NULL COMMENT 'Material quantity used',
  `material_unit` varchar(30) DEFAULT NULL COMMENT 'Tons, CY, LF, etc',
  `material_cost` decimal(12,2) DEFAULT NULL,
  `production_qty` decimal(10,2) DEFAULT NULL COMMENT 'Production completed that day',
  `production_unit` varchar(30) DEFAULT NULL COMMENT 'Tons, CY, LF, SF, etc',
  `notes` text DEFAULT NULL COMMENT 'Task-specific notes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_task_entries`
--

INSERT INTO `daily_task_entries` (`daily_task_entry_id`, `report_id`, `task_id`, `hours_spent`, `equipment_used`, `equipment_hours`, `equipment_cost`, `materials_used`, `material_quantity`, `material_unit`, `material_cost`, `production_qty`, `production_unit`, `notes`) VALUES
(40000101, 400001, 70001, 16.00, 'Cat 953 Track Loader; Pickup Truck', 16.00, 2320.00, 'Fuel and erosion-control supplies', 1.00, 'LS', 480.00, 1.00, 'LS', 'Clearing completed. Final actuals reconciled at job closeout.'),
(40000201, 400002, 70002, 30.00, 'Cat 225 Track Excavator; Skid Steer', 31.00, 4380.00, 'Disposal and temporary stone', 1.00, 'LS', 1260.00, 1.00, 'LS', 'Demolition completed. Final actuals reconciled at job closeout.'),
(40000301, 400003, 70003, 42.00, 'Dozer; Cat 953 Track Loader', 47.00, 6845.00, 'Select fill', 520.00, 'CY', 1420.00, 4500.00, 'CY', 'Building pad grading completed slightly above planned equipment usage.'),
(40000401, 400004, 70004, 44.00, 'Cat 225 Track Excavator', 31.00, 4120.00, 'Water pipe and fittings', 120.00, 'LF', 8660.00, 550.00, 'LF', 'Public water installation completed. Material cost finished slightly above estimate.'),
(40000501, 400005, 70005, 18.00, 'Roller; Skid Steer', 13.00, 1760.00, 'Asphalt mix', 38.00, 'TON', 4280.00, 1200.00, 'SF', 'Final asphalt restoration completed.'),
(40100101, 401001, 70101, 24.00, 'Skid Steer; Pickup Truck', 17.00, 2310.00, 'Silt fence and inlet protection', 1.00, 'LS', 5050.00, 1.00, 'LS', 'Sediment controls completed. Final actuals reconciled at job closeout.'),
(40100201, 401002, 70102, 32.00, 'Cat 953 Track Loader', 40.00, 5480.00, 'Fuel', 1.00, 'LS', 940.00, 6200.00, 'CY', 'Topsoil stripping completed with slightly higher equipment usage than planned.'),
(40100301, 401003, 70103, 56.00, 'Dozer; Cat 225 Track Excavator', 75.00, 10350.00, 'Select fill', 600.00, 'CY', 1950.00, 14500.00, 'CY', 'Earthwork completed. Equipment hours and cost finished above estimate.'),
(40100401, 401004, 70104, 64.00, 'Cat 225 Track Excavator; Loader', 61.00, 8060.00, 'Storm pipe and stone bedding', 210.00, 'LF', 30000.00, 1800.00, 'LF', 'Storm drainage completed. Utility conflicts contributed to labor and material variance.'),
(40100501, 401005, 70105, 48.00, 'Skid Steer', 25.00, 4140.00, 'Concrete', 26.00, 'CY', 23010.00, 2400.00, 'LF', 'Curb and sidewalk work completed. Final material cost finished above estimate.'),
(40200101, 402001, 70202, 6.00, 'Dozer; Cat 225 Track Excavator', 10.00, 1450.00, 'Select fill', 620.00, 'CY', 350.00, 2500.00, 'CY', 'South pad cut and fill.'),
(40200201, 402002, 70202, 7.00, 'Dozer; Cat 953 Track Loader', 12.00, 1740.00, 'Select fill', 680.00, 'CY', 400.00, 2700.00, 'CY', 'Good production and haul conditions.'),
(40200301, 402003, 70202, 7.00, 'Dozer; Cat 225 Track Excavator', 12.00, 1740.00, 'Select fill', 640.00, 'CY', 425.00, 2600.00, 'CY', 'Central fill area progressing.'),
(40200401, 402004, 70202, 7.00, 'Dozer; Cat 953 Track Loader', 13.00, 1885.00, 'Select fill', 650.00, 'CY', 425.00, 2600.00, 'CY', 'Production remains on pace with estimate.'),
(40300101, 403001, 70302, 7.00, 'Cat 225 Track Excavator', 10.00, 1450.00, 'Reinforced Concrete Pipe (RCP) and stone bedding', 130.00, 'LF', 3600.00, 130.00, 'LF', 'Storm line installed north of entrance.'),
(40300201, 403002, 70302, 8.00, 'Cat 225 Track Excavator; Loader', 11.00, 1595.00, 'Reinforced Concrete Pipe (RCP) and stone bedding', 140.00, 'LF', 4000.00, 140.00, 'LF', 'Production slowed near existing utilities.'),
(40300301, 403003, 70302, 8.00, 'Cat 225 Track Excavator', 10.00, 1450.00, 'Reinforced Concrete Pipe (RCP), stone and trench protection', 135.00, 'LF', 3900.00, 135.00, 'LF', 'Additional hand work required at crossing.'),
(40300401, 403004, 70302, 8.00, 'Cat 225 Track Excavator; Loader', 11.00, 1595.00, 'Reinforced Concrete Pipe (RCP) and stone bedding', 135.00, 'LF', 3900.00, 135.00, 'LF', 'Labor consumption high relative to production.'),
(40400101, 404001, 70402, 5.00, 'Dozer; Cat 953 Track Loader', 8.00, 1160.00, 'Fuel and grade stakes', 1.00, 'LS', 250.00, 2300.00, 'CY', 'Good haul cycle and dry material.'),
(40400201, 404002, 70402, 5.00, 'Dozer; Cat 225 Track Excavator', 8.00, 1160.00, 'Fuel and grade stakes', 1.00, 'LS', 275.00, 2400.00, 'CY', 'Production ahead of estimate.'),
(40400301, 404003, 70402, 5.00, 'Dozer; Cat 953 Track Loader', 9.00, 1305.00, 'Fuel and grade stakes', 1.00, 'LS', 275.00, 2500.00, 'CY', 'Favorable soil and haul conditions.'),
(40500101, 405001, 70503, 7.00, 'Cat 225 Track Excavator', 8.00, 1160.00, 'Storm pipe and stone bedding', 210.00, 'LF', 4200.00, 210.00, 'LF', 'Initial storm run installed.'),
(40500201, 405002, 70503, 7.00, 'Cat 225 Track Excavator; Loader', 8.00, 1160.00, 'Storm pipe and additional stone', 220.00, 'LF', 5000.00, 220.00, 'LF', 'Wet subgrade required additional stone bedding.'),
(40500301, 405003, 70503, 7.00, 'Cat 225 Track Excavator', 9.00, 1305.00, 'Storm pipe and additional stone', 220.00, 'LF', 4600.00, 220.00, 'LF', 'Material spend approaching estimate faster than production.'),
(40500302, 405004, 70401, 6.00, 'Skid Steer; Pickup Truck', 6.00, 850.00, 'Sediment control materials', 1.00, 'LS', 6200.00, 1.00, 'LS', 'Sediment control scope complete'),
(40500303, 405005, 70402, 8.00, 'Dozer; Cat 953 Track Loader', 12.00, 1740.00, 'Fuel and grade stakes', 1.00, 'LS', 400.00, 4800.00, 'CY', 'Mass grading scope complete'),
(40500304, 405006, 70403, 8.00, 'Cat 225 Track Excavator; Loader', 10.00, 1450.00, 'Storm pipe and stone bedding', 1100.00, 'LF', 24000.00, 1100.00, 'LF', 'Storm drainage scope complete'),
(40500305, 405007, 70404, 8.00, 'Dozer; Roller', 10.00, 1450.00, 'Stone and stabilization material', 1.00, 'LS', 3800.00, 9000.00, 'SY', 'Roadway subgrade scope complete'),
(40500306, 405008, 70501, 6.00, 'Skid Steer', 5.00, 700.00, 'Sediment control materials', 0.80, 'LS', 4200.00, 0.80, 'LS', 'Sediment control approximately 80% complete.'),
(40500307, 405008, 70502, 8.00, 'Dozer; Cat 953 Track Loader', 12.00, 1750.00, 'Fuel and grade stakes', 1.00, 'LS', 500.00, 7600.00, 'CY', 'Pad grading approximately 80% complete.'),
(40500308, 405008, 70503, 7.00, 'Cat 225 Track Excavator', 8.00, 1160.00, 'Storm pipe and stone', 150.00, 'LF', 3400.00, 150.00, 'LF', 'Additional storm drain installed.'),
(40500309, 405008, 70504, 8.00, 'Skid Steer; Mini Excavator', 7.00, 950.00, 'Concrete and stone', 1360.00, 'LF', 8200.00, 1360.00, 'LF', 'Curb and sidewalk approximately 80% complete.'),
(40500310, 405009, 70706, 8.00, 'Skid Steer; Dozer', 9.00, 1250.00, 'Sediment control materials', 1000.00, 'SY', 3600.00, 1000.00, 'SY', 'Initial sediment control installation underway.'),
(40500311, 405009, 70707, 8.00, 'Excavator; Loader', 10.00, 1450.00, 'Demolition disposal', 2.00, 'EA', 1800.00, 2.00, 'EA', 'Two demolition items completed.'),
(40500312, 405010, 70708, 8.00, 'Skid Steer', 6.00, 870.00, 'Sediment control materials', 1.00, 'LS', 5200.00, 1.00, 'LS', 'Sediment control installation complete.'),
(40500313, 405011, 70709, 8.00, 'Dozer; Cat 953 Track Loader', 18.00, 2610.00, 'Fuel and grade stakes', 1.00, 'LS', 650.00, 12000.00, 'CY', 'Site grading completed to proposed grades.'),
(40500314, 405012, 70710, 8.00, 'Cat 225 Track Excavator; Loader', 16.00, 2320.00, 'RCP and stone bedding', 1800.00, 'LF', 32000.00, 1800.00, 'LF', 'Storm drain installation complete.'),
(40500315, 405013, 70711, 8.00, 'Mini Excavator; Loader', 12.00, 1740.00, 'Water pipe and fittings', 900.00, 'LF', 28000.00, 900.00, 'LF', 'Water service completed and tested.'),
(40500316, 405014, 70713, 8.00, 'Skid Steer', 7.00, 1015.00, 'Sediment controls', 1.00, 'LS', 5100.00, 1.00, 'LS', 'Complete.'),
(40500317, 405014, 70714, 8.00, 'Dozer; Excavator', 20.00, 2900.00, 'Fuel', 1.00, 'LS', 700.00, 11200.00, 'CY', '1,200 CY over estimate.'),
(40500318, 405014, 70715, 8.00, 'Excavator; Loader', 16.00, 2320.00, 'RCP and stone', 1650.00, 'LF', 33000.00, 1650.00, 'LF', '150 LF over estimate.'),
(40500319, 405014, 70716, 8.00, 'Excavator', 10.00, 1450.00, 'Water pipe and fittings', 850.00, 'LF', 27500.00, 850.00, 'LF', '50 LF over estimate.'),
(40500320, 405014, 70717, 8.00, 'Paver; Roller', 14.00, 2100.00, 'Stone and asphalt', 15800.00, 'SY', 73500.00, 15800.00, 'SY', '800 SY over estimate.'),
(40500321, 405015, 70718, 8.00, 'Skid Steer', 7.00, 1015.00, 'Sediment controls', 1.00, 'LS', 5100.00, 1.00, 'LS', 'Complete.'),
(40500322, 405015, 70719, 8.00, 'Dozer; Loader', 20.00, 2900.00, 'Fuel', 1.00, 'LS', 700.00, 9900.00, 'CY', '900 CY over estimate.'),
(40500323, 405015, 70720, 8.00, 'Excavator; Loader', 16.00, 2320.00, 'RCP and stone', 1525.00, 'LF', 32500.00, 1525.00, 'LF', '125 LF over estimate.'),
(40500324, 405015, 70721, 8.00, 'Excavator', 10.00, 1450.00, 'Water pipe and fittings', 800.00, 'LF', 27000.00, 800.00, 'LF', '50 LF over estimate.');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `job_title`, `hourly_rate`, `active`) VALUES
(100, 'Justin', 'Carter', 'Foreman', 60.00, 1),
(101, 'Marcus', 'Reed', 'Foreman', 55.00, 1),
(102, 'Daniel', 'Brooks', 'Foreman', 50.00, 1),
(103, 'Mike', 'Torres', 'Equipment Operator', 44.00, 1),
(104, 'Tyler', 'Mason', 'Equipment Operator', 42.00, 1),
(105, 'Sam', 'Wilson', 'Skilled Laborer', 37.00, 1),
(106, 'Eric', 'Coleman', 'Skilled Laborer', 35.00, 1),
(107, 'Chris', 'Bennett', 'Laborer', 31.00, 0),
(108, 'Jake', 'Foster', 'Laborer', 29.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `estimate_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL COMMENT 'Planned Start',
  `estimated_end_date` date DEFAULT NULL COMMENT 'Planned Completion',
  `approval_status` varchar(25) NOT NULL DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estimates`
--

INSERT INTO `estimates` (`estimate_id`, `job_id`, `start_date`, `estimated_end_date`, `approval_status`) VALUES
(700, 400, '2026-05-04', '2026-06-12', 'Client Approved'),
(701, 401, '2026-05-18', '2026-07-10', 'Client Approved'),
(702, 402, '2026-08-03', '2026-10-02', 'Client Approved'),
(703, 403, '2026-08-10', '2026-11-06', 'Client Approved'),
(704, 404, '2026-08-17', '2026-10-23', 'Client Approved'),
(705, 405, '2026-08-24', '2026-10-30', 'Client Approved'),
(706, 406, '2026-10-05', '2027-01-15', 'Draft'),
(707, 407, '2026-11-02', '2027-03-12', 'Draft'),
(708, 408, '2026-11-20', '2027-01-15', 'Client Approved'),
(709, 409, '2026-08-10', '2026-10-16', 'Client Approved'),
(710, 410, '2026-07-06', '2026-09-25', 'Client Approved'),
(711, 411, '2026-08-03', '2026-10-30', 'Client Approved'),
(712, 412, '2026-11-16', '2027-02-12', 'Pending Executive'),
(713, 413, '2027-01-04', '2027-04-16', 'Pending Executive'),
(714, 414, '2026-11-30', '2027-03-05', 'Executive Approved'),
(715, 415, '2026-12-07', '2027-02-26', 'Executive Approved'),
(716, 416, '2026-11-09', '2027-01-29', 'Client Declined'),
(717, 417, '2026-10-30', '2026-12-31', 'Pending Executive');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_number` varchar(25) DEFAULT NULL,
  `job_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `city` varchar(75) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `scope_description` text DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'Estimating',
  `completed_date` date DEFAULT NULL,
  `contract_value` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_number`, `job_name`, `customer_name`, `address`, `city`, `state`, `zip_code`, `scope_description`, `status`, `completed_date`, `contract_value`) VALUES
(400, '400-PandaExpress', 'Panda Express - Woodlawn', 'Summit Retail Group', '1810 Security Blvd', 'Woodlawn', 'MD', '21244', 'Site demolition, grading, utility work, curb and asphalt restoration for new restaurant construction.', 'Complete', '2026-06-08', 100000.00),
(401, '401-CedarGrove', 'Cedar Grove Apartments', 'Horizon Development', '410 Cedar Grove Rd', 'Frederick', 'MD', '21701', 'Site preparation, storm drainage, grading, curb, sidewalk and final stabilization for apartment development.', 'Complete', '2026-07-06', 279000.00),
(402, '402-OakRidge', 'Oak Ridge Site Development', 'Oak Ridge Properties', '7250 Oak Ridge Dr', 'Hagerstown', 'MD', '21740', 'Clearing, mass grading, storm drainage and curb installation for commercial site development.', 'Active', NULL, 239000.00),
(403, '403-Riverside', 'Riverside Medical Center Expansion', 'Riverside Health', '1200 Medical Campus Way', 'Frederick', 'MD', '21702', 'Site utilities, storm drainage, grading and concrete improvements supporting medical center expansion.', 'Active', NULL, 186000.00),
(404, '404-ValleyCommerce', 'Valley Commerce Center', 'Valley Development LLC', '880 Commerce Park Dr', 'Martinsburg', 'WV', '25403', 'Mass grading, sediment control, storm drainage and roadway preparation for commerce center development.', 'Active', NULL, 198000.00),
(405, '405-Northgate', 'Northgate Retail Pad', 'Northgate Partners', '1550 Dual Hwy', 'Hagerstown', 'MD', '21740', 'Retail pad grading, drainage, utility installation, curb and sidewalk construction.', 'Active', NULL, 180000.00),
(406, '406-FrederickLogistics', 'Frederick Logistics Center', 'Keystone Commercial', '5200 Ballenger Creek Pike', 'Frederick', 'MD', '21703', 'Proposed logistics center sitework including clearing, grading, storm drain, public water and paving.', 'Estimating', NULL, NULL),
(407, '407-Meadowbrook', 'Meadowbrook Residential Phase II', 'Meadowbrook Homes', '390 Meadowbrook Ln', 'Boonsboro', 'MD', '21713', 'Proposed residential phase including earthwork, storm drain, water main, roadway subgrade and paving.', 'Estimating', NULL, NULL),
(408, '408-Costco', 'Costco-Barkley', 'Coakley Williams', '5240 Barkley Drive', 'Lanham', 'MD', '21710', 'Demo, grading, drains', 'Active', NULL, 26000.00),
(409, '409-Creekside', 'Creekside Business Park', 'Potomac Development Group', '1840 Creekside Drive', 'Frederick', 'MD', '21701', 'Site clearing, grading, storm drain, utilities and paving.', 'Active', NULL, 432000.00),
(410, '410-Summit', 'Summit Distribution Center', 'Summit Development', '7400 Commerce Way', 'Frederick', 'MD', '21703', 'Site development, utilities, grading and paving.', 'Active', NULL, 417000.00),
(411, '411-Westview', 'Westview Office Park', 'Westview Properties', '9100 Corporate Drive', 'Hagerstown', 'MD', '21740', 'Office park grading, drainage, utilities and paving.', 'Active', NULL, 390000.00),
(412, '412-PotomacCommerce', 'Potomac Commerce Expansion', 'Potomac Development Group', '2250 Industry Lane', 'Hagerstown', 'MD', '21740', 'Site grading, storm drainage, utilities and paving for commercial expansion.', 'Pending Exec Approval', NULL, NULL),
(413, '413-BlueRidgeIndustrial', 'Blue Ridge Industrial Park', 'Blue Ridge Holdings', '8100 Industrial Parkway', 'Frederick', 'MD', '21704', 'Mass grading, storm drainage, water service and roadway construction for industrial development.', 'Pending Exec Approval', NULL, NULL),
(414, '414-MedicalPavilion', 'Hagerstown Medical Pavilion', 'Valley Health Development', '1450 Medical Center Drive', 'Hagerstown', 'MD', '21742', 'Site preparation, utilities, drainage, concrete and paving for new medical pavilion.', 'Pending Client Approval', NULL, 649000.00),
(415, '415-FrederickRetail', 'Frederick Retail Center', 'Monocacy Retail Partners', '3600 Urbana Pike', 'Frederick', 'MD', '21704', 'Commercial site grading, storm drainage, curb, sidewalk and parking improvements.', 'Pending Client Approval', NULL, 515000.00),
(416, '416-GreenfieldWarehouse', 'Greenfield Warehouse Addition', 'Greenfield Logistics LLC', '1100 Distribution Way', 'Martinsburg', 'WV', '25403', 'Warehouse expansion sitework including grading, utilities, drainage and paving.', 'Declined', NULL, 564000.00),
(417, '417-Aviation', 'Aviation', 'MLG', '546 Melon Way', 'Jessup', 'MD', '21740', 'Grading building pad, curb & sidewalk\r\nGrade for black top', 'Pending Exec Approval', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_tasks`
--

CREATE TABLE `job_tasks` (
  `task_id` int(11) NOT NULL,
  `estimate_id` int(11) NOT NULL,
  `task_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `estimated_duration_days` decimal(5,2) DEFAULT NULL,
  `estimated_labor_hours` decimal(10,2) DEFAULT NULL,
  `estimated_labor_cost` decimal(12,2) DEFAULT NULL,
  `estimated_equipment_hours` decimal(10,2) DEFAULT NULL,
  `estimated_equipment_cost` decimal(12,2) DEFAULT NULL,
  `estimated_material_cost` decimal(12,2) DEFAULT NULL,
  `estimated_production_qty` decimal(10,2) DEFAULT NULL,
  `production_unit` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_tasks`
--

INSERT INTO `job_tasks` (`task_id`, `estimate_id`, `task_name`, `description`, `estimated_duration_days`, `estimated_labor_hours`, `estimated_labor_cost`, `estimated_equipment_hours`, `estimated_equipment_cost`, `estimated_material_cost`, `estimated_production_qty`, `production_unit`) VALUES
(70001, 700, 'Clearing', 'Clear and grub building and parking area.', 2.00, 80.00, 4224.00, 4.00, 2000.00, 0.00, 1.00, 'LS'),
(70002, 700, 'Demolition', 'Remove existing curb, sidewalk and asphalt.', 4.00, 192.00, 9792.00, 8.00, 3500.00, 0.00, 1.00, 'LS'),
(70003, 700, 'Grading', 'Cut, fill and establish building pad grades.', 5.00, 240.00, 12240.00, 20.00, 11000.00, 0.00, 4500.00, 'CY'),
(70004, 700, 'Public Water', 'Install water service and associated fittings.', 5.00, 200.00, 10240.00, 10.00, 4375.00, 11000.00, 550.00, 'LF'),
(70005, 700, 'Asphalt Repair', 'Restore asphalt disturbed by utility work.', 2.00, 64.00, 3424.00, 2.00, 300.00, 7500.00, 1200.00, 'SF'),
(70101, 701, 'Sediment Control', 'Install perimeter controls and stabilized entrance.', 3.00, 72.00, 3936.00, 3.00, 450.00, 6000.00, 1.00, 'LS'),
(70102, 701, 'Strip Topsoil', 'Strip and stockpile topsoil across work area.', 4.00, 128.00, 6848.00, 8.00, 4000.00, 0.00, 6200.00, 'CY'),
(70103, 701, 'Cuts to Fill', 'Excavate and place suitable material in fill areas.', 7.00, 336.00, 17136.00, 35.00, 21700.00, 0.00, 14500.00, 'CY'),
(70104, 701, 'Storm Drain', 'Install storm pipe and structures.', 8.00, 384.00, 19584.00, 24.00, 11800.00, 65700.00, 1800.00, 'LF'),
(70105, 701, 'Curb and Sidewalk', 'Install concrete curb and sidewalk.', 6.00, 288.00, 14304.00, 12.00, 3600.00, 48000.00, 2400.00, 'LF'),
(70201, 702, 'Clearing', 'Clear wooded and brush areas for site access.', 3.00, 120.00, 6336.00, 6.00, 3000.00, 0.00, 8.00, 'AC'),
(70202, 702, 'Mass Grading', 'Mass excavation and placement for site grades.', 10.00, 480.00, 24480.00, 50.00, 31000.00, 0.00, 20000.00, 'CY'),
(70203, 702, 'Storm Drain', 'Install site storm drainage network.', 8.00, 384.00, 19584.00, 24.00, 11800.00, 43800.00, 1200.00, 'LF'),
(70204, 702, 'Curb Installation', 'Install concrete curb around drives and parking.', 5.00, 240.00, 11920.00, 10.00, 3000.00, 36000.00, 1800.00, 'LF'),
(70301, 703, 'Site Grading', 'Fine and rough grading around expansion area.', 6.00, 288.00, 14688.00, 24.00, 13200.00, 0.00, 8500.00, 'CY'),
(70302, 703, 'Storm Drain Installation', 'Install storm pipe and structures.', 8.00, 384.00, 19584.00, 24.00, 11800.00, 32850.00, 900.00, 'LF'),
(70303, 703, 'Water Service', 'Install water service to expansion.', 5.00, 200.00, 10240.00, 10.00, 4375.00, 8400.00, 420.00, 'LF'),
(70304, 703, 'Concrete Improvements', 'Install curb, sidewalk and equipment pads.', 6.00, 288.00, 14304.00, 12.00, 3600.00, 15000.00, 1500.00, 'SF'),
(70401, 704, 'Sediment Control', 'Install initial erosion and sediment controls.', 3.00, 72.00, 3936.00, 3.00, 450.00, 6000.00, 1.00, 'LS'),
(70402, 704, 'Mass Grading', 'Excavate and balance site for building pads.', 8.00, 384.00, 19584.00, 40.00, 24800.00, 0.00, 12000.00, 'CY'),
(70403, 704, 'Storm Drain', 'Install storm drainage pipe and inlets.', 7.00, 336.00, 17136.00, 21.00, 10325.00, 40150.15, 1100.00, 'LF'),
(70404, 704, 'Roadway Subgrade', 'Prepare subgrade for internal roadway.', 5.00, 240.00, 12240.00, 20.00, 9000.00, 14400.00, 9000.00, 'SY'),
(70501, 705, 'Sediment Control', 'Install site erosion control measures.', 2.00, 48.00, 2624.00, 2.00, 300.00, 4000.00, 1.00, 'LS'),
(70502, 705, 'Pad Grading', 'Establish retail building pad and parking grades.', 6.00, 288.00, 14688.00, 24.00, 13200.00, 0.00, 9500.00, 'CY'),
(70503, 705, 'Storm Drain', 'Install storm drain serving retail pad.', 6.00, 288.00, 14688.00, 18.00, 8850.00, 36499.85, 1000.00, 'LF'),
(70504, 705, 'Curb and Sidewalk', 'Install curb and pedestrian sidewalk.', 5.00, 240.00, 11920.00, 10.00, 3000.00, 34000.00, 1700.00, 'LF'),
(70601, 706, 'Clearing', 'Clear and grub logistics center site.', 6.00, 240.00, 12672.00, 12.00, 6000.00, 0.00, 24.00, 'AC'),
(70602, 706, 'Mass Grading', 'Mass excavation and site balance.', 18.00, 864.00, 44064.00, 90.00, 55800.00, 0.00, 48000.00, 'CY'),
(70603, 706, 'Storm Drain', 'Install storm drain network and structures.', 14.00, 672.00, 34272.00, 42.00, 20650.00, 105850.15, 2900.00, 'LF'),
(70604, 706, 'Public Water', 'Install public water main and services.', 10.00, 400.00, 20480.00, 20.00, 8750.00, 36000.00, 1800.00, 'LF'),
(70605, 706, 'Paving', 'Base, asphalt paving and tie-ins.', 8.00, 384.00, 19584.00, 32.00, 14400.00, 172700.00, 22000.00, 'SY'),
(70701, 707, 'Clearing', 'Clear residential phase limits.', 5.00, 200.00, 10560.00, 10.00, 5000.00, 0.00, 18.00, 'AC'),
(70702, 707, 'Cuts to Fill', 'Excavate and place material throughout residential phase.', 15.00, 720.00, 36720.00, 75.00, 46500.00, 0.00, 36000.00, 'CY'),
(70703, 707, 'Storm Drain', 'Install residential storm drain system.', 12.00, 576.00, 29376.00, 36.00, 17700.00, 87600.00, 2400.00, 'LF'),
(70704, 707, 'Water Main', 'Install water main and residential services.', 11.00, 440.00, 22528.00, 22.00, 9625.00, 44000.00, 2200.00, 'LF'),
(70705, 707, 'Roadway and Paving', 'Prepare roadway subgrade and install paving.', 14.00, 672.00, 34272.00, 56.00, 25200.00, 137375.00, 17500.00, 'SY'),
(70706, 708, 'Sediment Control', '', 3.00, 72.00, 3936.00, 3.00, 450.00, 6000.00, 2500.00, 'SY'),
(70707, 708, 'Demolition', 'Demo Building & Add dumpster', 3.00, 144.00, 7344.00, 6.00, 2625.00, 0.00, 5.00, ''),
(70708, 709, 'Sediment Control', 'Install perimeter controls and stabilized entrance.', 3.00, 72.00, 3936.00, 3.00, 450.00, 6000.00, 1.00, 'LS'),
(70709, 709, 'Site Grading', 'Cut and fill site to proposed grades.', 10.00, 480.00, 24480.00, 40.00, 22000.00, 0.00, 12000.00, 'CY'),
(70710, 709, 'Storm Drain', 'Install storm drain piping and structures.', 8.00, 384.00, 19584.00, 24.00, 11800.00, 65700.00, 1800.00, 'LF'),
(70711, 709, 'Water Service', 'Install domestic and fire water services.', 6.00, 240.00, 12288.00, 12.00, 5250.00, 18000.00, 900.00, 'LF'),
(70712, 709, 'Paving', 'Prepare stone base and install asphalt paving.', 7.00, 336.00, 17136.00, 28.00, 12600.00, 125600.00, 16000.00, 'SY'),
(70713, 710, 'Sediment Control', 'Install site controls.', 3.00, 72.00, 3936.00, 3.00, 450.00, 6000.00, 1.00, 'LS'),
(70714, 710, 'Mass Grading', 'Complete site cut and fill.', 10.00, 480.00, 24480.00, 50.00, 31000.00, 0.00, 10000.00, 'CY'),
(70715, 710, 'Storm Drain', 'Install storm drainage.', 8.00, 384.00, 19584.00, 24.00, 11800.00, 54750.00, 1500.00, 'LF'),
(70716, 710, 'Water Main', 'Install water main.', 6.00, 240.00, 12288.00, 12.00, 5250.00, 16000.00, 800.00, 'LF'),
(70717, 710, 'Paving', 'Install stone base and asphalt.', 7.00, 336.00, 17136.00, 28.00, 12600.00, 117750.00, 15000.00, 'SY'),
(70718, 711, 'Sediment Control', 'Install site controls.', 3.00, 72.00, 3936.00, 3.00, 450.00, 6000.00, 1.00, 'LS'),
(70719, 711, 'Site Grading', 'Grade building and parking areas.', 10.00, 480.00, 24480.00, 40.00, 22000.00, 0.00, 9000.00, 'CY'),
(70720, 711, 'Storm Drain', 'Install storm drainage.', 8.00, 384.00, 19584.00, 24.00, 11800.00, 51100.15, 1400.00, 'LF'),
(70721, 711, 'Water Service', 'Install water services.', 6.00, 240.00, 12288.00, 12.00, 5250.00, 15000.00, 750.00, 'LF'),
(70722, 711, 'Paving', 'Install paving and final surface.', 7.00, 336.00, 17136.00, 28.00, 12600.00, 109900.00, 14000.00, 'SY'),
(70723, 712, 'Sediment Control', 'Install perimeter controls and stabilized entrance.', 4.00, 96.00, 5248.00, 8.00, 2400.00, 8000.00, 1.00, 'LS'),
(70724, 712, 'Mass Grading', 'Mass excavation and site balance.', 14.00, 672.00, 34272.00, 70.00, 43400.00, 0.00, 28000.00, 'CY'),
(70725, 712, 'Storm Drain', 'Install storm drainage system and structures.', 10.00, 480.00, 24480.00, 30.00, 14750.00, 73000.15, 2000.00, 'LF'),
(70726, 712, 'Water Service', 'Install site water service.', 7.00, 280.00, 14336.00, 14.00, 6125.00, 22000.00, 1100.00, 'LF'),
(70727, 712, 'Paving', 'Install stone base and asphalt paving.', 9.00, 432.00, 22032.00, 36.00, 16200.00, 141300.00, 18000.00, 'SY'),
(70728, 713, 'Clearing', 'Clear and grub industrial development area.', 6.00, 240.00, 12672.00, 18.00, 11400.00, 0.00, 20.00, 'AC'),
(70729, 713, 'Mass Grading', 'Mass excavation and fill placement.', 18.00, 864.00, 44064.00, 90.00, 55800.00, 0.00, 42000.00, 'CY'),
(70730, 713, 'Storm Drain', 'Install industrial storm drainage network.', 14.00, 672.00, 34272.00, 42.00, 20650.00, 102199.85, 2800.00, 'LF'),
(70731, 713, 'Water Main', 'Install water main and services.', 10.00, 400.00, 20480.00, 20.00, 8750.00, 34000.00, 1700.00, 'LF'),
(70732, 713, 'Roadway and Paving', 'Prepare roadway and install paving.', 12.00, 576.00, 29376.00, 60.00, 31800.00, 188400.00, 24000.00, 'SY'),
(70733, 714, 'Sediment Control', 'Install erosion and sediment controls.', 4.00, 96.00, 5248.00, 8.00, 2400.00, 8000.00, 1.00, 'LS'),
(70734, 714, 'Site Grading', 'Prepare building pad and parking areas.', 12.00, 576.00, 29376.00, 60.00, 37200.00, 0.00, 18000.00, 'CY'),
(70735, 714, 'Storm Drain', 'Install storm drainage and structures.', 11.00, 528.00, 26928.00, 33.00, 16225.00, 80299.85, 2200.00, 'LF'),
(70736, 714, 'Water Service', 'Install domestic and fire water services.', 8.00, 320.00, 16384.00, 16.00, 7000.00, 26000.00, 1300.00, 'LF'),
(70737, 714, 'Concrete Improvements', 'Install curb, sidewalk and equipment pads.', 8.00, 384.00, 19072.00, 16.00, 4800.00, 40000.00, 4000.00, 'SF'),
(70738, 714, 'Paving', 'Install parking lot stone base and asphalt.', 10.00, 480.00, 24480.00, 40.00, 18000.00, 157000.00, 20000.00, 'SY'),
(70739, 715, 'Sediment Control', 'Install site erosion controls.', 3.00, 72.00, 3936.00, 6.00, 1800.00, 6000.00, 1.00, 'LS'),
(70740, 715, 'Pad Grading', 'Establish retail pads and parking grades.', 10.00, 480.00, 24480.00, 50.00, 31000.00, 0.00, 15000.00, 'CY'),
(70741, 715, 'Storm Drain', 'Install storm drain system.', 9.00, 432.00, 22032.00, 27.00, 13275.00, 65700.00, 1800.00, 'LF'),
(70742, 715, 'Curb and Sidewalk', 'Install concrete curb and sidewalk.', 8.00, 384.00, 19072.00, 16.00, 4800.00, 60000.00, 3000.00, 'LF'),
(70743, 715, 'Paving', 'Install parking lot paving.', 8.00, 384.00, 19584.00, 32.00, 14400.00, 125600.00, 16000.00, 'SY'),
(70744, 716, 'Clearing', 'Clear warehouse expansion area.', 4.00, 160.00, 8448.00, 12.00, 7600.00, 0.00, 12.00, 'AC'),
(70745, 716, 'Site Grading', 'Grade warehouse pad and truck court.', 12.00, 576.00, 29376.00, 60.00, 37200.00, 0.00, 22000.00, 'CY'),
(70746, 716, 'Storm Drain', 'Install storm drainage.', 10.00, 480.00, 24480.00, 30.00, 14750.00, 73000.15, 2000.00, 'LF'),
(70747, 716, 'Water Service', 'Install water service extension.', 7.00, 280.00, 14336.00, 14.00, 6125.00, 20000.00, 1000.00, 'LF'),
(70748, 716, 'Paving', 'Install truck court and parking paving.', 10.00, 480.00, 24480.00, 40.00, 18000.00, 172700.00, 22000.00, 'SY'),
(70749, 717, 'Grading', '', 2.00, 64.00, 3424.00, 8.00, 4100.00, 0.00, 5500.00, 'SY'),
(70750, 717, 'Curb and Sidewalk', '', 4.00, 96.00, 5504.00, 13.00, 5450.00, 9000.00, 6500.00, 'LF');

-- --------------------------------------------------------

--
-- Table structure for table `labor_entries`
--

CREATE TABLE `labor_entries` (
  `labor_entry_id` int(11) NOT NULL COMMENT 'Unique labor record',
  `report_id` int(11) NOT NULL COMMENT 'Daily report',
  `employee_id` int(11) NOT NULL COMMENT 'Employee who worked',
  `task_id` int(11) DEFAULT NULL COMMENT 'Task employee worked on',
  `regular_hours` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Regular worked hours',
  `overtime_hours` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'OT hours worked',
  `work_description` varchar(255) DEFAULT NULL COMMENT 'What employee worked on'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labor_entries`
--

INSERT INTO `labor_entries` (`labor_entry_id`, `report_id`, `employee_id`, `task_id`, `regular_hours`, `overtime_hours`, `work_description`) VALUES
(10001, 400001, 100, 70001, 10.00, 1.00, 'Clearing supervision and chainsaw work'),
(10002, 400002, 100, 70002, 20.00, 1.00, 'Demolition supervision'),
(10003, 400003, 100, 70003, 26.00, 0.00, 'Grade checking and supervision'),
(10004, 400004, 100, 70004, 29.00, 1.00, 'Water installation supervision'),
(10005, 400005, 100, 70005, 12.00, 0.00, 'Asphalt repair supervision'),
(10006, 402001, 100, 70202, 6.00, 0.00, 'Mass grading supervision'),
(10007, 402002, 100, 70202, 7.00, 0.00, 'Mass grading supervision'),
(10008, 402003, 100, 70202, 7.00, 0.00, 'Mass grading supervision'),
(10009, 402004, 100, 70202, 7.00, 0.00, 'Mass grading supervision'),
(10010, 405001, 100, 70503, 7.00, 0.00, 'Storm drain supervision'),
(10011, 405002, 100, 70503, 7.00, 0.00, 'Storm drain supervision'),
(10012, 405003, 100, 70503, 7.00, 0.00, 'Storm drain supervision'),
(10101, 401001, 101, 70101, 16.00, 1.00, 'Sediment control supervision'),
(10102, 401002, 101, 70102, 22.00, 1.00, 'Topsoil stripping supervision'),
(10103, 401003, 101, 70103, 39.00, 1.00, 'Earthwork supervision'),
(10104, 401004, 101, 70104, 49.00, 2.00, 'Storm drain supervision'),
(10105, 401005, 101, 70105, 33.00, 1.00, 'Concrete supervision'),
(10106, 403001, 101, 70302, 7.00, 0.00, 'Storm drain supervision'),
(10107, 403002, 101, 70302, 8.00, 0.00, 'Storm drain supervision'),
(10108, 403003, 101, 70302, 8.00, 0.00, 'Utility conflict coordination'),
(10109, 403004, 101, 70302, 8.00, 0.00, 'Storm drain supervision'),
(10201, 404001, 102, 70402, 5.00, 0.00, 'Mass grading supervision'),
(10202, 404002, 102, 70402, 5.00, 0.00, 'Mass grading supervision'),
(10203, 404003, 102, 70402, 5.00, 0.00, 'Mass grading supervision'),
(10301, 400001, 103, 70001, 10.00, 1.00, 'Excavator operation'),
(10302, 400002, 103, 70002, 20.00, 1.00, 'Excavator demolition'),
(10303, 400003, 103, 70003, 26.00, 0.00, 'Dozer operation'),
(10304, 400004, 103, 70004, 29.00, 1.00, 'Excavator operation'),
(10305, 400005, 103, 70005, 12.00, 0.00, 'Equipment operation'),
(10306, 402001, 103, 70202, 6.00, 0.00, 'Excavator operation'),
(10307, 402002, 103, 70202, 7.00, 0.00, 'Dozer operation'),
(10308, 402003, 103, 70202, 7.00, 0.00, 'Excavator operation'),
(10309, 402004, 103, 70202, 7.00, 0.00, 'Dozer operation'),
(10310, 404001, 103, 70402, 5.00, 0.00, 'Dozer operation'),
(10311, 404002, 103, 70402, 5.00, 0.00, 'Excavator operation'),
(10312, 404003, 103, 70402, 5.00, 0.00, 'Dozer operation'),
(10401, 401001, 104, 70101, 16.00, 1.00, 'Loader operation'),
(10402, 401002, 104, 70102, 22.00, 0.00, 'Loader operation'),
(10403, 401003, 104, 70103, 39.00, 1.00, 'Dozer operation'),
(10404, 401004, 104, 70104, 49.00, 2.00, 'Excavator operation'),
(10405, 401005, 104, 70105, 33.00, 1.00, 'Material handling'),
(10406, 403001, 104, 70302, 7.00, 0.00, 'Excavator operation'),
(10407, 403002, 104, 70302, 8.00, 0.00, 'Excavator operation'),
(10408, 403003, 104, 70302, 7.00, 0.00, 'Excavation around utilities'),
(10409, 403004, 104, 70302, 8.00, 0.00, 'Excavator operation'),
(10410, 405001, 104, 70503, 7.00, 0.00, 'Excavator operation'),
(10411, 405002, 104, 70503, 7.00, 0.00, 'Excavator operation'),
(10412, 405003, 104, 70503, 6.00, 0.00, 'Excavator operation'),
(10501, 400001, 105, 70001, 9.00, 0.00, 'Clearing and debris handling'),
(10502, 400002, 105, 70002, 20.00, 0.00, 'Demo cleanup'),
(10503, 400003, 105, 70003, 26.00, 0.00, 'Grade support'),
(10504, 400004, 105, 70004, 28.00, 0.00, 'Pipe installation'),
(10505, 400005, 105, 70005, 11.00, 0.00, 'Asphalt restoration'),
(10506, 402001, 105, 70202, 6.00, 0.00, 'Grade checking'),
(10507, 402002, 105, 70202, 6.00, 0.00, 'Grade support'),
(10508, 402003, 105, 70202, 6.00, 0.00, 'Fill placement support'),
(10509, 402004, 105, 70202, 4.00, 2.00, 'Grade checking and finish work'),
(10601, 401001, 106, 70101, 16.00, 0.00, 'Fence and inlet protection installation'),
(10602, 401002, 106, 70102, 22.00, 0.00, 'Stockpile support'),
(10603, 401003, 106, 70103, 38.00, 0.00, 'Grade checking'),
(10604, 401004, 106, 70104, 47.00, 2.00, 'Pipe installation'),
(10605, 401005, 106, 70105, 32.00, 0.00, 'Curb and sidewalk installation'),
(10606, 403001, 106, 70302, 7.00, 0.00, 'Pipe installation'),
(10607, 403002, 106, 70302, 8.00, 0.00, 'Pipe and bedding installation'),
(10608, 403003, 106, 70302, 8.00, 0.00, 'Hand excavation and pipe work'),
(10609, 403004, 106, 70302, 7.00, 1.00, 'Pipe installation and cleanup'),
(10701, 404001, 107, 70402, 4.00, 0.00, 'Grade checking'),
(10702, 404002, 107, 70402, 4.00, 0.00, 'Fill placement'),
(10703, 404003, 107, 70402, 5.00, 0.00, 'Grade checking'),
(10801, 405001, 108, 70503, 6.00, 0.00, 'Pipe installation'),
(10802, 405002, 108, 70503, 5.00, 0.00, 'Stone bedding and pipe work'),
(10803, 405003, 108, 70503, 6.00, 0.00, 'Pipe and bedding installation'),
(10804, 405010, 101, 70708, 8.00, 0.00, 'Sediment control supervision'),
(10805, 405010, 105, 70708, 8.00, 0.00, 'Sediment control installation'),
(10806, 405010, 108, 70708, 8.00, 0.00, 'Sediment control installation'),
(10807, 405011, 101, 70709, 8.00, 0.00, 'Grading supervision'),
(10808, 405011, 103, 70709, 8.00, 1.00, 'Dozer operation'),
(10809, 405011, 104, 70709, 8.00, 0.00, 'Loader operation'),
(10810, 405012, 101, 70710, 8.00, 0.00, 'Storm drain supervision'),
(10811, 405012, 106, 70710, 8.00, 1.00, 'Pipe installation'),
(10812, 405012, 108, 70710, 8.00, 0.00, 'Pipe and bedding installation'),
(10813, 405013, 101, 70711, 8.00, 0.00, 'Water service supervision'),
(10814, 405013, 105, 70711, 8.00, 0.00, 'Water service installation'),
(10815, 405013, 106, 70711, 8.00, 0.00, 'Water service installation'),
(10816, 405014, 102, 70713, 8.00, 0.00, 'Sediment control'),
(10817, 405014, 103, 70714, 8.00, 2.00, 'Mass grading'),
(10818, 405014, 104, 70715, 8.00, 1.00, 'Storm drain'),
(10819, 405014, 105, 70716, 8.00, 0.00, 'Water main'),
(10820, 405014, 106, 70717, 8.00, 2.00, 'Paving'),
(10821, 405015, 100, 70718, 8.00, 0.00, 'Sediment control'),
(10822, 405015, 103, 70719, 8.00, 2.00, 'Site grading'),
(10823, 405015, 104, 70720, 8.00, 1.00, 'Storm drain'),
(10824, 405015, 105, 70721, 8.00, 0.00, 'Water service');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL,
  `resource_name` varchar(100) NOT NULL,
  `resource_type` varchar(25) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resource_id`, `resource_name`, `resource_type`, `unit`, `unit_cost`, `active`, `last_updated`) VALUES
(1, 'General Labor', 'Labor', 'Hour', 42.00, 1, '2026-10-03 22:58:05'),
(2, 'Cat D6 Dozer', 'Equipment', 'Day', 850.00, 1, '2026-10-03 21:12:19'),
(3, 'Cat 953 Track Loader', 'Equipment', 'Day', 900.00, 1, '2026-10-03 21:12:19'),
(4, 'Excavator', 'Equipment', 'Day', 725.00, 1, '2026-10-03 21:12:19'),
(5, 'Skid Steer', 'Equipment', 'Day', 450.00, 1, '2026-10-03 21:12:19'),
(6, 'Dirt/Trench Roller', 'Equipment', 'Day', 450.00, 1, '2026-10-03 21:12:19'),
(7, 'Dump Truck', 'Equipment', 'Day', 600.00, 1, '2026-10-03 21:12:19'),
(8, 'Pickup Truck', 'Equipment', 'Day', 150.00, 1, '2026-10-03 21:12:19'),
(9, '#57 Stone', 'Material', 'Ton', 45.00, 1, '2026-10-03 21:12:19'),
(10, '#2 Stone', 'Material', 'Ton', 40.00, 1, '2026-10-03 21:12:19'),
(11, 'Pea Gravel', 'Material', 'Ton', 55.00, 1, '2026-10-03 21:12:19'),
(12, 'Concrete', 'Material', 'Square Foot', 10.00, 1, '2026-10-03 21:12:19'),
(13, 'Concrete Curb', 'Material', 'Linear Foot', 20.00, 1, '2026-10-03 21:12:19'),
(14, 'Asphalt', 'Material', 'Ton', 125.00, 1, '2026-10-03 21:12:19'),
(15, 'Storm Pod', 'Material', 'Each', 750.00, 1, '2026-10-03 21:12:19'),
(16, 'Manhole', 'Material', 'Each', 1800.00, 1, '2026-10-03 21:12:19'),
(17, 'Inlet', 'Material', 'Each', 1200.00, 1, '2026-10-03 21:12:19'),
(18, 'Sewer Pipe', 'Material', 'Linear Foot', 25.00, 1, '2026-10-03 21:12:19'),
(19, 'Water Pipe', 'Material', 'Linear Foot', 20.00, 1, '2026-10-03 21:12:19'),
(20, 'Storm Drain Pipe', 'Material', 'Linear Foot', 35.00, 1, '2026-10-03 21:12:19'),
(21, 'Filter Fabric', 'Material', 'Square Foot', 1.25, 1, '2026-10-03 21:12:19'),
(22, 'Corlex', 'Material', 'Square Foot', 2.00, 1, '2026-10-03 21:12:19'),
(23, 'Silt Fence', 'Material', 'Linear Foot', 4.00, 1, '2026-10-03 21:12:19'),
(24, 'Foreman Labor', 'Labor', 'Hour', 80.00, 1, '2026-10-03 23:01:07'),
(25, 'Equipment Operator Labor', 'Labor', 'Hour', 50.00, 1, '2026-10-03 22:57:08'),
(28, 'CR-6', 'Material', 'Ton', 55.00, 1, '2026-10-04 21:01:51'),
(30, 'RC-6', 'Material', 'Ton', 50.00, 1, '2026-10-04 18:06:18');

-- --------------------------------------------------------

--
-- Table structure for table `task_resources`
--

CREATE TABLE `task_resources` (
  `task_resource_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_resources`
--

INSERT INTO `task_resources` (`task_resource_id`, `task_id`, `resource_id`, `quantity`) VALUES
(190, 70001, 8, 2.00),
(191, 70002, 8, 4.00),
(192, 70003, 8, 5.00),
(193, 70004, 8, 5.00),
(194, 70005, 8, 2.00),
(195, 70101, 8, 3.00),
(196, 70102, 8, 4.00),
(197, 70103, 8, 7.00),
(198, 70104, 8, 8.00),
(199, 70105, 8, 6.00),
(200, 70201, 8, 3.00),
(201, 70202, 8, 10.00),
(202, 70203, 8, 8.00),
(203, 70204, 8, 5.00),
(204, 70301, 8, 6.00),
(205, 70302, 8, 8.00),
(206, 70303, 8, 5.00),
(207, 70304, 8, 6.00),
(208, 70401, 8, 3.00),
(209, 70402, 8, 8.00),
(210, 70403, 8, 7.00),
(211, 70404, 8, 5.00),
(212, 70501, 8, 2.00),
(213, 70502, 8, 6.00),
(214, 70503, 8, 6.00),
(215, 70504, 8, 5.00),
(216, 70601, 8, 6.00),
(217, 70602, 8, 18.00),
(218, 70603, 8, 14.00),
(219, 70604, 8, 10.00),
(220, 70605, 8, 8.00),
(221, 70701, 8, 5.00),
(222, 70702, 8, 15.00),
(223, 70703, 8, 12.00),
(224, 70704, 8, 11.00),
(225, 70705, 8, 14.00),
(226, 70706, 8, 3.00),
(227, 70707, 8, 3.00),
(228, 70708, 8, 3.00),
(229, 70709, 8, 10.00),
(230, 70710, 8, 8.00),
(231, 70711, 8, 6.00),
(232, 70712, 8, 7.00),
(233, 70713, 8, 3.00),
(234, 70714, 8, 10.00),
(235, 70715, 8, 8.00),
(236, 70716, 8, 6.00),
(237, 70717, 8, 7.00),
(238, 70718, 8, 3.00),
(239, 70719, 8, 10.00),
(240, 70720, 8, 8.00),
(241, 70721, 8, 6.00),
(242, 70722, 8, 7.00),
(253, 70001, 2, 2.00),
(254, 70201, 2, 3.00),
(255, 70601, 2, 6.00),
(256, 70701, 2, 5.00),
(260, 70002, 4, 4.00),
(261, 70707, 4, 3.00),
(263, 70003, 2, 5.00),
(264, 70102, 2, 4.00),
(265, 70103, 2, 7.00),
(266, 70202, 2, 10.00),
(267, 70301, 2, 6.00),
(268, 70402, 2, 8.00),
(269, 70502, 2, 6.00),
(270, 70602, 2, 18.00),
(271, 70702, 2, 15.00),
(272, 70709, 2, 10.00),
(273, 70714, 2, 10.00),
(274, 70719, 2, 10.00),
(278, 70103, 3, 7.00),
(279, 70202, 3, 10.00),
(280, 70402, 3, 8.00),
(281, 70602, 3, 18.00),
(282, 70702, 3, 15.00),
(283, 70714, 3, 10.00),
(285, 70104, 4, 8.00),
(286, 70203, 4, 8.00),
(287, 70302, 4, 8.00),
(288, 70403, 4, 7.00),
(289, 70503, 4, 6.00),
(290, 70603, 4, 14.00),
(291, 70703, 4, 12.00),
(292, 70710, 4, 8.00),
(293, 70715, 4, 8.00),
(294, 70720, 4, 8.00),
(300, 70004, 4, 5.00),
(301, 70303, 4, 5.00),
(302, 70604, 4, 10.00),
(303, 70704, 4, 11.00),
(304, 70711, 4, 6.00),
(305, 70716, 4, 6.00),
(306, 70721, 4, 6.00),
(307, 70105, 5, 6.00),
(308, 70204, 5, 5.00),
(309, 70304, 5, 6.00),
(310, 70504, 5, 5.00),
(314, 70404, 6, 5.00),
(315, 70605, 6, 8.00),
(316, 70705, 6, 14.00),
(317, 70712, 6, 7.00),
(318, 70717, 6, 7.00),
(319, 70722, 6, 7.00),
(321, 70101, 23, 1500.00),
(322, 70401, 23, 1500.00),
(323, 70501, 23, 1000.00),
(324, 70706, 23, 1500.00),
(325, 70708, 23, 1500.00),
(326, 70713, 23, 1500.00),
(327, 70718, 23, 1500.00),
(328, 70104, 20, 1800.00),
(329, 70203, 20, 1200.00),
(330, 70302, 20, 900.00),
(331, 70403, 20, 1100.00),
(332, 70503, 20, 1000.00),
(333, 70603, 20, 2900.00),
(334, 70703, 20, 2400.00),
(335, 70710, 20, 1800.00),
(336, 70715, 20, 1500.00),
(337, 70720, 20, 1400.00),
(343, 70104, 9, 60.00),
(344, 70203, 9, 40.00),
(345, 70302, 9, 30.00),
(346, 70403, 9, 36.67),
(347, 70503, 9, 33.33),
(348, 70603, 9, 96.67),
(349, 70703, 9, 80.00),
(350, 70710, 9, 60.00),
(351, 70715, 9, 50.00),
(352, 70720, 9, 46.67),
(358, 70004, 19, 550.00),
(359, 70303, 19, 420.00),
(360, 70604, 19, 1800.00),
(361, 70704, 19, 2200.00),
(362, 70711, 19, 900.00),
(363, 70716, 19, 800.00),
(364, 70721, 19, 750.00),
(365, 70105, 13, 2400.00),
(366, 70204, 13, 1800.00),
(367, 70504, 13, 1700.00),
(368, 70304, 12, 1500.00),
(369, 70005, 14, 60.00),
(370, 70605, 14, 1100.00),
(371, 70705, 14, 875.00),
(372, 70712, 14, 800.00),
(373, 70717, 14, 750.00),
(374, 70722, 14, 700.00),
(376, 70404, 10, 360.00),
(377, 70605, 10, 880.00),
(378, 70705, 10, 700.00),
(379, 70712, 10, 640.00),
(380, 70717, 10, 600.00),
(381, 70722, 10, 560.00),
(383, 70001, 24, 16.00),
(384, 70002, 24, 32.00),
(385, 70003, 24, 40.00),
(386, 70004, 24, 40.00),
(387, 70005, 24, 16.00),
(388, 70101, 24, 24.00),
(389, 70102, 24, 32.00),
(390, 70103, 24, 56.00),
(391, 70104, 24, 64.00),
(392, 70105, 24, 48.00),
(393, 70201, 24, 24.00),
(394, 70202, 24, 80.00),
(395, 70203, 24, 64.00),
(396, 70204, 24, 40.00),
(397, 70301, 24, 48.00),
(398, 70302, 24, 64.00),
(399, 70303, 24, 40.00),
(400, 70304, 24, 48.00),
(401, 70401, 24, 24.00),
(402, 70402, 24, 64.00),
(403, 70403, 24, 56.00),
(404, 70404, 24, 40.00),
(405, 70501, 24, 16.00),
(406, 70502, 24, 48.00),
(407, 70503, 24, 48.00),
(408, 70504, 24, 40.00),
(409, 70601, 24, 48.00),
(410, 70602, 24, 144.00),
(411, 70603, 24, 112.00),
(412, 70604, 24, 80.00),
(413, 70605, 24, 64.00),
(414, 70701, 24, 40.00),
(415, 70702, 24, 120.00),
(416, 70703, 24, 96.00),
(417, 70704, 24, 88.00),
(418, 70705, 24, 112.00),
(419, 70706, 24, 24.00),
(420, 70707, 24, 24.00),
(421, 70708, 24, 24.00),
(422, 70709, 24, 80.00),
(423, 70710, 24, 64.00),
(424, 70711, 24, 48.00),
(425, 70712, 24, 56.00),
(426, 70713, 24, 24.00),
(427, 70714, 24, 80.00),
(428, 70715, 24, 64.00),
(429, 70716, 24, 48.00),
(430, 70717, 24, 56.00),
(431, 70718, 24, 24.00),
(432, 70719, 24, 80.00),
(433, 70720, 24, 64.00),
(434, 70721, 24, 48.00),
(435, 70722, 24, 56.00),
(446, 70001, 1, 32.00),
(447, 70002, 1, 96.00),
(448, 70003, 1, 120.00),
(449, 70004, 1, 120.00),
(450, 70005, 1, 32.00),
(451, 70101, 1, 48.00),
(452, 70102, 1, 64.00),
(453, 70103, 1, 168.00),
(454, 70104, 1, 192.00),
(455, 70105, 1, 192.00),
(456, 70201, 1, 48.00),
(457, 70202, 1, 240.00),
(458, 70203, 1, 192.00),
(459, 70204, 1, 160.00),
(460, 70301, 1, 144.00),
(461, 70302, 1, 192.00),
(462, 70303, 1, 120.00),
(463, 70304, 1, 192.00),
(464, 70401, 1, 48.00),
(465, 70402, 1, 192.00),
(466, 70403, 1, 168.00),
(467, 70404, 1, 120.00),
(468, 70501, 1, 32.00),
(469, 70502, 1, 144.00),
(470, 70503, 1, 144.00),
(471, 70504, 1, 160.00),
(472, 70601, 1, 96.00),
(473, 70602, 1, 432.00),
(474, 70603, 1, 336.00),
(475, 70604, 1, 240.00),
(476, 70605, 1, 192.00),
(477, 70701, 1, 80.00),
(478, 70702, 1, 360.00),
(479, 70703, 1, 288.00),
(480, 70704, 1, 264.00),
(481, 70705, 1, 336.00),
(482, 70706, 1, 48.00),
(483, 70707, 1, 72.00),
(484, 70708, 1, 48.00),
(485, 70709, 1, 240.00),
(486, 70710, 1, 192.00),
(487, 70711, 1, 144.00),
(488, 70712, 1, 168.00),
(489, 70713, 1, 48.00),
(490, 70714, 1, 240.00),
(491, 70715, 1, 192.00),
(492, 70716, 1, 144.00),
(493, 70717, 1, 168.00),
(494, 70718, 1, 48.00),
(495, 70719, 1, 240.00),
(496, 70720, 1, 192.00),
(497, 70721, 1, 144.00),
(498, 70722, 1, 168.00),
(509, 70001, 25, 32.00),
(510, 70002, 25, 64.00),
(511, 70003, 25, 80.00),
(512, 70004, 25, 40.00),
(513, 70005, 25, 16.00),
(514, 70102, 25, 32.00),
(515, 70103, 25, 112.00),
(516, 70104, 25, 128.00),
(517, 70105, 25, 48.00),
(518, 70201, 25, 48.00),
(519, 70202, 25, 160.00),
(520, 70203, 25, 128.00),
(521, 70204, 25, 40.00),
(522, 70301, 25, 96.00),
(523, 70302, 25, 128.00),
(524, 70303, 25, 40.00),
(525, 70304, 25, 48.00),
(526, 70402, 25, 128.00),
(527, 70403, 25, 112.00),
(528, 70404, 25, 80.00),
(529, 70502, 25, 96.00),
(530, 70503, 25, 96.00),
(531, 70504, 25, 40.00),
(532, 70601, 25, 96.00),
(533, 70602, 25, 288.00),
(534, 70603, 25, 224.00),
(535, 70604, 25, 80.00),
(536, 70605, 25, 128.00),
(537, 70701, 25, 80.00),
(538, 70702, 25, 240.00),
(539, 70703, 25, 192.00),
(540, 70704, 25, 88.00),
(541, 70705, 25, 224.00),
(542, 70707, 25, 48.00),
(543, 70709, 25, 160.00),
(544, 70710, 25, 128.00),
(545, 70711, 25, 48.00),
(546, 70712, 25, 112.00),
(547, 70714, 25, 160.00),
(548, 70715, 25, 128.00),
(549, 70716, 25, 48.00),
(550, 70717, 25, 112.00),
(551, 70719, 25, 160.00),
(552, 70720, 25, 128.00),
(553, 70721, 25, 48.00),
(554, 70722, 25, 112.00),
(572, 70003, 7, 10.00),
(573, 70103, 7, 14.00),
(574, 70104, 7, 8.00),
(575, 70202, 7, 20.00),
(576, 70203, 7, 8.00),
(577, 70301, 7, 12.00),
(578, 70302, 7, 8.00),
(579, 70402, 7, 16.00),
(580, 70403, 7, 7.00),
(581, 70404, 7, 10.00),
(582, 70502, 7, 12.00),
(583, 70503, 7, 6.00),
(584, 70602, 7, 36.00),
(585, 70603, 7, 14.00),
(586, 70605, 7, 16.00),
(587, 70702, 7, 30.00),
(588, 70703, 7, 12.00),
(589, 70705, 7, 28.00),
(590, 70709, 7, 20.00),
(591, 70710, 7, 8.00),
(592, 70712, 7, 14.00),
(593, 70714, 7, 20.00),
(594, 70715, 7, 8.00),
(595, 70717, 7, 14.00),
(596, 70719, 7, 20.00),
(597, 70720, 7, 8.00),
(598, 70722, 7, 14.00),
(603, 70723, 24, 32.00),
(604, 70724, 24, 112.00),
(605, 70725, 24, 80.00),
(606, 70726, 24, 56.00),
(607, 70727, 24, 72.00),
(608, 70728, 24, 48.00),
(609, 70729, 24, 144.00),
(610, 70730, 24, 112.00),
(611, 70731, 24, 80.00),
(612, 70732, 24, 96.00),
(613, 70733, 24, 32.00),
(614, 70734, 24, 96.00),
(615, 70735, 24, 88.00),
(616, 70736, 24, 64.00),
(617, 70737, 24, 64.00),
(618, 70738, 24, 80.00),
(619, 70739, 24, 24.00),
(620, 70740, 24, 80.00),
(621, 70741, 24, 72.00),
(622, 70742, 24, 64.00),
(623, 70743, 24, 64.00),
(624, 70744, 24, 32.00),
(625, 70745, 24, 96.00),
(626, 70746, 24, 80.00),
(627, 70747, 24, 56.00),
(628, 70748, 24, 80.00),
(634, 70723, 1, 64.00),
(635, 70724, 1, 336.00),
(636, 70725, 1, 240.00),
(637, 70726, 1, 168.00),
(638, 70727, 1, 216.00),
(639, 70728, 1, 96.00),
(640, 70729, 1, 432.00),
(641, 70730, 1, 336.00),
(642, 70731, 1, 240.00),
(643, 70732, 1, 288.00),
(644, 70733, 1, 64.00),
(645, 70734, 1, 288.00),
(646, 70735, 1, 264.00),
(647, 70736, 1, 192.00),
(648, 70737, 1, 256.00),
(649, 70738, 1, 240.00),
(650, 70739, 1, 48.00),
(651, 70740, 1, 240.00),
(652, 70741, 1, 216.00),
(653, 70742, 1, 256.00),
(654, 70743, 1, 192.00),
(655, 70744, 1, 64.00),
(656, 70745, 1, 288.00),
(657, 70746, 1, 240.00),
(658, 70747, 1, 168.00),
(659, 70748, 1, 240.00),
(665, 70724, 25, 224.00),
(666, 70725, 25, 160.00),
(667, 70726, 25, 56.00),
(668, 70727, 25, 144.00),
(669, 70728, 25, 96.00),
(670, 70729, 25, 288.00),
(671, 70730, 25, 224.00),
(672, 70731, 25, 80.00),
(673, 70732, 25, 192.00),
(674, 70734, 25, 192.00),
(675, 70735, 25, 176.00),
(676, 70736, 25, 64.00),
(677, 70737, 25, 64.00),
(678, 70738, 25, 160.00),
(679, 70740, 25, 160.00),
(680, 70741, 25, 144.00),
(681, 70742, 25, 64.00),
(682, 70743, 25, 128.00),
(683, 70744, 25, 64.00),
(684, 70745, 25, 192.00),
(685, 70746, 25, 160.00),
(686, 70747, 25, 56.00),
(687, 70748, 25, 160.00),
(696, 70723, 8, 4.00),
(697, 70724, 8, 14.00),
(698, 70725, 8, 10.00),
(699, 70726, 8, 7.00),
(700, 70727, 8, 9.00),
(701, 70728, 8, 6.00),
(702, 70729, 8, 18.00),
(703, 70730, 8, 14.00),
(704, 70731, 8, 10.00),
(705, 70732, 8, 12.00),
(706, 70733, 8, 4.00),
(707, 70734, 8, 12.00),
(708, 70735, 8, 11.00),
(709, 70736, 8, 8.00),
(710, 70737, 8, 8.00),
(711, 70738, 8, 10.00),
(712, 70739, 8, 3.00),
(713, 70740, 8, 10.00),
(714, 70741, 8, 9.00),
(715, 70742, 8, 8.00),
(716, 70743, 8, 8.00),
(717, 70744, 8, 4.00),
(718, 70745, 8, 12.00),
(719, 70746, 8, 10.00),
(720, 70747, 8, 7.00),
(721, 70748, 8, 10.00),
(727, 70724, 2, 14.00),
(728, 70728, 2, 6.00),
(729, 70729, 2, 18.00),
(730, 70732, 2, 12.00),
(731, 70734, 2, 12.00),
(732, 70740, 2, 10.00),
(733, 70744, 2, 4.00),
(734, 70745, 2, 12.00),
(742, 70724, 3, 14.00),
(743, 70728, 3, 6.00),
(744, 70729, 3, 18.00),
(745, 70734, 3, 12.00),
(746, 70740, 3, 10.00),
(747, 70744, 3, 4.00),
(748, 70745, 3, 12.00),
(749, 70725, 4, 10.00),
(750, 70726, 4, 7.00),
(751, 70730, 4, 14.00),
(752, 70731, 4, 10.00),
(753, 70735, 4, 11.00),
(754, 70736, 4, 8.00),
(755, 70741, 4, 9.00),
(756, 70746, 4, 10.00),
(757, 70747, 4, 7.00),
(764, 70723, 5, 4.00),
(765, 70733, 5, 4.00),
(766, 70737, 5, 8.00),
(767, 70739, 5, 3.00),
(768, 70742, 5, 8.00),
(771, 70727, 6, 9.00),
(772, 70732, 6, 12.00),
(773, 70738, 6, 10.00),
(774, 70743, 6, 8.00),
(775, 70748, 6, 10.00),
(778, 70724, 7, 28.00),
(779, 70725, 7, 10.00),
(780, 70727, 7, 18.00),
(781, 70729, 7, 36.00),
(782, 70730, 7, 14.00),
(783, 70732, 7, 24.00),
(784, 70734, 7, 24.00),
(785, 70735, 7, 11.00),
(786, 70738, 7, 20.00),
(787, 70740, 7, 20.00),
(788, 70741, 7, 9.00),
(789, 70743, 7, 16.00),
(790, 70745, 7, 24.00),
(791, 70746, 7, 10.00),
(792, 70748, 7, 20.00),
(793, 70723, 23, 2000.00),
(794, 70733, 23, 2000.00),
(795, 70739, 23, 1500.00),
(796, 70725, 20, 2000.00),
(797, 70730, 20, 2800.00),
(798, 70735, 20, 2200.00),
(799, 70741, 20, 1800.00),
(800, 70746, 20, 2000.00),
(803, 70725, 9, 66.67),
(804, 70730, 9, 93.33),
(805, 70735, 9, 73.33),
(806, 70741, 9, 60.00),
(807, 70746, 9, 66.67),
(810, 70726, 19, 1100.00),
(811, 70731, 19, 1700.00),
(812, 70736, 19, 1300.00),
(813, 70747, 19, 1000.00),
(817, 70737, 12, 4000.00),
(818, 70742, 13, 3000.00),
(819, 70727, 14, 900.00),
(820, 70732, 14, 1200.00),
(821, 70738, 14, 1000.00),
(822, 70743, 14, 800.00),
(823, 70748, 14, 1100.00),
(826, 70727, 10, 720.00),
(827, 70732, 10, 960.00),
(828, 70738, 10, 800.00),
(829, 70743, 10, 640.00),
(830, 70748, 10, 880.00),
(833, 70749, 24, 16.00),
(834, 70749, 25, 16.00),
(835, 70749, 1, 32.00),
(836, 70749, 8, 2.00),
(837, 70749, 2, 2.00),
(838, 70749, 6, 2.00),
(839, 70749, 7, 2.00),
(840, 70750, 24, 32.00),
(841, 70750, 25, 32.00),
(842, 70750, 1, 32.00),
(843, 70750, 8, 4.00),
(844, 70750, 6, 4.00),
(845, 70750, 2, 2.00),
(846, 70750, 5, 3.00),
(847, 70750, 9, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(50) NOT NULL COMMENT 'Login - lastname&first inital',
  `employee_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL COMMENT 'Foreman, Estimator, Admin, Executive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `employee_id`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
('BrooksD', 102, 'Daniel', 'Brooks', 'daniel.brooks@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Foreman', '2026-09-10 22:51:15'),
('CarterJ', 100, 'Justin', 'Carter', 'justin.carter@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Foreman', '2026-09-10 22:51:15'),
('HayesR', NULL, 'Robert', 'Hayes', 'robert.hayes@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Executive', '2026-09-10 22:51:15'),
('LeeA', NULL, 'Amanda', 'Lee', 'amanda.lee@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Admin', '2026-09-10 22:51:15'),
('MorganR', NULL, 'Rachel', 'Morgan', 'rachel.morgan@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Estimator', '2026-09-10 22:51:15'),
('ReedM', 101, 'Marcus', 'Reed', 'marcus.reed@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Foreman', '2026-09-10 22:51:15'),
('RoofL', NULL, 'Lacey', 'Roof', 'lacey_roof@SierSiteGrading.com', '$2y$10$GNBr8RiImo8cFz8hLN/CV.XkArLG6wmyPZkYmecKh30QAXRbulH6W', 'Admin', '2026-10-03 06:15:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_daily_reports_job` (`job_id`),
  ADD KEY `fk_daily_reports_foreman` (`foreman_id`);

--
-- Indexes for table `daily_task_entries`
--
ALTER TABLE `daily_task_entries`
  ADD PRIMARY KEY (`daily_task_entry_id`),
  ADD KEY `fk_daily_task_report` (`report_id`),
  ADD KEY `fk_daily_task_task` (`task_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`estimate_id`),
  ADD KEY `fk_estimates_job` (`job_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD UNIQUE KEY `job_number` (`job_number`);

--
-- Indexes for table `job_tasks`
--
ALTER TABLE `job_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `fk_job_tasks_estimate` (`estimate_id`);

--
-- Indexes for table `labor_entries`
--
ALTER TABLE `labor_entries`
  ADD PRIMARY KEY (`labor_entry_id`),
  ADD KEY `fk_labor_report` (`report_id`),
  ADD KEY `fk_labor_employee` (`employee_id`),
  ADD KEY `fk_labor_task` (`task_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resource_id`);

--
-- Indexes for table `task_resources`
--
ALTER TABLE `task_resources`
  ADD PRIMARY KEY (`task_resource_id`),
  ADD KEY `fk_task_resources_task` (`task_id`),
  ADD KEY `fk_task_resources_resource` (`resource_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_employee` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_reports`
--
ALTER TABLE `daily_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Daily Report', AUTO_INCREMENT=405016;

--
-- AUTO_INCREMENT for table `daily_task_entries`
--
ALTER TABLE `daily_task_entries`
  MODIFY `daily_task_entry_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unit task activity record', AUTO_INCREMENT=40500325;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `estimate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=718;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT for table `job_tasks`
--
ALTER TABLE `job_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70751;

--
-- AUTO_INCREMENT for table `labor_entries`
--
ALTER TABLE `labor_entries`
  MODIFY `labor_entry_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique labor record', AUTO_INCREMENT=10825;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `task_resources`
--
ALTER TABLE `task_resources`
  MODIFY `task_resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=848;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD CONSTRAINT `fk_daily_reports_foreman` FOREIGN KEY (`foreman_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `fk_daily_reports_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`);

--
-- Constraints for table `daily_task_entries`
--
ALTER TABLE `daily_task_entries`
  ADD CONSTRAINT `fk_daily_task_report` FOREIGN KEY (`report_id`) REFERENCES `daily_reports` (`report_id`),
  ADD CONSTRAINT `fk_daily_task_task` FOREIGN KEY (`task_id`) REFERENCES `job_tasks` (`task_id`);

--
-- Constraints for table `estimates`
--
ALTER TABLE `estimates`
  ADD CONSTRAINT `fk_estimates_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`);

--
-- Constraints for table `job_tasks`
--
ALTER TABLE `job_tasks`
  ADD CONSTRAINT `fk_job_tasks_estimate` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`estimate_id`);

--
-- Constraints for table `labor_entries`
--
ALTER TABLE `labor_entries`
  ADD CONSTRAINT `fk_labor_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `fk_labor_report` FOREIGN KEY (`report_id`) REFERENCES `daily_reports` (`report_id`),
  ADD CONSTRAINT `fk_labor_task` FOREIGN KEY (`task_id`) REFERENCES `job_tasks` (`task_id`);

--
-- Constraints for table `task_resources`
--
ALTER TABLE `task_resources`
  ADD CONSTRAINT `fk_task_resources_resource` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`),
  ADD CONSTRAINT `fk_task_resources_task` FOREIGN KEY (`task_id`) REFERENCES `job_tasks` (`task_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
