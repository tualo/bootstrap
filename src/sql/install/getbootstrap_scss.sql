DELIMITER ;
CREATE TABLE IF NOT EXISTS `getbootstrap_scss` (
  `filename` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
