/**********************************************************************
2017-03-16
Tamara Astakhova
Employee edit contract start date
#374
**********************************************************************/

ALTER TABLE `employee` ADD COLUMN `agreement_start_date` DATE NULL DEFAULT NULL AFTER `external_funding_percent`;