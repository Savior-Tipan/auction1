-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 02:14 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jma`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('super_admin','manager') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(2, 'bb', 'bb@gmail.com', '$2y$10$3xx77wlKywY.VpswF7KmOOKJBPMqFZozlU5stV3jYUs/gqBon6tWO', 'super_admin', '2024-09-27 13:15:23'),
(3, 'aa', 'aa@mail.com', '$2y$10$8eIrtk09o9gotNSkwHT9qOKRoSvxSsx9T2w7wpdkTPhYkAVBY0vj.', 'manager', '2024-09-27 13:42:46'),
(4, 'ss', 'savior@gmail.com', '$2y$10$GygzdzE/ZqGImkXGiEFubevqpsuwWvhyoD2Fd0gwrczb6dPjLKJby', 'super_admin', '2024-09-27 13:56:26'),
(5, 'm', 'mryan1669@gmail.com', '$2y$10$m3FdS60Z7x3bhXpzRgUNseXbpvYTREBJqs9H/QR5P5z6WJIEQLA9i', 'super_admin', '2024-09-27 14:30:20'),
(6, 'f', 'f@gmail.com', '$2y$10$FctpM9K4ELMApXC5m6QsD.gjpRCNH8RBHtKvY1KeRk887dT43adcG', 'super_admin', '2024-09-27 14:37:57'),
(7, 'll', 'll@g.batstate-u.edu.ph', '$2y$10$EQiZ9rsAnu1XV4diVu5ycen90bmdX5Lo.9seD/fM7Wb61EqLdSxNK', 'super_admin', '2024-09-27 14:39:50'),
(8, '4', '334@gmail.com', '$2y$10$MLxG6pdwYUswRHfCYKwWA./iDQVdrcPyaBb20a6IAjQT5x7KDKAfi', 'super_admin', '2024-09-27 14:40:51'),
(9, '00', 'ryanmanalo172mm@gmail.com', '$2y$10$If7A63SCWN667TJndo5Y6uwT08vrD4QQHg/DMcDIKGBs3yui27mXe', 'super_admin', '2024-09-27 14:41:51'),
(10, 'mmm', 'rjtm15703mm@gmail.com', '$2y$10$IkfKZaK8cHwReLNNYehycuaaptfilPSz8kKFr3Y6gJxOERGmIt84y', 'manager', '2024-09-27 14:42:01');

-- --------------------------------------------------------

--
-- Table structure for table `auctionhistory`
--

CREATE TABLE `auctionhistory` (
  `history_id` int(11) NOT NULL,
  `truck_id` int(11) DEFAULT NULL,
  `event_type` enum('bid','status_change') NOT NULL,
  `event_details` text DEFAULT NULL,
  `event_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bid_id` int(11) NOT NULL,
  `truck_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `bid_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `created_at`) VALUES
(1, 'Mini Truck', '2024-09-29 15:17:03'),
(2, 'Light Duty Trucks', '2024-09-29 15:17:03'),
(3, 'Used Heavy Duty Trucks', '2024-09-29 15:17:03'),
(4, 'Freezer Trucks', '2024-09-29 15:17:03'),
(5, 'Special Purpose Trucks', '2024-09-29 15:17:03'),
(6, 'Used Garbage Trucks', '2024-09-29 15:17:03'),
(7, 'Fire Trucks', '2024-09-29 15:17:03'),
(8, 'Cargo Trucks', '2024-09-29 15:17:03'),
(9, 'Container Trucks', '2024-09-29 15:17:03'),
(10, 'Armroll Trucks', '2024-09-29 15:17:03'),
(11, 'Used Tipper Trucks', '2024-09-29 15:17:03'),
(12, 'Trailer Trucks', '2024-09-29 15:17:03'),
(13, 'Wingbody Trucks', '2024-09-29 15:17:03'),
(14, 'Box Body Trucks', '2024-09-29 15:17:03'),
(15, 'Trailer Head', '2024-09-29 15:17:03'),
(16, 'Flat Trucks', '2024-09-29 15:17:03'),
(17, 'Tankers', '2024-09-29 15:17:03'),
(18, 'Used Trucks', '2024-09-29 15:17:03'),
(19, 'Dump Truck', '2024-10-01 13:59:57');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `truck_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `final_bid` decimal(10,2) NOT NULL,
  `transaction_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('cash','bank_transfer','financing') NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `truck_id`, `buyer_id`, `final_bid`, `transaction_time`, `payment_method`, `payment_status`) VALUES
(1, 1, 1, 50000.00, '2024-09-20 21:53:54', 'cash', 'completed'),
(2, 2, 2, 62000.00, '2024-09-20 21:53:54', 'bank_transfer', 'completed'),
(3, 3, 3, 55000.00, '2024-09-20 21:53:54', 'financing', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `truckcategorymapping`
--

CREATE TABLE `truckcategorymapping` (
  `truck_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `truckimages`
