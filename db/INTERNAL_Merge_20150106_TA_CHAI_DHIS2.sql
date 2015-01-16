/****************************************************************
2015-01-06
Tamara Astakhova
For Request: CHAI project: DHIS2 automatic upload of facility/locations, commodity names, commodity data. In 'dev_test_copy' only.
****************************************************************/

ALTER TABLE `facility` ADD COLUMN `external_id` varchar(20) DEFAULT NULL UNIQUE;
ALTER TABLE `location` ADD COLUMN `external_id` varchar(20) DEFAULT NULL UNIQUE;
ALTER TABLE `commodity_name_option` ADD COLUMN `external_id` varchar(20) DEFAULT NULL UNIQUE;

delete from facility;

/* leave only 6 zones and 37 states: total 43 records should be left in 'location' table*/
delete from location where tier=3;
UPDATE location SET location_name='Akwa Ibom' WHERE location_name='Akwa-Ibom';

delete from commodity_name_option;

delete from commodity;

/***************************************************************
2015-01-06
Tamara Astakhova
For Request: CHAI project: DHIS2 automatic upload of facility report rate
****************************************************************/

CREATE TABLE `facility_report_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `facility_external_id` varchar(20) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7569 DEFAULT CHARSET=latin1;