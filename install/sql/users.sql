-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 24 Juillet 2013 à 18:18
-- Version du serveur: 5.1.67-community
-- Version de PHP: 5.5.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données: `gameframework`
--

-- --------------------------------------------------------

--
-- Structure de la table `gf_users`
--
-- Création: Mer 24 Juillet 2013 à 16:18
--

DROP TABLE IF EXISTS `gf_users`;
CREATE TABLE IF NOT EXISTS `gf_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_username` varchar(30) NOT NULL,
  `user_hash` varchar(128) NOT NULL COMMENT 'Hash with Sha512',
  `user_salt` varchar(32) NOT NULL,
  `user_email` varchar(255) NOT NULL COMMENT '[64][@][190]',
  `user_datecreation` datetime NOT NULL,
  `user_validationcode` varchar(32) DEFAULT NULL COMMENT 'Validation code (md5)',
  `user_cookie_id` varchar(32) DEFAULT NULL COMMENT 'Cookie id (md5)',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `UU_Users_Username` (`user_username`),
  UNIQUE KEY `UU_Users_Email` (`user_email`),
  KEY `IX_Users_Cookie_Id` (`user_cookie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
COMMIT;
