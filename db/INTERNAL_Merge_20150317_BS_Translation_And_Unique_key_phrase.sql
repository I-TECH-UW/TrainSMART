/* ALTER IGNORE makes key_phrase unique and drops any entries after the first
that have a duplicate key_phrase */

ALTER IGNORE TABLE translation ADD UNIQUE (key_phrase);

INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Last Name', 'Last Name');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('First Name', 'First Name');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps nationality', 'ps nationality');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps national id', 'ps national id');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps specialty', 'ps specialty');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Age', 'Age');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Gender', 'Gender');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps spouse name', 'ps spouse name');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Highest Qualification Achieved', 'Highest Qualification Achieved');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps local address', 'ps local address');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Province', 'Province');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Phone', 'Phone');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Email', 'Email');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps marital status', 'ps marital status');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('List of Modules', 'List of Modules');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Facility Name', 'Facility Name');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Comments', 'Comments');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps person in charge', 'ps person in charge');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Commencing Date for Workplace', 'Commencing Date for Workplace');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('End Date for Workplace', 'End Date for Workplace');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Name of Employer', 'Name of Employer');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Address of Employer', 'Address of Employer');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Telephone Number', 'Telephone Number');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Name of Contact Person', 'Name of Contact Person');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Email Address', 'Email Address');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Reason for Enrollment', 'Reason for Enrollment');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps custom field 2', 'ps custom field 2');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps custom field 3', 'ps custom field 3');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Reason for Separation', 'Reason for Separation');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Date of Enrollment', 'Date of Enrollment');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Date of Separation', 'Date of Separation');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Date of Final Integrated External Assessment', 'Date of Final Integrated External Assessment');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps program enrolled in', 'ps program enrolled in');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps last university attended', 'ps last university attended');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Contact Number of Assessment Centre/Site', 'Contact Number of Assessment Centre/Site');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('ps religious denomination', 'ps religious denomination');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Date Certificate was Received From the AQP', 'Date Certificate was Received From the AQP');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Certificate Number', 'Certificate Number');
INSERT IGNORE INTO `translation` (key_phrase, phrase) values ('Date Learner Received Certificate', 'Date Learner Received Certificate');