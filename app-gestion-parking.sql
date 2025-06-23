-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 21 juin 2025 à 00:01
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `app-gestion-parking`
--
CREATE DATABASE IF NOT EXISTS `app-gestion-parking` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `app-gestion-parking`;

-- --------------------------------------------------------

--
-- Structure de la table `is_admin`
--

CREATE TABLE `is_admin` (
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `is_admin`
--

INSERT INTO `is_admin` (`user_id`, `status`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `owns_vehicle`
--

CREATE TABLE `owns_vehicle` (
  `id_owner` int(11) NOT NULL,
  `id_vehicle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `owns_vehicle`
--

INSERT INTO `owns_vehicle` (`id_owner`, `id_vehicle`) VALUES
(1, 1),
(1, 36),
(1, 64),
(2, 65),
(1, 66);

-- --------------------------------------------------------

--
-- Structure de la table `parking_spot`
--

CREATE TABLE `parking_spot` (
  `id` int(11) NOT NULL,
  `type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `parking_spot`
--

INSERT INTO `parking_spot` (`id`, `type`) VALUES
(1, 'Normale'),
(2, 'Normale'),
(3, 'Normale'),
(4, 'Normale'),
(5, 'Handicapée'),
(6, 'Handicapée'),
(7, 'Électrique'),
(8, 'Électrique'),
(9, 'Électrique'),
(10, 'Moto'),
(11, 'Moto');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `id_parking_spot` int(11) NOT NULL,
  `id_vehicle` int(11) NOT NULL,
  `start_day` text NOT NULL,
  `end_day` text NOT NULL,
  `start_hour` text NOT NULL,
  `end_hour` text NOT NULL,
  `is_paid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `id_parking_spot`, `id_vehicle`, `start_day`, `end_day`, `start_hour`, `end_hour`, `is_paid`) VALUES
(1, 5, 64, '2025-06-23', '2025-06-23', '18:00', '19:00', 1),
(2, 1, 64, '2025-06-23', '2025-06-23', '19:45', '20:45', 0),
(7, 2, 64, '2025-06-23', '2025-06-23', '23:05', '23:11', 1),
(8, 3, 1, '2025-06-23', '2025-06-23', '23:09', '23:43', 1),
(9, 1, 1, '2025-06-22', '2025-06-22', '01:26', '23:26', 1),
(10, 5, 66, '2025-06-21', '2025-06-22', '02:28', '00:28', 1),
(11, 1, 64, '2025-06-20', '2025-06-20', '09:12', '10:12', 1),
(12, 1, 36, '2025-06-19', '2025-06-19', '09:50', '14:50', 1),
(13, 2, 1, '2025-06-23', '2025-06-23', '17:51', '19:51', 1),
(14, 3, 64, '2025-06-23', '2025-06-23', '18:53', '20:53', 1),
(15, 4, 1, '2025-06-23', '2025-06-23', '10:19', '22:19', 1),
(16, 1, 66, '2025-06-15', '2025-06-15', '20:37', '23:41', 1),
(17, 1, 66, '2025-06-20', '2025-06-20', '20:48', '22:48', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `lastName` text NOT NULL,
  `firstName` text NOT NULL,
  `mail` text NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `lastName`, `firstName`, `mail`, `phoneNumber`, `password`) VALUES
(1, 'Marcel', 'Gabriel', 'gabriel.marcel@coda-student.school', '07 69 37 69 70', '$2y$10$NB8/0sBwqNiMKduADTXgb.Ilxq0RAt/mAwluq8u0H75Oj3NgqgZny'),
(2, 'Gréjon', 'Maxime', 'maxgrej@gmail.com', '06 06 06 06 06', '$2y$10$NB8/0sBwqNiMKduADTXgb.Ilxq0RAt/mAwluq8u0H75Oj3NgqgZny'),
(3, 'Dupuy', 'regis', 'regis.police@gmail.com', '07 07 07 07 07', '$2y$10$NB8/0sBwqNiMKduADTXgb.Ilxq0RAt/mAwluq8u0H75Oj3NgqgZny');

-- --------------------------------------------------------

--
-- Structure de la table `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int(11) NOT NULL,
  `license_plate` text NOT NULL,
  `brand` text NOT NULL,
  `model` text NOT NULL,
  `color` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vehicle`
--

INSERT INTO `vehicle` (`id`, `license_plate`, `brand`, `model`, `color`) VALUES
(1, 'AB-013-EV', 'Toyota', 'Yaris', 'Gris'),
(36, 'AB', 'Maserati', 'Imperium', 'Bleue'),
(64, 'AB-013-EF', 'Peugeot', '3008', 'Noire'),
(65, 'EB-014-RV', 'Renault', 'Espace', 'Rouge'),
(66, 'AZ-000-ZA', 'Lamborghini', 'Aventador', 'Vert');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `is_admin`
--
ALTER TABLE `is_admin`
  ADD KEY `id` (`user_id`);

--
-- Index pour la table `owns_vehicle`
--
ALTER TABLE `owns_vehicle`
  ADD KEY `id_owner` (`id_owner`),
  ADD KEY `owns_vehicle_ibfk_2` (`id_vehicle`);

--
-- Index pour la table `parking_spot`
--
ALTER TABLE `parking_spot`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_ibfk_1` (`id_parking_spot`),
  ADD KEY `id_vehicle` (`id_vehicle`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `is_admin`
--
ALTER TABLE `is_admin`
  ADD CONSTRAINT `is_admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `owns_vehicle`
--
ALTER TABLE `owns_vehicle`
  ADD CONSTRAINT `owns_vehicle_ibfk_1` FOREIGN KEY (`id_owner`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `owns_vehicle_ibfk_2` FOREIGN KEY (`id_vehicle`) REFERENCES `vehicle` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_parking_spot`) REFERENCES `parking_spot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_vehicle`) REFERENCES `vehicle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
