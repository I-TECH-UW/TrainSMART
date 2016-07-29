ALTER TABLE `mechanism_option` ADD COLUMN `owner_id` int(11) NOT NULL DEFAULT 0 AFTER `mechanism_phrase`;
ALTER TABLE `mechanism_option` ADD COLUMN `funder_id` int(11) NOT NULL DEFAULT 0 AFTER `owner_id`;
ALTER TABLE `mechanism_option` ADD COLUMN `external_id` int(11) NOT NULL DEFAULT 0 AFTER `funder_id`;
ALTER TABLE `mechanism_option` ADD COLUMN `end_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `external_id`;