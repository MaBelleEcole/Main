-- DROP TABLE IF EXISTS `#__rwcards`;

CREATE TABLE IF NOT EXISTS `#__rwcards` (
	`id` int(11) NOT NULL auto_increment,
	`autor` varchar(150) NOT NULL default '',
	`email` varchar(50) NOT NULL default '',
	`picture` varchar(50) NOT NULL default '',
	`description` text NOT NULL,
	`published` tinyint(1) NOT NULL default '0',
	`checked_out` int(11) NOT NULL default '0',
	`checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
	`ordering` int(11) NOT NULL default '0',
	`category_id` tinyint UNSIGNED NOT NULL,
	PRIMARY KEY  (`id`)
) TYPE=MyISAM;

ALTER TABLE `#__rwcards` MODIFY COLUMN `ordering` int(11) NOT NULL default '0';

-- DROP TABLE IF EXISTS `#__rwcardsdata`;

CREATE TABLE IF NOT EXISTS `#__rwcardsdata` (
	`id` int(10) NOT NULL auto_increment,
	`nameTo` varchar(50) NOT NULL default '',
	`nameFrom` varchar(50) NOT NULL default '',
	`emailTo` varchar(50) NOT NULL default '',
	`emailFrom` varchar(50) NOT NULL default '',
	`picture` varchar(50) NOT NULL default '',
	`sessionId` varchar(50) NOT NULL default '',
	`message` text NOT NULL,
	`writtenOn` DATE NOT NULL,
	`readOn` DATE NOT NULL,
	`cardSent` enum('0','1') NOT NULL default '0',
	`cardRead` enum('0','1') NOT NULL default '0',
	PRIMARY KEY  (`id`)
) TYPE=MyISAM;

ALTER TABLE `#__rwcardsdata` MODIFY COLUMN `nameTo` varchar(50) NOT NULL default '';
ALTER TABLE `#__rwcardsdata` MODIFY COLUMN `nameFrom` varchar(50) NOT NULL default '';

-- DROP TABLE IF EXISTS `#__rwcards_category`;

CREATE TABLE IF NOT EXISTS `#__rwcards_category` (
	`id` int(99) unsigned NOT NULL auto_increment,
	`category_kategorien_name` varchar(50) NOT NULL default '',
	`category_description` text NOT NULL,
	`published` tinyint(1) NOT NULL default '0',
	`checked_out` int(11) NOT NULL default '0',
	`checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
	`ordering` int(11) NOT NULL default '0',
	PRIMARY KEY  (`id`)
) TYPE=MyISAM;

ALTER TABLE `#__rwcards_category` MODIFY COLUMN `ordering` int(11) NOT NULL default '0';
