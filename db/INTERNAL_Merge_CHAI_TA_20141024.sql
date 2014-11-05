/*
2014-10-24 
Tamara Astakhova
For Request: CHAI project: Monthly emails report
*/

ALTER TABLE _system ADD COLUMN display_email_report_1 tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE _system ADD COLUMN display_email_report_2 tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE _system ADD COLUMN display_email_report_3 tinyint(1) NOT NULL DEFAULT '0';

INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Label Email Report Level 1','Federal',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Label Email Report Level 2','State',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Label Email Report Level 3','LGA',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Emails Report Level 1','',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Emails Report Level 2','',1,null,0);
INSERT INTO `translation`(`key_phrase`,`phrase`,`modified_by`,`created_by`,`is_deleted`) VALUES ('Emails Report Level 3','',1,null,0);




