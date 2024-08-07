-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2024 at 07:01 AM
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
-- Database: `studentgrant`
--

-- --------------------------------------------------------

--
-- Table structure for table `app`
--

CREATE TABLE `app` (
  `id` int(11) NOT NULL,
  `publicationtypeid` int(11) NOT NULL,
  `research_title` varchar(255) NOT NULL,
  `grantproposal` varchar(255) NOT NULL,
  `applicationdate` date NOT NULL,
  `applicationtimestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reviewerstatus` int(11) NOT NULL,
  `reqmodify` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `app`
--

INSERT INTO `app` (`id`, `publicationtypeid`, `research_title`, `grantproposal`, `applicationdate`, `applicationtimestamp`, `reviewerstatus`, `reqmodify`) VALUES
(9, 1, 'Sample Journal', 'sample-journal-1.pdf', '2024-07-27', '2024-07-27 16:14:00', 0, 0),
(10, 4, 'Sample Journal 2', 'sample-journal-2.pdf', '2024-08-01', '2024-08-01 03:39:16', 0, 0),
(11, 4, 'Sample Journal 2', 'sample-journal-2.pdf', '2024-08-01', '2024-08-01 03:41:15', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app`
--
ALTER TABLE `app`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publicationtypeid` (`publicationtypeid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app`
--
ALTER TABLE `app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app`
--
ALTER TABLE `app`
  ADD CONSTRAINT `app_ibfk_1` FOREIGN KEY (`publicationtypeid`) REFERENCES `publicationtype` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
