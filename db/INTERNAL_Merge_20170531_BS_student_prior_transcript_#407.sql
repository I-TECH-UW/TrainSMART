ALTER TABLE `_system`
ADD COLUMN `display_student_prior_transcript`  tinyint(1) NOT NULL DEFAULT 0;

CREATE TABLE `lookup_prior_education_courses` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`course_name`  varchar(255) NOT NULL ,
PRIMARY KEY (`id`)
)
;

CREATE TABLE `student_prior_education` (
`id`  int UNSIGNED NOT NULL AUTO_INCREMENT ,
`student_id`  int(10) UNSIGNED NOT NULL ,
`lookup_prior_education_courses_id`  int(10) UNSIGNED NOT NULL ,
`course_grade`  varchar(25) NOT NULL ,
PRIMARY KEY (`id`),
FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`lookup_prior_education_courses_id`) REFERENCES `lookup_prior_education_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
)
;

