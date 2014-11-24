-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2014 at 05:57 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `environment_room`
--
CREATE DATABASE IF NOT EXISTS `environment_room` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `environment_room`;

-- --------------------------------------------------------

--
-- Table structure for table `scenes`
--

DROP TABLE IF EXISTS `scenes`;
CREATE TABLE IF NOT EXISTS `scenes` (
  `name` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `numImages` int(3) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `soundtrack` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scenes`
--

INSERT INTO `scenes` (`name`, `description`, `numImages`, `path`, `soundtrack`) VALUES
('Park View', 'The beautiful View of the park', 1, 'data/Park View/', 'HanhTrinhDiTimBinhAn_lha_MPKH-HuuTruong.mp3 '),
('Waterfall', 'Beautiful Waterfall views', 2, 'data/Waterfall/', 'HanhTrinhDiTimBinhAn_lha_MPKH-HuuTruong.mp3 ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(32) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scenes`
--
ALTER TABLE `scenes`
 ADD PRIMARY KEY (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
