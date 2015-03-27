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

define('space',  " ");


class Globals {
	public static $BASE_PATH = '/vagrant/';
	public static $WEB_FOLDER = 'html';
	public static $COUNTRY = 'test';

	public function __construct() {

		require_once('settings.php');
		
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

