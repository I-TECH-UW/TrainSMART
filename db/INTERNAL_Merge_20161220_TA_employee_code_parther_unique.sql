/**********************************************************************
2016-12-20 
Tamara Astakhova
Employee Code to be unique per Partner and not Unique for the whole database
**********************************************************************/

ALTER TABLE employee DROP INDEX employee_code_key;
ALTER TABLE employee ADD CONSTRAINT employee_code_partner_key UNIQUE (employee_code,partner_id);


/*********************************************************************
2016-12-20 
Tamara Astakhova
Add the fields timestamp_updated and updated_by to the employee table
**********************************************************************/

ALTER TABLE employee ADD modified_by int(11) DEFAULT NULL;
ALTER TABLE employee ADD timestamp_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;
UPDATE employee t1 INNER JOIN employee t2 ON t1.id = t2.id SET t1.timestamp_updated = t2.timestamp_created, t1.modified_by=t2.created_by;   

