<?php

define('space', " ");

function redirect_to_www() {
    header("Location: //www.trainingdata.org/home/");
    exit();
}

class Globals
{
    public static $BASE_PATH = '/srv/www/trainsmart/all-shared-sites/';
    public static $WEB_FOLDER = 'html';
    public static $COUNTRY = null;
    public static $SITE_TITLE = 'TrainSMART';

    public function __construct()
    {
        // use subdomain to determine database
        $host = strtolower($_SERVER['HTTP_HOST']);

        // redirect to front-facing, non TrainSMART website
        if ($host === 'trainingdata.org') {
            redirect_to_www();
        }

        $parts = explode('.', $host);
        $rparts = array_reverse($parts);
        $subMostDomain = $parts[0];
        self::$COUNTRY = $subMostDomain;

        require_once('settings.php');

        $countryLoaded = false;

        if (($host == 'fpdashboard.ng') || ($host == 'www.fpdashboard.ng')) {
            // BS 20141031 - add in fpdashboard.ng alias for chainigeria
            Settings::$DB_DATABASE = 'itechweb_chainigeria';
            Settings::$COUNTRY_BASE_URL = 'http://www.fpdashboard.ng';
            $countryLoaded = true;
        }
        else if (($rparts[0] !== 'org') || ($rparts[1] !== 'trainingdata')) {
            redirect_to_www();
        }

        if (count($parts) === 3) {
            // we have a sitename.trainingdata.org address

            if ($subMostDomain === 'www') {
                // the web server should not be executing the current file to serve www.trainingdata.org
                throw new Exception("Server configuration problem.");
            }
            Settings::$DB_DATABASE = 'itechweb_' . $subMostDomain;
            self::$COUNTRY = $subMostDomain;

            // use https for *.trainingdata.org sites
            Settings::$COUNTRY_BASE_URL = 'https://' . $parts[0] . '.trainingdata.org';
            $countryLoaded = true;
        }

        if ($subMostDomain === 'www') {
            // redirect to sitename.trainingdata.org if we get a request for www.sitename.trainingdata.org
            $newUrl = implode('.', array_slice($parts, 1));
            header("Location: //$newUrl");
            exit();
        }

        if (count($parts) === 4 && $subMostDomain === 'jhpiego') {
            // request was in the form of //jhpiego.country.trainingdata.org

            // database name should be itechweb_jhpiego_country
            Settings::$DB_DATABASE = implode('_', array('itechweb', $rparts[0], $rparts[1]));

            // use http for *.*.trainingdata.org
            Settings::$COUNTRY_BASE_URL = 'http://' . implode('.', $parts);
            $countryLoaded = true;
            // Note that the predecessor file leaves self::$COUNTRY set at 'jhpiego' for all of these sites,
            // so this replacement is doing the same in order to not possibly break things
        }

        // redirect to front-facing, non-TrainSMART website
        if (!$countryLoaded) {
            redirect_to_www();
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
