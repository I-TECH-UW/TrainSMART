/**********************************************************************
2017-12-21
Tamara Astakhova
Ukraine changes 
**********************************************************************/

ALTER TABLE _system ADD display_last_name_first tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE _system ADD display_facility_address2 tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE _system ADD display_facility_email tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE facility 
ADD COLUMN email VARCHAR(45) NULL DEFAULT NULL;



