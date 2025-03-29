-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Време на генериране:  4 фев 2025 в 10:09
-- Версия на сървъра: 10.4.32-MariaDB
-- Версия на PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данни: `daycare`
--

-- --------------------------------------------------------

--
-- Структура на таблица `babysitter`
--

CREATE TABLE `babysitter` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL,
  `assigned_child` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `babysitter`
--

INSERT INTO `babysitter` (`id`, `user_id`, `user_name`, `password`, `date`, `profile_pic`, `assigned_child`) VALUES
(1, 4715035392321539221, 'hello', '1234', '2025-01-04 22:00:00', NULL, NULL),
(2, 8305156, 'ivayla', '1234', '2025-01-04 22:00:00', NULL, NULL),
(3, 64028697, 'stefani', '1234', '2025-01-04 22:00:00', NULL, 'Kira,Steven'),
(4, 47309887998759, 'Nail', '1234', '2025-01-28 20:57:21', '679c49063e618-Screenshot_44.jpg', ', Stephanie,Steven'),
(5, 9223372036854775807, 'Nail', '1234', '2025-01-28 21:22:13', '679c49063e618-Screenshot_44.jpg', NULL),
(6, 9471654, 'test3', '1234', '2025-02-01 04:10:46', NULL, 'Ema');

-- --------------------------------------------------------

--
-- Структура на таблица `babysitter_requests`
--

