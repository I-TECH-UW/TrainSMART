/**********************************************************************
2018-01-26
Tamara Astakhova
Pre-service student new fields 
#489
**********************************************************************/

/*  OLD */
ALTER TABLE addresses ADD COLUMN phone VARCHAR(32) NULL;
ALTER TABLE link_student_addresses ADD COLUMN kin_name VARCHAR(100) NULL;
ALTER TABLE link_student_addresses ADD COLUMN kin_relationship VARCHAR(50) NULL;

ALTER TABLE person ADD COLUMN home_district VARCHAR(100) NULL;
ALTER TABLE person ADD COLUMN ta VARCHAR(100) NULL;
ALTER TABLE person ADD COLUMN gvh VARCHAR(100) NULL;




