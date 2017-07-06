/**********************************************************************
2017-05-02
Tamara Astakhova
Student grade desciption
#402
**********************************************************************/

ALTER TABLE link_student_classes ADD COLUMN `grade_description` VARCHAR(45) NULL;

/*** new version ***/
ALTER TABLE link_student_practicums ADD COLUMN `grade_description` VARCHAR(45) NULL;
ALTER TABLE link_student_licenses ADD COLUMN `grade_description` VARCHAR(45) NULL;
