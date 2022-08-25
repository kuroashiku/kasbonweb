-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2022 at 04:18 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reenduxs_rhomes`
--

-- --------------------------------------------------------

--
-- Table structure for table `rms_kundental`
--

CREATE TABLE `rms_kundental` (
  `den_id` varchar(12) NOT NULL,
  `den_kun_id` varchar(11) NOT NULL,
  `den_nogigi` varchar(4) DEFAULT NULL,
  `den_tin` varchar(10) DEFAULT NULL,
  `den_keadaan` varchar(10) DEFAULT NULL,
  `den_posisi` varchar(10) DEFAULT NULL,
  `den_jembatan` varchar(100) DEFAULT NULL,
  `den_keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rms_kundental`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `rms_kundental`
--
ALTER TABLE `rms_kundental`
  ADD PRIMARY KEY (`den_id`),
  ADD KEY `fk_rms_kundental_rms_kunjungan1_idx` (`den_kun_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rms_kundental`
--
ALTER TABLE `rms_kundental`
  ADD CONSTRAINT `fk_rms_kundental_rms_kunjungan1` FOREIGN KEY (`den_kun_id`) REFERENCES `rms_kunjungan` (`kun_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
