/*
2016-12-20 
Tamara Astakhova
Employee Code to be unique per Partner and not Unique for the whole database
*/

ALTER TABLE employee DROP INDEX employee_code_key;
ALTER TABLE employee ADD CONSTRAINT employee_code_partner_key UNIQUE (employee_code,partner_id);