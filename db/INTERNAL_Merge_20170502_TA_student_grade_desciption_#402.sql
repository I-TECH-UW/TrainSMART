/**********************************************************************
2017-05-02
Tamara Astakhova
Student grade desciption
#402
**********************************************************************/

ALTER TABLE link_student_classes ADD COLUMN `grade_description` VARCHAR(45) NULL;

/*** #402.2 ***/
ALTER TABLE link_student_practicums ADD COLUMN `grade_description` VARCHAR(45) NULL;
ALTER TABLE link_student_licenses ADD COLUMN `grade_description` VARCHAR(45) NULL;

/*********** #402.3 **********/
CREATE TABLE `lookup_grade_description` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grade_description_name` varchar(255) NOT NULL DEFAULT '',
  `grade_description_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
