-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2023 at 08:33 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
  `grantproposal` varchar(255) NOT NULL,
  `applicationdate` date NOT NULL,
  `applicationtimestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reviewerstatus` int(11) NOT NULL,
  `Modify` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `app`
--

INSERT INTO `app` (`id`, `publicationtypeid`, `grantproposal`, `applicationdate`, `applicationtimestamp`, `reviewerstatus`, `Modify`) VALUES
(3, 1, 'CV-BrandonJohnLimYungChen-18032023.pdf', '2023-04-11', '2023-04-11 16:08:16', 11, 0);

-- --------------------------------------------------------

--
-- Table structure for table `publicationtype`
--

CREATE TABLE `publicationtype` (
  `id` int(11) NOT NULL,
  `publicationtype` varchar(255) NOT NULL,
  `publicationfund` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `publicationtype`
--

INSERT INTO `publicationtype` (`id`, `publicationtype`, `publicationfund`) VALUES
(1, 'Indexed-International Conference', 20000000),
(2, 'International Conference', 10000000),
(3, 'National Conference', 5000000),
(4, 'Indexed-International Journal', 50000000),
(5, 'International Journal', 20000000),
(6, 'National Journal', 10000000);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `rolename` varchar(255) NOT NULL,
  `accessrights` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `rolename`, `accessrights`) VALUES
(1, 'Student', ''),
(2, 'FacultyMember', ''),
(3, 'HoSD', ''),
(4, 'SAA', ''),
(5, 'ViceRectorIV', ''),
(6, 'CRCS', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passkey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `faculty`, `email`, `passkey`) VALUES
(1, 'Daniel', 'Adijaya', 'FET', 'brandonjohnlyc@gmail.com', '3d0f3b9ddcacec30c4008c5e030e6c13a478cb4f'),
(2, 'Brandon', 'Chen', 'FET', 'cheneducation19@gmail.com', '6c074fa94c98638dfe3e3b74240573eb128b3d16'),
(3, 'Bambang', 'Subardjo', 'FET', 'brandonjohnlyc@gmail.com', '8d915418744c262d862505a7747465e62d918c29');

-- --------------------------------------------------------

--
-- Table structure for table `userapplication`
--

CREATE TABLE `userapplication` (
  `userid` int(11) NOT NULL,
  `applicationid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userapplication`
--

INSERT INTO `userapplication` (`userid`, `applicationid`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `userrole`
--

CREATE TABLE `userrole` (
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userrole`
--

INSERT INTO `userrole` (`userid`, `roleid`) VALUES
(1, 1),
(2, 1),
(3, 2);

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
-- Indexes for table `publicationtype`
--
ALTER TABLE `publicationtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userapplication`
--
ALTER TABLE `userapplication`
  ADD KEY `userid` (`userid`),
  ADD KEY `applicationid` (`applicationid`);

--
-- Indexes for table `userrole`
--
ALTER TABLE `userrole`
  ADD KEY `roleid` (`roleid`),
  ADD KEY `userid` (`userid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app`
--
ALTER TABLE `app`
  ADD CONSTRAINT `app_ibfk_1` FOREIGN KEY (`publicationtypeid`) REFERENCES `publicationtype` (`id`);

--
-- Constraints for table `userapplication`
--
ALTER TABLE `userapplication`
  ADD CONSTRAINT `userapplication_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `userapplication_ibfk_2` FOREIGN KEY (`applicationid`) REFERENCES `app` (`id`);

--
-- Constraints for table `userrole`
--
ALTER TABLE `userrole`
  ADD CONSTRAINT `userrole_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `userrole_ibfk_2` FOREIGN KEY (`roleid`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
