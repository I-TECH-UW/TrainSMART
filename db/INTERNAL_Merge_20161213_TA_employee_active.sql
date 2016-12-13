/*
2016-12-13 
Tamara Astakhova
Add active field to employee add/edit page
*/

ALTER TABLE employee ADD is_active tinyint(1) NOT NULL DEFAULT '1';
