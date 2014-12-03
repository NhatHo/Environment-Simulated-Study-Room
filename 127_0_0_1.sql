-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2014 at 01:09 AM
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
  `title` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `cover` varchar(100) NOT NULL,
  `proj1_num_imgs` int(2) NOT NULL,
  `proj1_files` varchar(2000) NOT NULL,
  `proj2_num_imgs` int(2) NOT NULL,
  `proj2_files` varchar(2000) NOT NULL,
  `proj3_num_imgs` int(2) NOT NULL,
  `proj3_files` varchar(2000) NOT NULL,
  `soundtrack` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scenes`
--

INSERT INTO `scenes` (`title`, `description`, `cover`, `proj1_num_imgs`, `proj1_files`, `proj2_num_imgs`, `proj2_files`, `proj3_num_imgs`, `proj3_files`, `soundtrack`) VALUES
('Beautiful Lake', 'Come on Work it', '1640048.jpg ', 3, 'jpg', 1, 'starwar.mp4 ', 1, 'Jurassic World - Official Trailer (HD) 240p (Video Only).mp4 ', 'HanhTrinhDiTimBinhAn_lha_MPKH-HuuTruong.mp3 '),
('Testing this gain', 'Come on work you stupid', 'spectacular_jungle_waterfall_wallpaper_1920x1080.jpg ', 4, 'jpg', 1, 'starwar.mp4 ', 1, 'Jurassic World - Official Trailer (HD) 240p (Video Only).mp4 ', 'GuitarYeuThuong_lha_MPKH-HuuTruong.mp3 ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `salt`) VALUES
('admin', '7fe2058362c24e1f13b64dd2fb6206854e6a4f94b935b632d5fd7008e9dbffc5a95820e885f50bcc685dec1a84dfe291b38d044798a044b6b69abe3ebbf8f7e5', '0bf26f677c6c5eda4febe54c6c3f8c7d7cb209a62503bcd11122e7a566abc555bd39bb6c56f4239d8598a2d8604240d1192dc9f9e3fec69488e974a086e73039'),
('student', 'd3881ede0fac8e2d38a30972d9d8f9f90d3c30f50568346a68b92d8258e7426858e02d3e621b10e2a92606bffe0d76f49351b40c7a8a1a407e43e961f50fe70e', 'ab547d2847ce4a4fa3f2374d30d4c1d74fb5bc96e06fc32db2b7e3c309db2503542a83ae2e753238e49a8b3e1b7a3137d45fceb7d6f9916223ced526e0ce4cb4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scenes`
--
ALTER TABLE `scenes`
 ADD PRIMARY KEY (`title`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
