/*
2016-11-28 
Tamara Astakhova
For Request: PEPFAR South Africa Employee Multiple Locations
*/

CREATE TABLE `link_employee_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_employee` int(10) unsigned NOT NULL DEFAULT '0',
  `id_location` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx` (`id_employee`,`id_location`),
  KEY `FK_link_employee_location` (`id_location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*populate employee location_id to link_employee_location*/
insert into link_employee_location (id_employee, id_location) 
select id,location_id from employee order by id;

