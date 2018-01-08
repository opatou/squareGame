-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 21 Octobre 2016 à 15:22
-- Version du serveur :  10.1.13-MariaDB
-- Version de PHP :  5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `opatou`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueursquaregame`
--

CREATE TABLE `joueursquaregame` (
  `nickname` varchar(100) NOT NULL,
  `nomSession` varchar(100) NOT NULL,
  `acm2` float DEFAULT NULL,
  `bcm2` float DEFAULT NULL,
  `ccm2` float DEFAULT NULL,
  `aRel` float DEFAULT NULL,
  `bRel` float DEFAULT NULL,
  `cRel` float DEFAULT NULL,
  `dateVote` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `joueursquaregame`
--

INSERT INTO `joueursquaregame` (`nickname`, `nomSession`, `acm2`, `bcm2`, `ccm2`, `aRel`, `bRel`, `cRel`, `dateVote`) VALUES
('gui', 'essai12', 1, 2, 3, 4, 5, 6, '2016-08-31 12:11:21'),
('guigui', 'essai12', 1, 2, 3, 4, 5, 6, NULL),
('nom', 'test', 1, 1, 1, 1, 1, 1, '2016-08-29 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `sessionsquaregame`
--

CREATE TABLE `sessionsquaregame` (
  `nomSession` varchar(100) NOT NULL,
  `dateOuverture` datetime NOT NULL,
  `dateFermeture` datetime DEFAULT NULL,
  `acm2` float DEFAULT NULL,
  `bcm2` float DEFAULT NULL,
  `ccm2` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `sessionsquaregame`
--

INSERT INTO `sessionsquaregame` (`nomSession`, `dateOuverture`, `dateFermeture`, `acm2`, `bcm2`, `ccm2`) VALUES
('essai', '0000-00-00 00:00:00', '2016-08-31 12:19:53', 0, 0, NULL),
('essai10', '2016-08-31 09:17:04', '2016-08-31 12:23:07', 0, 0, NULL),
('essai11', '2016-08-31 09:18:47', '2016-09-01 12:23:07', 0, 0, NULL),
('essai12', '2016-08-31 11:21:42', NULL, 0, 0, NULL),
('essai14', '2016-08-31 11:53:37', NULL, NULL, NULL, NULL),
('essai15', '2016-08-31 11:54:47', NULL, NULL, NULL, NULL),
('essai2', '0000-00-00 00:00:00', '2016-08-31 12:20:36', 0, 0, NULL),
('essai3', '0000-00-00 00:00:00', NULL, 0, 0, NULL),
('essai4', '0000-00-00 00:00:00', NULL, 0, 0, NULL),
('essai5', '0000-00-00 00:00:00', NULL, 0, 0, NULL),
('essai6', '0000-00-00 00:00:00', NULL, 0, 0, NULL),
('essai7', '0000-00-00 00:00:00', NULL, 0, 0, NULL),
('essai8', '0000-00-00 00:00:00', NULL, 0, 0, NULL),
('essai9', '0000-00-00 00:00:00', NULL, 0, 0, NULL),
('test', '2016-08-29 00:00:00', '2016-08-30 00:00:00', 0, 0, NULL),
('test2', '2016-08-29 00:00:00', '2016-08-30 00:00:00', 0, 0, NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `joueursquaregame`
--
ALTER TABLE `joueursquaregame`
  ADD PRIMARY KEY (`nickname`,`nomSession`),
  ADD KEY `fkNomSession` (`nomSession`);

--
-- Index pour la table `sessionsquaregame`
--
ALTER TABLE `sessionsquaregame`
  ADD UNIQUE KEY `nomSession` (`nomSession`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `joueursquaregame`
--
ALTER TABLE `joueursquaregame`
  ADD CONSTRAINT `fkNomSession` FOREIGN KEY (`nomSession`) REFERENCES `sessionsquaregame` (`nomSession`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
