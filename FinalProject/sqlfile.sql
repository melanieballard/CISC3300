--data populated automatically based on php responses and user input

--create playlist table
CREATE TABLE `playlists` (
  `id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `owner` int(11) DEFAULT NULL,
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pk`),
  KEY `fk_user` (`owner`),
  CONSTRAINT `fk_user` FOREIGN KEY (`owner`) REFERENCES `access_tokens` (`id`)
) 

--create access tokens table
CREATE TABLE `access_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `token` varchar(400) NOT NULL,
  `userID` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
)
