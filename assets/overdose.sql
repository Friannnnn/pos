-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2024 at 09:47 AM
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
-- Database: `overdose`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `generated_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`id`, `first_name`, `last_name`, `email`, `password`, `generated_code`) VALUES
(1, 'sadas', 'sdaa', 'sadsa@gmail.com', 'sadas', 'F7AI01B0'),
(2, 'sadas', 'sdaa', 'sadsa@gmail.com', 'dsadsadsa', 'ZIX0Y9LR'),
(3, 'Frian', 'Maravilla', 'frian.maravilla@gmail.com', 'frian', '5DRLUVZU'),
(4, 'Frian Gabriel', 'Maravilla', 'frian_maravilla@gmail.com', 'lol123', 'H6950XGC'),
(5, 'lol123', 'sadasdsa', 'asdasd@gmail.com', '123', 'E04EFJRZ'),
(6, 'afasdf', 'fghjk', 'dfghdf@gmail.com', 'klj;jkl', 'Z0ES3MFR'),
(7, 'ghhj,hj', 'hjkl hj', 'hjklhjk@gmail.com', 'fgnffgn', 'R54YT0RG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
