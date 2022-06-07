-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2013 at 06:35 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `uplink_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `unlocked` tinyint(1) NOT NULL,
  `right1` tinyint(1) NOT NULL,
  `right2` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `rights_FI_1` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rights`
--

INSERT INTO `rights` (`unlocked`, `right1`, `right2`, `user_id`) VALUES
(1, 1, 1, 1),
(1, 1, 1, 6),
(0, 1, 0, 16),
(0, 1, 0, 19),
(0, 0, 1, 9),
(0, 1, 0, 14),
(0, 1, 0, 15),
(1, 0, 1, 23),
(0, 0, 1, 20),
(0, 0, 0, 21),
(1, 0, 0, 22);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `first_name`, `last_name`, `password`) VALUES
(19, 'propel', 'Propel', 'Smart', '6f304c372c84d202ff8e87512af159e3'),
(6, 'mynnt', 'My', 'Nguyen', 'd2107bdccd09c52ac3d8eb0dee5d4e78'),
(1, 'admin', 'Admin', 'Uplink', '3f14558f97da998422d79c3b87fe9d74'),
(23, 'thymy', 'Thy', 'My', '84700b0ef6d014495f9662524a186740'),
(9, 'justine', 'Justine', 'Timberlake', '4a8a08f09d37b73795649038408b5f33'),
(16, 'madonna', 'Madonna', 'Singer', '6ecc0500d10ea0a41cba814ce259ef75'),
(15, 'brunner', 'Brunner', 'Brunner', 'fa3b8367e3d38d8118bd91e91087152c'),
(14, 'christina', 'Christina', 'Aguilera', 'e311dd5fd4cdbba780ea0d0062df7788'),
(20, 'taylor', 'Taylor', 'Swift', '7d8bc5f1a8d3787d06ef11c97d4655df'),
(21, 'blake', 'Blake', 'Shelton', '3aa49ec6bfc910647fa1c5a013e48eef'),
(22, 'mariah', 'Mariah', 'Carey', 'ac1bbc3b39f2b3fe9a45111fc9db1ca4');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
