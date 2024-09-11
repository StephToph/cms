-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2024 at 06:59 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `td_form_extension`
--

CREATE TABLE `td_form_extension` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `church_id` int(11) NOT NULL DEFAULT 0,
  `fields` text DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `form_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `td_form_extension`
--

INSERT INTO `td_form_extension` (`id`, `user_id`, `church_id`, `fields`, `reg_date`, `form_id`) VALUES
(2, 2, 3, '[{\"label\":\"Address\",\"type\":\"text\"}]', '2024-09-11 17:44:05', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `td_form_extension`
--
ALTER TABLE `td_form_extension`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `td_form_extension`
--
ALTER TABLE `td_form_extension`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
