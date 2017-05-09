<?php

define('space', " ");

/*
 * This file is used for multiple-domain configurations of TrainSMART. Copy it to a file named 'globals.php' for
 * TrainSMART to use it.
 */

function redirect_to_location($newLocation) {
	header("Location: $newLocation");
	exit();
}

function showSiteMovedPage($newLocation) {
    echo "<html><title>Site Moved</title><body><h3>This website has been moved to <a href='$newLocation'>$newLocation</a>.</h3> Please update your bookmarks.</body></html>";
    exit();
}

class Globals
{
    public static $BASE_PATH = '/srv/www/trainsmart/all-shared-sites/';
    public static $WEB_FOLDER = 'html';
    public static $COUNTRY = null;
    public static $SITE_TITLE = 'TrainSMART';

	public static $REDIRECTS = array(
        // redirect to front-facing, non TrainSMART website
		'trainingdata.org' => '//www.trainingdata.org/',
    );

    public static $SITE_MOVED = array(
        'tanzaniapartners.trainingdata.org' => 'http://trainsmart.moh.go.tz/',
        'kzn.trainingdata.org' => 'https://skillsmart.trainingdata.org/',
    );

    public function __construct()
    {
        // use subdomain to determine database
        $host = strtolower($_SERVER['HTTP_HOST']);

        if (array_key_exists($host, self::$REDIRECTS)) {
            redirect_to_location(self::$REDIRECTS[$host]);
        }

		if (array_key_exists($host, self::$SITE_MOVED)) {
            showSiteMovedPage(self::$SITE_MOVED[$host]);
		}
		
        $parts = explode('.', $host);
        $rparts = array_reverse($parts);
        $subMostDomain = $parts[0];
        self::$COUNTRY = $subMostDomain;

        $fn = realpath(getcwd() . "/../sites/settings.php");
        if (!$fn)
        {
            echo "Configuration file 'settings.php' not found.";
        }
        require_once($fn);

        $countryLoaded = false;

        if ($subMostDomain === 'www' && ($rparts[1] === 'eventsmart' || $rparts[1] === 'trainingdata')) {
            // redirect to sitename.trainingdata.org if we get a request for www.sitename.trainingdata.org
            $newUrl = implode('.', array_slice($parts, 1));
            redirect_to_location("//$newUrl");
        }

        if (count($parts) === 2) {
            if ($host === 'eventsmart.info') {
                Settings::$DB_DATABASE = 'itechweb_eventsmart';
                self::$COUNTRY = $subMostDomain;

                // eventsmart.info is a special case site
                Settings::$COUNTRY_BASE_URL = 'http://eventsmart.info';
                $countryLoaded = true;
            }
        }
        else if (count($parts) === 3) {
            if ($subMostDomain === 'www') {
                // the web server should not be executing the current file to serve www.trainingdata.org or
                // www.eventsmart.info (we don't host www.eventsmart.info anyway)
                throw new Exception("Server configuration problem.");
            }

            if (($rparts[1] === 'trainingdata') && ($rparts[0] === 'org')) {
                // we have a sitename.trainingdata.org address

                Settings::$DB_DATABASE = 'itechweb_' . $subMostDomain;
                self::$COUNTRY = $subMostDomain;

                // use https for *.trainingdata.org sites
                Settings::$COUNTRY_BASE_URL = 'https://' . $parts[0] . '.trainingdata.org';
                $countryLoaded = true;
            }
            else if (($rparts[1] === 'eventsmart') && ($rparts[0] === 'info')) {
                // database name should be itechweb_eventsmart_subproject
                Settings::$DB_DATABASE = 'itechweb_eventsmart_' . $subMostDomain;
                self::$COUNTRY = $subMostDomain;
                Settings::$COUNTRY_BASE_URL = 'http://' . $parts[0] . '.eventsmart.info';
                $countryLoaded = true;
            }
        }
        else if (count($parts) === 4) {
            if ($subMostDomain === 'jhpiego') {
                // request was in the form of //jhpiego.country.trainingdata.org

                // database name should be itechweb_jhpiego_country
                Settings::$DB_DATABASE = 'itechweb_jhpiego_' . $rparts[2];

                // use http for *.*.trainingdata.org
                Settings::$COUNTRY_BASE_URL = 'http://' . implode('.', $parts);
                $countryLoaded = true;
                // Note that the predecessor file leaves self::$COUNTRY set at 'jhpiego' for all of these sites,
                // so this replacement is doing the same in order to not possibly break things
            }
        }

        // redirect to front-facing, non-TrainSMART website
        if (!$countryLoaded) {
            redirect_to_location('//www.trainingdata.org');
		}

        set_include_path(
            (Globals::$BASE_PATH) . PATH_SEPARATOR .
            (Globals::$BASE_PATH) . 'app' . PATH_SEPARATOR .
            (Globals::$BASE_PATH . 'ZendFramework' . DIRECTORY_SEPARATOR .
                'library') . PATH_SEPARATOR .
            get_include_path()
        );

        require_once 'Zend/Loader.php';
        require_once 'Zend/Db.php';

 
        //set a default database adaptor
        $db = Zend_Db::factory('PDO_MYSQL', array(
            'host' => Settings::$DB_SERVER,
            'username' => Settings::$DB_USERNAME,
            'password' => Settings::$DB_PWD,
            'dbname' => Settings::$DB_DATABASE
        ));

        require_once 'Zend/Db/Table/Abstract.php';
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }
}

new Globals();
