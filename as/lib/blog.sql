DROP TABLE IF EXISTS `%--%_attachment`;
CREATE TABLE IF NOT EXISTS `%--%_attachment` (
  `id` int(10) NOT NULL auto_increment,
  `post_id` int(10) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `date` int(10) NOT NULL,
  `downloads` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `%--%_comment`;
CREATE TABLE IF NOT EXISTS `%--%_comment` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `website` varchar(255) NOT NULL ,
  `info` text NOT NULL,
  `post_id` int(10) NOT NULL default '0',
  `post_name` varchar(255) NOT NULL,
  `post_cat` varchar(128) NOT NULL,
  `post_slug` varchar(128) NOT NULL,
  `date` int(10) NOT NULL default '0',
  `ip` varchar(39) NOT NULL default '',
  `reply_date` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `%--%_posts`;
CREATE TABLE IF NOT EXISTS `%--%_posts` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `keyword` varchar(255) NOT NULL default '',
  `tags` text NOT NULL,
  `description` varchar(255) NOT NULL default '',
  `sys_name` varchar(128) NOT NULL,
  `date` int(10) NOT NULL default '0',
  `category` int(10) NOT NULL default '0',
  `in_blog` tinyint(1) NOT NULL,
  `views` int(10) NOT NULL,
  `allow_comment` tinyint(1) NOT NULL default '1',
  `template` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `sys_name` (`sys_name`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


DROP TABLE IF EXISTS `%--%_category`;
CREATE TABLE IF NOT EXISTS `%--%_category` (
  `id` int(4) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `link` varchar(128) NOT NULL,
  `title` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `sort_word` text NOT NULL,
  `parent_id` int(10) NOT NULL default '0',
  `template` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `link` (`link`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `%--%_options`;
CREATE TABLE IF NOT EXISTS `%--%_options` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `%--%_item_plugin`;
CREATE TABLE IF NOT EXISTS `%--%_item_plugin` (
  `id` int(10) NOT NULL auto_increment,
  `item_id` int(10) NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `plugin` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


DROP TABLE IF EXISTS `%--%_links`;
CREATE TABLE IF NOT EXISTS `%--%_links`(
  `lid` int(10) NOT NULL auto_increment,
  `request` text NOT NULL,
  `url` text NOT NULL,
  `plugin` varchar(255) NOT NULL,
  PRIMARY KEY  (`lid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;