-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 08:28 AM
-- Server version: 11.8.3-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quickclean`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `date` date NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `extras` text DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','accepted','declined') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `service_id`, `service_name`, `price`, `date`, `time`, `extras`, `name`, `phone`, `email`, `address`, `notes`, `status`, `created_at`) VALUES
(1, 1, 3, 'Move-in/Move-out Cleaning', NULL, '2025-10-13', '17:05', NULL, 'Otelo Nobleza', '09123456789', 'otelo@gmail.com', 'Sugcad, Polangui, Albay', 'matthew', '', '2025-10-06 04:05:48'),
(3, 1, 4, 'Upholstery Cleaning', NULL, '2025-10-20', '13:00', NULL, 'Otelo Nobleza', '09123456789', 'otelo@gmail.com', 'Sugcad, Polangui, Albay', 'k', '', '2025-10-06 04:13:34'),
(4, 3, 0, '', NULL, '2025-10-09', '16:23', NULL, 'neknek', '09123456789', 'neknek@gmail.com', 'Sugcad, Polangui, Albay', 'hehehe', '', '2025-10-06 04:24:17'),
(5, 3, 0, '', NULL, '2025-10-09', '16:23', NULL, 'neknek', '09123456789', 'neknek@gmail.com', 'Sugcad, Polangui, Albay', 'hehehe', '', '2025-10-06 04:25:29'),
(6, 3, 1, 'Post-Construction Cleaning', NULL, '2025-10-09', '10:10', NULL, 'neknek', '09123456789', 'neknek@gmail.com', 'Sugcad, Polangui, Albay', '', '', '2025-10-06 04:26:22'),
(7, 3, 1, 'Post-Construction Cleaning', NULL, '2025-10-09', '10:19', NULL, 'neknek', '09123456789', 'neknek@gmail.com', 'Sugcad, Polangui, Albay', '', 'accepted', '2025-10-06 04:31:42'),
(8, 3, 2, 'Regular Home Cleaning', 4500.00, '2025-10-09', '15:14', NULL, 'neknek', '09123456788', 'neknek@gmail.com', 'Sugcad, Polangui, Albay', 'k', '', '2025-10-06 05:14:50'),
(9, 4, 2, 'Regular Home Cleaning', 4500.00, '2025-10-07', '16:31', NULL, 'Jaiden Fermante', '09123456789', 'jaiden@gmail.com', 'Sugcad, Polangui, Albay', '', '', '2025-10-06 06:31:22'),
(10, 1, 2, 'Regular Home Cleaning', 4500.00, '2025-10-18', '06:31', NULL, 'Otelo Nobleza', '09123456789', 'otelo@gmail.com', 'Sugcad, Polangui, Albay', '', '', '2025-10-17 10:28:12'),
(11, 1, 2, 'Regular Home Cleaning', 4500.00, '2025-10-18', '23:06', NULL, 'Otelo Nobleza', '09123456789', 'otelo@gmail.com', 'Pilar, Sorsogon', '', 'accepted', '2025-10-24 15:04:26'),
(12, 1, 2, 'Regular Home Cleaning', 4500.00, '2025-10-18', '23:06', NULL, 'Otelo Nobleza', '09123456789', 'otelo@gmail.com', 'Pilar, Sorsogon', '', '', '2025-10-24 15:05:43'),
(13, 5, 2, 'Regular Home Cleaning', 4500.00, '2025-10-18', '12:30', NULL, 'Jon Matthew Mella', '09123456789', 'Mella2@gmail.com', 'Sugcad, Polangui, Albay', '', 'declined', '2025-10-24 15:29:42'),
(14, 6, 2, 'Regular Home Cleaning', 4500.00, '2025-10-18', '01:05', NULL, 'Symon Cristoffer Cano', '09123456789', 'cano@gmail.com', 'Sugcad, Polangui, Albay', '', '', '2025-10-24 16:04:57'),
(15, 7, 2, 'Regular Home Cleaning', 4500.00, '2025-11-05', '17:34', NULL, 'nigga', '09755084276', 'nigga@gmail.com', 'Pilar, Sorsogon', '', '', '2025-11-22 06:35:07'),
(16, 16, 6, 'Deep Home Cleaning', 6000.00, '2025-11-17', '20:58', NULL, 'Jaiden Fermante', '09107132211', 'jaiden2@gmail.com', 'Legazpi City', 'puke', 'accepted', '2025-11-22 07:58:35'),
(17, 17, 1, 'Post-Construction Cleaning', 5000.00, '2025-11-25', '10:19', NULL, 'Andrei LLoyd', '09685286793', 'andreilloyd@gmail.com', 'Ligao', '', '', '2025-11-24 14:19:50'),
(18, 17, 4, 'Upholstery Cleaning', 3500.00, '2025-11-30', '01:13', NULL, 'Andrei LLoyd Sinfuego', '09685286793', 'andreilloyd@gmail.com', 'Ligao', '', 'accepted', '2025-11-27 16:13:16'),
(19, 18, 2, 'Regular Home Cleaning', 4500.00, '2025-12-01', '10:00', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', 'Beware of dogs', 'pending', '2025-11-30 17:12:19'),
(20, 18, 6, 'Deep Home Cleaning', 6000.00, '2025-12-01', '16:00', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', 'Beware of cats', 'pending', '2025-11-30 18:09:21'),
(21, 18, 4, 'Upholstery Cleaning', 3500.00, '2025-12-02', '10:00', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', 'Beware of crocodiles', 'pending', '2025-11-30 18:14:44'),
(22, 18, 1, 'Post-Construction Cleaning', 5000.00, '2025-12-02', '13:00', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', 'Beware of alligators', 'accepted', '2025-11-30 18:21:29'),
(23, 18, 1, 'Post-Construction Cleaning', 5000.00, '2025-12-03', '15:00', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', '', 'pending', '2025-11-30 18:39:58'),
(24, 18, 1, 'Post-Construction Cleaning', 5000.00, '2025-12-03', '15:00', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', '', 'pending', '2025-11-30 18:40:16'),
(25, 18, 3, 'Move-in/Move-out Cleaning', 3500.00, '2025-12-02', '16:30', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', 'Beware of snakes', 'pending', '2025-11-30 18:44:19'),
(26, 18, 3, 'Move-in/Move-out Cleaning', 3500.00, '2025-12-04', '09:00', NULL, 'User1', '09123456780', 'user1@gmail.com', 'Centro Occidental, Polangui Albay', 'Beware of cheaters', 'pending', '2025-11-30 19:13:55'),
(27, 19, 6, 'Deep Home Cleaning', 6000.00, '2025-12-03', '10:00', NULL, 'User2', '09123456780', 'user2@gmail.com', 'Centro Occidental, Polangui Albay', '', 'accepted', '2025-12-02 18:06:17'),
(28, 19, 3, 'Move-in/Move-out Cleaning', 3500.00, '2025-12-03', '18:00', NULL, 'User2', '09123456780', 'user2@gmail.com', 'Centro Occidental, Polangui Albay', 'Beware of zaijans', 'accepted', '2025-12-03 06:01:48'),
(29, 20, 10, 'Regular Home Cleaning', 4500.00, '2025-12-04', '12:00', NULL, 'Jaiden Nykluz M. Fermante', '09107132211', 'fermantejaiden@gmail.com', 'LIgao City', '', 'accepted', '2025-12-03 09:05:43'),
(30, 19, 7, 'Deep Home Cleaning', 6000.00, '2025-12-07', '08:30', NULL, 'User2', '09123456780', 'user2@gmail.com', 'Centro Occidental, Polangui Albay', '', 'accepted', '2025-12-06 21:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('qrcode') DEFAULT 'qrcode',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `user_id`, `amount`, `payment_method`, `payment_status`, `transaction_id`, `payment_date`) VALUES
(1, 1, 1, 0.00, '', 'paid', 'TXN1763787995', '2025-11-22 05:06:35'),
(2, 15, 1, 4500.00, 'qrcode', 'paid', 'TXN1763793309', '2025-11-22 06:35:09'),
(3, 16, 1, 6000.00, 'qrcode', 'paid', 'TXN1763798319', '2025-11-22 07:58:39'),
(4, 17, 1, 5000.00, 'qrcode', 'paid', 'TXN1763993998', '2025-11-24 14:19:58'),
(5, 18, 1, 3500.00, 'qrcode', 'paid', 'TXN1764260008', '2025-11-27 16:13:28'),
(6, 19, 1, 4500.00, 'qrcode', 'paid', 'TXN1764522743', '2025-11-30 17:12:23'),
(7, 20, 1, 6000.00, 'qrcode', 'paid', 'TXN1764526162', '2025-11-30 18:09:22'),
(8, 21, 1, 3500.00, 'qrcode', 'paid', 'TXN1764526485', '2025-11-30 18:14:45'),
(9, 22, 1, 5000.00, 'qrcode', 'paid', 'TXN1764526891', '2025-11-30 18:21:31'),
(10, 25, 18, 3500.00, 'qrcode', 'paid', 'TXN1764528260', '2025-11-30 18:44:20'),
(11, 26, 18, 3500.00, 'qrcode', 'paid', 'TXN1764530036', '2025-11-30 19:13:56'),
(12, 27, 19, 6000.00, 'qrcode', 'paid', 'TXN1764698779', '2025-12-02 18:06:19'),
(13, 28, 19, 3500.00, 'qrcode', 'paid', 'TXN1764741709', '2025-12-03 06:01:49'),
(14, 29, 20, 4500.00, 'qrcode', 'paid', 'TXN1764752826', '2025-12-03 09:07:06'),
(15, 30, 19, 6000.00, 'qrcode', 'paid', 'TXN1765057189', '2025-12-06 21:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `image`, `description`, `price`, `duration`, `category`, `status`, `date_created`, `last_updated`) VALUES
(7, 'Deep Home Cleaning', 'deep-home-cleaning.webp', 'Decluttering, Pre-Treatment, Dry Vacuuming, Wet Mopping, Intensive Scrubbing, Dry Mopping, Assessment, Organizing, Aromatizing', 6000.00, NULL, NULL, 'active', '2025-12-03 08:03:26', '2025-12-03 08:03:26'),
(8, 'Upholstery Cleaning', 'upholestry.webp', 'Decluttering, Pre-Treatment, Dry Vacuuming, Wet Mopping, Intensive Scrubbing, Dry Mopping, Assessment, Organizing, Aromatizing', 3500.00, NULL, NULL, 'active', '2025-12-03 08:04:30', '2025-12-03 08:04:30'),
(9, 'Move-in/Move-out Cleaning', 'moveinout.webp', 'Decluttering, Pre-Treatment, Dry Vacuuming, Wet Mopping, Intensive Scrubbing, Dry Mopping, Assessment, Organizing, Aromatizing', 3500.00, NULL, NULL, 'active', '2025-12-03 08:05:14', '2025-12-03 08:05:14'),
(10, 'Regular Home Cleaning', 'regular-cleaning.webp', 'Decluttering, Dry Vacuuming, Dry Wiping & Mopping, Moderate Scrubbing, Assessment, Aromatizing', 4500.00, NULL, NULL, 'active', '2025-12-03 08:06:00', '2025-12-03 08:06:00'),
(11, 'Post-Construction Cleaning', 'post-construction.webp', 'Dust & debris removal after renovations for a spotless finish.', 5000.00, NULL, NULL, 'active', '2025-12-03 08:06:33', '2025-12-03 08:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `testimonies`
--

CREATE TABLE `testimonies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('on the way','completed') DEFAULT 'on the way',
  `action_date` timestamp NULL DEFAULT current_timestamp(),
  `proof_image` varchar(255) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `booking_id`, `customer_name`, `service_name`, `date`, `time`, `address`, `phone`, `email`, `status`, `action_date`, `proof_image`, `completed_at`) VALUES
