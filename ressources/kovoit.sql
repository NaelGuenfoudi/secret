-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 07 mars 2024 à 08:12
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `kovoit`
--

-- --------------------------------------------------------

--
-- Structure de la table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `Id_Booking_P` int NOT NULL,
  `Id_User_F` int NOT NULL,
  `Id_Ride_F` int NOT NULL,
  `Statut` tinyint(1) NOT NULL,
  `Booking_Date` timestamp NOT NULL,
  `Seat_Number` int NOT NULL,
  KEY `Id_User_F` (`Id_User_F`),
  KEY `Id_Ride_F` (`Id_Ride_F`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `city`
--

DROP TABLE IF EXISTS `city`;
CREATE TABLE IF NOT EXISTS `city` (
  `Id_City_P` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Zip_Code` int NOT NULL,
  `Departement` int NOT NULL,
  PRIMARY KEY (`Id_City_P`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ride`
--

DROP TABLE IF EXISTS `ride`;
CREATE TABLE IF NOT EXISTS `ride` (
  `Id_Ride_P` int NOT NULL AUTO_INCREMENT,
  `Id_Vehicle_F` int NOT NULL,
  `Id_Start_City_F` int NOT NULL,
  `Id_End_City_F` int NOT NULL,
  `Date_Start` timestamp NOT NULL,
  `Date_End` timestamp NOT NULL,
  `Seat_Number` int NOT NULL,
  PRIMARY KEY (`Id_Ride_P`),
  UNIQUE KEY `Id_Vehicle_F` (`Id_Vehicle_F`),
  UNIQUE KEY `Id_Start_City_F` (`Id_Start_City_F`),
  UNIQUE KEY `Id_End_City_F` (`Id_End_City_F`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `Id_User_P` int NOT NULL AUTO_INCREMENT,
  `First_Name` varchar(64) NOT NULL,
  `Last_Name` varchar(64) NOT NULL,
  `Mail` varchar(128) NOT NULL,
  `Phone` int NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Account_Statut` tinyint(1) NOT NULL,
  `Genre` varchar(64) NOT NULL,
  PRIMARY KEY (`Id_User_P`),
  UNIQUE KEY `Mail` (`Mail`),
  UNIQUE KEY `Phone` (`Phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vehicle`
--

DROP TABLE IF EXISTS `vehicle`;
CREATE TABLE IF NOT EXISTS `vehicle` (
  `Id_Vehicle_P` int NOT NULL AUTO_INCREMENT,
  `Id_User_F` int NOT NULL,
  `Name` int NOT NULL,
  `Seat_Number` int NOT NULL,
  `Color` int NOT NULL,
  PRIMARY KEY (`Id_Vehicle_P`),
  UNIQUE KEY `Id_User` (`Id_User_F`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`Id_User_F`) REFERENCES `user` (`Id_User_P`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`Id_Ride_F`) REFERENCES `ride` (`Id_Ride_P`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ride`
--
ALTER TABLE `ride`
  ADD CONSTRAINT `ride_ibfk_1` FOREIGN KEY (`Id_Start_City_F`) REFERENCES `city` (`Id_City_P`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ride_ibfk_2` FOREIGN KEY (`Id_End_City_F`) REFERENCES `city` (`Id_City_P`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ride_ibfk_3` FOREIGN KEY (`Id_Vehicle_F`) REFERENCES `vehicle` (`Id_Vehicle_P`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`Id_User_F`) REFERENCES `user` (`Id_User_P`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
