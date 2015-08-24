CREATE TABLE `link_user_cadres` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `cadreid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx2` (`userid`,`cadreid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into acl values 
( 'edit_studenttutorinst', 'edit_studenttutorinst'),
( 'acl_delete_ps_cohort', 'acl_delete_ps_cohort'),
( 'view_studenttutorinst', 'view_studenttutorinst'),
( 'acl_delete_ps_student', 'acl_delete_ps_student'),
( 'acl_delete_ps_grades', 'acl_delete_ps_grades');

