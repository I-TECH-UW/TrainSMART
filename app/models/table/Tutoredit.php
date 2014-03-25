<?php

require_once('ITechTable.php');
require_once('Helper.php');

class Tutoredit extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'tutor';
	protected $_title = 'person_title_option';
	protected $_tutor = 'tutor';
	protected $_facility = 'facility';
	protected $_city = 'location_city';

	public function EditTutor($teacherid) {
		# MAKING MULTI DIMENSION OUTPUT FOR MULTIPLE TABLES
		$output = array();

		# GETTING BASIC PERSON RECORD
		$select = $this->dbfunc()->select()
			->from('person')
			->where('id = ?',$teacherid);
		$row = $this->dbfunc()->fetchAll($select);
		$output['person'] = $row;

		# GETTING TUTOR RECORD
		$select = $this->dbfunc()->select()
			->from('tutor')
			->where('personid = ?',$teacherid);
		$row = $this->dbfunc()->fetchAll($select);
		$output['tutor'] = $row;

		# GETTING PERMANENT ADDRESS
		$select = $this->dbfunc()->select()
			->from(array('a' => 'addresses'),
					array('address1','address2','city','postalcode','state','country','id_addresstype','id_geog1','id_geog2','id_geog3'))
			->join(array('l' => 'link_tutor_addresses'),
					'a.id = l.id_address')
			->where('l.id_tutor = ?',$output['tutor'][0]['id'])
			->where('a.id_addresstype = 1');
		$row = $this->dbfunc()->fetchAll($select);
		$output['permanent_address'] = $row;

		return $output;
	}

	public function ViewTutor($teacherid) {
		$select = $this->dbfunc()->select()
			->from('tutor')
			->where('personid = ?',$teacherid);
		$row = $this->dbfunc()->fetchAll($select);
		// echo $select->__toString();
		return $row;
	}

	public function PeopleFacility(){
		$select = $this->dbfunc()->select()
			->from($this->_facility);

		//echo $select->__toString();
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	public function ListTitle(){
		$select = $this->dbfunc()->select()
			->from('person_title_option');
		$result = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $result;
	}

	public function ListCadre(){
		$select = $this->dbfunc()->select()
			->from('cadres');
		$result = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $result;
	}

	//For Lisiting Selected  Title
	public function EditTitle($titleid){
		$select = $this->dbfunc()->select()
			->from($this->_title)
			->where('id = ?',$titleid);
		$result = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $result;
	}

	//For Lisiting Selected  Title
	public function EditFacility($fakeid){
		$select = $this->dbfunc()->select()
			->from($this->_facility)
			->where('id = ?',$fakeid);
		$result = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $result;
	}

	//For Lisiting City
	public function ListCity(){
		$select = $this->dbfunc()->select()
			->from($this->_city);
		$result = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $result;
	}

	public function UpdatePerson($param){
		$datepick=$param['dob'];
		$dobymd=date("Y-m-d",strtotime($datepick));
		$db = $this->dbfunc();
		$data = array(
			"title_option_id"	=>	$param['title'],
			'first_name'		=>	$param['firstname'],
			'middle_name'		=>	$param['middlename'],
			'last_name'			=>	$param['lastname'],
			'gender'			=>	$param['gender'],
			'birthdate'			=>	$dobymd,
			'home_address_1'	=>	$param['localaddress1'],
			'home_address_2'	=>	$param['localaddress2'],
			'home_postal_code'	=>	$param['localpostalcode'],
			'home_is_residential' => $param['localisresidential'],
			'email'				=>	$param['email'],
			'email_secondary'	=>	$param['email_secondary'],
			'phone_work'		=>	$param['localphone'],
			'national_id'		=>	$param['nationalid'],
			'phone_mobile'		=>	$param['localcell'],
			'phone_mobile_2'	=>	$param['localcell2'],
			'national_id'		=>	$param['nationalid']

			//'home_location_id'=>"$param[city]"
		);
		//print_r($data);

		$db->update('person',$data,"id = '".$param['id']."'");
		return $data;
	}

	public function UpdateTutor($param){
		$db = $this->dbfunc();

		if (!is_numeric($param['degree'])){
			# ADDED A NEW DEGREE
			$insert = array (
				'degree' => $param['degree'],
			);
			$cadreinsert = $db->insert('lookup_degrees',$insert);
			$id = $db->lastInsertId();
			$param['degree'] = $id;
		}

		$tutor = array (
			'tutorsince'		=>	$param['tutorsince'],
			'tutortimehere'		=>	$param['tutortimehere'],
			'degree'			=>	$param['degree'],
			'degreeinst'		=>	$param['degreeinst'],
			'degreeyear'		=>	$param['degreeyear'],
			'nationalityid'		=>	$param['nationality'],
			'positionsheld'		=>	$param['position'],
			'comments'			=>	$param['comments'],
			'facilityid'		=>	$param['facilityid'],
			'cadreid'			=>	$param['cadreid'],
		);

		$db->update('tutor',$tutor,"personid = '".$param['id']."'");

		$select = $this->dbfunc()->select()
			->from("tutor")
			->where("personid = ?", $param['id']);
		$result = $this->dbfunc()->fetchAll($select);
		$tutorid = $result[0]['id'];


		$address = array(
			'address1'			=>	$param['address1'] ? $param['address1'] : "",
			'address2'			=>	$param['address2'] ? $param['address2'] : "",
			'postalcode'		=>	$param['postalcode'] ? $param['postalcode'] : "",
			"id_geog1"			=>	$this->getRegionLastValue($param['province_id']),
			"id_geog2"			=>	$this->getRegionLastValue($param['district_id']),
			"id_geog3"			=>	$this->getRegionLastValue($param['region_c_id']),
			'city'				=>	$param['city'] ? $param['city'] : "",
			'id_addresstype'	=>	1,
		);

		$updAddressTableID = null; // success on update
		$ids = $db->fetchRow('SELECT id,id_address FROM link_tutor_addresses WHERE id_tutor = ?', $tutorid);
		$link_id = $ids['id'] or null;
		$addressid = $ids['id_address'] or null;

		if ($link_id) {
			$updAddressTableID = $db->update('addresses', $address, "id = '".$addressid."' AND id_addresstype = 1");
		} else {
			$db->insert("addresses", $address);
			$addressid = $db->lastInsertId();
		}

		echo "updrow: $addressid <br>";

		# LINKING ADDRESS TO TUTOR
		$link = array(
			"id_tutor"		=>	$tutorid,
			"id_address"	=>	$addressid,
		);

		if ($link_id) {
			$db->update('link_tutor_addresses',$link,"id = $link_id");
		} else {
			$db->insert("link_tutor_addresses", $link);
		}

		$helper = new Helper();
		$helper->setExternalValues("link_tutor_languages","id_tutor","id_language", isset($param['languagesspoken']) ? $param['languagesspoken'] : false,$tutorid);
		$helper->setExternalValues("link_tutor_tutortype","id_tutor","id_tutortype",isset($param['tutortype']) ? $param['tutortype'] : false,$tutorid);
/*

		if ((!$originalvar) || (!is_array ($originalvar)) || (count ($originalvar) == 0)){
			# REMOVING ALL INSTITUTION TYPE LINKS
			$query = "DELETE FROM " . $linktable . " WHERE " . $maincolumn . " = " . $id;
			$this->dbfunc()->query($query);
		} else {
			$languagesspoken = implode(",", $param['languagesspoken']);

			# REMOVING OLD LINKS NO LONGER SELECTED
			$query = "DELETE FROM " . $linktable . " WHERE " . $maincolumn . " = " . $id . " AND " . $linkcolumn . " NOT IN (" . $implodedvar . ")";
			$this->dbfunc()->query($query);

			# ADDING NEW LINKS THAT WERE ADDED
			foreach ($originalvar as $key=>$val){
				$select = $this->dbfunc()->select()
					->from($linktable)
					->where($maincolumn . ' = ?', $id)
					->where($linkcolumn . ' = ?', $val);
				$result = $this->dbfunc()->fetchAll($select);
				if (count ($result) == 0){
					# LINK NOT FOUND - ADDING
					$i_arr = array(
						$linkcolumn => $val,
						$maincolumn	=> $id
					);
					$instypeinsert = $this->dbfunc()->insert($linktable,$i_arr);
				}
			}
		}

*/


		return $tutor;

	}


	public function SelectCadre($tutorid){
		$db = $this->dbfunc();
		$select = $this->dbfunc()->select()
			->from('link_cadre_tutor')
			->where('id_tutor = ?',$tutorid);
		$result = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $result;
	}

	public function InsertCadre($param){
		$db = $this->dbfunc();
		$insert = array ('id_cadre'=>"$param[cadre]",
			'id_tutor'=>"$param[tutorid]"
		);
		//print_r($insert);
		$cadreinsert = $db->insert('link_cadre_tutor',$insert);
		$id = $db->lastInsertId();
		return $id;
	}

	public function UpdateCadre($param){
		$db = $this->dbfunc();
		$cadre = array ('id_cadre'=>"$param[cadre]",
			'id_tutor'=>"$param[tutorid]"
		);
		$db->update('link_cadre_tutor',$cadre,"id_tutor = '".$param['tutorid']."'");
		return $cadre;
	}

}

?>