ALTER TABLE user_to_acl ENGINE=InnoDB;
ALTER TABLE acl ENGINE=InnoDB;
DELETE FROM user_to_acl WHERE acl_id NOT IN (SELECT id FROM acl);
ALTER TABLE user_to_acl MODIFY acl_id varchar(32);
ALTER TABLE `user_to_acl` ADD CONSTRAINT `acl_fk` FOREIGN KEY (`acl_id`) REFERENCES `acl` (`id`);