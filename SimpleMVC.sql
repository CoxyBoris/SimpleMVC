-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Client :  localhost:3306
-- Généré le :  Dim 27 Septembre 2015 à 15:21
-- Version du serveur :  5.5.38
-- Version de PHP :  5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `SimpleMVC`
--

-- --------------------------------------------------------

--
-- Structure de la table `T_EXAMPLE_EXP`
--

CREATE TABLE `T_EXAMPLE_EXP` (
`EXP_ID` int(11) NOT NULL,
  `EXP_FIRSTNAME` varchar(45) NOT NULL,
  `EXP_NAME` varchar(45) NOT NULL,
  `EXP_AGE` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `T_EXAMPLE_EXP`
--

INSERT INTO `T_EXAMPLE_EXP` (`EXP_ID`, `EXP_FIRSTNAME`, `EXP_NAME`, `EXP_AGE`) VALUES
(1, 'John', 'Doe', 32),
(2, 'Jane', 'Santiago', 26);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `T_EXAMPLE_EXP`
--
ALTER TABLE `T_EXAMPLE_EXP`
 ADD PRIMARY KEY (`EXP_ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `T_EXAMPLE_EXP`
--
ALTER TABLE `T_EXAMPLE_EXP`
MODIFY `EXP_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
