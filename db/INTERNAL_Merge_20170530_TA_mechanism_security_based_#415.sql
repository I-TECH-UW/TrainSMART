/**********************************************************************
2017-05-4
Tamara Astakhova
New User (Activity Managers) - mechanism security based
#415
**********************************************************************/

CREATE TABLE `user_to_mechanism_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mechanism_option_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

insert into acl (id,acl) values ('mechanism_option_all','mechanism_option_all');

