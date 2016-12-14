/*
2016-12-13 
Tamara Astakhova
Add active field to employee add/edit page. Change cost fields to two places decimal type
*/

ALTER TABLE employee ADD is_active tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE employee CHANGE COLUMN salary salary DECIMAL(10,2) DEFAULT '0.00' ;
ALTER TABLE employee CHANGE COLUMN benefits benefits DECIMAL(10,2) DEFAULT '0.00' ;
ALTER TABLE employee CHANGE COLUMN additional_expenses additional_expenses DECIMAL(10,2) DEFAULT '0.00' ;
ALTER TABLE employee CHANGE COLUMN stipend stipend DECIMAL(10,2) DEFAULT '0.00' ;
ALTER TABLE employee CHANGE COLUMN annual_cost annual_cost DECIMAL(10,2) DEFAULT '0.00' ;
