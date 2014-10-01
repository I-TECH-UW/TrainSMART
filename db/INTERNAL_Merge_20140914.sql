
ALTER TABLE `link_student_classes`
ADD COLUMN `camark` varchar(50) collate utf8_unicode_ci NOT NULL default '' after `linkclasscohortid`;

ALTER TABLE `link_student_classes`
ADD COLUMN `exammark` varchar(50) collate utf8_unicode_ci NOT NULL default '' AFTER `camark;