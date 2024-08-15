-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 21, 2024 at 08:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbpos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `id` int(11) NOT NULL,
  `cashierName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `generated_code` varchar(10) NOT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`id`, `cashierName`, `email`, `password`, `generated_code`, `shift_start`, `shift_end`, `date_added`) VALUES
(8, 'admin', 'adminemail', 'admin', 'admin', NULL, NULL, '2024-05-31 15:08:00'),
(11, 'Frian Gabriel', 'friangabrielmaravilla@gmail.com', '1111', 'FWQAZCHR', '09:00:00', '10:00:00', '2024-05-31 15:08:00'),
(12, 'aaaa', 'aaa@gmail.com', '111', 'GXYADEYL', '12:00:00', '13:30:00', '2024-05-31 15:08:00'),
(13, 'frian', 'frian@gmail.com', 'lol123', 'QFIQJNTW', '12:00:00', '15:00:00', '2024-05-31 15:08:00'),
(14, 'Frian Gabriel', 'friangabriel@gmail.com', '$2y$10$U46qbQhHQkNlnLxDuRxmuOmrVpwd1If75ZrpEnWCkAI9XCR5yDt6G', 'QZGNXDEG', '08:54:00', '21:54:00', '2024-06-04 23:54:10'),
(15, 'Frian', 'frian@gmail.com', '$2y$10$EqY9WQ2GBrtHLsy8yAZMFeMNmvWAHReJH/6FD5K9i1iWikxwe2Q72', 'EROYEZKB', '08:54:00', '21:54:00', '2024-06-04 23:54:52'),
(16, 'furiyan', 'friangabriel@gmail.com', '1111', 'PFMBMKPX', '15:19:00', '15:21:00', '2024-06-05 07:19:18'),
(17, 'frian1', 'friangabriel@gmail.com', '1111', 'UQVSFXAT', '12:53:00', '21:00:00', '2024-06-14 04:53:42'),
(18, 'frianstarting', '111@gmail.com', '1111', 'DRRNVGJM', '19:00:00', '12:57:00', '2024-06-14 04:57:41'),
(19, 'frianstarting2', 'friangabriel@gmail.com', '1111', 'IYLBWVEO', '07:00:00', '13:58:00', '2024-06-14 04:58:37'),
(20, 'frianending', 'friangabriel@gmail.com', '1111', 'SUSZDTPN', '13:25:00', '21:00:00', '2024-06-14 05:25:58');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Coffee'),
(2, 'Frappe'),
(3, 'Pasta');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `configuration` varchar(255) DEFAULT NULL,
  `addons` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `extra_shot` tinyint(1) DEFAULT 0,
  `syrup` tinyint(1) DEFAULT 0,
  `milk_breve` tinyint(1) DEFAULT 0,
  `whipped_cream` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `size` varchar(50) NOT NULL DEFAULT '',
  `extra_shot` tinyint(1) DEFAULT 0,
  `syrup` tinyint(1) DEFAULT 0,
  `milk_breve` tinyint(1) DEFAULT 0,
  `whipped_cream` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `description`, `photo`, `size`, `extra_shot`, `syrup`, `milk_breve`, `whipped_cream`) VALUES
(1, 'Latte', 75.00, 1, 'Latte', 'uploads/latte.png', '', 0, 0, 0, 0),
(2, 'Latte', 100.00, 1, 'hot', 'uploads/hot.png', 'Small', 0, 0, 0, 0),
(3, 'Salted Caramel', 120.00, 1, 'SC', 'uploads/latte.png', 'Medium', 0, 0, 0, 0),
(4, 'Double Choco Cream', 120.00, 2, 'DCC', 'uploads/frappe.png', 'Medium', 0, 0, 0, 0),
(5, 'White Choco Cream', 120.00, 2, 'WCC', 'uploads/whitechoco.png', '', 0, 0, 0, 0),
(6, 'Mango Overload', 120.00, 2, 'MO', 'uploads/mango_overload.png', '', 0, 0, 0, 0),
(7, 'Italian Pasta', 179.00, 3, 'ITP', 'uploads/italian.png', '', 0, 0, 0, 0),
(8, 'Creamy Carbonara', 179.00, 3, 'CCB', 'uploads/carbo.png', '', 0, 0, 0, 0),
(9, 'Iced Latte', 75.00, 1, 'IC-L', 'uploads/frappe.png', '', 0, 0, 0, 0),
(10, 'Pasta', 179.00, 3, 'PASTA', 'uploads/italian.png', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `stocked_date` date NOT NULL,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`id`, `name`, `original_price`, `supplier_name`, `quantity`, `stocked_date`, `expiry_date`) VALUES
(2, 'Milk', 20.00, 'Arla', 200, '2024-02-01', '2024-07-01'),
(3, 'Coffee Beans', 10.00, 'Arabica', 100, '2024-06-05', '2024-06-19'),
(8, 'Syrups', 100.00, 'Hersheys', 100, '2024-06-08', '2024-06-16'),
(9, 'Syrups', 100.00, 'Hersheys', 100, '2024-06-08', '2024-06-16'),
(10, 'Syrups', 100.00, 'Hersheys', 100, '2024-06-08', '2024-06-16'),
(11, 'Coffee Beans', 1.00, 'Arabica', 1, '2024-06-14', '2024-06-15'),
(12, 'Coffee Beans', 1.00, 'Arabica', 1, '2024-06-14', '2024-06-15'),
(13, 'Syrups', 1.00, '1', 1, '2024-06-15', '2024-06-14'),
(14, '1', 1.00, '1', 1, '2024-06-14', '2024-06-15'),
(15, '2', 2.00, '2', 2, '2024-06-14', '2024-06-15');

-- --------------------------------------------------------

--
-- Table structure for table `wastages`
--

CREATE TABLE `wastages` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `stocked_date` date NOT NULL,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wastages`
--

INSERT INTO `wastages` (`id`, `material_name`, `original_price`, `supplier_name`, `quantity`, `stocked_date`, `expiry_date`) VALUES
(1, 'Heavy Cream', 20.00, 'IDK', 100, '2024-06-05', '2024-06-12'),
(2, 'Heavy Cream', 20.00, 'IDK', 100, '2024-06-05', '2024-06-12'),
(3, 'Heavy Cream', 20.00, 'IDK', 100, '2024-06-05', '2024-06-12'),
(4, 'Heavy Cream', 20.00, 'IDK', 100, '2024-06-05', '2024-06-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wastages`
--
ALTER TABLE `wastages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `wastages`
--
ALTER TABLE `wastages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
