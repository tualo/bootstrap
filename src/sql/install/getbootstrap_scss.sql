DELIMITER ;
CREATE TABLE IF NOT EXISTS `getbootstrap_scss` (
  `filename` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`filename`)
) ;