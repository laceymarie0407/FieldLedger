-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2026 at 02:24 AM
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
  `status` varchar(20) NOT NULL DEFAULT 'Submitted' COMMENT 'Submitted, In Progress, etc. ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Submission timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_reports`
--

INSERT INTO `daily_reports` (`report_id`, `job_id`, `foreman_id`, `report_date`, `weather`, `notes`, `status`, `created_at`) VALUES
(400001, 400, 100, '2026-05-05', 'Clear, 68F', 'Clearing underway. No issues.', 'Reviewed', '2026-09-10 22:51:15'),
(400002, 400, 100, '2026-05-12', 'Partly Cloudy, 72F', 'Demolition progressing as planned.', 'Reviewed', '2026-09-10 22:51:15'),
(400003, 400, 100, '2026-05-20', 'Clear, 75F', 'Building pad grading.', 'Reviewed', '2026-09-10 22:51:15'),
(400004, 400, 100, '2026-05-29', 'Cloudy, 70F', 'Water service installation.', 'Reviewed', '2026-09-10 22:51:15'),
(400005, 400, 100, '2026-06-08', 'Clear, 80F', 'Asphalt restoration completed.', 'Reviewed', '2026-09-10 22:51:15'),
(401001, 401, 101, '2026-05-19', 'Clear, 70F', 'Sediment controls installed.', 'Reviewed', '2026-09-10 22:51:15'),
(401002, 401, 101, '2026-05-27', 'Clear, 73F', 'Topsoil stripping in progress.', 'Reviewed', '2026-09-10 22:51:15'),
(401003, 401, 101, '2026-06-08', 'Hot, 84F', 'Earthwork continuing.', 'Reviewed', '2026-09-10 22:51:15'),
(401004, 401, 101, '2026-06-22', 'Clear, 81F', 'Storm drain installation.', 'Reviewed', '2026-09-10 22:51:15'),
(401005, 401, 101, '2026-07-06', 'Hot, 88F', 'Curb and sidewalk finishing work.', 'Reviewed', '2026-09-10 22:51:15'),
(402001, 402, 100, '2026-09-01', 'Clear, 78F', 'Mass grading south pad.', 'Reviewed', '2026-09-10 22:51:15'),
(402002, 402, 100, '2026-09-02', 'Clear, 80F', 'Continued mass grading.', 'Reviewed', '2026-09-10 22:51:15'),
(402003, 402, 100, '2026-09-03', 'Cloudy, 75F', 'Worked central fill area.', 'Reviewed', '2026-09-10 22:51:15'),
(402004, 402, 100, '2026-09-04', 'Clear, 77F', 'Mass grading remains on schedule.', 'Submitted', '2026-09-10 22:51:15'),
(403001, 403, 101, '2026-09-01', 'Clear, 79F', 'Storm line installation near north entrance.', 'Reviewed', '2026-09-10 22:51:15'),
(403002, 403, 101, '2026-09-02', 'Cloudy, 76F', 'Slow production around existing utilities.', 'Reviewed', '2026-09-10 22:51:15'),
(403003, 403, 101, '2026-09-03', 'Light Rain, 71F', 'Hand work required near utility crossing.', 'Reviewed', '2026-09-10 22:51:15'),
(403004, 403, 101, '2026-09-04', 'Clear, 75F', 'Storm installation continued.', 'Submitted', '2026-09-10 22:51:15'),
(404001, 404, 102, '2026-09-01', 'Clear, 80F', 'Good haul conditions.', 'Reviewed', '2026-09-10 22:51:15'),
(404002, 404, 102, '2026-09-02', 'Clear, 82F', 'Mass grading production ahead of plan.', 'Reviewed', '2026-09-10 22:51:15'),
(404003, 404, 102, '2026-09-03', 'Clear, 81F', 'Continued favorable production.', 'Submitted', '2026-09-10 22:51:15'),
(405001, 405, 100, '2026-09-01', 'Clear, 77F', 'Storm pipe installation.', 'Reviewed', '2026-09-10 22:51:15'),
(405002, 405, 100, '2026-09-02', 'Cloudy, 74F', 'Additional stone required due to wet subgrade.', 'Reviewed', '2026-09-10 22:51:15'),
(405003, 405, 100, '2026-09-03', 'Clear, 76F', 'Material usage higher than estimate.', 'Submitted', '2026-09-10 22:51:15');

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
(40000101, 400001, 70001, 8.00, 'Cat 953 Track Loader; Pickup Truck', 8.00, 1180.00, 'Fuel and erosion-control supplies', 1.00, 'LS', 220.00, 0.50, 'LS', 'Clearing approximately half complete.'),
(40000201, 400002, 70002, 8.00, 'Cat 225 Track Excavator; Skid Steer', 9.00, 1320.00, 'Disposal and temporary stone', 1.00, 'LS', 340.00, 0.35, 'LS', 'Removed curb, sidewalk and asphalt sections.'),
(40000301, 400003, 70003, 8.00, 'Dozer; Cat 953 Track Loader', 10.00, 1450.00, 'Select fill', 520.00, 'CY', 260.00, 980.00, 'CY', 'Building pad grading progressing.'),
(40000401, 400004, 70004, 9.00, 'Cat 225 Track Excavator', 8.00, 1160.00, 'Water pipe and fittings', 120.00, 'LF', 2150.00, 110.00, 'LF', 'Water service installed through main run.'),
(40000501, 400005, 70005, 8.00, 'Roller; Skid Steer', 6.00, 820.00, 'Asphalt mix', 38.00, 'TON', 1850.00, 1200.00, 'SF', 'Final asphalt restoration complete.'),
(40100101, 401001, 70101, 8.00, 'Skid Steer; Pickup Truck', 6.00, 760.00, 'Silt fence and inlet protection', 1.00, 'LS', 1400.00, 1.00, 'LS', 'Initial sediment controls complete.'),
(40100201, 401002, 70102, 8.00, 'Cat 953 Track Loader', 9.00, 1260.00, 'Fuel', 1.00, 'LS', 180.00, 1450.00, 'CY', 'Topsoil stripped and stockpiled.'),
(40100301, 401003, 70103, 8.00, 'Dozer; Cat 225 Track Excavator', 12.00, 1710.00, 'Select fill', 600.00, 'CY', 310.00, 2350.00, 'CY', 'Cuts placed in fill areas.'),
(40100401, 401004, 70104, 9.00, 'Cat 225 Track Excavator; Loader', 10.00, 1450.00, 'Storm pipe and stone bedding', 210.00, 'LF', 5100.00, 205.00, 'LF', 'Storm pipe installed through courtyard section.'),
(40100501, 401005, 70105, 8.00, 'Skid Steer', 5.00, 680.00, 'Concrete', 26.00, 'CY', 4200.00, 430.00, 'LF', 'Curb and sidewalk finishing work.'),
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
(40500301, 405003, 70503, 7.00, 'Cat 225 Track Excavator', 9.00, 1305.00, 'Storm pipe and additional stone', 220.00, 'LF', 4600.00, 220.00, 'LF', 'Material spend approaching estimate faster than production.');

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
(107, 'Chris', 'Bennett', 'Laborer', 31.00, 1),
(108, 'Jake', 'Foster', 'Laborer', 29.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `estimate_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL COMMENT 'Planned Start',
  `estimated_end_date` date DEFAULT NULL COMMENT 'Planned Completion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estimates`
--

INSERT INTO `estimates` (`estimate_id`, `job_id`, `start_date`, `estimated_end_date`) VALUES
(700, 400, '2026-05-04', '2026-06-12'),
(701, 401, '2026-05-18', '2026-07-10'),
(702, 402, '2026-08-03', '2026-10-02'),
(703, 403, '2026-08-10', '2026-11-06'),
(704, 404, '2026-08-17', '2026-10-23'),
(705, 405, '2026-08-24', '2026-10-30'),
(706, 406, '2026-10-05', '2027-01-15'),
(707, 407, '2026-11-02', '2027-03-12');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_number` varchar(25) NOT NULL,
  `job_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `city` varchar(75) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `scope_description` text DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'Active',
  `completed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_number`, `job_name`, `customer_name`, `address`, `city`, `state`, `zip_code`, `scope_description`, `status`, `completed_date`) VALUES
