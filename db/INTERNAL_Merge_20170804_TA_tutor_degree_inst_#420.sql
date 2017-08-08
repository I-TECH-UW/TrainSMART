/**********************************************************************
2017-08-04
Tamara Astakhova
Student degree institution
#420
**********************************************************************/

CREATE TABLE `lookup_degree_institution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `degree_institution` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx2` (`degree_institution`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*265 - cvs_old*/
select id,personid,degreeinst from tutor order by id; 

/*29 - list of degree inst  REVIEW */
select distinct(degreeinst) from tutor where degreeinst != '' order by degreeinst; 

/*populate degree_institution from tutor.degreeinst*/
insert into lookup_degree_institution(degree_institution) select distinct(degreeinst) from tutor where degreeinst != '' order by degreeinst;

/*replace tutor.degreeinst by numbers*/
UPDATE tutor LEFT JOIN lookup_degree_institution ON tutor.degreeinst = lookup_degree_institution.degree_institution SET tutor.degreeinst = lookup_degree_institution.id
where tutor.degreeinst != '';

/*265 - cvs new */
select tutor.id,tutor.personid,
CASE WHEN lookup_degree_institution.degree_institution IS NULL THEN '' ELSE lookup_degree_institution.degree_institution END as degree_institution from tutor 
left join lookup_degree_institution on lookup_degree_institution.id=tutor.degreeinst
order by tutor.id; 

/*compare cvs_old and cvs_new */

select distinct(degreeinst) from tutor where degreeinst != '' order by degreeinst;

itechweb_cham - 29
itechweb_demo - 6
itechweb_easterncape - 2,
itechweb_gauteng - 6,
itechweb_malawi - 2,
itechweb_namibia - 2,
itechweb_mpumalanga - 1




