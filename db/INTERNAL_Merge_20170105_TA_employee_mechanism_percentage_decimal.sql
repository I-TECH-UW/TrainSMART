/**********************************************************************
2017-01-05 
Tamara Astakhova
Employee Mechanism percentage decimal
**********************************************************************/

ALTER TABLE link_mechanism_employee CHANGE COLUMN percentage percentage DECIMAL(10,2) DEFAULT '0.00' ;

