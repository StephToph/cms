-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2024 at 09:18 PM
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
-- Database: `ceaurora_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `td_access`
--

CREATE TABLE `td_access` (
  `id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL DEFAULT 0,
  `user_id` bigint(20) NOT NULL DEFAULT 0,
  `crud` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_access`
--

INSERT INTO `td_access` (`id`, `role_id`, `user_id`, `crud`) VALUES
(1, 1, 0, '[\"1.1.1.1.1\",\"2.1.1.1.1\",\"3.1.1.1.1\",\"4.1.1.1.1\",\"5.1.1.1.1\",\"6.0.1.0.0\",\"7.0.1.0.0\",\"8.0.1.0.0\",\"10.1.1.1.1\",\"11.0.1.1.0\",\"12.1.1.1.1\",\"13.0.1.1.1\",\"14.0.1.1.0\",\"15.0.1.1.0\",\"9.0.1.1.0\",\"17.0.1.1.0\",\"18.0.1.1.0\",\"20.0.1.1.0\",\"19.0.1.1.0\",\"21.0.1.1.1\",\"24.0.1.1.0\",\"23.0.1.1.0\",\"22.0.1.1.0\",\"25.0.1.1.0\",\"26.0.1.0.0\",\"27.0.1.1.0\",\"28.0.1.1.0\",\"29.0.1.1.0\",\"30.0.1.1.0\",\"31.0.1.1.0\",\"32.0.1.1.0\",\"33.0.1.1.0\",\"35.0.1.1.0\",\"36.0.1.1.0\",\"37.0.1.1.0\",\"38.0.1.1.0\",\"39.0.1.1.0\",\"40.0.1.1.0\",\"43.0.1.1.0\",\"42.0.1.1.0\",\"41.0.1.1.0\",\"34.0.0.1.0\",\"44.0.1.0.0\",\"45.0.1.1.0\",\"46.0.1.1.0\",\"47.0.1.1.0\",\"48.0.1.1.0\",\"49.0.1.1.0\",\"50.0.1.1.0\",\"51.0.1.1.0\",\"52.0.1.1.0\",\"53.0.1.1.0\",\"54.0.1.1.0\",\"55.0.1.1.0\",\"56.0.1.1.0\",\"57.0.1.1.0\",\"58.0.1.1.0\",\"59.0.1.1.0\"]'),
(2, 2, 0, '[\"1.1.1.1.1\",\"2.1.1.1.1\",\"5.1.1.1.1\",\"4.1.1.1.1\",\"3.1.1.1.1\",\"7.0.1.0.0\",\"10.0.1.0.0\",\"9.0.1.0.0\",\"6.1.1.1.1\",\"12.1.1.1.1\",\"11.0.1.1.1\",\"50.0.1.1.0\",\"21.0.1.0.0\",\"24.0.1.1.0\",\"22.0.1.1.0\",\"23.0.1.1.0\",\"48.0.0.0.0\"]'),
(3, 3, 0, '[\"1.0.1.0.0\",\"7.0.1.0.0\",\"9.1.1.1.1\",\"10.0.1.0.0\",\"12.1.1.1.1\",\"11.0.1.1.1\",\"2.0.1.0.0\",\"3.1.1.1.1\",\"13.0.1.0.0\",\"14.0.1.0.0\"]'),
(4, 6, 0, '[\"1.0.1.0.0\",\"2.0.1.0.0\",\"3.1.1.1.1\",\"5.0.1.0.0\",\"12.0.1.1.0\",\"13.0.1.0.0\"]'),
(5, 4, 0, '[\"1.0.1.0.0\",\"15.0.1.1.0\",\"11.0.1.1.0\",\"13.0.1.1.0\",\"6.0.1.0.0\",\"16.0.0.0.0\",\"51.0.1.1.0\"]'),
(6, 8, 0, '[\"1.0.1.1.0\",\"15.0.1.1.0\",\"11.0.1.1.0\",\"13.0.1.1.0\",\"6.0.1.1.0\",\"18.0.1.1.0\",\"17.0.1.1.0\"]'),
(7, 14, 0, '[\"1.0.1.0.0\",\"21.0.1.0.0\",\"49.0.1.1.0\",\"51.0.1.1.0\",\"15.0.1.1.0\",\"53.0.1.1.0\",\"17.0.1.1.0\",\"6.0.1.1.0\"]');

-- --------------------------------------------------------

--
-- Table structure for table `td_access_module`
--

CREATE TABLE `td_access_module` (
  `id` bigint(20) NOT NULL,
  `parent` bigint(20) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `link` varchar(200) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_access_module`
--

INSERT INTO `td_access_module` (`id`, `parent`, `name`, `link`, `icon`, `priority`) VALUES
(1, 0, 'Dashboard', 'dashboard', 'icon ni ni-dashboard-fill', 0),
(6, 0, 'Activity', 'activity', 'icon ni ni-property', 8),
(15, 0, 'Profile', 'profile', 'icon ni ni-account-setting', 4),
(17, 0, 'Notification', 'notification/list', 'icon ni ni-vol', 6),
(57, 0, 'Membership', 'accounts/membership', 'icon ni ni-users', 1),
(58, 0, 'Department', 'accounts/dept', 'icon ni ni-building', 2),
(59, 0, 'Cells', 'accounts/cell', 'icon ni ni-tranx', 2);

-- --------------------------------------------------------

--
-- Table structure for table `td_access_role`
--

CREATE TABLE `td_access_role` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_access_role`
--

INSERT INTO `td_access_role` (`id`, `name`) VALUES
(1, 'Developer'),
(2, 'Administrator'),
(4, 'Member'),
(8, 'Business'),
(14, 'Tax Master'),
(15, 'Field Operative');

-- --------------------------------------------------------

--
-- Table structure for table `td_activity`
--

CREATE TABLE `td_activity` (
  `id` bigint(20) NOT NULL,
  `item` text NOT NULL,
  `item_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `reg_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_activity`
--

