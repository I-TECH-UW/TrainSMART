/* Apply for all DBs */

ALTER TABLE institution ADD created_by int(11) DEFAULT NULL;
ALTER TABLE institution ADD modified_by int(11) DEFAULT NULL;
ALTER TABLE institution ADD timestamp_created timestamp NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE institution ADD timestamp_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE cohort ADD created_by int(11) DEFAULT NULL;
ALTER TABLE cohort ADD modified_by int(11) DEFAULT NULL;
ALTER TABLE cohort ADD timestamp_created timestamp NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE cohort ADD timestamp_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;
update _system set display_use_offline_app=1;