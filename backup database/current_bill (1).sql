-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2022 at 02:36 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ocawbms`
--

-- --------------------------------------------------------

--
-- Table structure for table `current_bill`
--

CREATE TABLE `current_bill` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `meter` varchar(20) NOT NULL,
  `units` varchar(30) NOT NULL,
  `charge` varchar(20) NOT NULL,
  `total` varchar(20) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp(),
  `due` date NOT NULL,
  `status` varchar(20) DEFAULT 'Not Paid'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `current_bill`
--

INSERT INTO `current_bill` (`id`, `user_id`, `month`, `meter`, `units`, `charge`, `total`, `updated_at`, `due`, `status`) VALUES
(1, 1, '2022-11', '123', '20', '100', '10', '2022-09-13 10:19:58', '2022-09-20', 'Paid'),
(24, 9, '2023-04', '67', '67', '67', '67', '2022-11-07 12:03:10', '2023-05-07', 'Paid'),
(3, 3, '2022-10', '50', '50', '50', '50', '2022-10-05 09:46:30', '2022-11-05', 'Not Paid'),
(4, 4, '2022-11', '12345', '30', '100', '10', '2022-10-06 14:32:01', '2022-12-14', 'Paid'),
(5, 2, '2022-11', '123', '100', '100', '100', '2022-11-01 22:01:15', '2022-12-13', 'Not Paid'),
(6, 4, '2023-02', '123', '20', '100', '20', '2022-11-02 22:34:45', '2023-03-02', 'Paid'),
(7, 1, '2023-02', '50', '20', '100', '50', '2022-11-02 22:38:00', '2023-03-02', 'Not Paid'),
(8, 1, '2023-02', '9999', '99999', '99999', '99999', '2022-11-02 22:47:35', '2023-03-21', 'Not Paid'),
(51, 4, '2023-06', '5', '6', '7', '8', '2022-11-10 10:13:03', '2022-11-16', 'Paid'),
(10, 7, '2023-02', '99999999999', '999999999999999999', '9999999999999999999', '99999999999999999', '2022-11-02 22:56:21', '2023-03-14', 'Not Paid'),
(60, 9, '2023-06', '67', '9', '10', '11', '2022-11-11 11:57:11', '2022-11-24', 'Not Paid'),
(73, 9, '2023-06', '134', '67', '22.641890337238', 'Array', '2022-11-12 11:13:41', '2022-11-22', 'Not Paid'),
(13, 9, '2023-02', '1111111', '111', '1111', '111', '2022-11-02 23:20:50', '2023-03-02', 'Paid'),
(40, 1, '2023-06', '90989', '9898', '89898', '89898', '2022-11-09 18:48:46', '2022-11-23', 'Not Paid'),
(15, 1, '2023-02', '50', '20', '100', '99999', '2022-11-02 23:28:26', '2023-03-01', 'Not Paid'),
(16, 1, '2023-02', '12345', '99999', '44444444', '100', '2022-11-02 23:30:36', '2023-03-03', 'Not Paid'),
(17, 1, '2023-02', '1213123', '21312312', '12312312', '12312312', '2022-11-03 09:55:04', '2023-03-03', 'Not Paid'),
(18, 2, '2023-03', '11111111', '1111111', '1111111', '1111111', '2022-11-03 12:08:20', '2023-04-03', 'Not Paid'),
(21, 1, '2023-04', '115', '50', '20', '20', '2022-11-07 10:47:16', '2023-05-09', 'Paid'),
(20, 9, '2023-03', '555555', '555', '55555', '55555', '2022-11-03 13:09:29', '2023-04-03', 'Paid'),
(26, 9, '2023-06', '34', '34', '34', '34', '2022-11-08 10:21:00', '2023-07-04', 'Paid');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `current_bill`
--
ALTER TABLE `current_bill`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `current_bill`
--
ALTER TABLE `current_bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
