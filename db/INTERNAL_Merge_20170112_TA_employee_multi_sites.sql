/**********************************************************************
2017-01-12 
Tamara Astakhova
Employee Multiple sites with FTE
**********************************************************************/

drop table link_employee_location;

CREATE TABLE `link_employee_facility` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL DEFAULT '0',
  `facility_id` int(10) unsigned DEFAULT NULL,
  `fte_related` int(10) unsigned DEFAULT '0',
  'non_hiv' tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx` (`employee_id`,`facility_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*populate employee.site_id to link_employee_facility*/
insert into link_employee_facility (employee_id, facility_id)  select id,site_id from employee order by id;

/* TEST: save to CSV files and compare
select id,site_id from employee order by id;
select employee_id,facility_id from link_employee_facility order by employee_id;
*/