CREATE DATABASE  IF NOT EXISTS `reenduxs_rhomes` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `reenduxs_rhomes`;
-- MySQL dump 10.13  Distrib 5.6.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: reenduxs_rhomes
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.21-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `rms_kunobgyn`
--

DROP TABLE IF EXISTS `rms_kunobgyn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rms_kunobgyn` (
  `kog_id` varchar(12) NOT NULL,
  `kog_kun_id` varchar(12) NOT NULL,
  `kog_kpr_id` varchar(12) NOT NULL,
  `kog_menarrche` int(11) DEFAULT NULL,
  `kog_lasthaid` date DEFAULT NULL,
  `kog_rikawin` varchar(255) DEFAULT NULL,
  `kog_haidproblem` varchar(100) DEFAULT NULL,
  `kog_gravida` int(11) DEFAULT 0,
  `kog_paritas` int(11) DEFAULT 0,
  `kog_abortus` int(11) DEFAULT 0,
  `kog_masalah` varchar(100) DEFAULT NULL,
  `kog_rikit_kel` varchar(255) DEFAULT NULL,
  `kog_rigyn` varchar(100) DEFAULT NULL,
  `kog_kb` varchar(25) DEFAULT NULL,
  `kog_kb_pli` varchar(100) DEFAULT NULL,
  `kog_mata` varchar(100) DEFAULT NULL,
  `kog_dada` varchar(100) DEFAULT NULL,
  `kog_ektramilas` varchar(100) DEFAULT NULL,
  `kog_kardio` varchar(100) DEFAULT NULL,
  `kog_ob_abd` varchar(100) DEFAULT NULL,
  `kog_ob_tfu` int(11) DEFAULT NULL,
  `kog_ob_letpung` varchar(25) DEFAULT NULL,
  `kog_ob_pres` varchar(100) DEFAULT NULL,
  `kog_ob_kul` int(11) DEFAULT NULL,
  `kog_ob_kulstat` varchar(25) DEFAULT NULL,
  `kog_ob_kon` int(11) DEFAULT NULL,
  `kog_ob_konstat` varchar(25) DEFAULT NULL,
  `kog_gy_vul` varchar(100) DEFAULT NULL,
  `kog_gy_vag` varchar(45) DEFAULT NULL,
  `kog_gy_touch` varchar(45) DEFAULT NULL,
  `kog_gy_pang` varchar(45) DEFAULT NULL,
  `kog_gy_feto` varchar(45) DEFAULT NULL,
  `kog_nf_fut` varchar(25) DEFAULT NULL,
  `kog_nf_ctut` varchar(25) DEFAULT NULL,
  `kog_nf_loc` varchar(25) DEFAULT NULL,
  `kog_nf_luk` varchar(25) DEFAULT NULL,
  `kog_pelaksana` varchar(100) DEFAULT NULL,
  `kog_ketlain` varchar(100) DEFAULT NULL,
  `kog_kondisi` varchar(25) DEFAULT NULL,
  `kog_efeksmp` varchar(45) DEFAULT NULL,
  `kog_kunulang` tinyint(4) DEFAULT 0,
  `kog_rirawat` varchar(100) DEFAULT NULL,
  `kog_rioperasi` varchar(100) DEFAULT NULL,
  `kog_tgcreate` datetime DEFAULT NULL,
  `kog_ipaddrcreate` varchar(15) DEFAULT NULL,
  `kog_usercreate` varchar(15) DEFAULT NULL,
  `kog_tgedit` datetime DEFAULT NULL,
  `kog_ipaddredit` varchar(15) DEFAULT NULL,
  `kog_useredit` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`kog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rms_kunobgyn`
--

LOCK TABLES `rms_kunobgyn` WRITE;
/*!40000 ALTER TABLE `rms_kunobgyn` DISABLE KEYS */;
INSERT INTO `rms_kunobgyn` VALUES ('20210000001','20210000022','20211218004',9,'2021-12-18','1. umur saya/suami: 25/26, 2.umur saya/suami: 35/40','Spoting',3,2,1,'Pendarahan','DM, Hipertensi','Myoma','Suntik','','Pandangan Kabur','Puting susu menonjol','Tungkai simetris','Batuk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Hamil',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('20210000002','20210000024','20211220001',10,'2021-12-18','1. umur saya/suami: 30/31','Menorraghia',2,2,0,'Pendarahan','Kanker, DM','Myoma','Spiral','PID/Radang panggul','','Mamae asymmetris','Tungkai simetris','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Rumah jauh dari rumah sakit','Hamil',NULL,0,'Dirawat COVID19 di RSUD Dr. Sutomo','Operasi cesar di RSIA',NULL,NULL,NULL,NULL,NULL,NULL),('20210000003','20210000025','20211220002',NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'membesar dengan arah memanjang',8,'Normal','Normal',100,'Teratur',100,'Tidak teratur','Lendir, Darah','Normal',NULL,'Membesar','Normal',NULL,NULL,NULL,NULL,NULL,'Sakit perut di malam hari','Pasca melahirkan',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),('20211230001','20210000025','20211220002',12,'2021-12-30','','Dismenorroe, Spoting',0,0,0,'','','','','','Pandangan Kabur','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','asdasd',0,'','','2021-12-30 10:13:57','::1','demo','2021-12-30 10:13:57','::1','demo'),('20211230002','20210000025','20211220002',12,'2021-12-23','','',0,0,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 15:54:35','::1','demo','2021-12-30 15:54:35','::1','demo'),('20211230003','20210000025','20211220002',12,'2021-12-23','','',0,0,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 15:54:56','::1','demo','2021-12-30 15:54:56','::1','demo'),('20211230004','20210000025','20211220002',12,'2021-12-23','','',0,0,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 15:55:22','::1','demo','2021-12-30 15:55:22','::1','demo'),('20211230005','20210000025','20211220002',0,NULL,'','',0,0,0,'','','','','','Pandangan Kabur, Pemandangan dua','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','Pasca melahirkan','qweq',0,'','','2021-12-30 16:20:09','::1','demo','2021-12-30 16:20:09','::1','demo'),('20211230006','20210000025','20211220002',0,NULL,'','',0,0,0,'','','','','','Pandangan Kabur, Pemandangan dua','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','Pasca melahirkan','qweq',0,'','','2021-12-30 16:20:15','::1','demo','2021-12-30 16:20:15','::1','demo'),('20211230007','20210000026','20211230006',12,'2021-12-15','','',0,0,0,'','','','','','Pandangan Kabur, Pemandangan dua','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','234234',0,'','','2021-12-30 16:21:14','::1','demo','2021-12-30 16:21:14','::1','demo'),('20211230008','20210000027','20211230005',0,NULL,'','',0,0,0,'','','','','','Pandangan Kabur, Pemandangan dua','Mamae symmetric, Mamae asymmetric, Puting menonjol','Edema +','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 16:28:30','::1','demo','2021-12-30 16:28:30','::1','demo'),('20211230009','20210000027','20211229003',8,'2021-12-29','','Spoting, Menorragia, Metrorhagia',0,0,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 16:30:10','::1','demo','2021-12-30 16:30:10','::1','demo'),('20211230010','20210000028','20211230008',11,'2021-12-29','','Dismenorroe, Spoting, Syndrom pre menstruasi',0,0,0,'','','','','','','Mamae symmetric, Mamae asymmetric','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','sakit',0,'','','2021-12-30 16:33:28','::1','demo','2021-12-31 06:16:02','::1','demo'),('20211230011','20210000029','20211230009',0,'2021-12-29','','Dismenorroe, Spoting',0,0,0,'','Alergi, Batuk','','','Pendarahan, PID/radang panggul, Pengenan','Pemandangan dua, Selerai cleric, Conjungtive pucat','Mamae symmetric, Mamae asymmetric, Puting menonjol, Klostrum, Areola Hiperpegmentasi, Tumor','','',NULL,0,'','',2,'Teratur',4,'Tidak teratur','','','','','','','','','','','','','',0,'','','2021-12-30 16:40:39','::1','demo','2021-12-31 06:18:29','::1','demo'),('20211230012','20210000028','20211230007',3,'2021-12-07','','',0,0,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 17:11:40','::1','demo','2021-12-30 17:11:40','::1','demo'),('20211230013','20210000022','20211218001',0,'2021-12-22','','',0,0,0,'','','','','','Pemandangan dua, Conjungtive pucat','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 17:43:07','::1','demo','2021-12-30 17:47:20','::1','demo'),('20211230014','20210000027','20211230004',0,'2021-12-29','','',0,0,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','sakit',0,'','','2021-12-30 18:11:48','::1','demo','2021-12-30 18:12:08','::1','demo'),('20211230015','20210000027','20211230004',0,NULL,'','',0,0,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','','',0,'','','2021-12-30 18:12:38','::1','demo','2021-12-30 18:12:38','::1','demo'),('20211231001','20210000029','20211231001',12,'2021-12-30','','',0,0,0,'','Alergi, sakit perut','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','Hamil','sakit kepala',0,'','','2021-12-31 06:17:43','::1','demo','2021-12-31 06:22:22','::1','demo'),('20211231002','20210000030','',12,'2021-12-30','','Menorragia',3,3,0,'','','','','','','','','',NULL,0,'','',0,'',0,'','','','','','','','','','','','','Hamil','',0,'','','2021-12-31 19:30:19','::1','demo','2021-12-31 19:30:19','::1','demo'),('20211231003','20210000031','20211231003',5,'2021-12-22','ok','Menorragia, Metrorhagia',3,2,1,'Pendarahan, lesu','Kanker, Penyakit hati, Hipertensi, Diabetes, Ginjal, TBC, Penyakit jiwa, Kelainan bawaan, Hamil kembar, Epilepsi, Alergi, keseleo','Infetilitas, PMS, Operasi kandungan, Perkosaan','ok','Pendarahan, PID/radang panggul, ok','Pandangan Kabur, Pemandangan dua','Klostrum, Areola Hiperpegmentasi, Tumor','Tungkai simetris, Edema +, Edema -','Sputum, Batuk darah, Nyeri dada, Keringat malam','Memanjang, Melebar, Pelebaran vena, Linea alaba, Linea nigra, Striae albican, menonjol',0,'ok','ok',2,'Teratur',20,'Tidak teratur','Lendir','ok','ok','ok','ok','ok','ko','ok','ok','ok','ok','Hamil','',0,'ok','ok','2021-12-31 20:26:29','::1','demo','2021-12-31 21:05:08','::1','demo');
/*!40000 ALTER TABLE `rms_kunobgyn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rms_kunrihamil`
--

DROP TABLE IF EXISTS `rms_kunrihamil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rms_kunrihamil` (
  `mil_id` int(11) NOT NULL AUTO_INCREMENT,
  `mil_kpr_id` varchar(12) NOT NULL,
  `mil_tgl` date DEFAULT NULL,
  `mil_tpt` varchar(25) DEFAULT NULL,
  `mil_umur` int(11) DEFAULT NULL,
  `mil_jenis` varchar(6) NOT NULL,
  `mil_tolong` varchar(25) DEFAULT NULL,
  `mil_sulit` varchar(25) DEFAULT NULL,
  `mil_lamin` char(1) DEFAULT NULL,
  `mil_skrg` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`mil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rms_kunrihamil`
--

LOCK TABLES `rms_kunrihamil` WRITE;
/*!40000 ALTER TABLE `rms_kunrihamil` DISABLE KEYS */;
/*!40000 ALTER TABLE `rms_kunrihamil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'reenduxs_rhomes'
--

--
-- Dumping routines for database 'reenduxs_rhomes'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-01  9:32:07
