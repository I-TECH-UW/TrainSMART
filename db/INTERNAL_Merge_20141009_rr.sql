/*
2014-10-09
Rayce Rossum
For Request: Pre-service
*/

ALTER TABLE `_system` ADD COLUMN `ps_display_exam_mark` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_ca_mark` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `_system` ADD COLUMN `ps_display_credits` tinyint(1) NOT NULL DEFAULT '0';

INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('ps exam mark','Exam Mark',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('ps ca mark','CA Mark',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('ps credits','Credits',1,null,0);

alter table link_student_classes add column `exammark` varchar(50) collate utf8_unicode_ci NOT NULL default ''; 
alter table link_student_classes add column `camark` varchar(50) collate utf8_unicode_ci NOT NULL default '';

