/*
2014-01-05 Sean Smith 
For Request:
Add a check box for residential vs. non-residential on a students record.  Should fall after local address and before next of kin address
*/
ALTER TABLE `person`
ADD COLUMN `home_is_residential`  tinyint(1) NULL AFTER `home_postal_code`;

/*
2014-01-08 Sean Smith 
For Request:
Adding a pass/fail  credits columns in the class history table for a student.  
Both columns would behave the same way the grade column works, click on it and enter what you want.
*/

/* This was added to undo change to test.trainingdata.org db */
ALTER TABLE link_student_classes CHANGE pass_credits credits VARCHAR(50);
ALTER TABLE link_student_classes DROP COLUMN fail_credits;

ALTER TABLE `link_student_classes`
ADD COLUMN `credits`  varchar(50) NULL AFTER `grade`;

ALTER TABLE `link_student_practicums`
ADD COLUMN `credits`  varchar(50) NULL AFTER `grade`;

ALTER TABLE `link_student_licenses`
ADD COLUMN `credits`  varchar(50) NULL AFTER `grade`;


/*
2014-02-15 Greg Rossum
*/

alter table _system add column display_training_completion tinyint(1) NOT NULL DEFAULT '0';


/*
2014-02-14 Greg Rossum
*/
alter table evaluation_response add person_id int null after evaluation_to_training_id;
alter table evaluation_response change column trainer_person_id trainer_person_id int null;


