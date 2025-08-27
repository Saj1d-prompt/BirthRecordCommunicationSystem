-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2025 at 08:52 PM
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
-- Table structure for table `newborn_t`
--

CREATE TABLE `newborn_t` (
  `birthRegistrationNum` int(50) DEFAULT NULL,
  `weight` float(5,2) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `location` varchar(80) NOT NULL,
  `dateofBirth` date NOT NULL DEFAULT current_timestamp(),
  `fullName` varchar(80) NOT NULL,
  `fatherName` varchar(80) NOT NULL,
  `motherName` varchar(80) NOT NULL,
  `permanentAddress` varchar(90) NOT NULL,
  `contactNum` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
