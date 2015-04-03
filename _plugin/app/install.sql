DROP TABLE IF EXISTS `%--%_app`;
CREATE TABLE IF NOT EXISTS `%--%_app` (
  `id` int(10) auto_increment,
	`content` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `%--%_app_menus`;
CREATE TABLE IF NOT EXISTS `%--%_app_menus` (
  `id` int(10) auto_increment,
	`link_text` text NOT NULL,
	`link_url` text NOT NULL,
	`order` int(10) NOT NULL,
	`parent_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE `%--%_app_form` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`fields` TEXT NOT NULL,
	`method` ENUM('post','get') NOT NULL DEFAULT 'post',
	`action` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE `%--%_app_form_data` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`form_id` INT(10) NOT NULL,
	`data` TEXT NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;