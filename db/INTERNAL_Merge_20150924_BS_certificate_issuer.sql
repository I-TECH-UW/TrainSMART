ALTER TABLE `link_student_cohort`
ADD COLUMN `certificate_issuer_id`  int(10) NULL DEFAULT NULL AFTER `certificate_number`;

CREATE TABLE `certificate_issuers` (
`id`  int(10) NOT NULL ,
`issuer_name`  varchar(255) NOT NULL ,
`issuer_email`  varchar(255) NULL ,
`issuer_phone_number`  varchar(255) NULL ,
`issuer_logo_url`  varchar(4096) NULL ,
PRIMARY KEY (`id`)
);

