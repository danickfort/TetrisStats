-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 28 Juin 2013 à 09:40
-- Version du serveur: 5.1.66
-- Version de PHP: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `tetrisstats`
--

-- --------------------------------------------------------

--
-- Structure de la table `ts_friends`
--

CREATE TABLE IF NOT EXISTS `ts_friends` (
  `friend` int(11) DEFAULT NULL,
  `befriended` int(11) DEFAULT NULL,
  KEY `friend_foreign` (`friend`),
  KEY `befriended_foreign` (`befriended`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ts_times`
--

CREATE TABLE IF NOT EXISTS `ts_times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `time` varchar(10) NOT NULL,
  `playedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
-- Structure de la table `ts_users`
--

CREATE TABLE IF NOT EXISTS `ts_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `avatarPath` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Déclencheurs `ts_users`
--
DROP TRIGGER IF EXISTS `Init_User_Stats_After_Reg`;
DELIMITER //
CREATE TRIGGER `Init_User_Stats_After_Reg` AFTER INSERT ON `ts_users`
 FOR EACH ROW BEGIN
INSERT INTO ts_users_stats values(new.id,0,0,0,0,0);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `ts_users_stats`
--

CREATE TABLE IF NOT EXISTS `ts_users_stats` (
  `user` int(11) NOT NULL,
  `games` int(11) NOT NULL DEFAULT '0',
  `tetrises` int(11) NOT NULL,
  `doubles` int(11) NOT NULL,
  `triples` int(11) NOT NULL,
  `lines` int(11) NOT NULL,
  PRIMARY KEY (`user`),
  UNIQUE KEY `user_2` (`user`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `ts_friends`
--
ALTER TABLE `ts_friends`
  ADD CONSTRAINT `befriended_foreign` FOREIGN KEY (`befriended`) REFERENCES `ts_users` (`id`),
  ADD CONSTRAINT `friend_foreign` FOREIGN KEY (`friend`) REFERENCES `ts_users` (`id`);

--
-- Contraintes pour la table `ts_users_stats`
--
ALTER TABLE `ts_users_stats`
  ADD CONSTRAINT `userId_foeign` FOREIGN KEY (`user`) REFERENCES `ts_users` (`id`);
