-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2022 at 04:59 PM
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
-- Table structure for table `rms_kunppi`
--

CREATE TABLE `rms_kunppi` (
  `kpi_id` varchar(12) NOT NULL,
  `kpi_kun_id` varchar(11) NOT NULL,
  `kpi_tanggal` datetime DEFAULT NULL,
  `kpi_kateter` varchar(15) DEFAULT NULL,
  `kpi_infus` varchar(15) DEFAULT NULL,
  `kpi_plebitis` int(11) DEFAULT NULL,
  `kpi_postophari` int(11) DEFAULT NULL,
  `kpi_postopinfeksi` varchar(15) DEFAULT NULL,
  `kpi_tirahbaring` varchar(15) DEFAULT NULL,
  `kpi_hap` varchar(15) DEFAULT NULL,
  `kpi_hasilkultur` text DEFAULT NULL,
  `kpi_gunaantibiotik` text DEFAULT NULL,
  `kpi_klasinfeksi` text DEFAULT NULL,
  `kpi_diagnosa` text DEFAULT NULL,
  `kpi_ruangan` varchar(100) DEFAULT NULL,
  `kpi_user` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rms_kunppi`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `rms_kunppi`
--
ALTER TABLE `rms_kunppi`
  ADD PRIMARY KEY (`kpi_id`),
  ADD KEY `fk_rms_kunppi_rms_kunjungan1_idx` (`kpi_kun_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rms_kunppi`
--
ALTER TABLE `rms_kunppi`
  ADD CONSTRAINT `fk_rms_kunppi_rms_kunjungan1` FOREIGN KEY (`kpi_kun_id`) REFERENCES `rms_kunjungan` (`kun_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
