/* include a method for determining the kind of site we're running, 
to use instead of or in conjunction with the subdomain name */

ALTER TABLE `_system`
ADD COLUMN `site_style_id`  int(11) NOT NULL DEFAULT 1 AFTER `display_training_location`;

CREATE TABLE `site_styles` (
  `id` int(11) NOT NULL auto_increment,
  `site_style_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `site_styles` (`site_style_name`) VALUES ('default');
INSERT INTO `site_styles` (`site_style_name`) VALUES ('skillsmart');