--

CREATE TABLE `truckimages` (
  `image_id` int(11) NOT NULL,
  `truck_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `truckimages`
--

INSERT INTO `truckimages` (`image_id`, `truck_id`, `image_path`, `created_at`) VALUES
(1, 1, 'uploads/images/agriauction.png', '2024-10-01 05:03:36'),
(2, 1, 'uploads/images/agriauction logo.png', '2024-10-01 05:03:36'),
(3, 1, 'uploads/images/Screenshot 2024-08-05 201446.png', '2024-10-01 05:03:36'),
(4, 2, 'uploads/images/agriauction.png', '2024-10-01 05:04:01'),
(5, 3, 'uploads/images/Week 3 (Techno).png', '2024-10-01 07:00:21'),
(6, 4, 'uploads/images/Week 3 (Techno).png', '2024-10-01 12:22:19'),
(7, 5, 'uploads/images/agriauction.png', '2024-10-01 12:29:25'),
(8, 5, 'uploads/images/agriauction logo.png', '2024-10-01 12:29:25'),
(9, 5, 'uploads/images/Screenshot 2024-08-05 201446.png', '2024-10-01 12:29:25'),
(10, 6, 'uploads/images/logo.png', '2024-10-01 12:43:30'),
(11, 7, 'uploads/images/Week 3 (Techno).png', '2024-10-01 13:58:41'),
(12, 7, 'uploads/images/Green and Yellow Leaf Icon Agriculture Logo.png', '2024-10-01 13:58:41'),
(13, 7, 'uploads/images/image.png', '2024-10-01 13:58:41'),
(14, 7, 'uploads/images/Untitled design.png', '2024-10-01 13:58:41'),
(15, 7, 'uploads/images/jmalogo-removebg-preview.png', '2024-10-01 13:58:41'),
(16, 10, 'uploads/images/agriauction.png', '2024-10-03 01:03:16'),
(17, 10, 'uploads/images/Screenshot 2024-08-05 201446.png', '2024-10-03 01:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `truckmakers`
--

CREATE TABLE `truckmakers` (
  `maker_id` int(11) NOT NULL,
  `maker_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `truckmakers`
--

INSERT INTO `truckmakers` (`maker_id`, `maker_name`, `created_at`) VALUES
(1, 'Hino', '2024-10-03 00:56:41'),
(2, 'Isuzu', '2024-10-03 00:56:41'),
(3, 'Mitsubishi', '2024-10-03 00:56:41'),
(4, 'Fuso', '2024-10-03 00:56:41');

-- --------------------------------------------------------

--
-- Table structure for table `trucks`
--

CREATE TABLE `trucks` (
  `truck_id` int(11) NOT NULL,
  `truck_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `model_year` int(11) NOT NULL,
  `starting_bid` decimal(10,2) NOT NULL,
  `current_bid` decimal(10,2) DEFAULT 0.00,
  `auction_start` datetime NOT NULL,
  `auction_end` datetime NOT NULL,
  `status` enum('active','sold','canceled') DEFAULT 'active',
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `maker_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trucks`
--

INSERT INTO `trucks` (`truck_id`, `truck_name`, `description`, `model_year`, `starting_bid`, `current_bid`, `auction_start`, `auction_end`, `status`, `category_id`, `created_at`, `maker_id`) VALUES
(1, 'dump truck', 'dsadas', 2321, 321312.00, 500000.00, '2024-10-01 13:03:00', '2024-10-02 13:03:00', 'active', 12, '2024-10-01 05:03:36', NULL),
(2, 'dump truck', 'fgfsdfsd', 432432, 432432.00, 0.00, '2024-10-01 13:03:00', '2024-10-02 13:03:00', 'active', 14, '2024-10-01 05:04:01', NULL),
(3, 'dd', 'dd', 33, 33.00, 0.00, '2024-10-01 14:59:00', '2024-10-02 14:59:00', 'active', 8, '2024-10-01 07:00:21', NULL),
(4, 'vcxvxc', 'xcvxcvxc', 342, 432.00, 0.00, '2024-10-01 20:22:00', '2024-10-02 20:22:00', 'active', 16, '2024-10-01 12:22:19', NULL),
(5, 'ff', 'ff', 312, 523.00, 0.00, '2024-10-01 20:29:00', '2024-10-02 20:29:00', 'active', 14, '2024-10-01 12:29:25', NULL),
(6, 'dsads', 'dasdas', 3123, 2321312.00, 0.00, '2024-10-02 20:43:00', '2024-09-30 20:43:00', 'active', 14, '2024-10-01 12:43:30', NULL),
(7, 'dump truck', 'dsdsad', 2015, 100000.00, 0.00, '2024-10-01 21:58:00', '2024-10-02 21:58:00', 'active', 14, '2024-10-01 13:58:41', NULL),
(8, 'dasdas', '321312', 321312, 312312.00, 0.00, '2024-10-03 09:02:00', '2024-10-04 09:02:00', 'active', 16, '2024-10-03 01:02:19', 2),
(9, 'dasdas', '321', 321, 321.00, 0.00, '2024-10-03 09:02:00', '2024-10-04 09:02:00', 'active', 15, '2024-10-03 01:02:50', 1),
(10, 'sadas', 'das', 2131, 21312.00, 50.00, '2024-10-03 09:02:00', '2024-10-04 09:03:00', 'active', 17, '2024-10-03 01:03:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `truckvideos`
--

CREATE TABLE `truckvideos` (
  `video_id` int(11) NOT NULL,
  `truck_id` int(11) NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `truckvideos`
--

INSERT INTO `truckvideos` (`video_id`, `truck_id`, `video_path`, `created_at`) VALUES
(1, 1, 'uploads/videos/2023-11-30 12-46-18.mp4', '2024-10-01 05:03:36'),
(2, 1, 'uploads/videos/2023-11-30 12-58-36.mp4', '2024-10-01 05:03:36'),
(3, 2, 'uploads/videos/2023-10-24 17-21-10.mp4', '2024-10-01 05:04:01'),
(4, 3, 'uploads/videos/Untitled design.mp4', '2024-10-01 07:00:21'),
(5, 4, 'uploads/videos/2023-10-24 17-23-32.mp4', '2024-10-01 12:22:19'),
(6, 5, 'uploads/videos/2023-11-30 12-46-18.mp4', '2024-10-01 12:29:25'),
(7, 5, 'uploads/videos/2023-11-30 12-58-36.mp4', '2024-10-01 12:29:25'),
(8, 5, 'uploads/videos/2023-10-24 17-23-32.mp4', '2024-10-01 12:29:25'),
(9, 6, 'uploads/videos/2023-11-30 13-02-58.mp4', '2024-10-01 12:43:30'),
(10, 7, 'uploads/videos/2023-11-30 13-04-04.mp4', '2024-10-01 13:58:41'),
(11, 7, 'uploads/videos/2023-11-30 13-02-58.mp4', '2024-10-01 13:58:41'),
(12, 7, 'uploads/videos/2023-11-17 20-03-38.mp4', '2024-10-01 13:58:41'),
(13, 7, 'uploads/videos/2023-11-30 12-46-18.mp4', '2024-10-01 13:58:41'),
(14, 7, 'uploads/videos/2023-11-30 12-58-36.mp4', '2024-10-01 13:58:41'),
(15, 7, 'uploads/videos/2023-10-24 17-21-10.mp4', '2024-10-01 13:58:41'),
(16, 7, 'uploads/videos/2023-11-30 13-03-15.mp4', '2024-10-01 13:58:41'),
(17, 10, 'uploads/videos/2023-11-30 12-58-36.mp4', '2024-10-03 01:03:16'),
(18, 10, 'uploads/videos/2023-10-24 17-23-32.mp4', '2024-10-03 01:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `userreviews`
--

CREATE TABLE `userreviews` (
  `review_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text DEFAULT NULL,
  `review_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `images` longtext DEFAULT NULL,
  `videos` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userreviews`
--

INSERT INTO `userreviews` (`review_id`, `transaction_id`, `username`, `rating`, `review_text`, `review_time`, `images`, `videos`) VALUES
(1, 2, '1', 5, 'all is good ', '2024-09-30 13:53:06', '[\"66faad42b8a1b_agriauction.png\",\"66faad42b91f1_agriauctionlogo.png\",\"66faad42b9a85_Screenshot2024-08-05201446.png\"]', '[\"66faad42ba220_2023-11-3012-58-36.mp4\"]'),
(2, 1, 'c', 5, 'fdsfdsfsd', '2024-10-02 10:25:01', '[\"66fd1f7db984c_agriauction.png\",\"66fd1f7dba036_agriauctionlogo.png\"]', '[\"66fd1f7dba5ab_2023-11-3013-04-04.mp4\",\"66fd1f7dbaa65_2023-11-3013-02-58.mp4\"]'),
(3, 1, 'c', 5, 'sss', '2024-10-02 16:10:22', '[]', '[]'),
(4, 1, 'c', 5, 'sss', '2024-10-02 16:10:26', '[]', '[]'),
(5, 1, 'c', 5, 'sss', '2024-10-02 16:10:29', '[]', '[]'),
(6, 1, 'c', 5, 'sss', '2024-10-02 16:10:32', '[]', '[]'),
(7, 1, 'c', 5, 'd', '2024-10-02 16:11:36', '[]', '[]'),
(8, 1, 'c', 5, 'a', '2024-10-02 16:13:52', '[]', '[]'),
(9, 1, 'c', 5, 'a', '2024-10-02 16:13:58', '[]', '[]'),
(10, 1, 'c', 5, 's', '2024-10-02 16:14:11', '[]', '[]'),
(11, 1, 'c', 5, 's', '2024-10-02 16:14:35', '[]', '[]'),
(12, 1, 'c', 5, 'cccc', '2024-10-02 16:14:45', '[]', '[]'),
(13, 1, 'c', 5, 'd', '2024-10-02 16:16:58', '[]', '[]'),
(14, 1, 'c', 5, 'gg', '2024-10-02 16:17:27', '[]', '[]'),
(15, 1, 'c', 5, 'gg', '2024-10-02 16:17:31', '[]', '[]'),
(16, 1, 'c', 5, 'gg', '2024-10-02 16:17:48', '[]', '[]'),
(17, 1, 'c', 5, 'a', '2024-10-02 16:19:09', '[]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `business_permit` varchar(255) DEFAULT NULL,
  `govt_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `first_name`, `middle_name`, `last_name`, `contact`, `age`, `address`, `business_permit`, `govt_id`, `created_at`, `email_verified`, `verification_token`, `reset_token`, `token_expiry`) VALUES
(1, 'c', 'rjtm15703@gmail.com', '$2y$10$FJVvVUnlvpXLL84f1Sv5hOFzprUSi5tae3cxKA5QYs4alSWVfW1fW', 'c', 'c', 'c', 'c', '0', 'c', 'uploads/Week 3 (Techno).png', 'business_permit', '2024-09-19 16:22:17', 1, NULL, NULL, NULL),
(2, '1', 'mryan1669@gmail.com', '$2y$10$P6bdr4D7cZKIy2lHCRYAyu/6nIAJocfKcnRKWu./tLfW6ut.2wppW', '1', '1', '1', '1', '1', '1', 'uploads/Green and Yellow Leaf Icon Agriculture Logo.png', 'business_permit', '2024-09-20 04:08:51', 1, NULL, NULL, '0000-00-00 00:00:00'),
(3, 'y', 'y@gmail.com', '$2y$10$Ii0I5SNAXQUrD2Li5j0CZu5/dbHHhzl6nQGrZlvWS5yc2uga3TvO2', 'y', 'y', 'y', 'y', '0', 'y', 'uploads/460410173_511831891556364_2203269310027697891_n.jpg', 'dti_permit', '2024-09-26 03:53:22', 1, NULL, NULL, '0000-00-00 00:00:00'),
(9, 'g', 'g@mail.com', '$2y$10$UdguvjW.e8IY.ueQgdQG6enw0LcEIBz0.0ligY.YD2dVVO/c40hzm', 'g', 'g', 'g', 'g', '0', 'g', 'uploads/460410173_511831891556364_2203269310027697891_n.jpg', 'business_permit', '2024-09-26 04:36:32', 1, NULL, NULL, '0000-00-00 00:00:00'),
(17, 'l', 'l@gmail.com', '$2y$10$bFk0qCvM3gWbgPMeZMcLheUD4Bh7NHrVC.mi15tB8W0/XCk0k7lGu', 'l', 'l', 'l', 'l', '0', 'l', NULL, 'dti_permit', '2024-09-27 12:07:06', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `watchlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `truck_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watchlist`
--

INSERT INTO `watchlist` (`watchlist_id`, `user_id`, `truck_id`, `created_at`) VALUES
(1, 17, 1, '2024-10-04 12:12:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `auctionhistory`
--
ALTER TABLE `auctionhistory`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `truck_id` (`truck_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `truck_id` (`truck_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `truck_id` (`truck_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `truckcategorymapping`
--
ALTER TABLE `truckcategorymapping`
  ADD PRIMARY KEY (`truck_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `truckimages`
--
ALTER TABLE `truckimages`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `truck_id` (`truck_id`);

--
-- Indexes for table `truckmakers`
--
ALTER TABLE `truckmakers`
  ADD PRIMARY KEY (`maker_id`);

--
-- Indexes for table `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`truck_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `truckvideos`
--
ALTER TABLE `truckvideos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `truck_id` (`truck_id`);

--
-- Indexes for table `userreviews`
--
ALTER TABLE `userreviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`watchlist_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `truck_id` (`truck_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `auctionhistory`
--
ALTER TABLE `auctionhistory`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `truckimages`
--
ALTER TABLE `truckimages`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `truckmakers`
--
ALTER TABLE `truckmakers`
  MODIFY `maker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `truck_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `truckvideos`
--
ALTER TABLE `truckvideos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `userreviews`
--
ALTER TABLE `userreviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `watchlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctionhistory`
--
ALTER TABLE `auctionhistory`
  ADD CONSTRAINT `auctionhistory_ibfk_1` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`truck_id`) ON DELETE CASCADE;

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`truck_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`truck_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `truckcategorymapping`
--
ALTER TABLE `truckcategorymapping`
  ADD CONSTRAINT `truckcategorymapping_ibfk_1` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`truck_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `truckcategorymapping_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `truckcategories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `truckimages`
--
ALTER TABLE `truckimages`
  ADD CONSTRAINT `truckimages_ibfk_1` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`truck_id`) ON DELETE CASCADE;

--
-- Constraints for table `trucks`
--
ALTER TABLE `trucks`
  ADD CONSTRAINT `trucks_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `truckvideos`
--
ALTER TABLE `truckvideos`
  ADD CONSTRAINT `truckvideos_ibfk_1` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`truck_id`) ON DELETE CASCADE;

--
-- Constraints for table `userreviews`
--
ALTER TABLE `userreviews`
  ADD CONSTRAINT `userreviews_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`truck_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
