# Dump of table blog_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_members`;

CREATE TABLE `blog_members` (
  `memberID` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) DEFAULT NULL,
  `password` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`memberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `blog_members` WRITE;
/*!40000 ALTER TABLE `blog_members` DISABLE KEYS */;

INSERT INTO `blog_members` (`memberID`, `username`, `password`, `email`)
VALUES
  (1,'Demo','$2y$10$wJxa1Wm0rtS2BzqKnoCPd.7QQzgu7D/aLlMR5Aw3O.m9jx3oRJ5R2','demo@demo.com');

/*!40000 ALTER TABLE `blog_members` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blog_posts_bi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_posts_bi`;

CREATE TABLE `blog_posts_bi` (
  `postID` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `enTitle` VARCHAR(255) DEFAULT NULL,
  `frTitle` VARCHAR(255) DEFAULT NULL,
  `enDesc` TEXT,
  `frDesc` TEXT,
  `enContents` TEXT,
  `frContents` TEXT,
  `frPostDate` DATETIME DEFAULT NULL,
  `enPostDate` DATETIME DEFAULT NULL,
  PRIMARY KEY (`postID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `blog_posts_bi` WRITE;
/*!40000 ALTER TABLE `blog_posts_bi` DISABLE KEYS */;
INSERT INTO `blog_posts_bi` (`postID`, `enTitle`, `frTitle`, `enDesc`, `frDesc`, `enContents`, `frContents`, `enPostDate`, `frPostDate`)
VALUES
  (1,'First Blog','Premiere Blogue', 'English Description', 'Description francaise', 'blah blah blah', 'n\'importe quoi ......', NOW(), NOW());
/*!40000 ALTER TABLE `blog_posts_bi` ENABLE KEYS */;
UNLOCK TABLES;