(2, 3, 'Otelo Nobleza', 'Upholstery Cleaning', '2025-10-20', '13:00', 'Sugcad, Polangui, Albay', '09123456789', 'otelo@gmail.com', 'completed', '2025-10-17 10:19:31', NULL, NULL),
(3, 4, 'neknek', '', '2025-10-09', '16:23', 'Sugcad, Polangui, Albay', '09123456789', 'neknek@gmail.com', 'completed', '2025-10-17 10:26:07', NULL, NULL),
(4, 10, 'Otelo Nobleza', 'Regular Home Cleaning', '2025-10-18', '06:31', 'Sugcad, Polangui, Albay', '09123456789', 'otelo@gmail.com', 'completed', '2025-10-17 10:29:11', NULL, NULL),
(5, 14, 'Symon Cristoffer Cano', 'Regular Home Cleaning', '2025-10-18', '01:05', 'Sugcad, Polangui, Albay', '09123456789', 'cano@gmail.com', 'completed', '2025-10-24 16:14:27', NULL, NULL),
(6, 12, 'Otelo Nobleza', 'Regular Home Cleaning', '2025-10-18', '23:06', 'Pilar, Sorsogon', '09123456789', 'otelo@gmail.com', 'completed', '2025-10-24 16:20:56', NULL, NULL),
(7, 11, 'Otelo Nobleza', 'Regular Home Cleaning', '2025-10-18', '23:06', 'Pilar, Sorsogon', '09123456789', 'otelo@gmail.com', 'on the way', '2025-10-24 16:22:05', NULL, NULL),
(8, 15, 'nigga', 'Regular Home Cleaning', '2025-11-05', '17:34', 'Pilar, Sorsogon', '09755084276', 'nigga@gmail.com', 'completed', '2025-11-22 06:35:59', NULL, NULL),
(9, 17, 'Andrei LLoyd', 'Post-Construction Cleaning', '2025-11-25', '10:19', 'Ligao', '09685286793', 'andreilloyd@gmail.com', 'completed', '2025-11-24 14:51:50', NULL, NULL),
(10, 16, 'Jaiden Fermante', 'Deep Home Cleaning', '2025-11-17', '20:58', 'Legazpi City', '09107132211', 'jaiden2@gmail.com', 'on the way', '2025-11-24 14:56:21', NULL, NULL),
(11, 27, 'User2', 'Deep Home Cleaning', '2025-12-03', '10:00', 'Centro Occidental, Polangui Albay', '09123456780', 'user2@gmail.com', 'on the way', '2025-12-03 06:04:00', NULL, NULL),
(12, 29, 'Jaiden Nykluz M. Fermante', 'Regular Home Cleaning', '2025-12-04', '12:00', 'LIgao City', '09107132211', 'fermantejaiden@gmail.com', 'on the way', '2025-12-03 09:10:12', NULL, NULL),
(13, 7, 'neknek', 'Post-Construction Cleaning', '2025-10-09', '10:19', 'Sugcad, Polangui, Albay', '09123456789', 'neknek@gmail.com', 'on the way', '2025-12-06 20:08:38', NULL, NULL),
(14, 28, 'User2', 'Move-in/Move-out Cleaning', '2025-12-03', '18:00', 'Centro Occidental, Polangui Albay', '09123456780', 'user2@gmail.com', 'completed', '2025-12-06 20:13:39', 'uploads/completion_proofs/completion_28_1765054947.jpg', '2025-12-06 21:02:27'),
(15, 18, 'Andrei LLoyd Sinfuego', 'Upholstery Cleaning', '2025-11-30', '01:13', 'Ligao', '09685286793', 'andreilloyd@gmail.com', 'on the way', '2025-12-06 20:15:07', NULL, NULL),
(16, 26, '', '', '0000-00-00', NULL, NULL, NULL, NULL, 'on the way', '2025-12-06 20:37:56', NULL, NULL),
(17, 26, '', '', '0000-00-00', NULL, NULL, NULL, NULL, 'on the way', '2025-12-06 20:37:59', NULL, NULL),
(18, 22, '', '', '0000-00-00', NULL, NULL, NULL, NULL, 'on the way', '2025-12-06 20:41:53', NULL, NULL),
(19, 30, '', '', '0000-00-00', NULL, NULL, NULL, NULL, 'completed', '2025-12-06 21:40:47', 'uploads/completion_proofs/completion_30_1765057343.jpg', '2025-12-06 21:42:23');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `contact_num` varchar(50) DEFAULT NULL,
  `role` enum('admin','customer','cleaner','') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `profile_pic`, `address`, `contact_num`, `role`, `date_created`, `last_updated`) VALUES
