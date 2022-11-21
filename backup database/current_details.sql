-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2022 at 03:45 AM
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
-- Table structure for table `current_details`
--

CREATE TABLE `current_details` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_area` varchar(255) NOT NULL,
  `user_premises` varchar(20) NOT NULL,
  `user_account` varchar(20) NOT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `category` varchar(100) DEFAULT NULL,
  `status` varchar(15) DEFAULT 'Pending',
  `feedback` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `current_details`
--

INSERT INTO `current_details` (`user_id`, `name`, `user_address`, `user_area`, `user_premises`, `user_account`, `submitted_at`, `category`, `status`, `feedback`) VALUES
(1, 'haoyuan', 'lot123', 'abc', '1', '1111111111', '2022-09-13 10:09:02', 'Domestic', 'Approved', NULL),
(3, 'haoyuan', 'lot1234', 'long', '123', '7777777777', '2022-10-05 09:43:47', 'Domestic', 'Approved', NULL),
(4, 'ali', 'lot123', 'abc', '1', '1234567890', '2022-10-06 14:28:53', 'Domestic', 'Approved', NULL),
(2, 'aaa', 'lot123', 'abc', '1', '1234567890', '2022-10-22 17:25:17', 'Domestic', 'Approved', NULL),
(7, 'test2', 'lot123', 'abc', '1', '1234567890', '2022-10-23 09:57:03', 'Domestic', 'Approved', NULL),
(9, 'test4', 'test4', '4', '4', '4444444444', '2022-11-02 23:14:09', 'Domestic', 'Approved', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `current_details`
--
ALTER TABLE `current_details`
  ADD PRIMARY KEY (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
