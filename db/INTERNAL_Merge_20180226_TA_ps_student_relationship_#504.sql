/**********************************************************************
2018-02-26
Tamara Astakhova
Pre-service student relationship
#504
**********************************************************************/

CREATE TABLE `lookup_relationship` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relationship` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `relationship` (`relationship`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




