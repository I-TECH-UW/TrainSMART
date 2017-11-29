/**********************************************************************
2017-11-27
Tamara Astakhova
Merge mechanism and site
#464
**********************************************************************/

ALTER TABLE link_employee_facility CHANGE COLUMN non_hiv_fte_related mechanism_option_id int(10) DEFAULT NULL;
update link_employee_facility set mechanism_option_id=0;




