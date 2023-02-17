-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Feb 17, 2023 at 06:32 PM
-- Server version: 8.0.21
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vacation`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `email` varchar(120) NOT NULL,
  `salt` varchar(60) NOT NULL,
  `role` enum('employee','admin') NOT NULL,
  `createdDatetime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modifiedDatetime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `applicationId` int NOT NULL,
  `userId` int NOT NULL,
  `fromDate` date NOT NULL,
  `toDate` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','rejected','accepted') NOT NULL,
  `createdDatetime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modifiedDatetime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logAction`
--

CREATE TABLE `logAction` (
  `logActionId` int NOT NULL,
  `userId` int NOT NULL,
  `applicationId` int NOT NULL,
  `applicationStatus` enum('pending','rejected','accepted') NOT NULL,
  `createdDatetime` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `firstName`, `lastName`, `email`, `salt`, `role`, `createdDatetime`, `modifiedDatetime`) VALUES
(8, 'Margarit', 'Koka', 'margariti@hotmail.com', '$2y$09$1d71Fp/4RunQLXHQi4AxR.Hz5q36A1aS6EyJo6q1UxREwEjkqihKq', 'admin', '2023-02-17 17:22:32', '2023-02-17 17:22:32'),
(9, 'Ioannis', 'Joedopoulos', 'ioannis.joedopoulos@hotmail.com', '$2y$09$CxJ1QO/ZkjGxmDrnJPS31.7jAlmIVORK6096woJbvWD8vgTJnDdUe', 'employee', '2023-02-17 17:24:42', '2023-02-17 17:24:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`applicationId`),
  ADD KEY `User_On_Application_INDX` (`userId`);

--
-- Indexes for table `logAction`
--
ALTER TABLE `logAction`
  ADD PRIMARY KEY (`logActionId`),
  ADD KEY `User_On_LogAction_INDX` (`userId`),
  ADD KEY `Application_On_LogAction_INDX` (`applicationId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `Email_On_User_INDX` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
  MODIFY `applicationId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `logAction`
--
ALTER TABLE `logAction`
  MODIFY `logActionId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `User_On_Application_FK` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logAction`
--
ALTER TABLE `logAction`
  ADD CONSTRAINT `Application_On_LogAction_FK` FOREIGN KEY (`applicationId`) REFERENCES `application` (`applicationId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `User_On_LogAction_FK` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
