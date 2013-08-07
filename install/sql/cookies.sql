-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 07 Août 2013 à 19:01
-- Version du serveur: 5.1.67-community
-- Version de PHP: 5.5.0

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données: `gameframework`
--

-- --------------------------------------------------------

--
-- Structure de la table `gf_cookies`
--

CREATE TABLE IF NOT EXISTS `gf_cookies` (
  `cookie_userid` mediumint(8) unsigned NOT NULL,
  `cookie_hash` varchar(32) NOT NULL,
  `cookie_ip` varchar(15) NOT NULL,
  `cookie_location` varchar(200) NOT NULL,
  `cookie_browser` text NOT NULL,
  `cookie_datecreation` datetime NOT NULL,
  `cookie_lastlogin` datetime DEFAULT NULL,
  `cookie_dateending` datetime NOT NULL,
  PRIMARY KEY (`cookie_hash`),
  KEY `cookie_userid` (`cookie_userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `gf_cookies`
--
ALTER TABLE `gf_cookies`
  ADD CONSTRAINT `gf_cookies_ibfk_1` FOREIGN KEY (`cookie_userid`) REFERENCES `gf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
