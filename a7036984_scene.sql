
-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 11, 2014 at 04:10 PM
-- Server version: 5.1.57
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `a7036984_Scene`
--

-- --------------------------------------------------------

--
-- Table structure for table `Scenes`
--

DROP TABLE IF EXISTS `scenes`;
CREATE TABLE `scenes` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `image` varchar(100) NOT NULL,
  `audio` varchar(100) NOT NULL,
  `video` varchar(100) NOT NULL,
  PRIMARY KEY (`id`) KEY_BLOCK_SIZE=1024,
  UNIQUE KEY `name` (`name`) KEY_BLOCK_SIZE=1024,
  UNIQUE KEY `id` (`id`) KEY_BLOCK_SIZE=1024
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `Scenes`
--

INSERT INTO `scenes` VALUES(8, 'Ocean', 'A relaxing ocean scene.', 'pictures/ocean_1415240533.jpg', 'http://www.youtube.com/watch?v=f77SKdyn-1Y', 'http://www.youtube.com/watch?v=f77SKdyn-1Y');
INSERT INTO `scenes` VALUES(9, 'Waterfall', 'A beautiful waterfall scene.', 'pictures/waterfall_1415240553.jpg', 'http://www.youtube.com/watch?v=7r8NE4osAYA', 'http://www.youtube.com/watch?v=7r8NE4osAYA');
INSERT INTO `scenes` VALUES(10, 'Forest', 'A calming forest scene.\r\n', 'pictures/forest_1415240568.jpg', 'http://www.youtube.com/watch?v=2G8LAiHSCAs', 'http://www.youtube.com/watch?v=2G8LAiHSCAs');
INSERT INTO `scenes` VALUES(11, 'Thunderstorm', 'A fierce thunderstorm scene.', 'pictures/thunderstorm_1415240469.jpg', 'http://www.youtube.com/watch?v=mQ9OWMsJBTk', 'http://www.youtube.com/watch?v=mQ9OWMsJBTk');
INSERT INTO `scenes` VALUES(12, 'Light Thunderstorm', 'A mild thunderstorm scene.', 'pictures/light thunderstorm.jpg', 'https://www.youtube.com/watch?v=aE47I6V-J28', 'https://www.youtube.com/watch?v=aE47I6V-J28');
