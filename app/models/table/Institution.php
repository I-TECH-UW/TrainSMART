<?php 
require_once('ITechTable.php');
require_once('Helper.php');

class Institution extends ITechTable
{
	protected $_name 				= 'institution';
	protected $_cadrelink 			= 'link_cadre_institution';
	protected $_degreelink 			= 'link_institution_degrees';
	protected $_institutiontypelink	= 'link_institution_institutiontype';

	public function Addinstitution($param) {
#		print_r ($param);
#		die;
		$db = $this->dbfunc();

		if (!is_numeric($param['instype'])){
			# ADDED A NEW INSTITUTE TYPE
			$insert = array (
				'typename' => $param['instype'],
			);
			$insertresult = $db->insert('lookup_institutiontype',$insert);
			$id = $db->lastInsertId();
			$param['instype'] = $id;
		}

		if (!is_numeric($param['sponser'])){
			# ADDED A NEW INSTITUTE TYPE
			$insert = array (
				'sponsorname' => $param['sponser'],
			);
			$insertresult = $db->insert('lookup_sponsors',$insert);
			$id = $db->lastInsertId();
			$param['sponser'] = $id;
		}

		$insert = array(
			'institutionname'	=>	$param['instutitonname'],
			'type'				=>	$param['instype'],
			'address1'			=>	$param['address1'],
			'address2' 			=>	$param['address2'],
			'city' 				=>	$param['city'],
			'geography1'		=>	$param['province_id'],
			'geography2'		=>	(isset($param['district_id']) ? $param['district_id'] : 0),
			'geography3'		=>	(isset($param['region_c_id']) ? $param['region_c_id'] : 0),
			'postalcode'		=>	$param['postalcode'],
			'phone'				=>	$param['phone'],
			'fax'				=>	$param['fax'],
			'sponsor'			=>	$param['sponser'],
			'degrees'			=>	0,
			'computercount'		=>	$param['computer'],
			'tutorcount'		=>	$param['tutor'],
			'studentcount'		=>	$param['students'],
			'hasdormitories'	=>	$param['hasdormitories'],
			'dormcount'			=>	$param['dormcount'],
			'tutorhousing'		=>	$param['tutorhousing'],
			'tutorhouses'		=>	$param['tutorhouses'],
			'bedcount'			=>	$param['studbeds'],
			'yearfounded'		=>	$param['yearfound'],
			'comments'			=>	$param['comments']
		);
	
		$rowArray = $this->dbfunc()->insert($this->_name,$insert);
		$id = $this->dbfunc()->lastInsertId(); 
		
		# Linking this institution to the user, if necessary
		// Getting current credentials
		$auth = Zend_Auth::getInstance ();
		$identity = $auth->getIdentity ();

		$helper = new Helper();
		$helper->addUserInstitutionRights($identity->id,$id);

		# LINKING UP CADRES
		if ((is_array ($param['cadre'])) && (count ($param['cadre']) > 0)){
			foreach ($param['cadre'] as $key=>$val){
				$c_arr = array(
					'id_cadre' 			=> $val,
					'id_institution'	=> $id
				);
				$cadreinsert = $this->dbfunc()->insert($this->_cadrelink,$c_arr);
			}
		}


		# LINKING UP DEGREES
		if ((is_array ($param['degreetypeid'])) && (count ($param['degreetypeid']) > 0)){
			foreach ($param['degreetypeid'] as $key=>$val){
				$c_arr = array(
					'id_degree' 		=> $val,
					'id_institution'	=> $id
				);
				$cadreinsert = $this->dbfunc()->insert($this->_degreelink,$c_arr);
			}
		}
		return $id;
	}

	public function Editinstitute($instituteid) {
		$db = $this->dbfunc();
		
		$query = "select * from institution where id = '" . $instituteid . "'";
#		echo ($query);
		$select=$db->query($query);
		$row = $select->fetch();
		return $row;	
	}

	public function Listinstitute($fetch) {
		$db = $this->dbfunc();
		$select=$db->query("select * from institution ");
		$row = $select->fetchAll();
		return $row;	
	}

	public function ListCadre($getid) {
		$select = $this->dbfunc()->select()
			->from($this->_cadre)
			->where('id_institution = ?',$getid);
		$row = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $row;	
	}

	public function Updateinstitute($param) {
		$db = $this->dbfunc();
		
		if (!is_numeric($param['instype'])){
			# ADDED A NEW INSTITUTE TYPE
			$insert = array (
				'typename' => $param['instype'],
			);
			$insertresult = $db->insert('lookup_institutiontype',$insert);
			$id = $db->lastInsertId();
			$param['instype'] = $id;
		}

		if (!is_numeric($param['sponser'])){
			# ADDED A NEW INSTITUTE TYPE
			$insert = array (
				'sponsorname' => $param['sponser'],
			);
			$insertresult = $db->insert('lookup_sponsors',$insert);
			$id = $db->lastInsertId();
			$param['sponser'] = $id;
		}


		$data = array(
			'institutionname'	=>	$param['instutitonname'],
			'type'				=>	$param['instype'],
			'address1'			=>	$param['address1'],
			'address2' 			=>	$param['address2'],
			'city' 				=>	$param['city'],
			'geography1'		=>	$param['province_id'],
			'geography2'		=>	(isset($param['district_id']) ? substr($param['district_id'],(strrpos($param['district_id'],"_") + 1)) : 0),
			'geography3'		=>	(isset($param['region_c_id']) ? substr($param['region_c_id'],(strrpos($param['region_c_id'],"_") + 1)) : 0),
			'postalcode'		=>	$param['postalcode'],
			'phone'				=>	$param['phone'],
			'fax'				=>	$param['fax'],
			'sponsor'			=>	$param['sponser'],
			'computercount'		=>	$param['computer'],
			'tutorcount'		=>	$param['tutor'],
			'studentcount'		=>	$param['students'],
			'hasdormitories'	=>	$param['hasdormitories'],
			'dormcount'			=>	$param['dormcount'],
			'tutorhousing'		=>	$param['tutorhousing'],
			'tutorhouses'		=>	$param['tutorhouses'],
			'bedcount'			=>	$param['studbeds'],
			'yearfounded'		=>	$param['yearfound'],
			'comments'			=>	$param['comments'],
			'degreetypeid'		=>	$param['degreetypeid'],
		);	 

#var_dump ($data);
#exit;

		$id = $param['editid'];

		$db->update('institution', $data, "id = '".$param['editid']."'");

		$helper = new Helper();

		# LINKING UP CADRES
		$helper->setExternalValues($this->_cadrelink,"id_institution","id_cadre",isset($param['cadre']) ? $param['cadre'] : false,$id);

		# LINKING UP CADRES
		$helper->setExternalValues($this->_degreelink,"id_institution","id_degree",isset($param['degreetypeid']) ? $param['degreetypeid'] : false,$id);

		# LINKING UP DEGREES
#		$helper->setExternalValues("link_institution_degrees","id_institution","id_degree",isset($param['degree']) ? $param['degree'] : false,$id);


		return $data;	
	}

