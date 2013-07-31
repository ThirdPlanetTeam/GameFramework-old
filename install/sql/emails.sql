-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 31 Juillet 2013 à 19:05
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
-- Structure de la table `gf_emails`
--
-- Création: Mer 31 Juillet 2013 à 17:04
--

DROP TABLE IF EXISTS `gf_emails`;
CREATE TABLE IF NOT EXISTS `gf_emails` (
  `email_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email_hash` varchar(32) NOT NULL,
  `email_date_send` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email_to` text NOT NULL,
  `email_title` text NOT NULL,
  `email_message` text NOT NULL,
  `email_headers` text NOT NULL,
  PRIMARY KEY (`email_id`),
  KEY `IX_Emails_Hash` (`email_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
