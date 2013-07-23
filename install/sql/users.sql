--
-- Structure de la table `gf_users`
--
-- Création: Mar 23 Juillet 2013 à 13:47
--

CREATE TABLE IF NOT EXISTS `gf_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_username` varchar(30) NOT NULL,
  `user_hash` varchar(128) NOT NULL COMMENT 'Hash with Sha512',
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
