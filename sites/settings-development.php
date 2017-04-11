<?php
/*
 * Copy this file to the name 'settings.php' and customize for TrainSMART configuration.
 *
 * For multi-domain configurations, the database name is determined in the globals.php file, copied from
 * globals-shared-sites.php, but database credentials are still set here.
 */

class Settings {

	public static $COUNTRY_NAME = 'test';
	public static $COUNTRY_BASE_URL = 'http://localhost:62326';
    public static $DB_USERNAME = 'admin';
    public static $DB_PWD = '';
    
    public static $DB_SERVER = '127.0.0.1';

    public static $DB_DATABASE = 'itechweb_demo';
    
    public static $EMAIL_NAME = "ITECH Administrator";
    public static $EMAIL_ADDRESS = "noreply@trainingdata.org";
}


