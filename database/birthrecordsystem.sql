-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2025 at 09:53 PM
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
-- Database: `birthrecordsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `correctionapp_t`
--

CREATE TABLE `correctionapp_t` (
  `correctionSegment` varchar(500) DEFAULT NULL,
  `correctionReason` varchar(50) NOT NULL,
  `correctionName` varchar(80) DEFAULT NULL,
  `correctionFatherName` varchar(80) DEFAULT NULL,
  `correctionMotherName` varchar(80) DEFAULT NULL,
  `correctionGender` varchar(20) DEFAULT NULL,
  `correctionDoB` date DEFAULT NULL,
  `correctionPermanentAdd` varchar(80) DEFAULT NULL,
  `applicationStatus` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newborns`
--

CREATE TABLE `newborns` (
  `reg_number` varchar(20) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `weight` double DEFAULT NULL,
  `gestation` varchar(5) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `dateofBirth` date NOT NULL DEFAULT current_timestamp(),
  `fullName` varchar(80) DEFAULT NULL,
  `fatherName` varchar(80) DEFAULT NULL,
  `motherName` varchar(80) DEFAULT NULL,
  `permanentAddress` varchar(90) DEFAULT NULL,
  `contactNum` varchar(20) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newborns`
--

INSERT INTO `newborns` (`reg_number`, `id`, `weight`, `gestation`, `gender`, `location`, `dateofBirth`, `fullName`, `fatherName`, `motherName`, `permanentAddress`, `contactNum`, `status`) VALUES
('20250829161535184', 0, 0.11993, '8', 'Male', 'Bashundhara, Dhaka, Bangladesh', '2025-08-29', 'kazi Toushia Nahar', 'Kazi Kamruzzaman', 'Kamrunnahar', 'Mirpur', '01568632476', 'accepted'),
('20250829161645907', 0, 0.34132, '9', 'Male', 'Bashundhara, Dhaka, Bangladesh', '0000-00-00', 'Sajid', 'Md. Sirajul Islam', 'MST. Shahnaz parveen', 'Basundhara Residential Area, Block C, Road 7, House no 164', '01717626143', 'rejected'),
('20250829202337697', 0, 1.72864, '9', 'Male', 'Bashundhara, Dhaka, Bangladesh', '2025-08-30', NULL, NULL, NULL, NULL, NULL, 'accepted'),
('20250829202719121', 0, 2.71063, '8', 'Female', 'Bashundhara, Dhaka, Bangladesh', '2025-08-30', 'kazi Toushia Nahar', 'Kazi Kamruzzaman', 'Kamrunnahar', 'Mirpur', '01568632476', 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `reissueapp_t`
--

CREATE TABLE `reissueapp_t` (
  `applicantName` varchar(80) NOT NULL,
  `birthRegistrationNum` int(50) NOT NULL,
  `contactNum` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `reason` varchar(70) NOT NULL,
  `additionalInfo` varchar(90) DEFAULT NULL,
  `applicationStatus` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccinepreschedule_t`
--

CREATE TABLE `vaccinepreschedule_t` (
  `vaccineName` varchar(50) NOT NULL,
  `diseaseProtected` varchar(50) NOT NULL,
  `recommendAge` int(30) NOT NULL,
  `doseNum` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccinepreschedule_t`
--

INSERT INTO `vaccinepreschedule_t` (`vaccineName`, `diseaseProtected`, `recommendAge`, `doseNum`) VALUES
('BCG', 'Tuberculosis', 0, 1),
('Hep-B (Birth dose)', 'Hepatitis B', 0, 1),
('OPV-0', 'Polio', 0, 1),
('OPV-1', 'Polio', 2, 1),
('OPV-2', 'Polio', 3, 2),
('OPV-3', 'Polio', 4, 3),
('Pentavalent-1', 'Diphtheria, Tetanus, Pertussis, Hepatitis B, Hib', 2, 1),
('Pentavalent-2', 'Diphtheria, Tetanus, Pertussis, Hepatitis B, Hib', 3, 2),
('Pentavalent-3', 'Diphtheria, Tetanus, Pertussis, Hepatitis B, Hib', 4, 3),
('PCV-1', 'Pneumococcal diseases', 2, 1),
('PCV-2', 'Pneumococcal diseases', 3, 2),
('PCV-3', 'Pneumococcal diseases', 4, 3),
('IPV', 'Polio (Inactivated)', 4, 1),
('MR-1', 'Measles, Rubella', 9, 1),
('MR-2', 'Measles, Rubella', 15, 1),
('JE-1', 'Japanese Encephalitis', 9, 1),
('JE-2', 'Japanese Encephalitis', 24, 1),
('Vitamin A', 'Vitamin A deficiency', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vaccinereminder_t`
--

CREATE TABLE `vaccinereminder_t` (
  `birthRegistrationNum` int(50) NOT NULL,
  `vaccineName` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `rescheduleDate` date DEFAULT NULL,
  `rescheduleReason` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