INSERT INTO `td_activity` (`id`, `item`, `item_id`, `action`, `reg_date`) VALUES
(1, 'user', 0, 'Tophunmi Stephen created Administrator (Bilyamin Graba)', '2023-12-19 08:05:44'),
(2, 'user', 33, 'Tophunmi Stephen updated Administrator (Bilyamin Graba) Record', '2023-12-19 08:13:00'),
(3, 'user', 33, 'Tophunmi Stephen updated Administrator (Bilyamin Graba) Record', '2023-12-19 08:15:26'),
(4, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-19 14:41:34'),
(5, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-19 14:43:12'),
(6, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-19 19:36:16'),
(7, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-19 19:55:53'),
(8, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-19 20:14:32'),
(9, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-19 20:28:56'),
(10, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-19 23:22:32'),
(11, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 05:12:43'),
(12, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 05:22:31'),
(13, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-20 05:39:17'),
(14, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 05:39:32'),
(15, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-20 05:40:20'),
(16, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 05:47:11'),
(17, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-20 08:15:12'),
(18, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 09:20:55'),
(19, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-20 09:23:19'),
(20, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 10:05:28'),
(21, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 14:48:04'),
(22, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-20 16:03:29'),
(23, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-20 19:52:48'),
(24, 'user', 35, 'Tophunmi Stephen created Administrator (Bilyamin Graba)', '2023-12-20 23:07:15'),
(25, 'user', 35, 'Tophunmi Stephen updated Administrator (Bilyamin Graba) Record', '2023-12-20 23:18:10'),
(26, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-20 23:27:28'),
(27, 'authentication', 36, 'Tee Stores Logged Out', '2023-12-21 00:00:10'),
(28, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-21 00:00:21'),
(29, 'user', 37, 'Tophunmi Stephen created Tax Master (Dummy Names)', '2023-12-21 00:01:57'),
(30, 'user', 35, 'Tophunmi Stephen updated Field Operative (Dummy Names) Record', '2023-12-21 00:57:41'),
(31, 'user', 38, 'Tophunmi Stephen created Field Operative (Profile)', '2023-12-21 01:03:49'),
(32, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-21 02:56:01'),
(33, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-21 02:57:58'),
(34, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-21 07:05:33'),
(35, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-21 10:37:57'),
(36, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-21 10:45:20'),
(37, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-21 10:47:25'),
(38, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-21 17:15:32'),
(39, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-21 17:26:22'),
(40, 'authentication', 8, 'Tophunmi Stephen Updated Profile ', '2023-12-21 21:11:33'),
(41, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-22 06:46:51'),
(42, 'transaction', 8, 'Tophunmi Stephen Account Deposited with N1.00 using Bank Transfer', '2023-12-22 10:17:28'),
(43, 'transaction', 8, 'Tophunmi Stephen Account Deposited with N1.00 using Bank Transfer', '2023-12-22 10:20:39'),
(44, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-22 10:29:02'),
(45, 'transaction', 8, 'Tophunmi Stephen Account Deposited with N1.00 using Bank Transfer', '2023-12-22 13:06:05'),
(46, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-22 17:50:46'),
(47, 'user', 39, 'Tophunmi Stephen created Tax Master (Temiloluwa Adeagbo)', '2023-12-22 18:06:56'),
(48, 'user', 40, 'Tophunmi Stephen created Tax Master (Temiloluwa Adeagbo)', '2023-12-22 18:14:50'),
(49, 'user', 41, 'Tophunmi Stephen created Tax Master (Temiloluwa Adeagbo)', '2023-12-22 18:17:53'),
(50, 'user', 42, 'Tophunmi Stephen created Tax Master (Temiloluwa Adeagbo)', '2023-12-22 18:20:43'),
(51, 'user', 43, 'Tophunmi Stephen created Tax Master (Temiloluwa Adeagbo)', '2023-12-22 18:24:27'),
(52, 'user', 44, 'Tophunmi Stephen created Field Operative (Temiloluwa Adeagbo)', '2023-12-22 18:29:19'),
(53, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-22 18:57:54'),
(54, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-22 20:16:09'),
(55, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-23 10:30:50'),
(56, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-23 12:43:21'),
(57, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-23 17:28:42'),
(58, 'authentication', 8, 'Tophunmi Stephen Logged Out', '2023-12-23 18:03:38'),
(59, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-23 21:44:40'),
(60, 'authentication', 8, 'Tophunmi Stephen Logged Out ', '2023-12-24 00:22:13'),
(61, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-24 08:46:35'),
(62, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-24 15:47:52'),
(63, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-25 07:25:57'),
(64, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-26 09:15:50'),
(65, 'authentication', 8, 'Tophunmi Stephen Updated Profile ', '2023-12-26 10:45:56'),
(66, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-26 19:59:36'),
(67, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-27 17:57:20'),
(68, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-27 22:15:59'),
(69, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-28 09:49:08'),
(70, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-28 09:59:45'),
(71, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-28 16:24:32'),
(72, 'user', 46, 'Tophunmi Stephen created Field Operative (Bilyamin Graba)', '2023-12-28 16:29:21'),
(73, 'user', 47, 'Tophunmi Stephen created Field Operative (Bilyamin Graba)', '2023-12-28 16:30:33'),
(74, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-28 20:06:28'),
(75, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-29 21:24:50'),
(76, 'user', 48, 'Tophunmi Stephen created Tax Master (Tax Status Check)', '2023-12-29 22:54:18'),
(77, 'user', 34, 'Tophunmi Stephen updated Tax Master (Mummy Ire) Record', '2023-12-29 23:09:31'),
(78, 'user', 47, 'Tophunmi Stephen updated Field Operative (Bilyamin Graba) Record', '2023-12-29 23:22:12'),
(79, 'user', 38, 'Tophunmi Stephen updated Field Operative (Profile) Record', '2023-12-29 23:23:58'),
(80, 'authentication', 8, 'Tophunmi Stephen Logged Out ', '2023-12-29 23:24:54'),
(81, 'authentication', 49, 'Stephie registered on the Platform', '2023-12-29 23:45:04'),
(82, 'authentication', 50, 'Stephie registered on the Platform', '2023-12-29 23:45:49'),
(83, 'authentication', 51, 'Stephie registered on the Platform', '2023-12-29 23:46:43'),
(84, 'authentication', 52, 'Stephie registered on the Platform', '2023-12-29 23:48:59'),
(85, 'authentication', 52, 'Stephie Logged Out ', '2023-12-29 23:51:06'),
(86, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-29 23:51:18'),
(87, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-30 09:02:29'),
(88, 'user', 53, 'Tophunmi Stephen created Field Operative (Transaction)', '2023-12-30 10:04:05'),
(89, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-30 14:26:57'),
(90, 'user', 48, 'Tophunmi Stephen updated Tax Master (Tax Status Check) Record', '2023-12-30 14:48:56'),
(91, 'user', 48, 'Tophunmi Stephen updated Tax Master (Tax Status Check) Record', '2023-12-30 14:50:52'),
(92, 'user', 34, 'Tophunmi Stephen updated Tax Master (Mummy Ire) Record', '2023-12-30 15:07:39'),
(93, 'user', 53, 'Tophunmi Stephen updated Field Operative (Transaction) Record', '2023-12-30 15:29:09'),
(94, 'authentication', 8, 'Tophunmi Stephen logged in ', '2023-12-31 20:28:08'),
(95, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-02 11:55:19'),
(96, 'profile', 8, 'Tophunmi Stephen created Payment Profile and Updated Profile ', '2024-01-02 11:55:30'),
(97, 'profile', 8, 'Tophunmi Stephen created Payment Profile and Updated Profile ', '2024-01-02 11:56:40'),
(98, 'profile', 8, 'Tophunmi Stephen created Payment Profile and Updated Profile ', '2024-01-02 11:59:38'),
(99, 'profile', 8, 'Tophunmi Stephen created Payment Profile and Updated Profile ', '2024-01-02 12:03:20'),
(100, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-02 18:55:58'),
(101, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-03 09:09:59'),
(102, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-03 15:30:14'),
(103, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-03 21:52:40'),
(104, 'profile', 8, 'Tophunmi Stephen created Payment Profile and Updated Profile ', '2024-01-03 21:53:01'),
(105, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-04 09:53:46'),
(106, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-04 14:35:42'),
(107, 'support', 6, 'Tophunmi Stephen created Support Ticket (Registreation) Record', '2024-01-04 15:43:39'),
(108, 'support', 6, 'Tophunmi Stephen Replied to Support Ticket', '2024-01-04 15:47:53'),
(109, 'support', 6, 'Tophunmi Stephen Closed Support Ticket', '2024-01-04 15:48:20'),
(110, 'authentication', 54, 'tophu registered on the Platform', '2024-01-04 16:41:02'),
(111, 'authentication', 55, 'Mummy Ire registered on the Platform', '2024-01-04 21:43:54'),
(112, 'authentication', 56, 'Mummy Ire registered on the Platform', '2024-01-04 21:45:42'),
(113, 'authentication', 57, 'Mummy Ire registered on the Platform', '2024-01-04 21:49:04'),
(114, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-04 23:36:21'),
(115, 'profile', 8, 'Tophunmi Stephen created Payment Profile and Updated Profile ', '2024-01-05 00:13:17'),
(116, 'authentication', 8, 'Tophunmi Stephen Logged Out ', '2024-01-05 01:02:08'),
(117, 'authentication', 58, 'Adeagbo Stephen Tophunmi registered on the Platform', '2024-01-05 10:55:29'),
(118, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-05 11:10:27'),
(119, 'authentication', 8, 'Tophunmi Stephen Updated Profile ', '2024-01-05 14:22:21'),
(120, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-06 11:21:14'),
(121, 'user', 58, 'Tophunmi Stephen updated Personal (Adeagbo Stephen Tophunmi) Record', '2024-01-06 11:32:59'),
(122, 'authentication', 8, 'Tophunmi Stephen Logged Out ', '2024-01-06 12:49:42'),
(123, 'authentication', 36, 'Tee Stores logged in ', '2024-01-06 12:50:06'),
(124, 'profile', 36, 'Tee Stores created Payment Profile and Updated Profile ', '2024-01-06 12:50:30'),
(125, 'profile', 36, 'Tee Stores Generated Virtual Account/Tax ID ', '2024-01-06 12:51:01'),
(126, 'authentication', 36, 'Tee Stores Logged Out ', '2024-01-06 12:53:12'),
(127, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-06 12:53:28'),
(128, 'user', 36, 'Tophunmi Stephen updated Personal (Tee Stores) Record', '2024-01-06 12:54:36'),
(129, 'user', 36, 'Tophunmi Stephen updated Personal (Tee Stores) Record', '2024-01-06 12:57:03'),
(130, 'user', 36, 'Tophunmi Stephen updated Personal (Tee Stores) Record', '2024-01-06 12:58:32'),
(131, 'user', 36, 'Tophunmi Stephen updated Personal (Tee Stores) Record', '2024-01-06 13:24:42'),
(132, 'user', 54, 'Tophunmi Stephen updated Business (tophu) Record', '2024-01-06 13:31:52'),
(133, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-07 11:12:24'),
(134, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-07 13:52:48'),
(135, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-07 21:22:55'),
(136, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-08 18:41:12'),
(137, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-08 21:41:36'),
(138, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-09 07:24:59'),
(139, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-09 10:13:07'),
(140, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-09 19:58:32'),
(141, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-10 07:18:47'),
(142, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-10 10:10:53'),
(143, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-11 09:35:43'),
(144, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-19 19:11:40'),
(145, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-24 18:59:40'),
(146, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-25 16:52:14'),
(147, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-25 19:57:40'),
(148, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-26 09:48:54'),
(149, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-26 13:30:54'),
(150, 'authentication', 8, 'Tophunmi Stephen Logged Out ', '2024-01-26 16:07:00'),
(151, 'authentication', 59, 'Adeagbo Stephen Tophunmi registered on the Platform', '2024-01-26 16:09:23'),
(152, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-26 18:59:37'),
(153, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-27 03:10:49'),
(154, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-01-27 19:01:47'),
(155, 'authentication', 8, 'Tophunmi Stephen logged in ', '2024-02-03 10:51:37'),
(156, 'authentication', 1, 'Admin CEAURORA logged in ', '2024-02-20 03:54:49'),
(157, 'authentication', 1, 'Admin CEAURORA Logged Out ', '2024-02-20 04:07:15'),
(158, 'authentication', 2, 'Stephen Tophunmi logged in ', '2024-02-20 04:07:27'),
(159, 'user', 1, 'Stephen created Department (Choir) Record', '2024-02-20 05:38:29'),
(160, 'user', 1, 'Stephen updated Department (Choir) Record', '2024-02-20 05:39:08'),
(161, 'user', 1, 'Stephen updated Department (Choir) Record', '2024-02-20 05:40:24'),
(162, 'user', 1, 'Stephen updated Department (Choir) Record', '2024-02-20 05:58:40'),
(163, 'user', 1, 'Stephen created Department (Choir) Record', '2024-02-20 07:31:39'),
(164, 'user', 2, 'Stephen created Department () Record', '2024-02-20 07:48:56'),
(165, 'user', 2, 'Stephen updated Department () Record', '2024-02-20 07:57:39'),
(166, 'authentication', 2, 'Stephen Tophunmi logged in ', '2024-02-20 17:55:45'),
(167, 'user', 2, 'Stephen updated Department () Record', '2024-02-20 17:58:51'),
(168, 'user', 1, 'Stephen updated Department (Choir) Record', '2024-02-20 17:59:27'),
(169, 'user', 3, 'Stephen created Membership () Record', '2024-02-20 20:50:19'),
(170, 'user', 4, 'Stephen created Membership () Record', '2024-02-20 21:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `td_cells`
--

CREATE TABLE `td_cells` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `time` varchar(200) DEFAULT NULL,
  `roles` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_cells`
--

INSERT INTO `td_cells` (`id`, `name`, `location`, `time`, `roles`) VALUES
(1, 'Houston', 'Texas', NULL, '[\"Master\"]'),
(2, 'Vendor', 'Houston, Texas', '{\"Monday\":\"11:01\",\"Wednesday\":\"04:05\",\"Sunday\":\"22:02\"}', '[\"admin\",\"Mastyer\"]');

-- --------------------------------------------------------

--
-- Table structure for table `td_dept`
--

CREATE TABLE `td_dept` (
  `id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `roles` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_dept`
--

INSERT INTO `td_dept` (`id`, `name`, `roles`) VALUES
(1, 'Choir', '[\"developer\",\"admin\",\"choir\",\"Drummer\"]');

-- --------------------------------------------------------

--
-- Table structure for table `td_file`
--

CREATE TABLE `td_file` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT 0,
  `type` varchar(50) NOT NULL,
  `ext` varchar(5) DEFAULT NULL,
  `path` varchar(150) NOT NULL,
  `pics_small` varchar(150) DEFAULT NULL,
  `pics_square` varchar(150) DEFAULT NULL,
  `reg_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_file`
--

INSERT INTO `td_file` (`id`, `user_id`, `type`, `ext`, `path`, `pics_small`, `pics_square`, `reg_date`) VALUES
(1, 8, '', NULL, 'assets/images/users/8/1676004713_88a527000a077c0b02ad.jpg', 'assets/images/users/8/1676004713_88a527000a077c0b02ad.jpg', 'assets/images/users/8/1676004713_88a527000a077c0b02ad.jpg', '2023-02-10 05:51:53'),
(2, 8, '', NULL, 'assets/images/users/8/1676004801_da9ab8781f6737537115.jpg', 'assets/images/users/8/1676004801_da9ab8781f6737537115.jpg', 'assets/images/users/8/1676004801_da9ab8781f6737537115.jpg', '2023-02-10 05:53:23'),
(3, 8, '', NULL, 'assets/images/users/8/1676004857_52136a239d67c13be65e.jpg', 'assets/images/users/8/1676004857_52136a239d67c13be65e.jpg', 'assets/images/users/8/1676004857_52136a239d67c13be65e.jpg', '2023-02-10 05:54:18'),
(4, 8, '', NULL, 'assets/images/users/8/1676004927_1f51e16be5ced632fd1b.jpg', 'assets/images/users/8/1676004927_1f51e16be5ced632fd1b.jpg', 'assets/images/users/8/1676004927_1f51e16be5ced632fd1b.jpg', '2023-02-10 05:55:28'),
(5, 8, '', NULL, 'assets/images/users/8/1676004955_62434dd1cce6e9022eff.jpg', 'assets/images/users/8/1676004955_62434dd1cce6e9022eff.jpg', 'assets/images/users/8/1676004955_62434dd1cce6e9022eff.jpg', '2023-02-10 05:55:55'),
(6, 8, '', NULL, 'assets/images/users/8/1676005041_1b24633c18a40683e206.jpg', 'assets/images/users/8/1676005041_1b24633c18a40683e206.jpg', 'assets/images/users/8/1676005041_1b24633c18a40683e206.jpg', '2023-02-10 05:57:21'),
(7, 8, '', NULL, 'assets/images/users/8/1676005093_b7decd312baf0fdae2ce.jpg', 'assets/images/users/8/1676005093_b7decd312baf0fdae2ce.jpg', 'assets/images/users/8/1676005093_b7decd312baf0fdae2ce.jpg', '2023-02-10 05:58:13'),
(8, 8, '', NULL, 'assets/images/users/8/1676005137_8f0e4fc5ab5a6c2ebf33.jpg', 'assets/images/users/8/1676005137_8f0e4fc5ab5a6c2ebf33.jpg', 'assets/images/users/8/1676005137_8f0e4fc5ab5a6c2ebf33.jpg', '2023-02-10 05:58:57'),
(9, 8, '', NULL, 'assets/images/users/8/1676005196_0ea7bcc3e47bf7f9f868.jpg', 'assets/images/users/8/1676005196_0ea7bcc3e47bf7f9f868.jpg', 'assets/images/users/8/1676005196_0ea7bcc3e47bf7f9f868.jpg', '2023-02-10 05:59:57'),
(10, 8, '', NULL, 'assets/images/users/8/1676005253_fc5cc9746493601ecead.jpg', 'assets/images/users/8/1676005253_fc5cc9746493601ecead.jpg', 'assets/images/users/8/1676005253_fc5cc9746493601ecead.jpg', '2023-02-10 06:00:53'),
(11, 8, '', NULL, 'assets/images/users/8/1676005335_9f11f1c6e43bd40ba2fd.jpg', 'assets/images/users/8/1676005335_9f11f1c6e43bd40ba2fd.jpg', 'assets/images/users/8/1676005335_9f11f1c6e43bd40ba2fd.jpg', '2023-02-10 06:02:16'),
(12, 8, '', NULL, 'assets/images/users/8/1676005457_b792e160bc0b1ba3a800.jpg', 'assets/images/users/8/1676005457_b792e160bc0b1ba3a800.jpg', 'assets/images/users/8/1676005457_b792e160bc0b1ba3a800.jpg', '2023-02-10 06:04:17'),
(13, 8, '', NULL, 'assets/images/users/8/1676005502_20086785d613642cfcb2.jpg', 'assets/images/users/8/1676005502_20086785d613642cfcb2.jpg', 'assets/images/users/8/1676005502_20086785d613642cfcb2.jpg', '2023-02-10 06:05:03'),
(14, 8, '', NULL, 'assets/images/users/8/1676005609_39951a6193d19604289f.jpg', 'assets/images/users/8/1676005609_39951a6193d19604289f.jpg', 'assets/images/users/8/1676005609_39951a6193d19604289f.jpg', '2023-02-10 06:06:50'),
(15, 8, '', NULL, 'assets/images/users/8/1688057915_10ff9fff6ba8f032f0f4.jpg', 'assets/images/users/8/1688057915_10ff9fff6ba8f032f0f4.jpg', 'assets/images/users/8/1688057915_10ff9fff6ba8f032f0f4.jpg', '2023-06-29 17:58:35'),
(16, 8, '', NULL, 'assets/images/users/8/1688057931_f361d882a45a16fccd4c.jpg', 'assets/images/users/8/1688057931_f361d882a45a16fccd4c.jpg', 'assets/images/users/8/1688057931_f361d882a45a16fccd4c.jpg', '2023-06-29 17:58:51'),
(17, 8, '', NULL, 'assets/images/users/8/1688058035_ae48eb078984483a2391.jpg', 'assets/images/users/8/1688058035_ae48eb078984483a2391.jpg', 'assets/images/users/8/1688058035_ae48eb078984483a2391.jpg', '2023-06-29 18:00:36'),
(18, 8, '', NULL, 'assets/images/users/8/1688058084_883273c9abca97e5c843.jpg', 'assets/images/users/8/1688058084_883273c9abca97e5c843.jpg', 'assets/images/users/8/1688058084_883273c9abca97e5c843.jpg', '2023-06-29 18:01:24'),
(19, 8, '', NULL, 'assets/images/users/8/1688058171_960785c8b976d38e985a.jpg', 'assets/images/users/8/1688058171_960785c8b976d38e985a.jpg', 'assets/images/users/8/1688058171_960785c8b976d38e985a.jpg', '2023-06-29 18:02:51'),
(20, 8, '', NULL, 'assets/images/users/8/1688058232_b2d64300800c3544d7cb.jpg', 'assets/images/users/8/1688058232_b2d64300800c3544d7cb.jpg', 'assets/images/users/8/1688058232_b2d64300800c3544d7cb.jpg', '2023-06-29 18:03:53'),
(21, 8, '', NULL, 'assets/images/users/8/1688058321_1457446b981daba4f68b.jpg', 'assets/images/users/8/1688058321_1457446b981daba4f68b.jpg', 'assets/images/users/8/1688058321_1457446b981daba4f68b.jpg', '2023-06-29 18:05:21'),
(22, 8, '', NULL, 'assets/images/users/8/1688140354_f1dd4cb11fe6ba0bcb59.jpg', 'assets/images/users/8/1688140354_f1dd4cb11fe6ba0bcb59.jpg', 'assets/images/users/8/1688140354_f1dd4cb11fe6ba0bcb59.jpg', '2023-06-30 16:52:35'),
(23, 8, '', NULL, 'assets/images/users/8/1688140919_6e9d31118211d193bc20.jpg', 'assets/images/users/8/1688140919_6e9d31118211d193bc20.jpg', 'assets/images/users/8/1688140919_6e9d31118211d193bc20.jpg', '2023-06-30 17:02:01'),
(24, 8, '', NULL, 'assets/images/users/8/1688140992_9669ece95e1004c9c5b7.jpg', 'assets/images/users/8/1688140992_9669ece95e1004c9c5b7.jpg', 'assets/images/users/8/1688140992_9669ece95e1004c9c5b7.jpg', '2023-06-30 17:03:13'),
(25, 8, '', NULL, 'assets/images/users/8/1688141028_c99a174f8b10a3fd83a4.jpg', 'assets/images/users/8/1688141028_c99a174f8b10a3fd83a4.jpg', 'assets/images/users/8/1688141028_c99a174f8b10a3fd83a4.jpg', '2023-06-30 17:03:50'),
(26, 8, '', NULL, 'assets/images/users/8/1688141340_4622e110a33ac82e06ae.jpg', 'assets/images/users/8/1688141340_4622e110a33ac82e06ae.jpg', 'assets/images/users/8/1688141340_4622e110a33ac82e06ae.jpg', '2023-06-30 17:09:01'),
(27, 8, '', NULL, 'assets/images/users/8/1688141384_dca650cf80c387c87f59.jpg', 'assets/images/users/8/1688141384_dca650cf80c387c87f59.jpg', 'assets/images/users/8/1688141384_dca650cf80c387c87f59.jpg', '2023-06-30 17:09:45'),
(28, 8, '', NULL, 'assets/images/users/8/1688141473_c19e2ee480c6f112f1c5.jpg', 'assets/images/users/8/1688141473_c19e2ee480c6f112f1c5.jpg', 'assets/images/users/8/1688141473_c19e2ee480c6f112f1c5.jpg', '2023-06-30 17:11:14'),
(29, 8, '', NULL, 'assets/images/users/8/1688141512_a7dac3e1ca1131f60b0b.jpg', 'assets/images/users/8/1688141512_a7dac3e1ca1131f60b0b.jpg', 'assets/images/users/8/1688141512_a7dac3e1ca1131f60b0b.jpg', '2023-06-30 17:11:53'),
(30, 8, '', NULL, 'assets/images/users/8/1688141720_d9ee40f2d9cdb2aed6a8.jpg', 'assets/images/users/8/1688141720_d9ee40f2d9cdb2aed6a8.jpg', 'assets/images/users/8/1688141720_d9ee40f2d9cdb2aed6a8.jpg', '2023-06-30 17:15:21'),
(31, 8, '', NULL, 'assets/images/users/8/1688141727_3b7149518902130a6ea4.jpg', 'assets/images/users/8/1688141727_3b7149518902130a6ea4.jpg', 'assets/images/users/8/1688141727_3b7149518902130a6ea4.jpg', '2023-06-30 17:15:28'),
(32, 8, '', NULL, 'assets/images/users/8/1688141882_7e962bb2042eb8e367a0.jpg', 'assets/images/users/8/1688141882_7e962bb2042eb8e367a0.jpg', 'assets/images/users/8/1688141882_7e962bb2042eb8e367a0.jpg', '2023-06-30 17:18:03'),
(33, 8, '', NULL, 'assets/images/users/8/1688141908_935cd15e1dfc219505cd.jpg', 'assets/images/users/8/1688141908_935cd15e1dfc219505cd.jpg', 'assets/images/users/8/1688141908_935cd15e1dfc219505cd.jpg', '2023-06-30 17:18:29'),
(34, 8, '', NULL, 'assets/images/users/8/1688141930_acd8255ee167b939e7ab.jpg', 'assets/images/users/8/1688141930_acd8255ee167b939e7ab.jpg', 'assets/images/users/8/1688141930_acd8255ee167b939e7ab.jpg', '2023-06-30 17:18:51'),
(35, 8, '', NULL, 'assets/images/users/8/1688386519_82d379980154940fbbc2.jpg', 'assets/images/users/8/1688386519_82d379980154940fbbc2.jpg', 'assets/images/users/8/1688386519_82d379980154940fbbc2.jpg', '2023-07-03 13:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `td_language`
--

CREATE TABLE `td_language` (
  `id` bigint(20) NOT NULL,
  `phrase` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `english` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `hausa` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `spanish` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `arabic` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `dutch` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `russian` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `chinese` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `turkish` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `igbo` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `hungarian` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `french` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `greek` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `german` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `italian` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `thai` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `urdu` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `hindi` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `latin` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `indonesian` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `japanese` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `korean` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `yoruba` longtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `td_language`
--

INSERT INTO `td_language` (`id`, `phrase`, `english`, `hausa`, `spanish`, `arabic`, `dutch`, `russian`, `chinese`, `turkish`, `igbo`, `hungarian`, `french`, `greek`, `german`, `italian`, `thai`, `urdu`, `hindi`, `latin`, `indonesian`, `japanese`, `korean`, `yoruba`) VALUES
(1, 'log_in', 'Log In', 'Shiga', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', 'Wo ile'),
(4, 'sign_in', 'Sign In', 'Shiga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wọle'),
(5, 'access_the_fundme_panel_using_your_phone_number_and_password', 'Access The Fundme Panel Using Your Phone Number And Password', 'Shiga Ƙungiyar Asusun Ta Amfani da Lambar Wayar ku da Kalmar wucewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wọle si Igbimọ Fundme Lilo Nọmba Foonu rẹ Ati Ọrọigbaniwọle'),
(6, 'phone_number', 'Phone Number', 'Lambar tarho', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nomba fonu'),
(7, 'enter_your_phone_number', 'Enter Your Phone Number', 'Shigar da Lambar Wayarka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Nọmba Foonu Rẹ sii'),
(8, 'password', 'Password', 'Kalmar wucewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ọrọigbaniwọle'),
(9, 'reset_password', 'Reset Password', 'Sake saita kalmar wucewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tun Ọrọigbaniwọle to'),
(10, 'enter_your_password', 'Enter Your Password', 'Shigar da Kalmar wucewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Ọrọigbaniwọle Rẹ sii'),
(12, 'new_on_our_platform', 'New On Our Platform', 'Sabon Akan Dandalin Mu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Titun Lori Platform Wa'),
(13, 'create_an_account', 'Create An Account', 'Ƙirƙiri Asusu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ṣẹda akọọlẹ kan'),
(14, 'terms_condition', 'Terms Condition', 'Sharuɗɗan Sharuɗɗa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ofin Ipo'),
(15, 'privacy_policy', 'Privacy Policy', 'takardar kebantawa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Asiri Afihan'),
(16, 'all_rights_reserved', 'All Rights Reserved', 'Duka Hakkoki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbogbo awọn ẹtọ wa ni ipamọ'),
(17, 'add_money_make_transfers_pay_bills', 'Add Money Make Transfers Pay Bills', 'Ƙara Kuɗi Yi Canja wurin Biyan Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Fi Owo Ṣe awọn gbigbe San owo'),
(18, 'live_your_life_on_your_terms_and_without_limits_with_a_fundmecash_wallet_you_can_add_money_to_your_wallet_for_free_within_seconds_and_start_making_transfers_to_other_wallets_or_any_bank_accounts_take_control_of_how_you_make_your_bill_services_merchant_and_utility_payments', 'Live Your Life On Your Terms And Without Limits With A Fundmecash Wallet You Can Add Money To Your Wallet For Free Within Seconds And Start Making Transfers To Other Wallets Or Any Bank Accounts Take Control Of How You Make Your Bill Services Merchant And Utility Payments', 'Yi Rayuwar ku Akan Sharuɗɗanku Kuma Ba tare da Iyaka ba Tare da Wallet na Asusun Kuɗi Zaku iya Ƙara Kuɗi a Wallet ɗinku Kyauta A Cikin Daƙiƙa Kuma Fara Canja wurin Zuwa Wasu Wallet Ko Duk Wani Asusu na Banki Ya Kula da Yadda kuke Yin Kasuwancin Billy ɗinku da Biyan Kuɗi.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbe Igbesi aye Rẹ Lori Awọn ofin Rẹ Ati Laisi Awọn idiwọn Pẹlu Apamọwọ Fundmecash O le Fi Owo kun Apamọwọ Rẹ Fun Ọfẹ Laarin Awọn iṣẹju-aaya ati Bẹrẹ Ṣiṣe Awọn gbigbe si Awọn Woleti miiran tabi Awọn akọọlẹ Ile-ifowopamọ Eyikeyi Gba Iṣakoso Bi O Ṣe Ṣe Onisowo Awọn iṣẹ Bill rẹ ati Awọn sisanwo IwUlO'),
(19, 'account_not_activatedbr_please_validate_account', 'Account Not Activatedbr Please Validate Account', 'Ba a Kunna Asusu Don Da fatan za a tabbatar da Asusu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Account Ko Activatedbr Jọwọ sooto Account'),
(20, 'invalid_authentication', 'Invalid Authentication', 'Tabbatarwa mara inganci', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ijeri ti ko tọ'),
(55, 'register', 'Register', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Forukọsilẹ'),
(22, 'create_new_fundme_account', 'Create New Fundme Account', 'Ƙirƙiri Sabon Asusun Asusun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ṣẹda Account Fundme Tuntun'),
(23, 'user_type', 'User Type', 'Nau&#39;in Mai Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Olumulo Iru'),
(24, 'personal', 'Personal', 'Na sirri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ti ara ẹni'),
(25, 'business', 'Business', 'Kasuwanci', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣowo'),
(26, 'transporter', 'Transporter', 'Mai jigilar kaya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Olugbeja'),
(27, 'artisan', 'Artisan', 'Mai sana&#39;a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Onisegun'),
(28, 'fullname', 'Fullname', 'Cikakken suna', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Akokun Oruko'),
(29, 'enter_your_name', 'Enter Your Name', 'Shigar da Sunanka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Orukọ Rẹ sii'),
(30, 'username', 'Username', 'Sunan mai amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Orukọ olumulo'),
(31, 'enter_your_username', 'Enter Your Username', 'Shigar da sunan mai amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Orukọ olumulo Rẹ sii'),
(32, 'email', 'Email', 'Imel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Imeeli'),
(33, 'enter_your_email_address', 'Enter Your Email Address', 'Shigar da Adireshin Imel ɗin ku', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Adirẹsi Imeeli Rẹ sii'),
(34, 'phone', 'Phone', 'Waya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Foonu'),
(35, 'country', 'Country', 'Ƙasa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Orilẹ-ede'),
(36, '_select_country_', ' Select Country ', 'Zaɓi Ƙasa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Orilẹ-ede'),
(37, 'state', 'State', 'Jiha', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ìpínlẹ̀'),
(38, '_select_country_first_', ' Select Country First ', 'Zaɓi Ƙasa ta Farko', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Orilẹ-ede Akọkọ'),
(39, 'passwords', 'Passwords', 'Kalmomin sirri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ọrọigbaniwọle'),
(40, 'referral', 'Referral', 'Magana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ifiranṣẹ'),
(41, 'enter_referral_code', 'Enter Referral Code', 'Shigar da Lambar Magana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ koodu Ifiranṣẹ sii'),
(42, 'i_agree_to_fundme', 'I Agree To Fundme', 'Na Amince da Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mo Gba Lati Fundme'),
(43, 'terms', 'Terms', 'Sharuɗɗan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ofin'),
(44, 'already_have_an_account', 'Already Have An Account', 'Tuni Yana da Asusu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹlẹ Ni Account'),
(45, 'sign_in_instead', 'Sign In Instead', 'Shiga A maimakon haka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wole Ni Dipo'),
(46, 'if_you_forgot_your_password_well_then_well_email_you_instructions_to_reset_your_password', 'If You Forgot Your Password Well Then Well Email You Instructions To Reset Your Password', 'Idan Kun Manta Password ɗinku Da kyau To Sai Ku Yi Imel ɗin Umurnin Sake saita kalmar wucewarku', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ti o ba gbagbe ọrọ igbaniwọle rẹ daradara lẹhinna Imeeli O dara Awọn ilana Lati Tun ọrọ igbaniwọle rẹ pada'),
(47, 'email_address', 'Email Address', 'Adireshin i-mel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Adirẹsi imeeli'),
(48, 'send_reset_link', 'Send Reset Link', 'Aika Sake saitin mahaɗin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Firanṣẹ Ọna asopọ Tunto'),
(49, 'reset_code', 'Reset Code', 'Sake saitin Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tun koodu'),
(50, 'confirm_code', 'Confirm Code', 'Tabbatar da lamba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jẹrisi koodu'),
(51, 'new_password', 'New Password', 'Sabuwar Kalmar wucewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ọrọ aṣina Tuntun'),
(52, 'enter_password', 'Enter Password', 'Shigar da kalmar wucewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Ọrọigbaniwọle sii'),
(53, 'submit', 'Submit', 'Sallama', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Fi silẹ'),
(54, 'return_to_login', 'Return To Login', 'Komawa Zuwa Shiga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pada Lati Wọle'),
(56, 'reset_code_sent', 'Reset Code Sent', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'login_successful', 'Login Successful', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wọle Ṣe Aṣeyọri'),
(58, 'dashboard', 'Dashboard', 'Dashboard', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dasibodu'),
(59, 'welcome', 'Welcome', 'Barka da zuwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kaabo'),
(60, 'at_a_glance_summary_of_your_account_have_fun', 'At A Glance Summary Of Your Account Have Fun', 'A Kallo Takaitaccen Asusu naku Yi Nishaɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ni A kokan Lakotan ti rẹ Account Ni Fun'),
(61, 'cash_code', 'Cash Code', 'Lambar Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'koodu owo'),
(62, 'send_cash', 'Send Cash', 'Aika Cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Firanṣẹ Owo'),
(63, 'deposit', 'Deposit', 'Deposit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Idogo'),
(64, 'withdraw', 'Withdraw', 'Janye', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yiyọ kuro'),
(65, 'scan_to_pay', 'Scan To Pay', 'Duba Don Biya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ṣiṣayẹwo Lati Sanwo'),
(66, 'fundme_power_purchase_electricity_units_here', 'Fundme Power Purchase Electricity Units Here', 'Rukunin Sayen Wutar Lantarki na Fundme Anan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ẹya ina rira Agbara Fundme Nibi'),
(67, 'collections', 'Collections', 'Tari', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn akojọpọ'),
(68, 'select_collection', 'Select Collection', 'Zaɓi Tari', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Gbigba'),
(69, 'health_insurance', 'Health Insurance', 'Inshorar Lafiya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣeduro Ilera'),
(70, 'government_levy', 'Government Levy', 'Levy na Gwamnati', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Levy ijoba'),
(71, 'environmental_levy', 'Environmental Levy', 'Levy na Muhalli', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ayika Levy'),
(72, 'fundme_power', 'Fundme Power', 'Ƙarfin Asusun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Fundme Agbara'),
(73, 'overview', 'Overview', 'Dubawa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Akopọ'),
(74, 'available_balance', 'Available Balance', 'Akwai Ma&#39;auni', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iwontunwonsi to wa'),
(75, 'total_deposit', 'Total Deposit', 'Jimlar Deposit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lapapọ ohun idogo'),
(76, 'total_withdrawal', 'Total Withdrawal', 'Jimlar Janyewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lapapọ yiyọ kuro'),
(77, 'total_transfer', 'Total Transfer', 'Jumlar Canja wuri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lapapọ Gbigbe'),
(78, 'transaction_history', 'Transaction History', 'Tarihin ciniki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Itan iṣowo'),
(79, 'see_details', 'See Details', 'Dubi Cikakkun bayanai', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wo Awọn alaye'),
(80, 'loading_please_wait', 'Loading Please Wait', 'Loading Da fatan za a jira', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ikojọpọ Jọwọ Duro'),
(81, 'load_more', 'Load More', 'Ƙara Ƙara', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbee si Die e sii'),
(82, 'welcome_to_fundeme_cash_this_is_your_dashboard', 'Welcome To Fundeme Cash This Is Your Dashboard', 'Barka da zuwa Kuɗin Kuɗi Wannan Dashboard ɗin ku ne', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kaabọ si Owo Fundeme Eyi Ni Dasibodu Rẹ'),
(83, 'next', 'Next', 'Na gaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Itele'),
(84, 'generate_and_send_cash_code', 'Generate And Send Cash Code', 'Ƙirƙira Kuma Aika Lambar Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ina Ati Firanṣẹ koodu Owo'),
(85, 'transfer_cash_to_accounts_on_the_platform', 'Transfer Cash To Accounts On The Platform', 'Canja wurin Kuɗi zuwa Asusu Akan Dandalin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbigbe Owo Si Awọn akọọlẹ Lori Platform'),
(86, 'fund_your_account_with_cash_code_or_bank_transfer', 'Fund Your Account With Cash Code Or Bank Transfer', 'Yi Asusun Ku Tare da Lambar Kuɗi ko Canja wurin Banki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ṣe inawo akọọlẹ rẹ Pẹlu koodu Owo tabi Gbigbe Banki'),
(87, 'withdraw_your_balance_out_of_the_platform', 'Withdraw Your Balance Out Of The Platform', 'Cire Ma&#39;aunin ku Daga Dandalin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Fa Iwontunws.funfun Rẹ kuro Ninu Platform'),
(88, 'scan_qr_code_to_make_payments', 'Scan Qr Code To Make Payments', 'Duba lambar Qr Don Biyan Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ṣayẹwo koodu Qr Lati Ṣe Awọn sisanwo'),
(89, 'an_e_commerce_platform_to_get_all_products', 'An E Commerce Platform To Get All Products', 'Dandalin Kasuwancin E don Samun Duk Samfura', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Platform Iṣowo E kan Lati Gba Gbogbo Awọn ọja'),
(90, 'ext', 'Ext', 'Ext', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ext'),
(91, 'available_balance_in_wallet', 'Available Balance In Wallet', 'Akwai Ma&#39;auni A Wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iwontunws.funfun Wa Ni Apamọwọ'),
(92, 'total_money_that_has_entered_you_wallet_via_cash_code_and_bank_deposit', 'Total Money That Has Entered You Wallet Via Cash Code And Bank Deposit', 'Jimlar Kuɗin da Ya Shigar da Ku Wallet Ta Hanyar Cash Code da Deposit na Banki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lapapọ Owo ti o Wọ Apamọwọ Rẹ Nipasẹ koodu Owo ati idogo Banki'),
(93, 'total_money_withdrawn_from_wallet', 'Total Money Withdrawn From Wallet', 'Jimlar Kuɗin Cire Daga Wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lapapọ Owo Yiyọ Lati Apamọwọ'),
(94, 'total_cash_transfered_to_accounts_on_the_platform', 'Total Cash Transfered To Accounts On The Platform', 'Jimlar Kuɗi da Aka Canja wurin Asusu Akan Dandalin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Apapọ Owo Gbigbe Si Awọn akọọlẹ Lori Platform'),
(95, 'your_recent_transaction_history', 'Your Recent Transaction History', 'Tarihin Kasuwancinku na Kwanan nan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Itan Iṣowo Rẹ aipẹ'),
(96, 'finish', 'Finish', 'Gama', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pari'),
(97, 'sign_out', 'Sign Out', 'Fita', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ifowosi jada'),
(98, 'menu', 'Menu', 'Menu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Akojọ aṣyn'),
(99, 'analytics', 'Analytics', 'Bincike', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Atupale'),
(100, 'administrator', 'Administrator', 'Mai gudanarwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alakoso'),
(101, 'hmo', 'Hmo', 'Hmo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hmo'),
(102, 'trader', 'Trader', 'Dan kasuwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Onisowo'),
(103, 'environmental_volunteer_youth_service', 'Environmental Volunteer Youth Service', 'Sa-kai na Matasa na Muhalli', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣẹ Iyọọda Awọn ọdọ Ayika'),
(104, 'accounts', 'Accounts', 'Lissafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn iroyin'),
(105, 'profile', 'Profile', 'Bayanan martaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Profaili'),
(106, 'beneficiary_list', 'Beneficiary List', 'Jerin Masu Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Atokọ Awọn anfani'),
(107, 'health_subscription', 'Health Subscription', 'Biyan Kuɗi na Lafiya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alabapin Ilera'),
(108, 'collection_subscription', 'Collection Subscription', 'Biyan kuɗi na Tarin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbigba alabapin'),
(109, 'environment_levy_set_up', 'Environment Levy Set Up', 'Saitin Levy na Muhalli', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ayika Levy Ṣeto Up'),
(110, 'environment_subscription', 'Environment Subscription', 'Biyan Muhalli', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alabapin ayika'),
(111, 'cashback', 'Cashback', 'Cashback', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cashback'),
(112, 'referrals', 'Referrals', 'Magana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn itọkasi'),
(113, 'wallet', 'Wallet', 'Wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Apamọwọ'),
(114, 'promotion', 'Promotion', 'Gabatarwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Igbega'),
(115, 'leaderboard', 'Leaderboard', 'Allon jagora', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Atẹle olori'),
(116, 'campaign', 'Campaign', 'Gangamin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ipolongo'),
(117, 'support_ticket', 'Support Ticket', 'Tikitin tallafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tiketi atilẹyin'),
(118, 'notification', 'Notification', 'Sanarwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iwifunni'),
(119, 'activity', 'Activity', 'Ayyuka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣẹ-ṣiṣe'),
(120, 'access_roles', 'Access Roles', 'Matsayin shiga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ipa Wiwọle'),
(121, 'modules', 'Modules', 'Modules', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn modulu'),
(122, 'roles', 'Roles', 'Matsayi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ipa'),
(123, 'access_crud', 'Access Crud', 'Samun damar Cred', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wọle si Crud'),
(124, 'about', 'About', 'Game da', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nipa'),
(125, 'developer_account', 'Developer Account', 'Asusun Haɓakawa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Account Olùgbéejáde'),
(126, 'wallet_account', 'Wallet Account', 'Asusun Wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Apamọwọ apamọwọ'),
(127, 'withdraw_funds', 'Withdraw Funds', 'Cire Kudade', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yiyọ Awọn inawo'),
(128, 'view_profile', 'View Profile', 'Duba Bayanan martaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wo Profaili'),
(129, 'my_activity', 'My Activity', 'Ayyukana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣẹ-ṣiṣe Mi'),
(130, 'notifications', 'Notifications', 'Sanarwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn iwifunni'),
(131, 'wallet_debited_with_n110000_for_electricity', 'Wallet Debited With N110000 For Electricity', 'Bashin Wallet Da N110000 Domin Wutar Lantarki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbese Apamọwọ Pẹlu N110000 Fun Itanna'),
(132, 'wallet_debited_with_n50000_for_electricity', 'Wallet Debited With N50000 For Electricity', 'Bashin Wallet Da N50000 Na Wutar Lantarki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbese Apamọwọ Pẹlu N50000 Fun Itanna'),
(133, 'wallet_debited_with_n60000_for_electricity', 'Wallet Debited With N60000 For Electricity', 'Bashin Wallet Da N60000 Na Wutar Lantarki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbese Apamọwọ Pẹlu N60000 Fun Itanna'),
(134, 'view_all', 'View All', 'Duba Duk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wo Gbogbo'),
(135, 'transaction_code', 'Transaction Code', 'Lambar ciniki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Idunadura koodu'),
(136, 'account', 'Account', 'Asusu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iroyin'),
(137, 'payment_type', 'Payment Type', 'Nau&#39;in Biyan Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Isanwo Iru'),
(138, 'amount', 'Amount', 'Adadin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iye'),
(139, 'status', 'Status', 'Matsayi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ipo'),
(140, 'date', 'Date', 'Kwanan wata', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ọjọ'),
(141, 'electricity', 'Electricity', 'Wutar Lantarki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Itanna'),
(142, 'prepaid', 'Prepaid', 'An riga an biya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ti san tẹlẹ'),
(143, 'payments', 'Payments', 'Biyan kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn sisanwo'),
(144, 'what_do_you_want_to_do', 'What Do You Want To Do', 'Me kake so ka yi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kini O Fẹ Lati Ṣe'),
(145, 'choose_what_you_want_to_do', 'Choose What You Want To Do', 'Zabi Abin da kuke son Yi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 'deposit_via_bank_or_cash_code', 'Deposit Via Bank Or Cash Code', 'Deposit Ta Banki Ko Cash Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 'withdraw_into_bank_account', 'Withdraw Into Bank Account', 'Cire A Asusun Banki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 'generate_and_pay_with_cash_code', 'Generate And Pay With Cash Code', 'Ƙirƙiri Kuma Biya Tare da Lambar Cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 'fundme_power_', 'Fundme Power ', 'Ƙarfin Asusun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(150, 'beneficiary_type', 'Beneficiary Type', 'Nau&#39;in Mai Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 'select_beneficiary_type', 'Select Beneficiary Type', 'Zaɓi Nau&#39;in Mai Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(152, 'select_beneficiary', 'Select Beneficiary', 'Zaɓi Mai Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 'saved_beneficiary', 'Saved Beneficiary', 'Ajiye Mai Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 'new_beneficiary', 'New Beneficiary', 'Sabon Mai Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(155, 'enter_phone_number', 'Enter Phone Number', 'Shigar da Lambar Waya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Nọmba foonu sii'),
(156, 'beneficiary', 'Beneficiary', 'Mai amfana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(157, 'add_to_beneficary_list', 'Add To Beneficary List', 'Ƙara Zuwa Jerin Masu Amfani', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 'add', 'Add', 'Ƙara', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(159, 'paying_for', 'Paying For', 'Biya Domin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 'select_payment_for', 'Select Payment For', 'Zaɓi Biyan Don', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(161, 'personal_transaction', 'Personal Transaction', 'Ciniki na sirri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 'transportation', 'Transportation', 'Sufuri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(163, 'amount_to', 'Amount To', 'Adadin Zuwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(164, 'transfer', 'Transfer', 'Canja wurin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(165, 'enter_amount', 'Enter Amount', 'Shigar da Adadi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ iye sii'),
(166, 'payment_method', 'Payment Method', 'Hanyar Biyan Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Eto isanwo'),
(167, 'bank_transfer', 'Bank Transfer', 'Canja wurin Banki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 'amount_to_receive', 'Amount To Receive', 'Adadin Karɓa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 'enter_deposit_amount', 'Enter Deposit Amount', 'Shigar da Adadin ajiya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 'fundme_fee', 'Fundme Fee', 'Kudaden Kudi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(171, 'amount_to_send', 'Amount To Send', 'Adadin Aika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(172, 'withdraw_type', 'Withdraw Type', 'Nau&#39;in Janyewa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(173, 'personal_bank_account', 'Personal Bank Account', 'Asusun banki na sirri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(174, 'other_bank_acount', 'Other Bank Acount', 'Sauran Bankin Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(175, 'bank_account', 'Bank Account', 'Asusun banki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(176, 'select', 'Select', 'Zaɓi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan'),
(177, 'account_number', 'Account Number', 'Lambar akant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(178, 'code', 'Code', 'Lambar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(179, 'verify', 'Verify', 'Tabbatar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(180, 'remark', 'Remark', 'Magana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(181, 'enter_remark', 'Enter Remark', 'Shigar da Magana', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(182, 'security_pin', 'Security Pin', 'Tsaro Pin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aabo Pin'),
(183, 'enter_pin', 'Enter Pin', 'Shigar da Pin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ PIN sii'),
(184, 'continue_to_transfer', 'Continue To Transfer', 'Ci gaba Don Canja wurin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(185, 'note_our_transfer_fee_included_see_our_fees', 'Note Our Transfer Fee Included See Our Fees', 'Kula da Kuɗin Canja wurin Mu Ya Haɗa Duba Kuɗin Mu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(186, 'note_service_fee_of_n5_on_withdrawal', 'Note Service Fee Of N5 On Withdrawal', 'Kudin Sabis Na Kulawa Na N5 Lokacin Fitar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(187, 'continue_to_', 'Continue To ', 'Ci gaba Zuwa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(188, 'generate_cash_code', 'Generate Cash Code', 'Ƙirƙirar Lambar Kuɗi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(189, 'note_service_fee_charge_of_1_on_bank_transfer', 'Note Service Fee Charge Of 1 On Bank Transfer', 'Kuɗin Sabis na Bayanan kula na 1 akan Canja wurin Banki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(190, 'loading', 'Loading', 'Ana lodawa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ikojọpọ'),
(191, 'enter_code', 'Enter Code', 'Shigar da lamba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(192, '50_cashback_on_maximum_of_n500_ride_for_5_rides', '50 Cashback On Maximum Of N500 Ride For 5 Rides', 'Cashback 50 Akan Matsakaicin Hawan N500 Don Hawa 5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(193, 'start_camera', 'Start Camera', 'Fara Kamara', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(194, 'no_transaction_returned', 'No Transaction Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(195, 'activity_logs', 'Activity Logs', 'Rubutun Ayyuka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn akọọlẹ aṣayan iṣẹ-ṣiṣe'),
(196, 'search', 'Search', 'Bincika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wa'),
(197, 'load', 'Load', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(198, 'more', 'More', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(199, 'tophunmi_stephen_logged_in_', 'Tophunmi Stephen Logged In ', 'Tophunmi Stephen ya shiga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tophunmi Stephen Logged In'),
(200, 'tophunmi_stephen_logged_out', 'Tophunmi Stephen Logged Out', 'Tophunmi Stephen ya fita', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tophunmi Stephen Wọle Jade'),
(201, 'toph_transfered_n110000_to_toph_for_electricity_bill', 'Toph Transfered N110000 To Toph For Electricity Bill', 'An Canja Wayar Toph N110000 Zuwa Toph Domin Kudin Lantarki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Toph Ti gbe N110000 Si Toph Fun Iwe-owo ina'),
(202, 'toph_transfered_n110000', 'Toph Transfered N110000', 'An Canja wurin Toph N110000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Toph Gbigbe N110000'),
(203, 'toph_transfered_n50000_to_toph_for_electricity_bill', 'Toph Transfered N50000 To Toph For Electricity Bill', 'Toph Ya Tura N50000 Zuwa Toph Domin Kudin Lantarki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Toph Ti gbe N50000 Si Toph Fun Iwe-owo ina'),
(204, 'toph_transfered_n50000', 'Toph Transfered N50000', 'Toph An Canja wurin N50000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Toph Gbigbe N50000'),
(205, 'toph_transfered_n60000_to_toph_for_electricity_bill', 'Toph Transfered N60000 To Toph For Electricity Bill', 'Toph Ya Tura N60000 Zuwa Toph Domin Kudin Lantarki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Toph Ti gbe N60000 Si Toph Fun Iwe-owo ina'),
(206, 'toph_transfered_n60000', 'Toph Transfered N60000', 'An Canja wurin Toph N60000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Toph Gbigbe N60000'),
(207, 'this', 'This', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Eyi'),
(208, 'month', 'Month', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Osu'),
(209, 'today', 'Today', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Loni'),
(210, 'yesterday', 'Yesterday', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lana'),
(211, 'this_month', 'This Month', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Osu yii'),
(212, 'this_year', 'This Year', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Odun yi'),
(213, 'last_30', 'Last 30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '30 kẹhin'),
(214, 'date_range', 'Date Range', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ọjọ Ibiti'),
(215, 'start_date', 'Start Date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ọjọ Ibẹrẹ'),
(216, 'end_date', 'End Date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ọjọ Ipari'),
(217, 'commission', 'Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Igbimọ'),
(218, 'sms_charge', 'Sms Charge', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Owo SMS'),
(219, 'deposit_commission', 'Deposit Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Igbimo idogo'),
(220, 'withdrawal_commission', 'Withdrawal Commission', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yiyọ Commission'),
(221, 'cash_code_overview', 'Cash Code Overview', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Owo Code Akopọ'),
(222, 'total_no_of_cash_code_created', 'Total No Of Cash Code Created', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lapapọ No Of Owo koodu Da'),
(223, 'no_of_cash_code_used', 'No Of Cash Code Used', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ko si Of Owo koodu Lo'),
(224, 'no_of_cash_code_unused', 'No Of Cash Code Unused', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ko si Of Cash Code Lo'),
(225, 'total_amount_of_cash_code', 'Total Amount Of Cash Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lapapọ iye Of Cash Code'),
(226, 'amount_of_used_cash_code', 'Amount Of Used Cash Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iye Of Lo Cash Code'),
(227, 'amount_of_unused_cash_code', 'Amount Of Unused Cash Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iye Of ajeku Owo koodu'),
(228, 'last_7_days', 'Last 7 Days', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ọjọ 7 kẹhin'),
(229, 'type', 'Type', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iru'),
(230, 'postpaid', 'Postpaid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ti o sanwo lẹhin'),
(231, 'review_your_order', 'Review Your Order', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ṣe ayẹwo ibere rẹ'),
(232, 'meter_number', 'Meter Number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nọmba Mita'),
(233, 'name', 'Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Oruko'),
(234, 'meter_type', 'Meter Type', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mita Iru'),
(235, 'address', 'Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Adirẹsi'),
(236, 'amount_to_buy', 'Amount To Buy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iye Lati Ra'),
(237, 'service_charge', 'Service Charge', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Owo iṣẹ'),
(238, 'total_amount', 'Total Amount', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Oye gbo e'),
(239, 'debit_card', 'Debit Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kaadi Debiti'),
(240, 'ussd_code', 'Ussd Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USsd koodu'),
(241, 'proceed_to_payment_page', 'Proceed To Payment Page', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹsiwaju Si Oju-iwe Isanwo'),
(242, 'pay_now', 'Pay Now', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sanwo Bayi'),
(243, 'make_changes', 'Make Changes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ṣe Awọn iyipada'),
(244, 'please_enter_valid_phone_number_and_amount_to_proceed', 'Please Enter Valid Phone Number And Amount To Proceed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jọwọ Tẹ Nọmba Foonu Wulo Ati Iye Lati Tẹsiwaju'),
(245, 'number_of_unit', 'Number Of Unit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nọmba Of Unit'),
(246, 'service', 'Service', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣẹ'),
(247, 'transaction_successful', 'Transaction Successful', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(248, 'payment_receipt', 'Payment Receipt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(249, 'transaction_receipt', 'Transaction Receipt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(250, 'token', 'Token', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(251, 'number_of_units', 'Number Of Units', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(252, 'reference', 'Reference', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `td_language` (`id`, `phrase`, `english`, `hausa`, `spanish`, `arabic`, `dutch`, `russian`, `chinese`, `turkish`, `igbo`, `hungarian`, `french`, `greek`, `german`, `italian`, `thai`, `urdu`, `hindi`, `latin`, `indonesian`, `japanese`, `korean`, `yoruba`) VALUES
(253, 'amount_bought', 'Amount Bought', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(254, 'account_name', 'Account Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(255, 'account_address', 'Account Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(256, 'print_receipt', 'Print Receipt', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(257, 'back_to_payments', 'Back To Payments', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(258, 'information', 'Information', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alaye'),
(259, 'environment_information', 'Environment Information', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alaye Ayika'),
(260, 'fill_the_details', 'Fill The Details', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kun Awọn alaye'),
(261, 'completed', 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ti pari'),
(262, 'review_and_submit', 'Review And Submit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Atunwo Ati Firanṣẹ'),
(263, 'hmo_service_for_artisans_transporters_and_individuals_our_model_of_managed_care_is_the_prepaid_health_insurance_this_is_the_provision_of_a_defined_set_of_healthcare_services_to_an_enrolled_population_at_affordable_cost_by_a_network_of_accredited_healthcare_providers_statewide_their_activities_are_monitored_by_our_quality_assurance_team', 'Hmo Service For Artisans Transporters And Individuals Our Model Of Managed Care Is The Prepaid Health Insurance This Is The Provision Of A Defined Set Of Healthcare Services To An Enrolled Population At Affordable Cost By A Network Of Accredited Healthcare Providers Statewide Their Activities Are Monitored By Our Quality Assurance Team', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣẹ Hmo Fun Awọn Olukọni Awọn Onisẹ-ọnà Ati Awọn Olukuluku Awoṣe ti Itọju Iṣakoso jẹ Iṣeduro Ilera ti a ti san tẹlẹ Eyi ni Ipese Eto Itumọ Awọn iṣẹ Itọju Ilera Si Olugbe ti o forukọsilẹ ni idiyele ti o ni ifarada nipasẹ Nẹtiwọọki ti Awọn Olupese Ilera ti a fọwọsi ni gbogbo ipinlẹ Awọn iṣẹ wọn jẹ abojuto nipasẹ Didara wa Egbe idaniloju'),
(264, 'advantage_of_the_scheme', 'Advantage Of The Scheme', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Anfani ti Eto naa'),
(265, 'medical_expenses_can_be_adequately_budgeted_for', 'Medical Expenses Can Be Adequately Budgeted For', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn inawo iṣoogun Le Ṣe Isuna To peye Fun'),
(266, 'access_to_healthcare_services_247', 'Access To Healthcare Services 247', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Wiwọle si Awọn iṣẹ Itọju Ilera 247'),
(267, 'no_out_of_pocket_payment_for_them_and_their_', 'No Out Of Pocket Payment For Them And Their ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ko si Jade Ninu Apo Isanwo Fun Wọn Ati Wọn'),
(268, 'statewide_coveragebr', 'Statewide Coveragebr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbogbo ipinlẹ Coveragebr'),
(269, 'effective_monitoring_of_healthcare_providers', 'Effective Monitoring Of Healthcare Providers', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Abojuto ti o munadoko Ti Awọn olupese Ilera'),
(270, 'healthy_workforce_resulting_in_improved_workforce_productivity', 'Healthy Workforce Resulting In Improved Workforce Productivity', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Abajade Agbara Iṣẹ ti Ni ilera Ni Imudara Iṣe Iṣẹ Iṣẹ'),
(271, 'select_state', 'Select State', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Ipinle'),
(272, 'select_city', 'Select City', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Ilu'),
(273, 'select_state_first', 'Select State First', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan State First'),
(274, 'select_address_type', 'Select Address Type', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Orisi Adirẹsi'),
(275, 'residential', 'Residential', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ibugbe'),
(276, 'commercial', 'Commercial', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iṣowo'),
(277, 'price', 'Price', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iye owo'),
(278, 'contact_name', 'Contact Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Orukọ olubasọrọ'),
(279, 'contact_phone', 'Contact Phone', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Foonu olubasọrọ'),
(280, 'select_duration', 'Select Duration', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Iye akoko'),
(281, 'weekly', 'Weekly', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Osẹ-ọsẹ'),
(282, 'monthly', 'Monthly', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Oṣooṣu'),
(283, 'annually', 'Annually', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ododun'),
(284, 'price_to_be_paid_per_duration', 'Price To Be Paid Per Duration', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Iye Lati San Fun Iye akoko'),
(285, 'select_payment_method', 'Select Payment Method', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Ọna Isanwo'),
(286, 'select_method', 'Select Method', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Yan Ọna'),
(287, 'manual', 'Manual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Afowoyi'),
(288, 'automatic', 'Automatic', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laifọwọyi'),
(289, 'request_bin_basket_for_this_address', 'Request Bin Basket For This Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Beere Bin Agbọn Fun Yi adirẹsi'),
(290, 'costs_n15000', 'Costs N15000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Owo N15000'),
(291, 'delete', 'Delete', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Paarẹ'),
(292, 'add_more_address', 'Add More Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Fi Die Adirẹsi'),
(293, 'your_are_done', 'Your Are Done', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ti pari Rẹ'),
(294, 'congrats_successfully_created_your_profile', 'Congrats Successfully Created Your Profile', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Oriire ni Aṣeyọri Ṣẹda Profaili Rẹ'),
(295, 'back', 'Back', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pada'),
(296, 'subscribe', 'Subscribe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alabapin'),
(297, 'continue', 'Continue', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tesiwaju'),
(298, 'coming_soon', 'Coming Soon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Nbọ laipẹ'),
(299, 'welcome_to_fundeme_cash_loginregister_to_access_your_dashhboard', 'Welcome To Fundeme Cash Loginregister To Access Your Dashhboard', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(300, 'change_to_your_local_language', 'Change To Your Local Language', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(301, 'welcome_to_fundeme_cash_login_or_register_to_access_your_dashhboard', 'Welcome To Fundeme Cash Login Or Register To Access Your Dashhboard', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(302, 'welcome_to_fundeme_cash_reset_your_password', 'Welcome To Fundeme Cash Reset Your Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(303, 'welcome_to_fundeme_cash_login_to_access_your_dashhboard', 'Welcome To Fundeme Cash Login To Access Your Dashhboard', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(304, 'enter_fullname', 'Enter Fullname', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(305, 'enter_username', 'Enter Username', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(306, 'fullname_of_contact_person', 'Fullname Of Contact Person', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(307, 'business_name', 'Business Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(308, 'enter_name_of_contact_person', 'Enter Name Of Contact Person', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(309, 'enter_business_name', 'Enter Business Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(310, 'welcome_to_fundeme_cash_create_new_account', 'Welcome To Fundeme Cash Create New Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(311, 'change_your_language', 'Change Your Language', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(312, 'processing_please_wait', 'Processing Please Wait', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(313, 'language_settings', 'Language Settings', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(314, 'disable', 'Disable', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(315, 'activate', 'Activate', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(316, 'access_the_tidrem_panel_using_your_phone_number_and_password', 'Access The Tidrem Panel Using Your Phone Number And Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(317, 'access_the_tidrem_panel_using_your_email_addressphone_number_and_password', 'Access The Tidrem Panel Using Your Email Addressphone Number And Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(318, 'emailphone_number', 'Emailphone Number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(319, 'enter_your_emailphone_number', 'Enter Your Emailphone Number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(320, 'emai_or_phone_number', 'Emai Or Phone Number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(321, 'enter_your_email_or_phone_number', 'Enter Your Email Or Phone Number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(322, 'create_new_tidrem_account', 'Create New Tidrem Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(323, 'i_agree_to_tidrem', 'I Agree To Tidrem', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(324, '_select_state_', ' Select State ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(325, '_select_state_first_', ' Select State First ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(326, '_select_lga_', ' Select Lga ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(327, '_select_trade_type_', ' Select Trade Type ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(328, 'residential_address', 'Residential Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(329, 'enter_your_residential_address', 'Enter Your Residential Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(330, 're_enter_your_password', 'Re Enter Your Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(331, 'account_type', 'Account Type', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(332, 'enter_your_business_name', 'Enter Your Business Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(333, 'business_address', 'Business Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(334, 'enter_your_business_address', 'Enter Your Business Address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(335, 'email_accepted', 'Email Accepted', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(336, 'email_taken', 'Email Taken', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(337, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(338, 'please_type_carefully_and_fill_out_the_form_with_your_personal_details_your_cant_edit_these_details_once_you_submitted_the_form', 'Please Type Carefully And Fill Out The Form With Your Personal Details Your Cant Edit These Details Once You Submitted The Form', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(339, 'in_order_to_complete_please_upload_any_of_the_following_personal_document', 'In Order To Complete Please Upload Any Of The Following Personal Document', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(340, 'document_upload', 'Document Upload', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(341, 'to_verify_your_identity_please_upload_any_of_your_document', 'To Verify Your Identity Please Upload Any Of Your Document', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(342, 'passport', 'Passport', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(343, 'national_id', 'National Id', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(344, 'driving_license', 'Driving License', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(345, 'to_avoid_delays_when_verifying_account_please_make_sure_bellow', 'To Avoid Delays When Verifying Account Please Make Sure Bellow', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(346, 'chosen_credential_must_not_be_expaired', 'Chosen Credential Must Not Be Expaired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(347, 'document_should_be_good_condition_and_clearly_visible', 'Document Should Be Good Condition And Clearly Visible', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(348, 'make_sure_that_there_is_no_light_glare_on_the_card', 'Make Sure That There Is No Light Glare On The Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(349, 'upload_id_card_here', 'Upload Id Card Here', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(350, 'choose_image', 'Choose Image', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(351, 'upload_utility_bill_here', 'Upload Utility Bill Here', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(352, 'drag_and_drop_file', 'Drag And Drop File', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(353, 'or', 'Or', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(354, 'i_have_read_the', 'I Have Read The', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(355, 'terms_of_condition', 'Terms Of Condition', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(356, 'and', 'And', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(357, 'all_the_personal_information_i_have_entered_is_correct', 'All The Personal Information I Have Entered Is Correct', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(358, 'upload_image', 'Upload Image', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(359, 'account_created', 'Account Created', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(360, 'otp_sent_check_your_email_or_sms', 'Otp Sent Check Your Email Or Sms', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(361, 'one_time_password', 'One Time Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(362, 'enter_the_otp_sent_to_your_phone_or_email_to_verify_you_account', 'Enter The Otp Sent To Your Phone Or Email To Verify You Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(363, 'resend_code', 'Resend Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(364, 'confirm', 'Confirm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(365, 'welcome_to_fundeme_cash_verify_your_account', 'Welcome To Fundeme Cash Verify Your Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(366, '_logged_out', ' Logged Out', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(367, 'phone_number_taken', 'Phone Number Taken', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(368, 'phone_number_accepted', 'Phone Number Accepted', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(369, 'email_or_phone_number_already_exist', 'Email Or Phone Number Already Exist', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(370, 'invalid_otp', 'Invalid Otp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(371, 'otp_confirmed_you_can_now_login', 'Otp Confirmed You Can Now Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(372, '6e69e8', '6e69e8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(373, 'reset_code_confirmed', 'Reset Code Confirmed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(374, 'email_or_phone_number', 'Email Or Phone Number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(375, 'history', 'History', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(376, 'annual_remittance', 'Annual Remittance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(377, 'total_paid', 'Total Paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(378, 'total_unpaid', 'Total Unpaid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(379, 'wallet_balance', 'Wallet Balance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(380, 'mummy_ire_logged_out', 'Mummy Ire Logged Out', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(381, 'no_activity_returned', 'No Activity Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(382, 'administrators', 'Administrators', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(383, 'add_account', 'Add Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(384, 'no_notification', 'No Notification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(385, 'full_name', 'Full Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(386, 'all_state', 'All State', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(387, 'set_role', 'Set Role', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(388, 'save_record', 'Save Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(389, 'email_andor_phone_already_exist', 'Email Andor Phone Already Exist', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(390, 'record_created', 'Record Created', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(391, 'lga', 'Lga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(392, 'all_lga', 'All Lga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(393, 'record_updated', 'Record Updated', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(394, 'no_changes', 'No Changes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(395, 'personal_account', 'Personal Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Account ti ara ẹni'),
(396, 'personal_account_lists', 'Personal Account Lists', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(397, 'you_have_total', 'You Have Total', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'O ni Apapọ'),
(398, 'personal_accounts', 'Personal Accounts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(399, 'filter_customers', 'Filter Customers', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Àlẹmọ Onibara'),
(400, 'active_status', 'Active Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ipo ti nṣiṣe lọwọ'),
(401, 'all_status', 'All Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gbogbo Ipo'),
(402, 'activated', 'Activated', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mu ṣiṣẹ'),
(403, 'banned', 'Banned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Idilọwọ'),
(404, 'referral_status', 'Referral Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(405, 'referred', 'Referred', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(406, 'not_referred', 'Not Referred', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(407, 'enter_start_and_end_date', 'Enter Start And End Date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tẹ Bẹrẹ Ati Ọjọ Ipari'),
(408, 'start_date_cannot_be_greater', 'Start Date Cannot Be Greater', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ọjọ Ibẹrẹ Ko le tobi ju'),
(409, 'contact', 'Contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(410, 'date_joined', 'Date Joined', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(411, 'no_personal_account_returned', 'No Personal Account Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(412, 'business_account', 'Business Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(413, 'business_account_lists', 'Business Account Lists', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(414, 'business_accounts', 'Business Accounts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(415, 'filter_business_account', 'Filter Business Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(416, 'no_business_account_returned', 'No Business Account Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(417, 'view_details', 'View Details', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(418, 'edit', 'Edit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(419, 'tax_master', 'Tax Master', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(420, 'field_operatives', 'Field Operatives', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(421, 'tophunmi_stephen_updated_administrator_bilyamin_graba_record', 'Tophunmi Stephen Updated Administrator Bilyamin Graba Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(422, 'tophunmi_stephen_created_administrator_bilyamin_graba', 'Tophunmi Stephen Created Administrator Bilyamin Graba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(423, 'security_setup', 'Security Setup', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(424, 'my_profile', 'My Profile', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(425, 'you_have_full_control_to_manage_your_own_account_setting', 'You Have Full Control To Manage Your Own Account Setting', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(426, 'security', 'Security', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(427, 'qr_code', 'Qr Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(428, 'notification_sms_alerts', 'Notification Sms Alerts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(429, 'sms_cost_n5', 'Sms Cost N5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(430, 'receive_sms_on_transactions', 'Receive Sms On Transactions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(431, 'security_information', 'Security Information', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(432, 'environmental_set_up', 'Environmental Set Up', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(433, 'environment_set_up', 'Environment Set Up', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(434, 'manage_environmental_levy_set_up', 'Manage Environmental Levy Set Up', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(435, 'choose_one', 'Choose One', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(436, 'residential_fee', 'Residential Fee', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(437, 'commercial_fee', 'Commercial Fee', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(438, 'security_question_1', 'Security Question 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(439, 'what_is_your_favourite_city', 'What Is Your Favourite City', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(440, 'security_answer_1', 'Security Answer 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(441, 'answer_1', 'Answer 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(442, 'security_question_2', 'Security Question 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(443, 'what_is_your_favourite_color', 'What Is Your Favourite Color', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(444, 'security_answer_2', 'Security Answer 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(445, 'answer_2', 'Answer 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(446, 'update_profile', 'Update Profile', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(447, 'security_question_passed_enter_security_pin', 'Security Question Passed Enter Security Pin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(448, 'incorrect_answers', 'Incorrect Answers', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(449, 'payment_setup', 'Payment Setup', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(450, 'kerosene', 'Kerosene', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(451, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(452, 'welcome_to_tidrem_cash_login_to_access_your_dashhboard', 'Welcome To Tidrem Cash Login To Access Your Dashhboard', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(453, 'setup_information', 'Setup Information', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(454, 'medium_presumptive_tax', 'Medium Presumptive Tax', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(455, 'payment_duration', 'Payment Duration', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(456, '_select_duration_', ' Select Duration ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(457, 'daily', 'Daily', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(458, 'amount_per_duration_selected', 'Amount Per Duration Selected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(459, 'profile_settings_updated', 'Profile Settings Updated', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(460, 'save_settings', 'Save Settings', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(461, 'trade_line', 'Trade Line', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(462, 'tax_payments', 'Tax Payments', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(463, 'personal_information', 'Personal Information', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(464, 'basics', 'Basics', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(465, 'manage_profile', 'Manage Profile', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(466, 'referral_code', 'Referral Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(467, 'chosen_credential_must_not_be_expired', 'Chosen Credential Must Not Be Expired', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(468, 'national_id_or_utility_bill', 'National Id Or Utility Bill', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(469, 'upload_passport_here', 'Upload Passport Here', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(470, 'upload_passport', 'Upload Passport', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(471, 'upload_national_id_or_utility_bill_here', 'Upload National Id Or Utility Bill Here', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(472, 'upload_passport_photograph_here', 'Upload Passport Photograph Here', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(473, 'minimum', 'Minimum', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(474, 'medium', 'Medium', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(475, 'maximum', 'Maximum', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(476, 'territory', 'Territory', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(477, 'local_government', 'Local Government', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(478, 'delta_local_government', 'Delta Local Government', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(479, 'asaba_1', 'Asaba 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(480, 'asaba_2', 'Asaba 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(481, 'ogbe_ogonogo', 'Ogbe Ogonogo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(482, 'ibusa', 'Ibusa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(483, 'akwukwu_igbo', 'Akwukwu Igbo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(484, 'filter_artisan_account', 'Filter Artisan Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(485, 'no_territory_returned', 'No Territory Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(486, 'filter_territory', 'Filter Territory', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(487, 'tax_masters', 'Tax Masters', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(488, 'filter_tax_master_account', 'Filter Tax Master Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(489, 'no_tax_master_returned', 'No Tax Master Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(490, 'ban', 'Ban', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(491, 'no', 'No', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(492, 'yes', 'Yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(493, 'passport_photograph', 'Passport Photograph', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(494, 'valid_id_card', 'Valid Id Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(495, 'upload_id_card', 'Upload Id Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(496, 'utility_bill', 'Utility Bill', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(497, 'upload_utility_bill', 'Upload Utility Bill', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(498, 'select_territory', 'Select Territory', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(499, '_dear_dummy_namesbr_a_tax_master_account_has_been_created_with_this_email_below_are_the_login_credentials_emailabubakaraudugmailcomsbr_phone080559718890br_password12345br_', ' Dear Dummy Namesbr A Tax Master Account Has Been Created With This Email Below Are The Login Credentials Emailabubakaraudugmailcomsbr Phone080559718890br Password12345br ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(500, 'field_operative', 'Field Operative', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(501, 'no_field_operative_returned', 'No Field Operative Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(502, 'tax_payer_registered', 'Tax Payer Registered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(503, 'task_master', 'Task Master', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(504, 'select_territory_first', 'Select Territory First', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(505, '_dear_profilebr_a_field_operative_account_has_been_created_with_this_email_below_are_the_login_credentials_emailfluxoregoldgmailcombr_phone0703154950br_password12345br_', ' Dear Profilebr A Field Operative Account Has Been Created With This Email Below Are The Login Credentials Emailfluxoregoldgmailcombr Phone0703154950br Password12345br ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(506, 'tax_status_check', 'Tax Status Check', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(507, 'beneficiaty_lists', 'Beneficiaty Lists', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(508, 'beneficiary_accounts', 'Beneficiary Accounts', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(509, 'select_country', 'Select Country', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(510, 'no_administrator_account_returned', 'No Administrator Account Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(511, 'profile_view', 'Profile View', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(512, 'qr_code_information', 'Qr Code Information', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(513, 'download_qr_code', 'Download Qr Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(514, 'print_qr_code', 'Print Qr Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(515, 'tax_id', 'Tax Id', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(516, 'select_lga', 'Select Lga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(517, 'post', 'Post', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `td_language` (`id`, `phrase`, `english`, `hausa`, `spanish`, `arabic`, `dutch`, `russian`, `chinese`, `turkish`, `igbo`, `hungarian`, `french`, `greek`, `german`, `italian`, `thai`, `urdu`, `hindi`, `latin`, `indonesian`, `japanese`, `korean`, `yoruba`) VALUES
(518, 'otp_sent_check_your_email', 'Otp Sent Check Your Email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(519, 'this_is_your_otp_code_6573', 'This Is Your Otp Code 6573', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(520, 'successful', 'Successful', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(521, 'account_view', 'Account View', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(522, 'user_id', 'User Id', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(523, 'last_login', 'Last Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(524, 'transactions', 'Transactions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(525, 'activities', 'Activities', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(526, 'mobile_number', 'Mobile Number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(527, 'additional_information', 'Additional Information', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(528, 'joining_date', 'Joining Date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(529, 'wallet_history', 'Wallet History', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(530, 'total_credit', 'Total Credit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(531, 'activity_log', 'Activity Log', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(532, 'wallet_type', 'Wallet Type', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(533, 'no_notification_returned', 'No Notification Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(534, 'tax_history', 'Tax History', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(535, 'wallet_credited_with_n100', 'Wallet Credited With N100', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(536, 'tax', 'Tax', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(537, 'tax_account', 'Tax Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(538, 'payment_date', 'Payment Date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(539, 'tophunmi_stephen', 'Tophunmi Stephen', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(540, 'tax_payment', 'Tax Payment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(541, 'payment_of_tax', 'Payment Of Tax', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(542, 'login', 'Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(543, 'sign_in_to_continue', 'Sign In To Continue', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(544, 'wallet_credited_with_n1', 'Wallet Credited With N1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(545, '_dear_temiloluwa_adeagbobr_a_tax_master_account_has_been_created_with_this_email_below_are_the_login_credentials_emailtofunmi015gmailcombr_phone09156518930br_password12345br_', ' Dear Temiloluwa Adeagbobr A Tax Master Account Has Been Created With This Email Below Are The Login Credentials Emailtofunmi015gmailcombr Phone09156518930br Password12345br ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(546, '_dear_temiloluwa_adeagbo_a_tax_master_account_has_been_created_with_this_email_below_are_the_login_credentials_emailtofunmi015gmailcom_phone070315495007_password12345_', ' Dear Temiloluwa Adeagbo A Tax Master Account Has Been Created With This Email Below Are The Login Credentials Emailtofunmi015gmailcom Phone070315495007 Password12345 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(547, 'account_not_activated', 'Account Not Activated', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(548, 'verify_otp', 'Verify Otp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(549, 'try_again_later', 'Try Again Later', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(550, 'invalid_account', 'Invalid Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(551, 'id_card', 'Id Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(552, '_logged_out_', ' Logged Out ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(553, 'trade', 'Trade', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(554, 'duration', 'Duration', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(555, 'utilty', 'Utilty', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(556, '_card', ' Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(557, '_code', ' Code', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(558, 'download_id_card', 'Download Id Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(559, 'print_id_card', 'Print Id Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(560, 'unread', 'Unread', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(561, 'mark_as_read', 'Mark As Read', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(562, 'marked', 'Marked', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(563, 'read', 'Read', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(564, 'upload_valid_id_card_here', 'Upload Valid Id Card Here', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(565, 'upload_pasport_photograph_here', 'Upload Pasport Photograph Here', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(566, 'at_a_glance_summary_of_your_dashboard_have_fun', 'At A Glance Summary Of Your Dashboard Have Fun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(567, 'filter_lga', 'Filter Lga', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(568, 'select_lga_first', 'Select Lga First', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(569, 'all_territory', 'All Territory', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(570, 'role', 'Role', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(571, 'personal_tax_payer', 'Personal Tax Payer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(572, 'business_tax_payer', 'Business Tax Payer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(573, 'account_not_found', 'Account Not Found', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(574, 'amount_paid', 'Amount Paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(575, 'dear_tophunmi_stephen_your_tax_payment_of_n54167_was_successful_', 'Dear Tophunmi Stephen Your Tax Payment Of N54167 Was Successful ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(576, 'dear_tophunmi_stephen_your_tax_payment_of_n150000_was_successful_balance_of_n54167', 'Dear Tophunmi Stephen Your Tax Payment Of N150000 Was Successful Balance Of N54167', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(577, 'dear_tophunmi_stephen_your_tax_payment_of_n150000_was_successful_balance_of_n10000', 'Dear Tophunmi Stephen Your Tax Payment Of N150000 Was Successful Balance Of N10000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(578, 'dear_tophunmi_stephen_your_tax_payment_of_n150000_was_successful_', 'Dear Tophunmi Stephen Your Tax Payment Of N150000 Was Successful ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(579, 'tax_check', 'Tax Check', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(580, 'method', 'Method', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(581, 'payment_cycle_monthly', 'Payment Cycle Monthly', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(582, 'payment_cycle', 'Payment Cycle', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(583, 'tax_invoices', 'Tax Invoices', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(584, 'payment_due_date', 'Payment Due Date', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(585, 'tophunmi_stephen_created_payment_profile_and_updated_profile_', 'Tophunmi Stephen Created Payment Profile And Updated Profile ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(586, 'tophunmi_stephen_updated_field_operative_transaction_record', 'Tophunmi Stephen Updated Field Operative Transaction Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(587, 'tophunmi_stephen_updated_tax_master_mummy_ire_record', 'Tophunmi Stephen Updated Tax Master Mummy Ire Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(588, 'tophunmi_stephen_updated_tax_master_tax_status_check_record', 'Tophunmi Stephen Updated Tax Master Tax Status Check Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(589, 'tophunmi_stephen_created_field_operative_transaction', 'Tophunmi Stephen Created Field Operative Transaction', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(590, 'stephie_logged_out_', 'Stephie Logged Out ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(591, 'stephie_registered_on_the_platform', 'Stephie Registered On The Platform', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(592, 'create', 'Create', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(593, 'closed', 'Closed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(594, 'active', 'Active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(595, 'all', 'All', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(596, 'search_by_message', 'Search By Message', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(597, 'no_support_ticket_returned', 'No Support Ticket Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(598, 'title', 'Title', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(599, 'details', 'Details', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(600, 'picture', 'Picture', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(601, 'mark_as_closed', 'Mark As Closed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(602, 'technical_problem', 'Technical Problem', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(603, 'thank_you', 'Thank You', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(604, 'reply', 'Reply', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(605, 'hello', 'Hello', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(606, 'no_reply_yet_please_check_back', 'No Reply Yet Please Check Back', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(607, 'to_verify_your_identity_please_upload_your_passport', 'To Verify Your Identity Please Upload Your Passport', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(608, 'you_can_now_login', 'You Can Now Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(609, 'successfully_sent_payment', 'Successfully Sent Payment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(610, 'return', 'Return', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(611, 'account_not_activated_please_validate_account', 'Account Not Activated Please Validate Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(612, 'reference_phone', 'Reference Phone', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(613, 'enter_your_reference_phone', 'Enter Your Reference Phone', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(614, 'invalid_referral', 'Invalid Referral', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(615, 'invalid_referrence_phone', 'Invalid Referrence Phone', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(616, '_phone_number_already_exist', ' Phone Number Already Exist', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(617, 'record_updated_successfully', 'Record Updated Successfully', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(618, 'tax_payers', 'Tax Payers', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(619, 'tax_payer', 'Tax Payer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(620, 'tee_stores', 'Tee Stores', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(621, 'tophunmi_stephen_updated_business_tophu_record', 'Tophunmi Stephen Updated Business Tophu Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(622, 'tophunmi_stephen_updated_personal_tee_stores_record', 'Tophunmi Stephen Updated Personal Tee Stores Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(623, 'tee_stores_logged_out_', 'Tee Stores Logged Out ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(624, 'tee_stores_generated_virtual_accounttax_id_', 'Tee Stores Generated Virtual Accounttax Id ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(625, 'tee_stores_created_payment_profile_and_updated_profile_', 'Tee Stores Created Payment Profile And Updated Profile ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(626, 'tee_stores_logged_in_', 'Tee Stores Logged In ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(627, 'tophunmi_stephen_logged_out_', 'Tophunmi Stephen Logged Out ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(628, 'tophunmi_stephen_updated_personal_adeagbo_stephen_tophunmi_record', 'Tophunmi Stephen Updated Personal Adeagbo Stephen Tophunmi Record', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(629, 'tophunmi_stephen_updated_profile_', 'Tophunmi Stephen Updated Profile ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(630, 'adeagbo_stephen_tophunmi_registered_on_the_platform', 'Adeagbo Stephen Tophunmi Registered On The Platform', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(631, 'mummy_ire_registered_on_the_platform', 'Mummy Ire Registered On The Platform', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(632, 'tophu_registered_on_the_platform', 'Tophu Registered On The Platform', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(633, 'tophunmi_stephen_closed_support_ticket', 'Tophunmi Stephen Closed Support Ticket', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(634, 'tophunmi_stephen_replied_to_support_ticket', 'Tophunmi Stephen Replied To Support Ticket', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(635, 'adeagbo_stephen_tophunmi', 'Adeagbo Stephen Tophunmi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(636, 'mummy_ire', 'Mummy Ire', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(637, 'tophu', 'Tophu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(638, 'transaction', 'Transaction', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(639, 'stephie', 'Stephie', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(640, 'bilyamin_graba', 'Bilyamin Graba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(641, 'temiloluwa_adeagbo', 'Temiloluwa Adeagbo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(642, 'dummy_names', 'Dummy Names', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(643, 'tax_invoice', 'Tax Invoice', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(644, 'dear_adeagbo_stephen_tophunmi_you_have_successfully_registered_as_a_tax_payer_with_delta_state_government_your_tax_id_is_9977862277_kindly_make_your_allocated_tax_payment_to_account_no_9977862277_providus_bank_congratulations', 'Dear Adeagbo Stephen Tophunmi You Have Successfully Registered As A Tax Payer With Delta State Government Your Tax Id Is 9977862277 Kindly Make Your Allocated Tax Payment To Account No 9977862277 Providus Bank Congratulations', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(645, 'dear_tophu_you_have_successfully_registered_as_a_tax_payer_with_delta_state_government_your_tax_id_is_9977861373_kindly_make_your_allocated_tax_payment_to_account_no_9977861373_providus_bank_congratulations', 'Dear Tophu You Have Successfully Registered As A Tax Payer With Delta State Government Your Tax Id Is 9977861373 Kindly Make Your Allocated Tax Payment To Account No 9977861373 Providus Bank Congratulations', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(646, 'bulk_smsemail', 'Bulk Smsemail', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(647, 'filter', 'Filter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(648, 'otp_check', 'Otp Check', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(649, '_check', ' Check', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(650, 'payment', 'Payment', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(651, 'least_amounttransaction', 'Least Amounttransaction', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(652, 'least_total_transaction', 'Least Total Transaction', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(653, 'duration_days', 'Duration Days', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(654, 'description', 'Description', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(655, 'message', 'Message', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(656, 'send', 'Send', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(657, 'channel', 'Channel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(658, 'if_you_forgot_your_password_well_then_we_will_email_you_instructions_to_reset_your_password', 'If You Forgot Your Password Well Then We Will Email You Instructions To Reset Your Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(659, 'department', 'Department', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ẹka'),
(660, 'departments', 'Departments', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Awọn ẹka'),
(661, 'no_department_returned', 'No Department Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(662, 'add_more_roles', 'Add More Roles', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(663, 'cells', 'Cells', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(664, 'cell', 'Cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(665, 'daytime', 'Daytime', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(666, 'no_cell_returned', 'No Cell Returned', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(667, 'location', 'Location', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(668, 'cell_role', 'Cell Role', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(669, 'meeting_day', 'Meeting Day', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(670, 'meeting_time', 'Meeting Time', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(671, 'add_more_days', 'Add More Days', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(672, 'view_meeting_time', 'View Meeting Time', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(673, 'membership', 'Membership', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(674, 'new_membership', 'New Membership', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(675, 'memberships', 'Memberships', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `td_language_code`
--

CREATE TABLE `td_language_code` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `flag` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `td_language_code`
--

INSERT INTO `td_language_code` (`id`, `name`, `code`, `flag`, `status`) VALUES
(1, 'Afrikaans', 'af', NULL, 0),
(2, 'Albanian', 'sq', NULL, 0),
(3, 'Amharic', 'am', NULL, 0),
(4, 'Arabic', 'ar', NULL, 1),
(5, 'Armenian', 'hy', NULL, 0),
(6, 'Azerbaijani', 'az', NULL, 0),
(7, 'Basque', 'eu', NULL, 0),
(8, 'Belarusian', 'be', NULL, 0),
(9, 'Bengali', 'bn', NULL, 0),
(10, 'Bosnian', 'bs', NULL, 0),
(11, 'Bulgarian', 'bg', NULL, 0),
(12, 'Catalan', 'ca', NULL, 0),
(13, 'Cebuano', 'ceb', NULL, 0),
(14, 'Chichewa', 'ny', NULL, 0),
(15, 'Chinese (Simplified)', 'zh', NULL, 0),
(16, 'Chinese (Traditional)', 'zh-TW', NULL, 0),
(17, 'Corsican', 'co', NULL, 0),
(18, 'Croatian', 'hr', NULL, 0),
(19, 'Czech', 'cs', NULL, 0),
(20, 'Danish', 'da', NULL, 0),
(21, 'Dutch', 'nl', NULL, 0),
(22, 'English', 'en', NULL, 1),
(23, 'Esperanto', 'eo', NULL, 0),
(24, 'Estonian', 'et', NULL, 0),
(25, 'Filipino', 'tl', NULL, 0),
(26, 'Finnish', 'fi', NULL, 0),
(27, 'French', 'fr', NULL, 1),
(28, 'Frisian', 'fy', NULL, 0),
(29, 'Galician', 'gl', NULL, 0),
(30, 'Georgian', 'ka', NULL, 0),
(31, 'German', 'de', NULL, 0),
(32, 'Greek', 'el', NULL, 0),
(33, 'Gujarati', 'gu', NULL, 0),
(34, 'Haitian Creole', 'ht', NULL, 0),
(35, 'Hausa', 'ha', NULL, 1),
(36, 'Hawaiian', 'haw', NULL, 0),
(37, 'Hebrew', 'iw', NULL, 0),
(38, 'Hindi', 'hi', NULL, 0),
(39, 'Hmong', 'hmn', NULL, 0),
(40, 'Hungarian', 'hu', NULL, 0),
(41, 'Icelandic', 'is', NULL, 0),
(42, 'Igbo', 'ig', NULL, 1),
(43, 'Indonesian', 'id', NULL, 0),
(44, 'Irish', 'ga', NULL, 0),
(45, 'Italian', 'it', NULL, 0),
(46, 'Japanese', 'ja', NULL, 0),
(47, 'Javanese', 'jw', NULL, 0),
(48, 'Kannada', 'kn', NULL, 0),
(49, 'Kazakh', 'kk', NULL, 0),
(50, 'Khmer', 'km', NULL, 0),
(51, 'Korean', 'ko', NULL, 0),
(52, 'Kurdish (Kurmanji)', 'ku', NULL, 0),
(53, 'Kyrgyz', 'ky', NULL, 0),
(54, 'Lao', 'lo', NULL, 0),
(55, 'Latin', 'la', NULL, 0),
(56, 'Latvian', 'lv', NULL, 0),
(57, 'Lithuanian', 'lt', NULL, 0),
(58, 'Luxembourgish', 'lb', NULL, 0),
(59, 'Macedonian', 'mk', NULL, 0),
(60, 'Malagasy', 'mg', NULL, 0),
(61, 'Malay', 'ms', NULL, 0),
(62, 'Malayalam', 'ml', NULL, 0),
(63, 'Maltese', 'mt', NULL, 0),
(64, 'Maori', 'mi', NULL, 0),
(65, 'Marathi', 'mr', NULL, 0),
(66, 'Mongolian', 'mn', NULL, 0),
(67, 'Myanmar (Burmese)', 'my', NULL, 0),
(68, 'Nepali', 'ne', NULL, 0),
(69, 'Norwegian', 'no', NULL, 0),
(70, 'Pashto', 'ps', NULL, 0),
(71, 'Persian', 'fa', NULL, 0),
(72, 'Polish', 'pl', NULL, 0),
(73, 'Portuguese', 'pt', NULL, 0),
(74, 'Punjabi', 'pa', NULL, 0),
(75, 'Romanian', 'ro', NULL, 0),
(76, 'Russian', 'ru', NULL, 0),
(77, 'Samoan', 'sm', NULL, 0),
(78, 'Scots Gaelic', 'gd', NULL, 0),
(79, 'Serbian', 'sr', NULL, 0),
(80, 'Sesotho', 'st', NULL, 0),
(81, 'Shona', 'sn', NULL, 0),
(82, 'Sindhi', 'sd', NULL, 0),
(83, 'Sinhala', 'si', NULL, 0),
(84, 'Slovak', 'sk', NULL, 0),
(85, 'Slovenian', 'sl', NULL, 0),
(86, 'Somali', 'so', NULL, 0),
(87, 'Spanish', 'es', NULL, 0),
(88, 'Sundanese', 'su', NULL, 0),
(89, 'Swahili', 'sw', NULL, 0),
(90, 'Swedish', 'sv', NULL, 0),
(91, 'Tajik', 'tg', NULL, 0),
(92, 'Tamil', 'ta', NULL, 0),
(93, 'Telugu', 'te', NULL, 0),
(94, 'Thai', 'th', NULL, 0),
(95, 'Turkish', 'tr', NULL, 0),
(96, 'Ukrainian', 'uk', NULL, 0),
(97, 'Urdu', 'ur', NULL, 0),
(98, 'Uzbek', 'uz', NULL, 0),
(99, 'Vietnamese', 'vi', NULL, 0),
(100, 'Welsh', 'cy', NULL, 0),
(101, 'Xhosa', 'xh', NULL, 0),
(102, 'Yiddish', 'yi', NULL, 0),
(103, 'Yoruba', 'yo', NULL, 1),
(104, 'Zulu', 'zu', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `td_notify`
--

CREATE TABLE `td_notify` (
  `id` bigint(20) NOT NULL,
  `from_id` bigint(20) NOT NULL DEFAULT 0,
  `to_id` bigint(20) NOT NULL DEFAULT 0,
  `content` longtext DEFAULT NULL,
  `item` varchar(250) DEFAULT NULL,
  `item_id` bigint(20) NOT NULL DEFAULT 0,
  `new` int(1) NOT NULL DEFAULT 1,
  `reg_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_notify`
--

INSERT INTO `td_notify` (`id`, `from_id`, `to_id`, `content`, `item`, `item_id`, `new`, `reg_date`) VALUES
(1, 0, 8, 'Wallet Credited with N1.00', 'deposit', 4, 0, '2023-12-22 10:17:28'),
(2, 0, 8, 'Wallet Credited with N1.00', 'deposit', 5, 0, '2023-12-22 10:20:39'),
(3, 0, 8, 'Wallet Credited with N1', 'deposit', 6, 0, '2023-12-22 13:06:05'),
(4, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67', 'payment', 1, 1, '2024-01-02 12:28:54'),
(5, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N541.67 was successful. ', 'payment', 1, 1, '2024-01-02 12:33:01'),
(6, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. ', 'payment', 1, 1, '2024-01-02 12:36:34'),
(7, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N100.00', 'payment', 1, 1, '2024-01-02 12:37:45'),
(8, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67', 'payment', 4, 1, '2024-01-03 11:21:03'),
(9, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67', 'payment', 5, 1, '2024-01-03 11:23:10'),
(10, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67', 'payment', 1, 1, '2024-01-03 11:59:39'),
(11, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67', 'payment', 1, 1, '2024-01-03 12:05:50'),
(12, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N541.67 was successful. ', 'payment', 1, 1, '2024-01-03 12:07:14'),
(13, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 12:26:24'),
(14, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 12:30:38'),
(15, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 12:35:47'),
(16, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 12:52:57'),
(17, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 14:54:35'),
(18, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 14:58:43'),
(19, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N582.67 was successful. ', 'payment', 1, 1, '2024-01-03 15:05:04'),
(20, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 15:35:56'),
(21, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N582.67 was successful. ', 'payment', 1, 1, '2024-01-03 15:41:47'),
(22, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 15:45:07'),
(23, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 15:49:49'),
(24, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,459.00 was successful.  Balance of N582.67.  TIDREM Team', 'payment', 25, 1, '2024-01-03 15:49:57'),
(25, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. ', 'payment', 1, 1, '2024-01-03 15:52:37'),
(26, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,458.33 was successful.  Balance of N583.34.  TIDREM Team', 'payment', 26, 1, '2024-01-03 15:52:42'),
(27, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,458.33 was successful. ', 'payment', 1, 1, '2024-01-03 15:53:34'),
(28, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 was successful. Your next payment is due on 02/04/2024.  TIDREM Team', 'payment', 27, 1, '2024-01-03 15:53:40'),
(29, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67', 'payment', 1, 1, '2024-01-03 16:07:36'),
(30, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67 Your next payment of N2,041.67 is due on 02/03/2024. TIDREM Team', 'payment', 28, 1, '2024-01-03 16:07:44'),
(31, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67', 'payment', 1, 1, '2024-01-03 16:16:42'),
(32, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 was successful. Balance of N541.67 Your next payment of N2,041.67 is due on 02/03/2024. TIDREM Team', 'payment', 29, 1, '2024-01-03 16:16:53'),
(33, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N541.67 was successful. ', 'payment', 1, 1, '2024-01-03 16:27:10'),
(34, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N958.33 was successful. Balance of N1,083.34', 'payment', 1, 1, '2024-01-03 16:27:22'),
(35, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N958.33 was successful. Balance of N1,083.34 Your next payment of N2,041.67 is due on 02/04/2024. TIDREM Team', 'payment', 30, 1, '2024-01-03 16:27:33'),
(36, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 to the Delta State Government was successful. Your Payment Reference is {865235282} Balance of N541.67', 'payment', 1, 1, '2024-01-03 23:06:33'),
(37, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 to the Delta State Government was successful. Your Payment Reference is {1515590218} Balance of N541.67', 'payment', 1, 1, '2024-01-03 23:10:01'),
(38, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N541.67 to the Delta State Government was successful. Your Payment Reference is {2113986001} ', 'payment', 1, 1, '2024-01-03 23:10:44'),
(39, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N958.33 to the Delta State Government was successful. Your Payment Reference is {174729727} Balance of N1,083.34', 'payment', 1, 1, '2024-01-03 23:10:50'),
(40, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,083.34 to the Delta State Government was successful. Your Payment Reference is {164659100} ', 'payment', 1, 1, '2024-01-03 23:11:50'),
(41, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 to the Delta State Government was successful. Your Payment Reference is {1599269157} ', 'payment', 1, 1, '2024-01-03 23:11:59'),
(42, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N374.99 to the Delta State Government was successful. Your Payment Reference is {1781508846} Balance of N1,666.68', 'payment', 1, 1, '2024-01-03 23:12:07'),
(43, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N500.00 to the Delta State Government for 2024-04-03 was successful. Your Payment Reference is {771004234} Balance of N1,166.68', 'payment', 1, 1, '2024-01-03 23:15:14'),
(44, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 to the Delta State Government for 2024-01-04 was successful. Your Payment Reference is {938610118}. Balance of N541.67', 'payment', 1, 1, '2024-01-04 15:12:51'),
(45, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N541.67 to the Delta State Government for 2024-01-04 was successful. Your Payment Reference is {1263641695}. ', 'payment', 1, 1, '2024-01-04 15:12:57'),
(46, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 to the Delta State Government for 2024-02-04 was successful. Your Payment Reference is {1772332684}. ', 'payment', 1, 1, '2024-01-04 15:13:04'),
(47, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 to the Delta State Government for 2024-03-04 was successful. Your Payment Reference is {539547242}. ', 'payment', 1, 1, '2024-01-04 15:13:13'),
(48, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N374.99 to the Delta State Government for 2024-04-04 was successful. Your Payment Reference is {776013557}. Balance of N1,666.68', 'payment', 1, 1, '2024-01-04 15:13:22'),
(49, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,500.00 to the Delta State Government for 2024-04-04 was successful. Your Payment Reference is {603628173}. Balance of N166.68', 'payment', 1, 1, '2024-01-04 15:13:28'),
(50, 8, 8, 'Tophunmi Stephen Created a Support Ticket', 'support', 6, 1, '2024-01-04 15:43:39'),
(51, 8, 33, 'Tophunmi Stephen Created a Support Ticket', 'support', 6, 1, '2024-01-04 15:43:39'),
(52, 8, 8, 'Comment on Support Ticket', 'support', 6, 1, '2024-01-04 15:47:53'),
(53, 8, 8, 'Tophunmi Stephen Closed Support Ticket', 'support', 1, 1, '2024-01-04 15:48:20'),
(54, 0, 54, 'Dear Tophu, you have successfully registered as a Tax Payer with Delta State Government. Your Tax ID is 9977861373. Kindly make your allocated tax payment to Account No: 9977861373 (Providus Bank). Congratulations.', 'authentication', 54, 1, '2024-01-04 16:41:24'),
(55, 0, 55, 'Dear Mummy Ire, you have successfully registered as a Tax Payer with Delta State Government. Your Tax ID is 9977861713. Kindly make your allocated tax payment to Account No: 9977861713 (Providus Bank). Congratulations.', 'authentication', 55, 1, '2024-01-04 21:44:12'),
(56, 0, 56, 'Dear Mummy Ire, you have successfully registered as a Tax Payer with Delta State Government. Your Tax ID is 9977861720. Kindly make your allocated tax payment to Account No: 9977861720 (Providus Bank). Congratulations.', 'authentication', 56, 1, '2024-01-04 21:45:58'),
(57, 0, 57, 'Dear Mummy Ire, you have successfully registered as a Tax Payer with Delta State Government. Your Tax ID is 9977861744. Kindly make your allocated tax payment to Account No: 9977861744 (Providus Bank). Congratulations.', 'authentication', 57, 1, '2024-01-04 21:49:20'),
(58, 0, 58, 'Dear Adeagbo Stephen Tophunmi, you have successfully registered as a Tax Payer with Delta State Government. Your Tax ID is 9977862277. Kindly make your allocated tax payment to Account No: 9977862277 (Providus Bank). Congratulations.', 'authentication', 58, 1, '2024-01-05 10:55:34'),
(59, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N166.68 to the Delta State Government for 2024-04-04 was successful. Your Payment Reference is {843467910}. ', 'payment', 1, 1, '2024-01-07 00:15:35'),
(60, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N333.32 to the Delta State Government for 2024-05-04 was successful. Your Payment Reference is {1536180252}. Balance of N1,708.35', 'payment', 1, 1, '2024-01-07 00:15:41'),
(61, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,708.35 to the Delta State Government for 2024-05-04 was successful. Your Payment Reference is {1360388610}. ', 'payment', 1, 1, '2024-01-07 00:27:01'),
(62, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N2,041.67 to the Delta State Government for 2024-06-04 was successful. Your Payment Reference is {2085556174}. ', 'payment', 1, 1, '2024-01-07 00:27:12'),
(63, 0, 8, 'Dear Tophunmi Stephen, your tax payment of N1,249.98 to the Delta State Government for 2024-07-04 was successful. Your Payment Reference is {1488240554}. Balance of N791.69', 'payment', 1, 1, '2024-01-07 00:27:20'),
(64, 0, 59, 'Dear Adeagbo Stephen Tophunmi, you have successfully registered as a Tax Payer with Delta State Government. Your Tax ID is 9977573399. Kindly make your allocated tax payment to Account No: 9977573399 (Providus Bank). Congratulations.', 'authentication', 59, 1, '2024-01-26 16:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `td_setting`
--

CREATE TABLE `td_setting` (
  `id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_setting`
--

INSERT INTO `td_setting` (`id`, `name`, `value`) VALUES
(2, 'sandbox', 'yes'),
(3, 'live_key', 'c998fd2c0949d73c13839450d5a6daebe110ccd55825f6499bf19e3b393cc5a9061a263283a2ced3af20bdf397f37e120b5e037d4da57a2ab39c821389ad3948'),
(5, 'test_key', 'FLWPUBK_TEST-f44eb646e461490e90fade825e39ec41-X'),
(25, 'bulk_api', '3piMkNQjw14SGi0uzDidL1YPP1EFmlEvxZeRsW8sFSDyep8b2Hw2KTp77RyJ'),
(24, 'termil_api', 'TL0k0TFm6yJHrHO9hR7cSWH1JoMsZ7bzTNbkve9lVo9zLYyfY81cINAqtS9GOM'),
(23, 'client_secret', '926D9BFFB9270F39BBF46C182A289636505F41D845B983FB0ED017555DDEE77C'),
(22, 'client_id', 'blRlY2haZWQg'),
(21, 'test_url', 'http://154.113.16.142:8088/appdevapi/api/'),
(20, 'live_url', 'https://vps.providusbank.com/vps/api/');

-- --------------------------------------------------------

--
-- Table structure for table `td_user`
--

CREATE TABLE `td_user` (
  `id` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `othername` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `surname` varchar(200) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `role_id` bigint(20) DEFAULT 0,
  `reset` varchar(150) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `activate` int(1) NOT NULL DEFAULT 0,
  `dob` date DEFAULT NULL,
  `family_status` varchar(20) DEFAULT NULL,
  `marriage_anniversary` date DEFAULT NULL,
  `dept_id` int(11) NOT NULL DEFAULT 0,
  `last_log` datetime DEFAULT NULL,
  `is_staff` int(1) NOT NULL DEFAULT 0,
  `reg_date` datetime NOT NULL,
  `img_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(20) DEFAULT NULL,
  `chat_handle` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `family_position` varchar(15) DEFAULT NULL,
  `cell_id` int(11) NOT NULL DEFAULT 0,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `dept_role` varchar(40) DEFAULT NULL,
  `cell_role` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `td_user`
--

INSERT INTO `td_user` (`id`, `email`, `firstname`, `othername`, `password`, `phone`, `surname`, `address`, `role_id`, `reset`, `otp`, `activate`, `dob`, `family_status`, `marriage_anniversary`, `dept_id`, `last_log`, `is_staff`, `reg_date`, `img_id`, `title`, `chat_handle`, `gender`, `family_position`, `cell_id`, `parent_id`, `dept_role`, `cell_role`) VALUES
(1, 'admin@ceaurora.vip', 'Admin', NULL, '827ccb0eea8a706c4c34a16891f84e7b', NULL, 'CEAURORA', NULL, 2, NULL, NULL, 1, NULL, NULL, NULL, 0, '2024-02-20 03:54:49', 0, '2024-02-19 11:45:20', 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(2, 'tofunmi015@gmail.com', 'Stephen', NULL, '827ccb0eea8a706c4c34a16891f84e7b', NULL, 'Tophunmi', NULL, 1, NULL, NULL, 1, NULL, NULL, NULL, 0, '2024-02-20 17:55:45', 0, '2024-02-19 19:04:22', 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL),
(3, 'tofunmi015@gmail.com', 'Test', 'Test', '', '0801234556', 'Test', 'ikorodu lagos', 4, NULL, NULL, 1, '2024-02-14', 'single', NULL, 1, NULL, 0, '2024-02-20 20:50:19', 0, NULL, 'tgest@chat.nd', 'Male', 'Parent', 1, 0, 'admin', 'Master'),
(4, 'test@mail.com', 'ersts', '', '827ccb0eea8a706c4c34a16891f84e7b', '08012345', 'rest', 'tetst', 4, NULL, NULL, 1, '2024-02-08', 'married', NULL, 1, NULL, 0, '2024-02-20 21:05:25', 0, NULL, 'tets@jdj.mn', 'Male', 'Parent', 1, 0, 'choir', 'Master');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `td_access`
--
ALTER TABLE `td_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_access_module`
--
ALTER TABLE `td_access_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_access_role`
--
ALTER TABLE `td_access_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_activity`
--
ALTER TABLE `td_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_cells`
--
ALTER TABLE `td_cells`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_dept`
--
ALTER TABLE `td_dept`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_file`
--
ALTER TABLE `td_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_language`
--
ALTER TABLE `td_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_language_code`
--
ALTER TABLE `td_language_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_notify`
--
ALTER TABLE `td_notify`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_setting`
--
ALTER TABLE `td_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `td_user`
--
ALTER TABLE `td_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `td_access`
--
ALTER TABLE `td_access`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `td_access_module`
--
ALTER TABLE `td_access_module`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `td_access_role`
--
ALTER TABLE `td_access_role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `td_activity`
--
ALTER TABLE `td_activity`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `td_cells`
--
ALTER TABLE `td_cells`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `td_dept`
--
ALTER TABLE `td_dept`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `td_file`
--
ALTER TABLE `td_file`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `td_language`
--
ALTER TABLE `td_language`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=676;

--
-- AUTO_INCREMENT for table `td_language_code`
--
ALTER TABLE `td_language_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `td_notify`
--
ALTER TABLE `td_notify`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `td_setting`
--
ALTER TABLE `td_setting`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `td_user`
--
ALTER TABLE `td_user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
