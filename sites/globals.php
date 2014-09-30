<?php
/*
 * Created on Feb 11, 2008
 *
*  Built for I-Tech
*  Fuse IQ -- todd@fuseiq.com
*
*/

ini_set('max_execution_time','300');
ini_set('memory_limit', '1024M');

#ini_set('display_errors', 'On');
#error_reporting(E_ALL | E_STRICT);

define('space',  " ");


class Globals {
	public static $BASE_PATH = '/wamp/www/trainsmartSA/';
	public static $WEB_FOLDER = 'html';
	//orig 	
	public static $COUNTRY = 'test';
	//public static $COUNTRY = 'abri';

	public function __construct() {

		require_once('settings.php');
/* orig
		error_reporting( E_ALL ^ E_NOTICE); // E_ALL | E_NOTICE// & E_STRICT); // todo ... set back to normal
		// | E_STRICT
		//);
		$parts = array('jhpiego','ethiopia','trainingdata','org');
		$countryLoaded = false;
		if ( $_SERVER['HTTP_HOST'] == 'jhpiego.ethiopia.trainingdata.org')
			Settings::$DB_DATABASE = 'itechweb_jpeigo_ethiopa';
			*/

		// PATH_SEPARATOR =  ; for windows, : for *nix
		$iReturn = ini_set( 'include_path',
					(Globals::$BASE_PATH).PATH_SEPARATOR.
					(Globals::$BASE_PATH).'app'.PATH_SEPARATOR.
					(Globals::$BASE_PATH.'ZendFramework'.DIRECTORY_SEPARATOR.'library').PATH_SEPARATOR.
						ini_get('include_path'));
		require_once 'Zend/Loader.php';

		require_once 'Zend/Db.php';
		//fixes mysterious configuration issue
		require_once('Zend/Db/Adapter/Pdo/Mysql.php');
		//set a default database adaptor		
		$db = Zend_Db::factory('PDO_MYSQL', array(
			'host'     => Settings::$DB_SERVER,
			'username' => Settings::$DB_USERNAME,
			'password' => Settings::$DB_PWD,
			'dbname'   => Settings::$DB_DATABASE
		));
		 require_once 'Zend/Db/Table/Abstract.php';
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		}
}

new Globals();