CREATE TABLE `babysitter_requests` (
  `id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `babysitter_id` bigint(20) NOT NULL,
  `child_name` varchar(200) NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `babysitter_requests`
--

INSERT INTO `babysitter_requests` (`id`, `parent_id`, `babysitter_id`, `child_name`, `status`, `request_date`) VALUES
(1, 20, 4, '', 'accepted', '2025-01-31 04:54:41'),
(2, 13, 4, 'Stephanie,Steven', 'accepted', '2025-01-31 05:16:23'),
(3, 13, 5, 'Stephanie,Steven', 'pending', '2025-01-31 05:16:25'),
(4, 21, 3, 'Ema', 'pending', '2025-02-01 03:56:45'),
(5, 21, 2, 'Ema', 'rejected', '2025-02-01 03:59:27'),
(6, 21, 4, 'Ema', '', '2025-02-01 04:06:22'),
(7, 21, 1, 'Ema', 'pending', '2025-02-01 04:09:42'),
(8, 21, 6, 'Ema', 'accepted', '2025-02-01 04:11:09'),
(9, 21, 5, 'Ema', 'pending', '2025-02-03 01:46:37'),
(10, 22, 4, '', 'pending', '2025-02-03 01:47:01'),
(11, 23, 3, 'Kira,Steven', 'accepted', '2025-02-03 08:01:51');

-- --------------------------------------------------------

--
-- Структура на таблица `babysitter_updates`
--

CREATE TABLE `babysitter_updates` (
  `id` bigint(20) NOT NULL,
  `babysitter_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `child_name` varchar(200) NOT NULL,
  `update_text` text NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `babysitter_updates`
--

INSERT INTO `babysitter_updates` (`id`, `babysitter_id`, `parent_id`, `child_name`, `update_text`, `update_time`) VALUES
(1, 4, 20, 'Steven and Ema', 'lost ', '2025-01-31 21:03:51'),
(2, 4, 20, 'Steven and Ema', 'lost ', '2025-01-31 21:06:43'),
(3, 4, 13, 'Steven', 'dadada', '2025-01-31 21:07:23'),
(4, 4, 13, 'All Children', 'fdsfsdf', '2025-01-31 22:13:41'),
(5, 6, 21, 'Ema', 'dadadd', '2025-02-01 04:13:28'),
(6, 3, 23, 'Kira', 'Ate her vegetables', '2025-02-03 08:04:31');

-- --------------------------------------------------------

--
-- Структура на таблица `events`
--

CREATE TABLE `events` (
  `id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `child_name` varchar(200) NOT NULL,
  `event_date` date NOT NULL,
  `event_description` text NOT NULL,
  `event_time` time NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `events`
--

INSERT INTO `events` (`id`, `parent_id`, `child_name`, `event_date`, `event_description`, `event_time`, `status`) VALUES
(1, 14, 'Steven', '2025-01-17', 'dadad', '00:00:00', 'pending'),
(2, 14, 'Steven', '2025-01-17', 'dadad', '00:00:00', 'pending'),
(3, 14, 'Steven', '2025-01-17', 'dadad', '00:00:00', 'pending'),
(4, 14, 'Steven', '2025-01-17', 'dadad', '00:00:00', 'pending'),
(5, 14, 'Steven', '2025-01-18', 'da', '00:00:00', 'pending'),
(6, 14, 'Steven', '2025-01-18', 'da', '00:00:00', 'pending'),
(7, 14, 'ema', '2025-01-25', 'dada', '04:42:00', 'pending'),
(8, 14, 'Steven', '2025-01-18', 'golf', '02:13:00', 'pending'),
(9, 14, 'Steven', '2025-01-18', 'golf', '02:13:00', 'pending'),
(10, 14, 'Steven', '2025-01-24', '78yyggjuh', '05:38:00', 'pending'),
(11, 15, 'Stela', '2025-02-14', 'sreshta s nail', '20:30:00', 'pending'),
(12, 13, 'Stephanie', '2025-01-17', 'каране на работа', '23:20:00', 'completed'),
(13, 16, 'Steven', '2025-01-25', 'dadada', '18:23:00', 'pending'),
(14, 17, 'Elenor', '2025-01-17', 'golf ', '17:52:00', 'pending'),
(15, 13, 'Steven', '2025-02-21', 'take from school', '17:46:00', 'completed'),
(16, 13, 'Stephanie', '2025-01-25', 'fxfsf', '22:56:00', 'pending'),
(17, 13, 'Stephanie', '2025-03-13', 'fdsfs', '03:12:00', 'pending'),
(18, 21, 'Ema', '2025-02-13', 'dada', '09:15:00', 'completed'),
(19, 21, 'Ema', '2025-02-06', 'sleeep', '06:15:00', 'completed'),
(20, 23, 'Steven', '2025-02-04', 'na uchilishte', '12:07:00', 'completed');

-- --------------------------------------------------------

--
-- Структура на таблица `parents`
--

CREATE TABLE `parents` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_pic` varchar(60) DEFAULT NULL,
  `child` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `parents`
--

INSERT INTO `parents` (`id`, `user_id`, `user_name`, `password`, `date`, `profile_pic`, `child`) VALUES
(1, 3450676104031, 'dadada', '1234', '2024-12-16 21:57:06', '', ''),
(2, 861513438360650175, 'dadadada', '1234', '2024-12-16 21:57:57', '', ''),
(3, 847169814, 'nanaivanova', '1234', '2024-12-16 22:27:52', '', ''),
(4, 9601, 'nail', '1907', '2024-12-16 22:28:21', '', ''),
(5, 5870096883, 'stela34', 'stela34', '2024-12-21 21:36:14', '', ''),
(6, 759040496564, 'stela', 'stela', '2025-01-04 21:26:49', '', ''),
(7, 48647553078025357, 'stela12', 'stela', '2025-01-04 21:37:23', '', ''),
(8, 948944, 'sladka', '1234', '2025-01-04 21:38:25', '', ''),
(9, 2020456, 'dadada', '1234', '2025-01-04 21:48:09', '', ''),
(10, 569588133355964, 'hristo', '12345', '2025-01-04 22:28:13', '', ''),
(11, 9223372036854775807, 'zahari', '1234', '2025-01-15 02:18:55', '67871b0fc1470-Screenshot_684.jpg', 'Steven,Elenor,Ema,Kira,Tina'),
(12, 473094855886974493, 'hristo', '1234', '2025-01-13 03:01:36', '', ''),
(13, 3339477034075941793, 'hristo1', '1234', '2025-02-01 02:54:59', '679d8d03cb67e-.gif', 'Stephanie,Steven'),
(14, 4777, 'admin1', '1234', '2025-01-15 21:44:19', NULL, 'Steven,ema'),
(15, 2916996915, 'ceco', '1234', '2025-01-16 01:12:25', '67885ce7e0477-Screenshot_532.jpg', 'Stela,Zahari'),
(16, 6415311393827524204, 'admin2', '1234', '2025-01-16 13:20:26', '67890793e0a78-robot-sign-hi-done-d-69353625.jpg', 'Steven'),
(17, 548135988797241219, 'admin3', '1234', '2025-01-16 13:56:27', '67890f1060db4-robot-sign-hi-done-d-69353625.jpg', 'Elenor,Steven,Ema'),
(18, 732957080437, 'nail', '1234', '2025-01-28 20:56:45', NULL, ''),
(19, 2487, 'Nail', '1234', '2025-01-31 03:24:10', NULL, ''),
(20, 52112817874, 'neznamveche', '1234', '2025-01-31 03:24:47', NULL, ''),
(21, 249914006313500, 'test2', '1234', '2025-02-01 03:51:44', NULL, 'Ema'),
(22, 67569, 'test25', '1234', '2025-02-03 01:46:53', NULL, ''),
(23, 9223372036854775807, 'Test22', '1234', '2025-02-03 08:01:15', '67a077bd69382-robot-sign-hi-done-d-69353625.jpg', 'Kira,Steven');

--
-- Indexes for dumped tables
--

--
-- Индекси за таблица `babysitter`
--
ALTER TABLE `babysitter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_name` (`user_name`),
  ADD KEY `date` (`date`);

--
-- Индекси за таблица `babysitter_requests`
--
ALTER TABLE `babysitter_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `babysitter_id` (`babysitter_id`);

--
-- Индекси за таблица `babysitter_updates`
--
ALTER TABLE `babysitter_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `babysitter_id` (`babysitter_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индекси за таблица `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`parent_id`),
  ADD KEY `user_id_2` (`parent_id`),
  ADD KEY `child_name` (`child_name`),
  ADD KEY `event _time` (`event_time`);

--
-- Индекси за таблица `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `user_name` (`user_name`),
  ADD KEY `child` (`child`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `babysitter`
--
ALTER TABLE `babysitter`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `babysitter_requests`
--
ALTER TABLE `babysitter_requests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `babysitter_updates`
--
ALTER TABLE `babysitter_updates`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `babysitter_requests`
--
ALTER TABLE `babysitter_requests`
  ADD CONSTRAINT `babysitter_requests_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `babysitter_requests_ibfk_2` FOREIGN KEY (`babysitter_id`) REFERENCES `babysitter` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `babysitter_updates`
--
ALTER TABLE `babysitter_updates`
  ADD CONSTRAINT `babysitter_updates_ibfk_1` FOREIGN KEY (`babysitter_id`) REFERENCES `babysitter` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `babysitter_updates_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