	public function InstitutionSearch($param) {

		$output = array();
		$select = $this->dbfunc()->select()
			->from(array('i' => $this->_name),
				array ('id', 'institutionname','geography1','geography2','geography3'))
			->joinLeft(array('t' => 'lookup_institutiontype'),
				'i.type = t.id',
				array('typename'));
#		if ((isset($param['geo1'])) && (is_numeric($param['geo1'])) && ($param['geo1'] > 0)){
			$select->joinLeft(array('l1' => 'location'),
				'l1.id = i.geography1',
				array("geo1" => 'location_name'));
#		}
#		if ((isset($param['geo2'])) && (is_numeric($param['geo2'])) && ($param['geo2'] > 0)){
			$select->joinLeft(array('l2' => 'location'),
				'l2.id = i.geography2',
				array("geo2" => 'location_name'));
#		}
#		if ((isset($param['geo3'])) && (is_numeric($param['geo3'])) && ($param['geo3'] > 0)){
			$select->joinLeft(array('l3' => 'location'),
				'l3.id = i.geography3',
				array("geo2" => 'location_name'));
#		}


		$helper = new Helper();
		$institutions = $helper->getUserInstitutions($helper->myid(),false);
		if ((is_array($institutions)) && (count($institutions) > 0)){
			$insids = implode(",", $institutions);
			$select->where('i.id IN (' . $insids . ')');
		}

		if (trim($param['name']) != ""){
			$select->where('institutionname like ?', "%".$param['name']."%");
		}
		if ((isset($param['geo1'])) && (is_numeric($param['geo1'])) && ($param['geo1'] > 0)){
			$select->where('geography1 = ?', "".$param['geo1']."");
			if ((isset($param['geo2'])) && (is_numeric($param['geo2'])) && ($param['geo2'] > 0)){
				$select->where('geography2 = ?', $param['geo2']);
				if ((isset($param['geo3'])) && (is_numeric($param['geo3'])) && ($param['geo3'] > 0)){
					$select->where('geography3 = ?', $param['geo3']);
				}
			}
		}
		if ($param['sponsor'] != 0){
			$select->where('sponsor = ?', $param['sponsor']);
		}
		if ($param['instype'] != 0){
			$select->where('t.id = ?', $param['instype']);
		}

		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}
	
	public function listStaff($insid){

		$select = $this->dbfunc()->select()
			->from(array('p' => "person"), 
				array('id','first_name','last_name','phone_work','email'))
			->join(array('t' => 'tutor'),
				'p.id = t.personid',
				array("tutorid"=>"id"))
			->join(array('l' => 'link_tutor_institution'),
				'l.id_tutor = t.id',
				array())
			->joinLeft(array('pt' => 'person_title_option'),
				'pt.id = p.title_option_id',
				array('pt.title_phrase'))
			->where('l.id_institution = ?', $insid);
		#die( $select->__toString());
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}
	
	public function getStudentCount($insid){
		$select = $this->dbfunc()->select()
			->from(array("s" => "student"),
				array("totalcount" => "COUNT(*)"))
			->join(array("l" => "link_student_cohort"),
				"l.id_student = s.id",
				array())
			->join(array("c" => "cohort"),
				"l.id_cohort = c.id",
				array())
			->where("c.institutionid = ?", $insid)
			->where("l.dropdate = '0000-00-00'")
			->where("c.graddate >= '" . date("Y-m-d") . "'")
			->where("c.startdate <= '" . date("Y-m-d") . "'");
		//echo $select->__toString() . "<br>";
		$result = $this->dbfunc()->fetchAll($select);
		return $result[0]['totalcount'];
	}
	
	public function getTutorCount($insid){
		$select = $this->dbfunc()->select()
			->from(array("t" => "tutor"),
				array("totalcount" => "COUNT(*)"))
			->join(array("l" => "link_tutor_institution"),
				"l.id_tutor = t.id",
				array())
			->where("l.id_institution = ?", $insid);
		$result = $this->dbfunc()->fetchAll($select);
		return $result[0]['totalcount'];
	}

	public function updateStaff(){
		print_r ($_POST);
		# LINKING UP STAFF
		$helper = new Helper();
		$helper->setExternalValues("link_tutor_institution","id_institution","id_tutor",isset($_POST['staff']) ? $_POST['staff'] : false,$_POST['updateid']);
	}

 }
  
?>