(400, '400-PandaExpress', 'Panda Express - Woodlawn', 'Summit Retail Group', '1810 Security Blvd', 'Woodlawn', 'MD', '21244', 'Site demolition, grading, utility work, curb and asphalt restoration for new restaurant construction.', 'Complete', NULL),
(401, '401-CedarGrove', 'Cedar Grove Apartments', 'Horizon Development', '410 Cedar Grove Rd', 'Frederick', 'MD', '21701', 'Site preparation, storm drainage, grading, curb, sidewalk and final stabilization for apartment development.', 'Complete', NULL),
(402, '402-OakRidge', 'Oak Ridge Site Development', 'Oak Ridge Properties', '7250 Oak Ridge Dr', 'Hagerstown', 'MD', '21740', 'Clearing, mass grading, storm drainage and curb installation for commercial site development.', 'Active', NULL),
(403, '403-Riverside', 'Riverside Medical Center Expansion', 'Riverside Health', '1200 Medical Campus Way', 'Frederick', 'MD', '21702', 'Site utilities, storm drainage, grading and concrete improvements supporting medical center expansion.', 'Active', NULL),
(404, '404-ValleyCommerce', 'Valley Commerce Center', 'Valley Development LLC', '880 Commerce Park Dr', 'Martinsburg', 'WV', '25403', 'Mass grading, sediment control, storm drainage and roadway preparation for commerce center development.', 'Active', NULL),
(405, '405-Northgate', 'Northgate Retail Pad', 'Northgate Partners', '1550 Dual Hwy', 'Hagerstown', 'MD', '21740', 'Retail pad grading, drainage, utility installation, curb and sidewalk construction.', 'Active', NULL),
(406, '406-FrederickLogistics', 'Frederick Logistics Center', 'Keystone Commercial', '5200 Ballenger Creek Pike', 'Frederick', 'MD', '21703', 'Proposed logistics center sitework including clearing, grading, storm drain, public water and paving.', 'Estimating', NULL),
(407, '407-Meadowbrook', 'Meadowbrook Residential Phase II', 'Meadowbrook Homes', '390 Meadowbrook Ln', 'Boonsboro', 'MD', '21713', 'Proposed residential phase including earthwork, storm drain, water main, roadway subgrade and paving.', 'Estimating', NULL);

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
(70001, 700, 'Clearing', 'Clear and grub building and parking area.', 2.00, 32.00, 1760.00, 16.00, 2400.00, 500.00, 1.00, 'LS'),
(70002, 700, 'Demolition', 'Remove existing curb, sidewalk and asphalt.', 4.00, 64.00, 3520.00, 32.00, 4500.00, 1200.00, 1.00, 'LS'),
(70003, 700, 'Grading', 'Cut, fill and establish building pad grades.', 5.00, 80.00, 4400.00, 45.00, 6500.00, 1500.00, 4500.00, 'CY'),
(70004, 700, 'Public Water', 'Install water service and associated fittings.', 5.00, 90.00, 4950.00, 30.00, 4100.00, 8500.00, 550.00, 'LF'),
(70005, 700, 'Asphalt Repair', 'Restore asphalt disturbed by utility work.', 2.00, 36.00, 1980.00, 12.00, 1800.00, 4200.00, 1200.00, 'SF'),
(70101, 701, 'Sediment Control', 'Install perimeter controls and stabilized entrance.', 3.00, 48.00, 2640.00, 16.00, 2200.00, 4800.00, 1.00, 'LS'),
(70102, 701, 'Strip Topsoil', 'Strip and stockpile topsoil across work area.', 4.00, 64.00, 3520.00, 38.00, 5200.00, 900.00, 6200.00, 'CY'),
(70103, 701, 'Cuts to Fill', 'Excavate and place suitable material in fill areas.', 7.00, 112.00, 6160.00, 72.00, 9800.00, 1800.00, 14500.00, 'CY'),
(70104, 701, 'Storm Drain', 'Install storm pipe and structures.', 8.00, 144.00, 7920.00, 58.00, 7600.00, 28500.00, 1800.00, 'LF'),
(70105, 701, 'Curb and Sidewalk', 'Install concrete curb and sidewalk.', 6.00, 96.00, 5280.00, 24.00, 3200.00, 22000.00, 2400.00, 'LF'),
(70201, 702, 'Clearing', 'Clear wooded and brush areas for site access.', 3.00, 48.00, 2880.00, 24.00, 3600.00, 1000.00, 8.00, 'AC'),
(70202, 702, 'Mass Grading', 'Mass excavation and placement for site grades.', 10.00, 160.00, 9600.00, 100.00, 14500.00, 3500.00, 20000.00, 'CY'),
(70203, 702, 'Storm Drain', 'Install site storm drainage network.', 8.00, 120.00, 7200.00, 65.00, 8900.00, 22000.00, 1200.00, 'LF'),
(70204, 702, 'Curb Installation', 'Install concrete curb around drives and parking.', 5.00, 80.00, 4800.00, 24.00, 3300.00, 14000.00, 1800.00, 'LF'),
(70301, 703, 'Site Grading', 'Fine and rough grading around expansion area.', 6.00, 96.00, 5280.00, 50.00, 7200.00, 2200.00, 8500.00, 'CY'),
(70302, 703, 'Storm Drain Installation', 'Install storm pipe and structures.', 8.00, 120.00, 6600.00, 60.00, 8400.00, 22000.00, 900.00, 'LF'),
(70303, 703, 'Water Service', 'Install water service to expansion.', 5.00, 80.00, 4400.00, 28.00, 3900.00, 16500.00, 420.00, 'LF'),
(70304, 703, 'Concrete Improvements', 'Install curb, sidewalk and equipment pads.', 6.00, 90.00, 4950.00, 20.00, 2800.00, 19000.00, 1500.00, 'SF'),
(70401, 704, 'Sediment Control', 'Install initial erosion and sediment controls.', 3.00, 40.00, 2200.00, 15.00, 2100.00, 6200.00, 1.00, 'LS'),
(70402, 704, 'Mass Grading', 'Excavate and balance site for building pads.', 8.00, 100.00, 5500.00, 60.00, 8700.00, 2500.00, 12000.00, 'CY'),
(70403, 704, 'Storm Drain', 'Install storm drainage pipe and inlets.', 7.00, 110.00, 6050.00, 54.00, 7500.00, 24000.00, 1100.00, 'LF'),
(70404, 704, 'Roadway Subgrade', 'Prepare subgrade for internal roadway.', 5.00, 72.00, 3960.00, 42.00, 6100.00, 3800.00, 9000.00, 'SY'),
(70501, 705, 'Sediment Control', 'Install site erosion control measures.', 2.00, 32.00, 1760.00, 12.00, 1700.00, 4800.00, 1.00, 'LS'),
(70502, 705, 'Pad Grading', 'Establish retail building pad and parking grades.', 6.00, 88.00, 4840.00, 52.00, 7600.00, 2400.00, 9500.00, 'CY'),
(70503, 705, 'Storm Drain', 'Install storm drain serving retail pad.', 6.00, 90.00, 4950.00, 40.00, 5600.00, 15000.00, 1000.00, 'LF'),
(70504, 705, 'Curb and Sidewalk', 'Install curb and pedestrian sidewalk.', 5.00, 78.00, 4290.00, 18.00, 2500.00, 17500.00, 1700.00, 'LF'),
(70601, 706, 'Clearing', 'Clear and grub logistics center site.', 6.00, 96.00, 5280.00, 55.00, 8000.00, 2400.00, 24.00, 'AC'),
(70602, 706, 'Mass Grading', 'Mass excavation and site balance.', 18.00, 288.00, 15840.00, 190.00, 28500.00, 7200.00, 48000.00, 'CY'),
(70603, 706, 'Storm Drain', 'Install storm drain network and structures.', 14.00, 240.00, 13200.00, 105.00, 15500.00, 58000.00, 2900.00, 'LF'),
(70604, 706, 'Public Water', 'Install public water main and services.', 10.00, 176.00, 9680.00, 72.00, 10200.00, 47000.00, 1800.00, 'LF'),
(70605, 706, 'Paving', 'Base, asphalt paving and tie-ins.', 8.00, 128.00, 7040.00, 62.00, 9800.00, 86000.00, 22000.00, 'SY'),
(70701, 707, 'Clearing', 'Clear residential phase limits.', 5.00, 80.00, 4400.00, 44.00, 6400.00, 1800.00, 18.00, 'AC'),
(70702, 707, 'Cuts to Fill', 'Excavate and place material throughout residential phase.', 15.00, 240.00, 13200.00, 160.00, 24000.00, 5200.00, 36000.00, 'CY'),
(70703, 707, 'Storm Drain', 'Install residential storm drain system.', 12.00, 208.00, 11440.00, 88.00, 12600.00, 44000.00, 2400.00, 'LF'),
(70704, 707, 'Water Main', 'Install water main and residential services.', 11.00, 192.00, 10560.00, 78.00, 11100.00, 52000.00, 2200.00, 'LF'),
(70705, 707, 'Roadway and Paving', 'Prepare roadway subgrade and install paving.', 14.00, 224.00, 12320.00, 115.00, 17200.00, 97000.00, 17500.00, 'SY');

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
(10001, 400001, 100, 70001, 8.00, 0.00, 'Clearing supervision and chainsaw work'),
(10002, 400002, 100, 70002, 8.00, 0.00, 'Demolition supervision'),
(10003, 400003, 100, 70003, 8.00, 0.00, 'Grade checking and supervision'),
(10004, 400004, 100, 70004, 8.00, 1.00, 'Water installation supervision'),
(10005, 400005, 100, 70005, 8.00, 0.00, 'Asphalt repair supervision'),
(10006, 402001, 100, 70202, 6.00, 0.00, 'Mass grading supervision'),
(10007, 402002, 100, 70202, 7.00, 0.00, 'Mass grading supervision'),
(10008, 402003, 100, 70202, 7.00, 0.00, 'Mass grading supervision'),
(10009, 402004, 100, 70202, 7.00, 0.00, 'Mass grading supervision'),
(10010, 405001, 100, 70503, 7.00, 0.00, 'Storm drain supervision'),
(10011, 405002, 100, 70503, 7.00, 0.00, 'Storm drain supervision'),
(10012, 405003, 100, 70503, 7.00, 0.00, 'Storm drain supervision'),
(10101, 401001, 101, 70101, 8.00, 0.00, 'Sediment control supervision'),
(10102, 401002, 101, 70102, 8.00, 0.00, 'Topsoil stripping supervision'),
(10103, 401003, 101, 70103, 8.00, 0.00, 'Earthwork supervision'),
(10104, 401004, 101, 70104, 8.00, 1.00, 'Storm drain supervision'),
(10105, 401005, 101, 70105, 8.00, 0.00, 'Concrete supervision'),
(10106, 403001, 101, 70302, 7.00, 0.00, 'Storm drain supervision'),
(10107, 403002, 101, 70302, 8.00, 0.00, 'Storm drain supervision'),
(10108, 403003, 101, 70302, 8.00, 0.00, 'Utility conflict coordination'),
(10109, 403004, 101, 70302, 8.00, 0.00, 'Storm drain supervision'),
(10201, 404001, 102, 70402, 5.00, 0.00, 'Mass grading supervision'),
(10202, 404002, 102, 70402, 5.00, 0.00, 'Mass grading supervision'),
(10203, 404003, 102, 70402, 5.00, 0.00, 'Mass grading supervision'),
(10301, 400001, 103, 70001, 8.00, 0.00, 'Excavator operation'),
(10302, 400002, 103, 70002, 8.00, 0.00, 'Excavator demolition'),
(10303, 400003, 103, 70003, 8.00, 0.00, 'Dozer operation'),
(10304, 400004, 103, 70004, 8.00, 1.00, 'Excavator operation'),
(10305, 400005, 103, 70005, 8.00, 0.00, 'Equipment operation'),
(10306, 402001, 103, 70202, 6.00, 0.00, 'Excavator operation'),
(10307, 402002, 103, 70202, 7.00, 0.00, 'Dozer operation'),
(10308, 402003, 103, 70202, 7.00, 0.00, 'Excavator operation'),
(10309, 402004, 103, 70202, 7.00, 0.00, 'Dozer operation'),
(10310, 404001, 103, 70402, 5.00, 0.00, 'Dozer operation'),
(10311, 404002, 103, 70402, 5.00, 0.00, 'Excavator operation'),
(10312, 404003, 103, 70402, 5.00, 0.00, 'Dozer operation'),
(10401, 401001, 104, 70101, 8.00, 0.00, 'Loader operation'),
(10402, 401002, 104, 70102, 8.00, 0.00, 'Loader operation'),
(10403, 401003, 104, 70103, 8.00, 0.00, 'Dozer operation'),
(10404, 401004, 104, 70104, 8.00, 1.00, 'Excavator operation'),
(10405, 401005, 104, 70105, 8.00, 0.00, 'Material handling'),
(10406, 403001, 104, 70302, 7.00, 0.00, 'Excavator operation'),
(10407, 403002, 104, 70302, 8.00, 0.00, 'Excavator operation'),
(10408, 403003, 104, 70302, 7.00, 0.00, 'Excavation around utilities'),
(10409, 403004, 104, 70302, 8.00, 0.00, 'Excavator operation'),
(10410, 405001, 104, 70503, 7.00, 0.00, 'Excavator operation'),
(10411, 405002, 104, 70503, 7.00, 0.00, 'Excavator operation'),
(10412, 405003, 104, 70503, 6.00, 0.00, 'Excavator operation'),
(10501, 400001, 105, 70001, 8.00, 0.00, 'Clearing and debris handling'),
(10502, 400002, 105, 70002, 8.00, 0.00, 'Demo cleanup'),
(10503, 400003, 105, 70003, 8.00, 0.00, 'Grade support'),
(10504, 400004, 105, 70004, 8.00, 0.00, 'Pipe installation'),
(10505, 400005, 105, 70005, 8.00, 0.00, 'Asphalt restoration'),
(10506, 402001, 105, 70202, 6.00, 0.00, 'Grade checking'),
(10507, 402002, 105, 70202, 6.00, 0.00, 'Grade support'),
(10508, 402003, 105, 70202, 6.00, 0.00, 'Fill placement support'),
(10509, 402004, 105, 70202, 4.00, 2.00, 'Grade checking and finish work'),
(10601, 401001, 106, 70101, 8.00, 0.00, 'Fence and inlet protection installation'),
(10602, 401002, 106, 70102, 8.00, 0.00, 'Stockpile support'),
(10603, 401003, 106, 70103, 8.00, 0.00, 'Grade checking'),
(10604, 401004, 106, 70104, 8.00, 0.00, 'Pipe installation'),
(10605, 401005, 106, 70105, 8.00, 0.00, 'Curb and sidewalk installation'),
(10606, 403001, 106, 70302, 7.00, 0.00, 'Pipe installation'),
(10607, 403002, 106, 70302, 8.00, 0.00, 'Pipe and bedding installation'),
(10608, 403003, 106, 70302, 8.00, 0.00, 'Hand excavation and pipe work'),
(10609, 403004, 106, 70302, 7.00, 1.00, 'Pipe installation and cleanup'),
(10701, 404001, 107, 70402, 4.00, 0.00, 'Grade checking'),
(10702, 404002, 107, 70402, 4.00, 0.00, 'Fill placement'),
(10703, 404003, 107, 70402, 5.00, 0.00, 'Grade checking'),
(10801, 405001, 108, 70503, 6.00, 0.00, 'Pipe installation'),
(10802, 405002, 108, 70503, 5.00, 0.00, 'Stone bedding and pipe work'),
(10803, 405003, 108, 70503, 6.00, 0.00, 'Pipe and bedding installation');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(50) NOT NULL COMMENT 'Login - lastname&first inital',
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL COMMENT 'Foreman, Admin, PM, Executive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
('BrooksD', 'Daniel', 'Brooks', 'daniel.brooks@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Foreman', '2026-09-10 22:51:15'),
('CarterJ', 'Justin', 'Carter', 'justin.carter@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Foreman', '2026-09-10 22:51:15'),
('HayesR', 'Robert', 'Hayes', 'robert.hayes@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Executive', '2026-09-10 22:51:15'),
('LeeA', 'Amanda', 'Lee', 'amanda.lee@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Admin', '2026-09-10 22:51:15'),
('MorganR', 'Rachel', 'Morgan', 'rachel.morgan@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Estimator', '2026-09-10 22:51:15'),
('ReedM', 'Marcus', 'Reed', 'marcus.reed@fieldledger.test', '$2y$10$ObzkN5rcLgOwGPZ69.p0CO4yqPXoCz5crVwdXOhu4Aj3m96TccQ8G', 'Foreman', '2026-09-10 22:51:15');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_reports`
--
ALTER TABLE `daily_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Daily Report', AUTO_INCREMENT=405004;

--
-- AUTO_INCREMENT for table `daily_task_entries`
--
ALTER TABLE `daily_task_entries`
  MODIFY `daily_task_entry_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unit task activity record', AUTO_INCREMENT=40500302;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `estimate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=708;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=408;

--
-- AUTO_INCREMENT for table `job_tasks`
--
ALTER TABLE `job_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70706;

--
-- AUTO_INCREMENT for table `labor_entries`
--
ALTER TABLE `labor_entries`
  MODIFY `labor_entry_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique labor record', AUTO_INCREMENT=10804;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
