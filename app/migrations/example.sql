-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 25, 2020 at 02:59 PM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my2cents`
--

-- --------------------------------------------------------

--
-- Table structure for table `btcexample`
--

DROP TABLE IF EXISTS `btcexample`;
CREATE TABLE IF NOT EXISTS `btcexample` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_currency` varchar(4) NOT NULL,
  `to_currency` varchar(4) NOT NULL,
  `exchange_rate` decimal(10,2) NOT NULL,
  `last_refreshed` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `btcexample`
--

INSERT INTO `btcexample` (`id`, `from_currency`, `to_currency`, `exchange_rate`, `last_refreshed`) VALUES
(1, 'BTC', 'USD', '8359.00', 20200125);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