(1, 'otelo', 'otelo@gmail.com', '$2y$10$AvPI4xIX0eXa7i9pM/h0zOFq8JD2XgJPcu28MkEW/kv', '', 'Sugcad, Polangui, Albay', '09123456789', 'customer', '2025-10-05 03:09:14', '2025-10-05 03:09:14'),
(2, 'otelo1', 'otelo1@gmail.com', '$2y$10$wmvnpX9QGpUzQ40uIElzje6URYfpFoC12bHInNlDQ8e', '', 'Sugcad, Polangui, Albay', '09123456789', 'customer', '2025-10-05 07:30:40', '2025-10-05 07:30:40'),
(3, 'neknek', 'neknek@gmail.com', '$2y$10$ZioQhcG7SYZR91nuDOx.tu1dXJD2nJrHmgqxDJHD43P', '', 'Sugcad, Polangui, Albay', '09123456789', 'customer', '2025-10-05 12:06:40', '2025-10-05 12:06:40'),
(4, 'Jaiden Fermante', 'jaiden@gmail.com', '$2y$10$BwFjr3kEO.pfi/05zmXXlOCtUEQgzoJA1YbNFNPfDrC', '', 'Sugcad, Polangui, Albay', '09123456789', 'customer', '2025-10-06 06:29:07', '2025-10-06 06:29:07'),
(5, 'jon matthew mella', 'Mella2@gmail.com', '$2y$10$JVGvRFFpcbiotIUUMTRH/OAZsOnedE4jjMUi8JDuuxl', '', 'Sugcad, Polangui, Albay', '09123456789', 'customer', '2025-10-24 15:26:30', '2025-10-24 15:26:30'),
(6, 'Symon Cristoffer Cano', 'cano@gmail.com', '$2y$10$7zraWRxY2ztaFVwBG3G15eff.PhncYFp4G2A1JI1UWz', '', 'Sugcad, Polangui, Albay', '09123456789', 'customer', '2025-10-24 16:02:57', '2025-10-24 16:02:57'),
(7, 'nigga', 'nigga@gmail.com', '$2y$10$tv6SY4AuVuKYepghv7EcOuGC7qTg6kdv2r3SxMIPpJi', '', 'Pilar, Sorsogon', '09755084276', 'customer', '2025-11-22 05:25:47', '2025-11-22 05:25:47'),
(8, 'Khritine Botin', 'Botin@gmail.com', '$2y$10$1T.tmiFEy4U0i7qZJbbYxO7UneZWUukCDlUsoJYV7xk', '', 'polangui', '09123456789', 'customer', '2025-11-22 06:52:51', '2025-11-22 06:52:51'),
(9, 'Pula', 'pula@gmail.com', '$2y$10$0O/cei.QobwLLNGS3o1UBuAzXWZyKv56dzq1pdfQZ46', '', 'polangui', '09123456789', 'customer', '2025-11-22 07:03:41', '2025-11-22 07:03:41'),
(10, 'Bo10', 'Bo10@gmail.com', '$2y$10$SZFhqJ3hix60UXDZqqV9zeP3gHwCxZGgZAwyD92luRN', '', 'polangui', '09123456789', 'customer', '2025-11-22 07:09:41', '2025-11-22 07:09:41'),
(11, 'plats', 'plat@gmail.com', '$2y$10$ew.FtQY27pEJDI6lLeAqUODOtiuIowMOeiA6gxbsmbX', '', 'polangui', '09123456789', 'customer', '2025-11-22 07:15:13', '2025-11-22 07:15:13'),
(12, 'kyla', 'kyla@gmail.com', '$2y$10$llbwYhewOaMMeGfixl.bDuoEU8yWrpfi/pI30JhKIE2', '', 'polangui', '09123456789', 'customer', '2025-11-22 07:19:17', '2025-11-22 07:19:17'),
(13, 'kyla1', 'kyla1@gmail.com', '$2y$10$WQh4O.JHVf4mb8ODK87.seVKPcQWieK733xYviD/7ZE', '', 'polangui', '09123456789', 'customer', '2025-11-22 07:30:39', '2025-11-22 07:30:39'),
(14, 'Zai', 'zai@gmail.com', '$2y$10$05PCm3yTAGU2EtGlev6lQuwPBkxn/NZGvhyLyeiKlOR', '', 'polangui', '09755084276', 'customer', '2025-11-22 07:37:30', '2025-11-22 07:37:30'),
(15, '123', '123@gmail.com', '$2y$10$yNo5xwDa6LS7o33turyaVu9purS9AjXot55LaQ1bs06', '', 'polangui', '09755084276', 'customer', '2025-11-22 07:40:25', '2025-11-22 07:40:25'),
(16, 'Jaiden Fermante', 'jaiden2@gmail.com', '$2y$10$M96FUsKL.OCCdKT14Yeos.FKvQF2IkyuEAstusUBAZLx4BS0taIDG', '', 'Legazpi City', '09107132211', 'customer', '2025-11-22 07:49:48', '2025-11-22 07:49:48'),
(17, 'Andrei LLoyd Sinfuego', 'andreilloyd@gmail.com', '$2y$10$Nt/SUlJjGIHCH1PGPIRjtOPpmAYihbIvRpV7S1v5qPzW7oRaSTkkm', '', 'Ligao', '09685286793', 'customer', '2025-11-24 14:18:21', '2025-11-24 14:40:32'),
(18, '', '', '$2y$10$l8s3GWVScit5PA2.gRTO4emegTqeweaxvBTKjwgdR9k1.ZLS/MfX.', '', NULL, NULL, 'customer', '2025-11-30 15:47:08', '2025-12-06 09:25:48'),
(19, 'User2', 'user2@gmail.com', '$2y$10$12pOc247u9MTro6GMpF2a.rEZLH8JtWhukiKyNYhrRn0ZWkFsCGoi', '1765013656_592134419_1629160934737875_5107404351623353746_n.jpg', 'Centro Occidental, Polangui Albay', '09123456780', 'customer', '2025-12-02 18:05:26', '2025-12-06 09:34:16'),
(20, 'Jaiden Nykluz M. Fermante', 'fermantejaiden@gmail.com', '$2y$10$tzb9FnmgCPAVYPYQ60aYp.Yr.WMixHQSbXCkI69fdJxXy4hapRUM2', '', 'LIgao City', '09107132211', 'customer', '2025-12-03 09:03:12', '2025-12-03 09:03:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `testimonies`
--
ALTER TABLE `testimonies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `testimonies`
--
ALTER TABLE `testimonies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
