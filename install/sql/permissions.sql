-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 05 Août 2013 à 15:40
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
-- Structure de la table `gf_groups`
--

DROP TABLE IF EXISTS `gf_groups`;
CREATE TABLE IF NOT EXISTS `gf_groups` (
  `group_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `group_label` varchar(30) NOT NULL,
  `group_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'This group is an admin group',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `gf_groups_perms`
--

DROP TABLE IF EXISTS `gf_groups_perms`;
CREATE TABLE IF NOT EXISTS `gf_groups_perms` (
  `gp_groups` smallint(5) unsigned NOT NULL,
  `gp_perms` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`gp_groups`,`gp_perms`),
  KEY `gp_perms` (`gp_perms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gf_groups_users`
--

DROP TABLE IF EXISTS `gf_groups_users`;
CREATE TABLE IF NOT EXISTS `gf_groups_users` (
  `gu_groups` smallint(5) unsigned NOT NULL,
  `gu_users` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`gu_groups`,`gu_users`),
  KEY `gu_users` (`gu_users`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gf_perms`
--

DROP TABLE IF EXISTS `gf_perms`;
CREATE TABLE IF NOT EXISTS `gf_perms` (
  `perm_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `perm_label` varchar(30) NOT NULL,
  `perm_code` varchar(15) NOT NULL,
  PRIMARY KEY (`perm_id`),
  UNIQUE KEY `IX_Perms_Code` (`perm_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `gf_groups_perms`
--
ALTER TABLE `gf_groups_perms`
  ADD CONSTRAINT `gf_groups_perms_ibfk_2` FOREIGN KEY (`gp_perms`) REFERENCES `gf_perms` (`perm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gf_groups_perms_ibfk_1` FOREIGN KEY (`gp_groups`) REFERENCES `gf_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `gf_groups_users`
--
ALTER TABLE `gf_groups_users`
  ADD CONSTRAINT `gf_groups_users_ibfk_2` FOREIGN KEY (`gu_users`) REFERENCES `gf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gf_groups_users_ibfk_1` FOREIGN KEY (`gu_groups`) REFERENCES `gf_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
