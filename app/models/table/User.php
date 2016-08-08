<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

require_once('ITechTable.php');


class User extends ITechTable
{
    protected $_name = 'user';
	protected $_primary = 'id';

	public function insert(array $data) {
	    if ( isset($data['password']) )
	    	$data['password'] = md5($data['password']);

	    return parent::insert($data);
	}

	public function update(array $data,$where) {
	    if ( isset($data['password']) and $data['password'] )
	    	$data['password'] = md5($data['password']);

	    return parent::update($data,$where);
	}

	public function recordLogin($userRow) {
		if ( $userRow->id ) {
			$this->update(array('timestamp_last_login' => new Zend_Db_Expr('NOW()')), 'id = '.$userRow->id);
		}
	}

	public function updateLocale($locale, $id) {
		if ( $id ) {
			$this->update(array('locale' => $locale), 'id = '.$id);
		}
	}

    public function createAuthIdentity($userRow)
        {
            $identity = new stdClass;
            $identity->id = $userRow->id;
            $identity->username = $userRow->username;
            $identity->first_name = $userRow->first_name;
            $identity->last_name = $userRow->last_name;
            $identity->email = $userRow->email;
            $identity->locale = $userRow->locale;

            return $identity;
        }

 	static public function isUnique($username = false, $email = false) {
		$rtn = array();

		$userTable = new User();
    	$select = $userTable->select();

    	if ( $username )
    		$select->orWhere("username = ?",$username);

    	if ( $email )
    		$select->orWhere("email = ?",$email);

      	$rowset = $userTable->fetchAll($select);
		foreach ($rowset as $row) {
			if ( $row->email == $email ) {
				$rtn['email'] = 'found';
			}

			if ( $row->username == $username ) {
				$rtn['username'] = 'found';
			}
		}

		return $rtn;
 	}

 	public function hasPS($userid) {
 		// CHECK IF USER HAS PRE-SERVICE ACCESS
		$db = Zend_Db_Table_Abstract::getDefaultAdapter (); 
		$select = $db->query("select * from user_to_acl WHERE user_id = " . $userid . " AND acl_id = 'pre_service'");
		$row = $select->fetch();
		if ($row !== false){
			return true;
		} else {
			return false;
		}
 	}

 	public function hasIS($userid) {
 		// CHECK IF USER HAS IN-SERVICE ACCESS
		$db = Zend_Db_Table_Abstract::getDefaultAdapter (); 
		$select = $db->query("select * from user_to_acl WHERE user_id = " . $userid . " AND acl_id = 'in_service'");
		$row = $select->fetch();
		if ($row !== false){
			return true;
		} else {
			return false;
		}
 	}

 	/**
 	 * Called by ITechController
 	 * To view ACLs in an action function use Zend_Auth::getInstance()->getIdentity()->acls; or ITechController::_getACLs();
 	 */
 	static public function getACLs($user_id) {
	    $rtn = array();
		if ( $user_id ) {
			$userTable = new User();
	    	$select = $userTable->select()->setIntegrityCheck(false);
	    	$select->join(array('uacl' => 'user_to_acl'), 'uacl.user_id = user.id', 'uacl.acl_id');
	    	$select->where('uacl.user_id = ?',$user_id);

	      	$rowset = $userTable->fetchAll($select);
			foreach ($rowset as $row) {
				$rtn []= $row->acl_id;
			}
		}

		return $rtn;

 	}
 	
 	//TA:97
 	public function getUserFullName($user_id) {
 	    $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
 	    $select = $db->query("select first_name, last_name from user WHERE id = " . $user_id);
 	    $row = $select->fetch();
 	    return $row['first_name'] . " " . $row['last_name'];
 	}
}
