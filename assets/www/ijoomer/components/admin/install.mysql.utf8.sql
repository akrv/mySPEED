--
-- Table structure for table `#__ijoomer_plugins`
--

CREATE TABLE IF NOT EXISTS `#__ijoomer_plugins` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(255) NOT NULL,
  `plugin_classname` varchar(255) NOT NULL,
  `plugin_option` varchar(255) NOT NULL,	
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`plugin_id`)
);

CREATE TABLE IF NOT EXISTS `#__ijoomer_users` (
`userid` INT NOT NULL ,
`jomsocial_params` TEXT NOT NULL ,
`device_token` varchar(255) NOT NULL,
`android_device_token` varchar(255) NOT NULL,
`bb_device_token` varchar(255) NOT NULL,
PRIMARY KEY ( `userid` )
); 

CREATE TABLE IF NOT EXISTS `#__ijoomer_plist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plist_name` varchar(255) NOT NULL,
  `plist_value` varchar(255) NOT NULL,
  `plist_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parent` varchar(255) NOT NULL,
  `type` varchar(1) NOT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `#__ijoomer_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(1) NOT NULL,
  `tab_icon_size` varchar(250) NOT NULL,
  `list_icon_size` varchar(250) NOT NULL,
  `devices` text NOT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `#__ijoomer_display` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `plist_value` varchar(256) NOT NULL,
  `tab_icon` varchar(256) NOT NULL,
  `tab_icon2` varchar(256) NOT NULL,
  `show_tab` int(11) NOT NULL DEFAULT '0',
  `list_icon` varchar(256) NOT NULL,
  `list_icon2` varchar(256) NOT NULL,
  `show_list` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `#__ijoomer_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  PRIMARY KEY (`config_id`)
); 

CREATE TABLE IF NOT EXISTS `#__ijoomer_app_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `plugin_classname` varchar(256) NOT NULL,
  `module_code` varchar(256) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `title` tinyint(1) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `access` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `screens_i` varchar(255) NOT NULL,
  `screens_a` varchar(255) NOT NULL,
  `screens_b` varchar(255) NOT NULL,
  `pidi` varchar(255) NOT NULL,
  `pida` varchar(255) NOT NULL,
  `pidb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__ijoomer_qrcode_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `qrcode_image` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__ijoomer_push_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_type` text NOT NULL,
  `send_to_user` text NOT NULL,
  `send_to_all` tinyint(1) NOT NULL default '0',
  `notification_text` text NOT NULL,
  `current_tmsp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
); 
