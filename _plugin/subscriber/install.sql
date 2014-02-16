DROP TABLE IF EXISTS `%--%_mailbody`;
CREATE TABLE IF NOT EXISTS `%--%_mailbody` (
  `id` int(6) auto_increment NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `text_body` text NOT NULL,
  `date` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `to` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `%--%_maillist`;
CREATE TABLE IF NOT EXISTS `%--%_maillist` (
  `email` varchar(255) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY  (`email`)
) ENGINE=MyISAM ;