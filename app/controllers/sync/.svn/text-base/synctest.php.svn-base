<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
header('content-type: text/plain');

require_once('../../../sites/globals.php');
require_once('app/controllers/sync/SyncCompare.php');
require_once('app/models/table/System.php');

#test 1 sqlite.db
#insert in every table
test('test_insert.sqlite');

#test 2 sqlite.db

#update every table
test('test_update.sqlite');

#test 3 sqlite.db

#delete every table

#test 4 sqlite.db

#person and training conflict

function test($filename) {
		
		$db = SyncCompare::getDesktopConnectionParams('_app',Globals::$BASE_PATH . 'app/controllers/sync/test_dbs/'.$filename);
		$_app = new ITechTable($db);
		$_app = $_app->fetchAll()->current();
				
		
		$previous_files = new SyncFile();
		$previous = $previous_files->getAdapter()->query("SELECT * FROM syncfile WHERE application_id = '".($_app->app_id)."' AND timestamp_completed IS NOT NULL ORDER BY timestamp_completed DESC LIMIT 1");
		
		$previous_timestamp = $_app->init_timestamp; //first time
		if ( $previous ) {
			$previous = $previous->fetchAll();
			if ( $previous )
				$previous_timestamp = $previous[0]['timestamp_completed'];
		}	
		
		$save = array(
			'filename' => $filename,
			'filepath' => Globals::$BASE_PATH . 'app/controllers/sync/test_dbs/',
			'application_id' => $_app->app_id,
			'application_version' => $_app->app_id, 
			'timestamp_last_sync' => $previous_timestamp
		);
		// dest info 
		$syncFile = new SyncFile();
		$fid = $syncFile->insert($save);
		
		try {
			$syncCompare = new SyncCompare($fid);
			echo "sanity check\n";
			$msg = $syncCompare->sanityCheck();
			if ( !$msg ) {
				echo "find diffs\n";
				$has_errors = $syncCompare->findDifferencesProcess();
				$syncLog = new SyncLog($fid);
				$totals = $syncLog->pendingTotals();
				foreach($totals as $tot) {
					echo $tot['item_type'].'::'.$tot['action'].'::'.$tot['cnt']."\n";
				}
				$pendingList = $syncLog->pendingList();
				//var_dump($pendingList);
				//insert
				var_dump($syncCompare->doUpdatesProcess());
				
				echo "verifying \n";
				//look for inserted values
				foreach($pendingList as $p) {
					if ( $p->action == 'insert' ) {
						$set = SyncSetFactory::create($p->item_type, $db, $fid);
						if ( $p->left_id ) {
							$lo = $set->fetchLeftItemById($p->left_id);
							
							if ($p->item_type != 'trainer')
								$ro = $set->fetchFieldMatch($lo);
							else
								$ro = $set->fetchRightItemByUuid($lo->uuid);
							
							if ( !$ro ) {
								echo $p->item_type.' not found: '.$p->left_id."\n";
							}
						}
					}
				}
				
			} else {
				$has_errors = $msg;
			}
		} catch (Exception  $e) {
			$has_errors = $e->getMessage();
		}
		try {
			if ( !$msg ) {
				echo "find diffs\n";
				//$has_errors = $syncCompare->findDifferencesProcess();
			} else {
				$has_errors = $msg;
			}
		} catch (Exception  $e) {
			$has_errors = $e->getMessage();
		}

		var_dump($has_errors);
		
	}