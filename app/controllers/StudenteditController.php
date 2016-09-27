<?php
require_once ('ITechController.php');
require_once ('models/table/Peopleadd.php');
require_once ('models/table/Studentedit.php');
require_once ('models/table/Helper.php');
require_once ('models/table/System.php');

class StudenteditController extends ITechController
{
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		parent::__construct($request, $response, $invokeArgs);
	}

	public function init()
	{
	}

	public function preDispatch()
	{
		parent::preDispatch();

		if (!$this->isLoggedIn())

			$this->doNoAccessError();

	}

	public function studenteditAction()
	{

		$this->view->assign('title', $this->view->translation['Application Name']);
		$status = ValidationContainer::instance();

		$params = $this->getAllParams();
		if (isset($params['addpeople'])) {

			$peopleadd = new Peopleadd();
			 //TA:87 !!!! DO NOT REMOVE: in PS add people for duplicat chjeck used first_name and last_name
			$params['firstname'] = $params['first_name'];
			$params['lastname'] = $params['last_name'];
			
			$pupiladd = $peopleadd->peopleadd($params);

			if ($pupiladd) {// sucess
				if ($params['type'] == 'tutor' || $params['type'] == 'key') {
					$this->_redirect('tutoredit/tutoredit/id/' . $pupiladd);
					exit;
				}
				$status->setStatusMessage(t('The person was saved.'));
				$_SESSION['status'] = t('The person was saved.');
				$this->_redirect('studentedit/personview/id/' . $pupiladd);
				#echo "<script language=\"JavaScript\">location.replace('../personview/id/" . $pupiladd . "');</script>";
				#$_SESSION['status'] = 'The person was saved';
			} else {
				$status->setStatusMessage(t('That person could not be saved.'));
				$_SESSION['status'] = t('That person could not be saved.');
				$this->_redirect('peopleadd/peopleadd');
			}


			exit;

			//print_r($pupiladd);
			$this->viewAssignEscaped('locations', Location::getAll());
			$studentedit = new Studentedit();
			$details = $studentedit->EditStudent($pupiladd);

			// For Geo Listing
			$listgeo = $studentedit->ViewStudent($pupiladd);
			$this->view->assign('getgeo', $listgeo);
			//print_r($listgeo);

			$geo1 = $listgeo[0]['geog1'];
			$geo2 = $listgeo[0]['geog2'];
			$geo3 = $listgeo[0]['geog3'];

			$this->view->assign('selgeo1', $geo1);
			$this->view->assign('selgeo2', $geo2);
			$this->view->assign('selgeo3', $geo3);
			$this->view->assign('enrolldate', $listgeo[0]['enrollmentdate']);
			$this->view->assign('enrollreason', $listgeo[0]['enrollmentreason']);
			$this->view->assign('seprationreason', $listgeo[0]['separationdate']);
			$this->view->assign('sepration', $listgeo[0]['separationreason']);
			$this->view->assign('study', $listgeo[0]['yearofstudy']);

			//For Title List
			$listtitle = $studentedit->ListTitle();
			$this->view->assign('gettitle', $listtitle);

			//For city List
			$listcity = $studentedit->ListCity();
			$this->view->assign('getcity', $listcity);

			//For Selected Title
			$titleid = $details[0]['title_option_id'];
			$fetchtitle = $studentedit->EditTitle($titleid);
			$title = $fetchtitle[0]['title_phrase'];
			$this->view->assign('selid', $fetchtitle[0]['id']);
			$this->view->assign('seltitle', $title);


			//For Selected City
			$cityid = $details[0]['home_location_id'];
			$fetchcity = $studentedit->EditCity($cityid);
			$city = $fetchcity[0]['city_name'];
			$this->view->assign('selcity', $city);

			$date = $details[0]['birthdate'];
			$dob = date("d-m-Y", strtotime($date));

			$this->view->assign('id', $pupiladd);
			$this->view->assign('studentid', $details[0]['first_name']);
			$this->view->assign('firstname', $details[0]['first_name']);
			$this->view->assign('lastname', $details[0]['last_name']);
			$this->view->assign('gender', $details[0]['gender']);
			$this->view->assign('dob', $dob);
			$this->view->assign('address1', $details[0]['home_address_1']);
			$this->view->assign('address2', $details[0]['home_address_2']);
			$this->view->assign('city', $details[0]['city']);
			$this->view->assign('zip', $details[0]['home_postal_code']);

			//$this->view->assign('action','../../studentedit/studentupdate/'.$pupiladd);
		}


		if (isset($params['update'])) {
			$studentupdate = new Studentedit();
			$update = $studentupdate->UpdatePerson($params);

			$pupiladd = $params['id'];
			$sid = $params['sid'];

			$this->viewAssignEscaped('locations', Location::getAll());
			$studentedit = new Studentedit();
			$details = $studentedit->EditStudent($pupiladd);

			// For Geo Listing
			$listgeo = $studentedit->ViewStudent($pupiladd);
			$this->view->assign('getgeo', $listgeo);


			$studid = $listgeo[0]['id'];
			$this->view->assign('stdid', $studid);

			$date = $update['birthdate'];
			$dob = date("d-m-Y", strtotime($date));

			$this->view->assign('newid', $pupiladd);
			$this->view->assign('firstname', $update['first_name']);
			$this->view->assign('lastname', $update['last_name']);
			$this->view->assign('gender', $update['gender']);
			$this->view->assign('dob', $dob);
			$this->view->assign('address1', $update['home_address_1']);
			$this->view->assign('address2', $update['home_address_2']);
			$this->view->assign('city', $update['city']);
			$this->view->assign('zip', $update['home_postal_code']);
			$this->view->assign('enroll', $update['home_address_2']);
			$this->view->assign('city', $update['city']);
			$this->view->assign('email', $update['email']);
			$this->view->assign('email2', $update['email_secondary']);
			$this->view->assign('titid', $update['title_option_id']);
			$this->view->assign('phone', $update['phone_work']);
			$this->view->assign('cell', $update['phone_mobile']);
			//TA: there is no 'phone_mobile_2' column in PERSON table -> link it 'phone_home' column
			//$this->view->assign('cell2',$update['phone_mobile_2']);
			$this->view->assign('cell2', $update['phone_home']);
			$this->view->assign('cadre', $update['cadre']);

			$geo1 = $listgeo[0]['geog1'];
			$geo2 = $listgeo[0]['geog2'];
			$geo3 = $listgeo[0]['geog3'];

			$this->view->assign('selgeo1', $geo1);
			$this->view->assign('selgeo2', $geo2);
			$this->view->assign('selgeo3', $geo3);
			$this->view->assign('enrolldate', $listgeo[0]['enrollmentdate']);
			$this->view->assign('enrollreason', $listgeo[0]['enrollmentreason']);
			$this->view->assign('seprationreason', $listgeo[0]['separationdate']);
			$this->view->assign('sepration', $listgeo[0]['separationreason']);
			$this->view->assign('study', $listgeo[0]['yearofstudy']);

			//For Title List
			$listtitle = $studentedit->ListTitle();
			$this->view->assign('gettitle', $listtitle);


			//For city List
			$listcity = $studentedit->ListCity();
			$this->view->assign('getcity', $listcity);


			//For Selected Title
			/*$titleid =  $details[0]['title_option_id'];
			$fetchtitle=$studentedit->EditTitle($titleid);*/

			$title = $fetchtitle[0]['title_phrase'];
			$this->view->assign('selid', $fetchtitle[0]['id']);
			$this->view->assign('seltitle', $title);


			//For Selected City
			$cityid = $details[0]['home_location_id'];
			$fetchcity = $studentedit->EditCity($cityid);
			$city = $fetchcity[0]['city_name'];
			$this->view->assign('selcity', $city);

			$cadre = $listgeo[0]['cadre'];

			$updatestud = $studentupdate->UpdateStudent($params);

			$enroll = $updatestud['enrollmentdate'];
			$enrolldate = date("d-m-Y", strtotime($enroll));

			$seprate = $updatestud['separationdate'];
			$seprationdate = date("d-m-Y", strtotime($seprate));


			$this->view->assign('grade', $updatestud['isgraduated']);
			$this->view->assign('enrollmentdate', $enrolldate);
			$this->view->assign('enrollmentreason', $updatestud['enrollmentreason']);
			$this->view->assign('separation', $seprationdate);
			$this->view->assign('separationreason', $updatestud['separationreason']);
			$this->view->assign('study', $updatestud['yearofstudy']);
			$this->view->assign('comments', $updatestud['comments']);
			$this->view->assign('cadre', $updatestud['cadre']);

			#print_r($updatehttps://www.google.com/webhp?tab=ww&ei=f-EkU5C1B9HmoAS864LoCg&ved=0CBQQ1S4stud);

			$updatecadre = $studentupdate->UpdateCadre($params);
			$this->view->assign('id', $pupiladd);
			$this->view->assign('cadrename', $updatecadre['cadrename']);

			$this->view->assign('success', 'Update succesfully');
		}
		$this->view->assign('status', ValidationContainer::instance());
	}

	public function personviewAction()
	{
		if ($this->hasACL('edit_studenttutorinst')) {
			$instid = $this->getSanParam('id');
			if ($this->setting('site_style') == 'skillsmart') {
				$this->_redirect("studentedit/skillsmart-chw-student-edit/id/".$instid);
			}
			else {
				$this->_redirect("studentedit/personedit/id/" . $instid);
			}
		}


		require_once('models/table/Studentedit.php');
		$request = $this->getRequest();
		$helper = new Helper();
		$params = $this->getAllParams();
		if (isset($params['licenseaction'])) {
			$helper->updateStudentLicense($request->getparam('sid'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $request->getparam('id'));
		}
		if (isset($params['classaction'])) {
			$helper->updateStudentClass($request->getparam('sid'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $request->getparam('id'));
		}
		if (isset($params['practicumaction'])) {
			$helper->updateStudentPracticum($request->getparam('sid'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $request->getparam('id'));
		}


		// UPDATING FIRST, SO WE WILL LATER RETRIEVE UPDATED VALUES
		if (isset($params['personupdate'])) {
			require_once('models/table/Studentedit.php');

			$studentupdate = new Studentedit();
			$update = $studentupdate->UpdatePerson($params);                        # STORING PERSON RECORD
			$update = $studentupdate->UpdateStudent($params);                    # STORING STUDENT RECORD
			$update = $studentupdate->UpdateStudentCohort($params);                # STORING COHORT LINK
			$update = $studentupdate->UpdatePermanentAddress($params);            # STORING PERMANENT ADDRESS
			$update = $studentupdate->UpdateFunding($params);                    # STORING FUNDING

			ValidationContainer::instance()->setStatusMessage(t('The person was saved.'));
			$_SESSION['status'] = t('The person was saved');
		}


		$pupiladd = $request->getParam('id'); // Sean: This is personid, not student id !!!!!!!!


		$this->view->assign('id', $pupiladd);

		$this->viewAssignEscaped('locations', Location::getAll());
		$studentedit = new Studentedit();
		$details = $studentedit->EditStudent($pupiladd);
		$sid = $details['student'][0]['id'];
		$this->view->assign('id', $sid);

		$date = $details['person'][0]['birthdate'];
		$dob = date("d-m-Y", strtotime($date));

		$this->view->assign('id', $pupiladd);
		$this->view->assign('sid', $sid);
		$this->view->assign('selid', $details['person'][0]['title_option_id']);
		$this->view->assign('firstname', $details['person'][0]['first_name']);
		$this->view->assign('middlename', $details['person'][0]['middle_name']);
		$this->view->assign('lastname', $details['person'][0]['last_name']);
		$this->view->assign('gender', $details['person'][0]['gender']);
		$this->view->assign('dob', $dob);
		$this->view->assign('localgeo1', $details['student'][0]['geog1']);
		$this->view->assign('localgeo2', $details['student'][0]['geog2']);
		$this->view->assign('localgeo3', $details['student'][0]['geog3']);
		$this->view->assign('address1', $details['person'][0]['home_address_1']);
		$this->view->assign('address2', $details['person'][0]['home_address_2']);
		$this->view->assign('city', $details['person'][0]['home_city']);
		$this->view->assign('zip', $details['person'][0]['home_postal_code']);
		$this->view->assign('home_is_residential', $details['person'][0]['home_is_residential']);
		$this->view->assign('enroll', $details['person'][0]['home_address_2']);
		$this->view->assign('email', $details['person'][0]['email']);
		$this->view->assign('email2', $details['person'][0]['email_secondary']);
		$this->view->assign('titid', $details['person'][0]['title_option_id']);
		$this->view->assign('phone', $details['person'][0]['phone_work']);
		$this->view->assign('cell', $details['person'][0]['phone_mobile']);
		$this->view->assign('cell2', $details['person'][0]['phone_home']);
		$this->view->assign('nationalid', $details['person'][0]['national_id']);
		$this->view->assign('graduated', $details['student'][0]['isgraduated']);
		$this->view->assign('cohortid', $details['link_cohort'][0]['id_cohort']);

		//TA:6: added 8/8/2014 - 8/10/2014
		$dc = strtotime($details['person'][0]['timestamp_created']);
		$dateCreated = $dc != '' && $dc > 0 ? date("d-m-Y",$dc) : t("N/A");
		$this->view->assign('dateCreated', $dateCreated);
		$dm = strtotime($details['person'][0]['timestamp_updated']);
		$dateModified = $dm != '' && $dm >0 ? date("d-m-Y",$dm): t("N/A");
		$this->view->assign('dateModified', $dateModified);
		$this->view->assign('uuid', $details['person'][0]['uuid']);
		require_once('models/table/Person.php');
		$personObj = new Person ();
		$created_by = $details['person'][0]['created_by'] ? $personObj->getPersonName($details['person'][0]['created_by']) : t("N/A");
		$this->viewAssignEscaped('creator', $created_by);
		$update_by = $details['person'][0]['modified_by'] ? $personObj->getPersonName($details['person'][0]['modified_by']) : t("N/A");
		$this->viewAssignEscaped('updater', $update_by);

		$this->view->assign('facilities', $helper->getFacilities());

		if ($details['link_cohort'][0]['id_cohort'] != 0) {
			//$this->view->assign('allclasses',$helper->listcurrentclasses($details['link_cohort'][0]['id_cohort']));
			//$this->view->assign('allpracticum',$helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort']));
			//$this->view->assign('alllicenses',$helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort']));

			$this->view->assign('currentclasses', $helper->listcurrentclasses($details['link_cohort'][0]['id_cohort'], $sid));
			$this->view->assign('currentpracticum', $helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort'], $sid));
			$this->view->assign('currentlicenses', $helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort'], $sid));
		} else {
			$this->view->assign('currentclasses', array());
			$this->view->assign('currentpracticum', array());
			$this->view->assign('currentlicenses', array());
		}


		# PERMANENT ADDRESS
		$this->view->assign('permanentgeo1', $details['permanent_address'][0]['id_geog1']);
		$this->view->assign('permanentgeo2', $details['permanent_address'][0]['id_geog2']);
		$this->view->assign('permanentgeo3', $details['permanent_address'][0]['id_geog3']);
		$this->view->assign('permanentaddress1', $details['permanent_address'][0]['address1']);
		$this->view->assign('permanentaddress2', $details['permanent_address'][0]['address2']);
		$this->view->assign('permanentcity', $details['permanent_address'][0]['city']);
		$this->view->assign('permanentzip', $details['permanent_address'][0]['postalcode']);

		# STUDENT VIEW
		$this->view->assign('studentid', $details['student'][0]['studentid']);
		$this->view->assign('studenttype', $details['student'][0]['studenttype']);
		$this->view->assign('comments', $details['student'][0]['comments']);

		if (count($details['link_cohort']) == 0) {
			$joindate = "";
			$dropdate = "";
			$joinreason = 0;
			$dropreason = 0;
		} else {
			$joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
			$dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));
			$joinreason = $details['link_cohort'][0]['joinreason'];
			$dropreason = $details['link_cohort'][0]['dropreason'];
		}
		$this->view->assign('enrollmentdate', $joindate);
		$this->view->assign('enrollmentreason', $joinreason);
		$this->view->assign('separationdate', $dropdate);
		$this->view->assign('separationreason', $dropreason);

		$this->view->assign('cadre', $details['student'][0]['cadre']);
		$this->view->assign('advisor', $details['student'][0]['advisorid']);

		$this->view->assign('fundingsource', $details['funding'][0]['fundingsource']);
		$this->view->assign('fundingamount', $details['funding'][0]['fundingamount']);

		//TA: added 7/17/2014 - 7/25/2014
		$sysTable = new System();
		$sysRows = $sysTable->fetchAll()->toArray();
		foreach ($sysRows as $row) {
			foreach ($row as $column => $value) {
				if ($column == 'ps_display_inst_compl_date' && $value != '0') {
					$this->view->assign('label_hscomldate', $this->view->translation['ps high school completion date']);
					$this->view->assign('hscomldate', $details['student'][0]['hscomldate']);
				} else if ($column == 'ps_display_last_inst_attended' && $value != '0') {
					$this->view->assign('label_lastinstatt', $this->view->translation['ps last school attended']);
					$this->view->assign('lastinstatt', $details['student'][0]['lastinstatt']);
				} else if ($column == 'ps_display_start_school_date' && $value != '0') {
					$this->view->assign('label_schoolstartdate', $this->view->translation['ps school start date']);
					$this->view->assign('schoolstartdate', $details['student'][0]['schoolstartdate']);
				} else if ($column == 'ps_display_equivalence' && $value != '0') {
					$this->view->assign('label_equivalence', $this->view->translation['ps equivalence']);
					$this->view->assign('equivalence', $details['student'][0]['equivalence']);
				} else if ($column == 'ps_display_last_univ_attended' && $value != '0') {
					$this->view->assign('label_lastunivatt', $this->view->translation['ps last university attended']);
					$this->view->assign('lastunivatt', $details['student'][0]['lastunivatt']);
				} else if ($column == 'ps_display_person_charge' && $value != '0') {
					$this->view->assign('label_personincharge', $this->view->translation['ps person in charge']);
					$this->view->assign('personincharge', $details['student'][0]['personincharge']);
				} else if ($column == 'ps_display_custom_field1' && $value != '0') {
					$this->view->assign('label_custom_field1', $this->view->translation['ps custom field 1']);
					$this->view->assign('custom_field1', $details['person'][0]['custom_field1']);
				} else if ($column == 'ps_display_custom_field2' && $value != '0') {
					$this->view->assign('label_custom_field2', $this->view->translation['ps custom field 2']);
					$this->view->assign('custom_field2', $details['person'][0]['custom_field2']);
				} else if ($column == 'ps_display_custom_field3' && $value != '0') {
					$this->view->assign('label_custom_field3', $this->view->translation['ps custom field 3']);
					$this->view->assign('custom_field3', $details['person'][0]['custom_field3']);
				} else if ($column == 'ps_display_marital_status' && $value != '0') {
					$this->view->assign('label_marital_status', $this->view->translation['ps marital status']);
					$this->view->assign('marital_status', $details['person'][0]['marital_status']);
				} else if ($column == 'ps_display_spouse_name' && $value != '0') {
					$this->view->assign('label_spouse_name', $this->view->translation['ps spouse name']);
					$this->view->assign('spouse_name', $details['person'][0]['spouse_name']);
				} else if ($column == 'ps_display_local_address' && $value != '0') {
					$this->view->assign('label_ps_local_address', $this->view->translation['ps local address']);
				} else if ($column == 'ps_display_religious_denomin' && $value != '0') {
					$this->view->assign('label_ps_religious_denomin', $this->view->translation['ps religious denomination']);
				} else if ($column == 'ps_display_nationality' && $value != '0') {
					$this->view->assign('label_ps_nationality', $this->view->translation['ps nationality']);
				} else if ($column == 'ps_display_permanent_address' && $value != '0') {
					$this->view->assign('label_ps_permanent_address', $this->view->translation['ps permanent address']);
				}
			}
		}

		//TA: added 7/18/2014
		$this->view->assign('emergcontact', $details['student'][0]['emergcontact']);

		// POST GRADUATION FIELDS
		$this->view->assign('postgeo1', $details['student'][0]['postgeo1']);
		$this->view->assign('postgeo2', $details['student'][0]['postgeo2']);
		$this->view->assign('postgeo3', $details['student'][0]['postgeo3']);
		$this->view->assign('postaddress1', $details['student'][0]['postaddress1']);
		$this->view->assign('postfacilityname', $details['student'][0]['postfacilityname']);

		$helper = new Helper();

		$this->view->assign('joinreasons', $helper->getReasons('join'));
		$this->view->assign('dropreasons', $helper->getReasons('drop'));


		// For Title List
		$listtitle = $studentedit->ListTitle();
		$this->view->assign('gettitle', $listtitle);

		# GETTING COHORTS
		$listcohort = $studentedit->ListCohort();
		$this->view->assign('getcohorts', $listcohort);

		# GETTING CADRES
		$listcadre = $studentedit->listCadre();
		$this->view->assign('getcadres', $listcadre);

		# GETTING TUTORS
		$listtutors = $studentedit->ListTutors($details['link_cohort'][0]['id_cohort']);
		$this->view->assign('gettutors', $listtutors);

		# NATIONALITY INFO
		$helper = new Helper();
		$this->view->assign('lookupnationalities', $helper->getNationalities());
		$this->view->assign('nationalityid', $details['student'][0]['nationalityid']);

		if (count($details['link_cohort']) > 0) {
			$this->view->assign('hascohort', true);

			#$cstartdate = $details['cohort'][0]['startdate'];
			$cstartdate = $joindate;
			#echo '<pre>';var_dump($details);die();
			if ($cstartdate) {
				$joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
				$dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));

				$lastclass = $dropdate ? $dropdate : date("m/d/Y");  // seperate date or today
				$yearofstudy = intval((strtotime($lastclass) - strtotime($cstartdate)) / 31536000 + 1); // time spent in class / seconds in a year + 1 year to show 1 year if 6mo of class or whatever
			}

			$this->view->assign('yearofstudy', $cstartdate ? $yearofstudy : "");

			if (count($details['cadre']) > 0) {
				$cadre = $details['cadre'][0]['id'];
			} else {
				$cadre = 0;
			}


		} else {
			$this->view->assign('hascohort', false);
			$this->view->assign('yearofstudy', "");
			$cadre = 0;
		}

		$this->view->assign('lookupstudenttypes', $helper->getStudentTypes());
		$this->view->assign('cadre', $cadre);


		$this->view->assign('lookupfunding', $helper->getFunding());
		$this->view->assign('studentfunding', $studentedit->getStudentFunding($details['student'][0]['id']));

	}

	public function personeditAction()
	{
		if (!$this->hasACL('edit_studenttutorinst')) {
			$instid = $this->getSanParam('id');
			$this->_redirect("studentedit/personview/id/" . $instid);
		}


		require_once('models/table/Studentedit.php');
		$request = $this->getRequest();
		$helper = new Helper();

		$params = $this->getAllParams();
		if (isset($params['licenseaction'])) {
			$helper->updateStudentLicense($request->getparam('sid'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personedit/id/' . $request->getparam('id'));
		}
		if (isset($params['classaction'])) {
			$helper->updateStudentClass($request->getparam('sid'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personedit/id/' . $request->getparam('id'));
		}
		if (isset($params['practicumaction'])) {
			$helper->updateStudentPracticum($request->getparam('sid'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personedit/id/' . $request->getparam('id'));
		}


		// UPDATING FIRST, SO WE WILL LATER RETRIEVE UPDATED VALUES
		if (isset($params['personupdate'])) {
			require_once('models/table/Studentedit.php');

			$studentupdate = new Studentedit();
			$update = $studentupdate->UpdatePerson($params);                        # STORING PERSON RECORD
			$update = $studentupdate->UpdateStudent($params);                    # STORING STUDENT RECORD
			$update = $studentupdate->UpdateStudentCohort($params);                # STORING COHORT LINK
			$update = $studentupdate->UpdatePermanentAddress($params);            # STORING PERMANENT ADDRESS
			$update = $studentupdate->UpdateFunding($params);                    # STORING FUNDING

			ValidationContainer::instance()->setStatusMessage(t('The person was saved.'));
			$_SESSION['status'] = t('The person was saved');
		}

//TA:51 10/05/2015
		if (isset($_POST['persondelete'])) {
		    require_once('models/table/Studentedit.php');
		    $studentupdate = new Studentedit();
		    $studentupdate->DeleteStudent($request->getParams());
		    ValidationContainer::instance()->setStatusMessage(t('The student was deleted.'));
		    $_SESSION['status'] = t('The student was deleted');
		    $this->_redirect(Settings::$COUNTRY_BASE_URL . '/people/people');
		}


		$pupiladd = $request->getParam('id'); // Sean: This is personid, not student id !!!!!!!!


		$this->view->assign('id', $pupiladd);

		$this->viewAssignEscaped('locations', Location::getAll());
		$studentedit = new Studentedit();
		$details = $studentedit->EditStudent($pupiladd);
		$sid = $details['student'][0]['id'];
		$this->view->assign('id', $sid);


		#print_r ($details);

		$date = $details['person'][0]['birthdate'];
		$dob = $date?date("d-m-Y", strtotime($date)):'';

		$this->view->assign('id', $pupiladd);
		$this->view->assign('sid', $sid);
		$this->view->assign('selid', $details['person'][0]['title_option_id']);
		$this->view->assign('firstname', $details['person'][0]['first_name']);
		$this->view->assign('middlename', $details['person'][0]['middle_name']);
		$this->view->assign('lastname', $details['person'][0]['last_name']);
		$this->view->assign('gender', $details['person'][0]['gender']);
		$this->view->assign('dob', $dob);
		$this->view->assign('localgeo1', $details['student'][0]['geog1']);
		$this->view->assign('localgeo2', $details['student'][0]['geog2']);
		$this->view->assign('localgeo3', $details['student'][0]['geog3']);
		$this->view->assign('address1', $details['person'][0]['home_address_1']);
		$this->view->assign('address2', $details['person'][0]['home_address_2']);
		$this->view->assign('city', $details['person'][0]['home_city']);
		$this->view->assign('zip', $details['person'][0]['home_postal_code']);
		$this->view->assign('home_is_residential', $details['person'][0]['home_is_residential']);
		$this->view->assign('enroll', $details['person'][0]['home_address_2']);
		$this->view->assign('email', $details['person'][0]['email']);
		$this->view->assign('email2', $details['person'][0]['email_secondary']);
		$this->view->assign('titid', $details['person'][0]['title_option_id']);
		$this->view->assign('phone', $details['person'][0]['phone_work']);
		$this->view->assign('cell', $details['person'][0]['phone_mobile']);
		$this->view->assign('cell2', $details['person'][0]['phone_home']);
		$this->view->assign('nationalid', $details['person'][0]['national_id']);
		$this->view->assign('graduated', $details['student'][0]['isgraduated']);
		$this->view->assign('cohortid', $details['link_cohort'][0]['id_cohort']);

		//TA:6: added 8/8/2014 - 8/10/2014
		$dc = strtotime($details['person'][0]['timestamp_created']);
		$dateCreated = $dc != '' && $dc > 0 ? date("d-m-Y",$dc) : t("N/A");
		$this->view->assign('dateCreated', $dateCreated);
		$dm = strtotime($details['person'][0]['timestamp_updated']);
		$dateModified = $dm != '' && $dm >0 ? date("d-m-Y",$dm): t("N/A");
		$this->view->assign('dateModified', $dateModified);
		$this->view->assign('uuid', $details['person'][0]['uuid']);
		//TA:105 display audit box
 		require_once('models/table/User.php');
  		$userObj = new User ();
   		$created_by = $details['person'][0]['created_by'] ? $userObj->getUserFullName($details['person'][0]['created_by']) : t("N/A");
   		$this->viewAssignEscaped('creator', $created_by);
   		$update_by = $details['person'][0]['modified_by'] ? $userObj->getUserFullName($details['person'][0]['modified_by']) : t("N/A");
  		$this->viewAssignEscaped('updater', $update_by);
  		//print_r($details['person']);

		$this->view->assign('facilities', $helper->getFacilities());

		if ($details['link_cohort'][0]['id_cohort'] != 0) {
			//$this->view->assign('allclasses',$helper->listcurrentclasses($details['link_cohort'][0]['id_cohort']));
			//$this->view->assign('allpracticum',$helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort']));
			//$this->view->assign('alllicenses',$helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort']));

			$this->view->assign('currentclasses', $helper->listcurrentclasses($details['link_cohort'][0]['id_cohort'], $sid));
			$this->view->assign('currentpracticum', $helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort'], $sid));
			$this->view->assign('currentlicenses', $helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort'], $sid));
		} else {
			$this->view->assign('currentclasses', array());
			$this->view->assign('currentpracticum', array());
			$this->view->assign('currentlicenses', array());
		}


		# PERMANENT ADDRESS
		$this->view->assign('permanentgeo1', $details['permanent_address'][0]['id_geog1']);
		$this->view->assign('permanentgeo2', $details['permanent_address'][0]['id_geog2']);
		$this->view->assign('permanentgeo3', $details['permanent_address'][0]['id_geog3']);
		$this->view->assign('permanentaddress1', $details['permanent_address'][0]['address1']);
		$this->view->assign('permanentaddress2', $details['permanent_address'][0]['address2']);
		$this->view->assign('permanentcity', $details['permanent_address'][0]['city']);
		$this->view->assign('permanentzip', $details['permanent_address'][0]['postalcode']);

		# STUDENT VIEW
		$this->view->assign('studentid', $details['student'][0]['studentid']);
		$this->view->assign('studenttype', $details['student'][0]['studenttype']);
		$this->view->assign('comments', $details['student'][0]['comments']);

		if (count($details['link_cohort']) == 0) {
			$joindate = "";
			$dropdate = "";
			$joinreason = 0;
			$dropreason = 0;
		} else {
			$joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
			$dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));
			$joinreason = $details['link_cohort'][0]['joinreason'];
			$dropreason = $details['link_cohort'][0]['dropreason'];
		} 
		$this->view->assign('enrollmentdate', $joindate);
		$this->view->assign('enrollmentreason', $joinreason);
		$this->view->assign('separationdate', $dropdate);
		$this->view->assign('separationreason', $dropreason);

		$this->view->assign('cadre', $details['student'][0]['cadre']);
		$this->view->assign('advisor', $details['student'][0]['advisorid']);

		$this->view->assign('fundingsource', $details['funding'][0]['fundingsource']);
		$this->view->assign('fundingamount', $details['funding'][0]['fundingamount']);

		//TA: added 7/17/2014 - 7/25/2014
		$sysTable = new System();
		$sysRows = $sysTable->fetchAll()->toArray();
		foreach ($sysRows as $row) {
			foreach ($row as $column => $value) {
				if ($column == 'ps_display_inst_compl_date' && $value != '0') {
					$this->view->assign('label_hscomldate', $this->view->translation['ps high school completion date']);
					$this->view->assign('hscomldate', $details['student'][0]['hscomldate']);
				} else if ($column == 'ps_display_last_inst_attended' && $value != '0') {
					$this->view->assign('label_lastinstatt', $this->view->translation['ps last school attended']);
					$this->view->assign('lastinstatt', $details['student'][0]['lastinstatt']);
				} else if ($column == 'ps_display_start_school_date' && $value != '0') {
					$this->view->assign('label_schoolstartdate', $this->view->translation['ps school start date']);
					$this->view->assign('schoolstartdate', $details['student'][0]['schoolstartdate']);
				} else if ($column == 'ps_display_equivalence' && $value != '0') {
					$this->view->assign('label_equivalence', $this->view->translation['ps equivalence']);
					$this->view->assign('equivalence', $details['student'][0]['equivalence']);
				} else if ($column == 'ps_display_last_univ_attended' && $value != '0') {
					$this->view->assign('label_lastunivatt', $this->view->translation['ps last university attended']);
					$this->view->assign('lastunivatt', $details['student'][0]['lastunivatt']);
				} else if ($column == 'ps_display_person_charge' && $value != '0') {
					$this->view->assign('label_personincharge', $this->view->translation['ps person in charge']);
					$this->view->assign('personincharge', $details['student'][0]['personincharge']);
				} else if ($column == 'ps_display_custom_field1' && $value != '0') {
					$this->view->assign('label_custom_field1', $this->view->translation['ps custom field 1']);
					$this->view->assign('custom_field1', $details['person'][0]['custom_field1']);
				} else if ($column == 'ps_display_custom_field2' && $value != '0') {
					$this->view->assign('label_custom_field2', $this->view->translation['ps custom field 2']);
					$this->view->assign('custom_field2', $details['person'][0]['custom_field2']);
				} else if ($column == 'ps_display_custom_field3' && $value != '0') {
					$this->view->assign('label_custom_field3', $this->view->translation['ps custom field 3']);
					$this->view->assign('custom_field3', $details['person'][0]['custom_field3']);
				} else if ($column == 'ps_display_marital_status' && $value != '0') {
					$this->view->assign('label_marital_status', $this->view->translation['ps marital status']);
					$this->view->assign('marital_status', $details['person'][0]['marital_status']);
				} else if ($column == 'ps_display_spouse_name' && $value != '0') {
					$this->view->assign('label_spouse_name', $this->view->translation['ps spouse name']);
					$this->view->assign('spouse_name', $details['person'][0]['spouse_name']);
				} else if ($column == 'ps_display_local_address' && $value != '0') {
					$this->view->assign('label_ps_local_address', $this->view->translation['ps local address']);
				} else if ($column == 'ps_display_religious_denomin' && $value != '0') {
					$this->view->assign('label_ps_religious_denomin', $this->view->translation['ps religious denomination']);
				} else if ($column == 'ps_display_nationality' && $value != '0') {
					$this->view->assign('label_ps_nationality', $this->view->translation['ps nationality']);
				} else if ($column == 'ps_display_permanent_address' && $value != '0') {
					$this->view->assign('label_ps_permanent_address', $this->view->translation['ps permanent address']);
				}
			}
		}

		//TA: added 7/18/2014
		$this->view->assign('emergcontact', $details['student'][0]['emergcontact']);

		// POST GRADUATION FIELDS
		$this->view->assign('postgeo1', $details['student'][0]['postgeo1']);
		$this->view->assign('postgeo2', $details['student'][0]['postgeo2']);
		$this->view->assign('postgeo3', $details['student'][0]['postgeo3']);
		$this->view->assign('postaddress1', $details['student'][0]['postaddress1']);
		$this->view->assign('postfacilityname', $details['student'][0]['postfacilityname']);

		$helper = new Helper();

		$this->view->assign('joinreasons', $helper->getReasons('join'));
		$this->view->assign('dropreasons', $helper->getReasons('drop'));


		// For Title List
		$listtitle = $studentedit->ListTitle();
		$this->view->assign('gettitle', $listtitle);

		# GETTING COHORTS
		$listcohort = $studentedit->ListCohort();
		$this->view->assign('getcohorts', $listcohort);

		# GETTING CADRES
		$listcadre = $studentedit->listCadre();
		$this->view->assign('getcadres', $listcadre);

		# GETTING TUTORS
		$listtutors = $studentedit->ListTutors($details['link_cohort'][0]['id_cohort']);
		$this->view->assign('gettutors', $listtutors);

		# NATIONALITY INFO
		$helper = new Helper();
		$this->view->assign('lookupnationalities', $helper->getNationalities());
		$this->view->assign('nationalityid', $details['student'][0]['nationalityid']);

		if (count($details['link_cohort']) > 0) {
			$this->view->assign('hascohort', true);

			#$cstartdate = $details['cohort'][0]['startdate'];
			$cstartdate = $joindate;
			#echo '<pre>';var_dump($details);die();
			if ($cstartdate) {
				$joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
				$dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));

				$lastclass = $dropdate ? $dropdate : date("m/d/Y");  // seperate date or today
				$yearofstudy = intval((strtotime($lastclass) - strtotime($cstartdate)) / 31536000 + 1); // time spent in class / seconds in a year + 1 year to show 1 year if 6mo of class or whatever
			}

			$this->view->assign('yearofstudy', $cstartdate ? $yearofstudy : "");

			if (count($details['cadre']) > 0) {
				$cadre = $details['cadre'][0]['id'];
			} else {
				$cadre = 0;
			}


		} else {
			$this->view->assign('hascohort', false);
			$this->view->assign('yearofstudy', "");
			$cadre = 0;
		}

		$this->view->assign('lookupstudenttypes', $helper->getStudentTypes());
		$this->view->assign('cadre', $cadre);


		$this->view->assign('lookupfunding', $helper->getFunding());
		$this->view->assign('studentfunding', $studentedit->getStudentFunding($details['student'][0]['id']));
		
		//TA:#217 remove top  cohort from the history beacuse it will displyed in student form
		if(count($details['link_cohort']) > 1){
		    array_shift($details['link_cohort']);
		    $this->view->assign('cohort_history', $details['link_cohort']);//use this to display cohort history
		}

	}


	public function transcriptAction()
	{
		require_once('models/table/Studentedit.php');
		$request = $this->getRequest();
		$helper = new Helper();

		$this->viewAssignEscaped('locations', Location::getAll());
		$params = $this->getAllParams();
		if (isset($params['licenseaction'])) {
			$helper->updateStudentLicense($request->getparam('id'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $params['id']);
		}
		if (isset($params['classaction'])) {
			$helper->updateStudentClass($request->getparam('id'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $params['id']);
		}
		if (isset($params['practicumaction'])) {
			$helper->updateStudentPracticum($request->getparam('id'), $params);
			$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/personview/id/' . $params['id']);
		}


		// UPDATING FIRST, SO WE WILL LATER RETRIEVE UPDATED VALUES
		if (isset($params['personupdate'])) {
			require_once('models/table/Studentedit.php');

			$studentupdate = new Studentedit();
			$update = $studentupdate->UpdatePerson($params);                        # STORING PERSON RECORD
			$update = $studentupdate->UpdateStudent($params);                    # STORING STUDENT RECORD
			$update = $studentupdate->UpdateStudentCohort($params);                # STORING COHORT LINK
			$update = $studentupdate->UpdatePermanentAddress($params);            # STORING PERMANENT ADDRESS
			$update = $studentupdate->UpdateFunding($params);                    # STORING FUNDING
		}


		$pupiladd = $request->getParam('id');

		$this->view->assign('id', $pupiladd);

		$this->viewAssignEscaped('locations', Location::getAll());
		$studentedit = new Studentedit();
		$details = $studentedit->EditStudent($pupiladd);

		#print_r ($details);

		$date = $details['person'][0]['birthdate'];
		$dob = date("d-m-Y", strtotime($date));

		$this->view->assign('id', $pupiladd);
		$this->view->assign('selid', $details['person'][0]['title_option_id']);
		$this->view->assign('firstname', $details['person'][0]['first_name']);
		$this->view->assign('middlename', $details['person'][0]['middle_name']);
		$this->view->assign('lastname', $details['person'][0]['last_name']);
		$this->view->assign('gender', $details['person'][0]['gender']);
		$this->view->assign('dob', $dob);
		$this->view->assign('localgeo1', $details['student'][0]['geog1']);
		$this->view->assign('localgeo2', $details['student'][0]['geog2']);
		$this->view->assign('localgeo3', $details['student'][0]['geog3']);
		$this->view->assign('address1', $details['person'][0]['home_address_1']);
		$this->view->assign('address2', $details['person'][0]['home_address_2']);
		$this->view->assign('city', $details['person'][0]['home_city']);
		$this->view->assign('zip', $details['person'][0]['home_postal_code']);
		$this->view->assign('enroll', $details['person'][0]['home_address_2']);
		$this->view->assign('email', $details['person'][0]['email']);
		$this->view->assign('email2', $details['person'][0]['email_secondary']);
		$this->view->assign('titid', $details['person'][0]['title_option_id']);
		$this->view->assign('phone', $details['person'][0]['phone_work']);
		$this->view->assign('cell', $details['person'][0]['phone_mobile']);
		$this->view->assign('nationalid', $details['person'][0]['national_id']);
		$this->view->assign('graduated', $details['student'][0]['isgraduated']);
		$this->view->assign('cohortid', $details['link_cohort'][0]['id_cohort']);

		$this->view->assign('facilities', $helper->getFacilities());

		if ($details['link_cohort'][0]['id_cohort'] != 0) {
			$this->view->assign('allclasses', $helper->listcurrentclasses($details['link_cohort'][0]['id_cohort']));
			$this->view->assign('allpracticum', $helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort']));
			$this->view->assign('alllicenses', $helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort']));

			$this->view->assign('currentclasses', $helper->listcurrentclasses($details['link_cohort'][0]['id_cohort'], $details['person'][0]['id']));
			$this->view->assign('currentpracticum', $helper->ListCurrentPracticum($details['link_cohort'][0]['id_cohort'], $details['person'][0]['id']));
			$this->view->assign('currentlicenses', $helper->ListCurrentLicenses($details['link_cohort'][0]['id_cohort'], $details['person'][0]['id']));
		} else {
			$this->view->assign('currentclasses', array());
			$this->view->assign('currentpracticum', array());
			$this->view->assign('currentlicenses', array());
		}


		# PERMANENT ADDRESS
		$this->view->assign('permanentgeo1', $details['permanent_address'][0]['id_geog1']);
		$this->view->assign('permanentgeo2', $details['permanent_address'][0]['id_geog2']);
		$this->view->assign('permanentgeo3', $details['permanent_address'][0]['id_geog3']);
		$this->view->assign('permanentaddress1', $details['permanent_address'][0]['address1']);
		$this->view->assign('permanentaddress2', $details['permanent_address'][0]['address2']);
		$this->view->assign('permanentcity', $details['permanent_address'][0]['city']);
		$this->view->assign('permanentzip', $details['permanent_address'][0]['postalcode']);

		# STUDENT VIEW
		$this->view->assign('studentid', $details['student'][0]['studentid']);
		$this->view->assign('studenttype', $details['student'][0]['studenttype']);
		$this->view->assign('comments', $details['student'][0]['comments']);

		if (count($details['link_cohort']) == 0) {
			$joindate = "";
			$dropdate = "";
			$joinreason = 0;
			$dropreason = 0;
		} else {
			$joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
			$dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));
			$joinreason = $details['link_cohort'][0]['joinreason'];
			$dropreason = $details['link_cohort'][0]['dropreason'];
		}
		$this->view->assign('enrollmentdate', $joindate);
		$this->view->assign('enrollmentreason', $joinreason);
		$this->view->assign('separationdate', $dropdate);
		$this->view->assign('separationreason', $dropreason);

		$this->view->assign('cadre', $details['student'][0]['cadre']);
		$this->view->assign('advisor', $details['student'][0]['advisorid']);

		$this->view->assign('fundingsource', $details['funding'][0]['fundingsource']);
		$this->view->assign('fundingamount', $details['funding'][0]['fundingamount']);

		// POST GRADUATION FIELDS
		$this->view->assign('postgeo1', $details['student'][0]['postgeo1']);
		$this->view->assign('postgeo2', $details['student'][0]['postgeo2']);
		$this->view->assign('postgeo3', $details['student'][0]['postgeo3']);
		$this->view->assign('postaddress1', $details['student'][0]['postaddress1']);
		$this->view->assign('postfacilityname', $details['student'][0]['postfacilityname']);

		$helper = new Helper();

		$this->view->assign('joinreasons', $helper->getReasons('join'));
		$this->view->assign('dropreasons', $helper->getReasons('drop'));


		// For Title List
		$listtitle = $studentedit->ListTitle();
		$this->view->assign('gettitle', $listtitle);

		# GETTING COHORTS
		$listcohort = $studentedit->ListCohort();
		$this->view->assign('getcohorts', $listcohort);

		# GETTING CADRES
		$listcadre = $studentedit->listCadre();
		$this->view->assign('getcadres', $listcadre);

		# GETTING TUTORS
		$listtutors = $studentedit->ListTutors();
		$this->view->assign('gettutors', $listtutors);

		# NATIONALITY INFO
		$helper = new Helper();
		$this->view->assign('lookupnationalities', $helper->getNationalities());
		$this->view->assign('nationalityid', $details['student'][0]['nationalityid']);

		if (count($details['link_cohort']) > 0) {
			$this->view->assign('hascohort', true);

			#$cstartdate = $details['cohort'][0]['startdate'];
			$cstartdate = $joindate;
			#echo '<pre>';var_dump($details);die();
			if ($cstartdate) {
				$joindate = $details['link_cohort'][0]['joindate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['joindate']));
				$dropdate = $details['link_cohort'][0]['dropdate'] == "0000-00-00" ? "" : date("m/d/Y", strtotime($details['link_cohort'][0]['dropdate']));

				$lastclass = $dropdate ? $dropdate : date("m/d/Y");  // seperate date or today
				$yearofstudy = intval((strtotime($lastclass) - strtotime($cstartdate)) / 31536000 + 1); // time spent in class / seconds in a year + 1 year to show 1 year if 6mo of class or whatever
			}

			$this->view->assign('yearofstudy', $cstartdate ? $yearofstudy : "");

			if (count($details['cadre']) > 0) {
				$cadre = $details['cadre'][0]['id'];
			} else {
				$cadre = 0;
			}


		} else {
			$this->view->assign('hascohort', false);
			$this->view->assign('yearofstudy', "");
			$cadre = 0;
		}

		$this->view->assign('lookupstudenttypes', $helper->getStudentTypes());
		$this->view->assign('cadre', $cadre);


		$this->view->assign('lookupfunding', $helper->getFunding());
		$this->view->assign('studentfunding', $studentedit->getStudentFunding($details['student'][0]['id']));

	}

	public function skillsmartChwStudentEditAction()
	{

		$request = $this->getRequest();
		$db = $this->dbfunc();
		$helper = new Helper();

		$params = $this->getAllParams();
		if ($request->isPost()) {
			if (isset($params['licenseaction'])) {
				$helper->updateStudentLicense($params['sid'], $params);
				$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/skillsmart-chw-student-edit/id/' . $params['id']);
			}
			if (isset($params['classaction'])) {
				$helper->updateStudentClass($params['sid'], $params);
				$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/skillsmart-chw-student-edit/id/' . $params['id']);
			}
			if (isset($params['practicumaction'])) {
				$helper->updateStudentPracticum($params['sid'], $params);
				$this->_redirect(Settings::$COUNTRY_BASE_URL . '/studentedit/skillsmart-chw-student-edit/id/' . $params['id']);
			}

			require_once('views/helpers/Location.php');

			$person_location_id = regionFiltersGetLastID('person', $params);
			$workplace_location_id = regionFiltersGetLastID('workplace', $params);
			$employer_location_id = regionFiltersGetLastID('employer', $params);

			$studentData = array();
			$personData = array();
			$workplaceData = array();
			$cohortData = array();

			if (isset($params['update'])) {
				$studentData = $db->fetchRow("SELECT * from student where personid = ?", $params['id']);
				$personData = $db->fetchRow("SELECT * FROM person WHERE id = ?", $params['id']);
				$workplaceData = $db->fetchRow("SELECT * FROM workplace where id = ?", $personData['workplace_id']);
				$cohortData = $db->fetchRow("SELECT * from link_student_cohort where id_student = ?", $studentData['id']);
			}

			// person
			$personData = array_merge($personData, array(
				'last_name' => $params['last_name'],
				'first_name' => $params['first_name'],
				'national_id' => $params['national_id'],
				'title_option_id' => $params['title_option_id'],
				'birthdate' => $this->_euro_date_to_sql($params['dob']),
				'gender' => $params['gender'],
				'spouse_name' => $params['spouse_name'],
				'primary_qualification_option_id' => $params['primary_qualification_option_id'],
				'race_option_id' => $params['race_option_id'],

				'home_address_1' => $params['person_address_1'],
				'home_address_2' => $params['person_address_2'],
				'home_location_id' => $person_location_id,
				'phone_home' => $params['phone_home'],
				'email' => $params['email'],
				'marital_status' => $params['marital_status'], // Recognition of Prior Learning - is this field needed?
				'custom_field2' => $params['custom_field2'], // List of Modules for Prior Learning
				'custom_field3' => $params['custom_field3'], // is student employed
			));

			// student
			$studentData = array_merge($studentData, array(
				'nationalityid' => $params['nationalityid'],
				'cadre' => $params['cadre'],
				'lastunivatt' => $params['lastunivatt'],
				'emergcontact' => $params['emergcontact'],
				'studenttype' => $params['studenttype'],
			));

			// workplace
			$workplaceData = array_merge($workplaceData, array(
				'name' => $params['workplace_name'],

				'work_address_1' => $params['work_address_1'],
				'work_address_2' => $params['work_address_2'],
				'work_location_id' => $workplace_location_id,
				'work_phone' => $params['work_phone'],
				'start_date' => $this->_euro_date_to_sql($params['start_date']),
				'end_date' => $this->_euro_date_to_sql($params['end_date']),
				'employer_name' => $params['employer_name'],

				'employer_address_1' => $params['employer_address_1'],
				'employer_address_2' => $params['employer_address_2'],
				'employer_location_id' => $employer_location_id,
				'contact_phone' => $params['contact_phone'],
				'contact_person' => $params['contact_person'],
				'contact_email' => $params['contact_email'],
			));

			if (isset($params['addpeople'])) {
				// need IDs for data relationships
				$db->insert('workplace', $workplaceData);
				$params['workplace_id'] = $db->lastInsertId('workplace');
				$workplaceData['id'] = $params['workplace_id'];

				$personData['workplace_id'] = $params['workplace_id'];
				$db->insert('person', $personData);
				$params['person_id'] = $db->lastInsertId('person');
				$params['id'] = $params['person_id'];

				$studentData['personid'] = $params['person_id'];
				$db->insert('student', $studentData);

				$params['student_id'] = $db->lastInsertId('student');
				$studentData['id'] = $params['student_id'];
			}

			// link_student_cohort
			if (isset($params['cohort']) && strlen($params['cohort'])) {
				$cohortData = array_merge($cohortData, array(
					'id_student' => $params['student_id'],
					'id_cohort' => $params['cohort'],
					'joinreason' => $params['joinreason'],
					'dropreason' => $params['dropreason'],
					'joindate' => $this->_euro_date_to_sql($params['joindate']),
					'dropdate' => $this->_euro_date_to_sql($params['dropdate']),
					'examdate' => $this->_euro_date_to_sql($params['examdate']),
					'certificate_issue_date' => $this->_euro_date_to_sql($params['certificate_issue_date']),
					'certificate_number' => $params['certificate_number'],
					'certificate_received_date' => $this->_euro_date_to_sql($params['certificate_received_date']),
					'certificate_issuer_id' => $params['certificate_issuer']
				));
			}

			if (isset($params['addpeople'])) {
				if (isset($params['cohort']) && strlen($params['cohort'])) {
					$db->insert('link_student_cohort', $cohortData);
				}

			} elseif (isset($params['update'])) {
				$db->update('person', $personData, "id = {$params['person_id']}");
				$db->update('student', $studentData, "personid = {$params['person_id']}");

				if ($personData['workplace_id'] && $personData['workplace_id'] > 0) {
					$db->update('workplace', $workplaceData, "id = {$personData['workplace_id']}");
				}
				if (isset($params['cohort']) && strlen($params['cohort'])) {
					$db->update('link_student_cohort', $cohortData, "id_student = {$studentData['id']}");
				}
				else if ($cohortData) {
					$db->delete('link_student_cohort', "where id_student = {$studentData['id']}");
				}
			}

			if ($params['add_modules_ids']) {
				$ids = explode(',', $params['add_modules_ids']);
				$pairs = array();
				foreach ($ids as $id) {
					array_push($pairs, "({$studentData['id']}, $id)");
				}

				$q = "insert into link_student_class_modules (student_id, class_modules_id) VALUES " . implode(',', $pairs);
				$db->query($q);
			}

			if ($params['remove_modules_ids']) {
				$q = "delete from link_student_class_modules where student_id = {$studentData['id']} and class_modules_id in ({$params['remove_modules_ids']})";
				$db->query($q);
			}

			if ($params['']) {

			}
		}
		require_once('views/helpers/FormHelper.php');
		require_once('views/helpers/DropDown.php');
		require_once('views/helpers/Location.php');


		// Since this form is only intended for use by South Africa, I hard coded the date format.
		$q = "SELECT * FROM person WHERE id = {$params['id']}";
		$personData = $db->fetchRow($q);
		$personData['birthdate'] = formhelperdate($personData['birthdate'], "d/m/y");

		$q = "SELECT * FROM student WHERE personid = {$params['id']}";
		$studentData = $db->fetchRow($q);

		if (($personData['studenttype'] == 1) && $personData['workplace_id']) {
			$q = "SELECT * FROM workplace WHERE id = {$personData['workplace_id']}";
			$workplaceData = $db->fetchRow($q);
			$workplaceData['start_date'] = formhelperdate($workplaceData['start_date'], "d/m/y");
			$workplaceData['end_date'] = formhelperdate($workplaceData['end_date'], "d/m/y");
		}

		$q = "SELECT * FROM link_student_cohort WHERE id_student = {$studentData['id']}";
		$studentCohortData = $db->fetchRow($q);
		$studentCohortData['joindate'] = formhelperdate($studentCohortData['joindate'], "d/m/y");
		$studentCohortData['dropdate'] = formhelperdate($studentCohortData['dropdate'], "d/m/y");
        $studentCohortData['examdate'] = formhelperdate($studentCohortData['examdate'], "d/m/y");
        $studentCohortData['certificate_issue_date'] = formhelperdate($studentCohortData['certificate_issue_date'], "d/m/y");
        $studentCohortData['certificate_received_date'] = formhelperdate($studentCohortData['certificate_received_date'], "d/m/y");

		$this->view->assign('locations', Location::getAll());
		$this->view->assign('personCriteria', locationIDTo3TierCriteriaArray($personData['home_location_id'], 'person'));
		$this->view->assign('workplaceCriteria', locationIDTo3TierCriteriaArray($workplaceData['work_location_id'], 'workplace'));
		$this->view->assign('employerCriteria', locationIDTo3TierCriteriaArray($workplaceData['employer_location_id'], 'employer'));

		$this->view->assign('title', $this->view->translation['Application Name']);
		$this->view->assign('required_fields', array('last_name', 'first_name', 'primary_qualification_option_id'));
		$this->view->assign('action', '/studentedit/skillsmart-chw-student-edit/id/' . $params['id']);

		$this->view->assign('personData', $personData);
		$this->view->assign('studentData', $studentData);
		$this->view->assign('workplaceData', $workplaceData);

		$this->view->assign('studentCohortData', $studentCohortData);
		//$this->view->assign('studentClassData', $studentClassData);

		if (isset($params['update'])) {
			$this->view->assign('formType', 'update');
		}

		if ($studentCohortData['id_cohort'] != 0) {
			$this->view->assign('currentclasses', $helper->ListCurrentClasses($studentCohortData['id_cohort'], $studentData['id']));
			$this->view->assign('currentpracticum', $helper->ListCurrentPracticum($studentCohortData['id_cohort'], $studentData['id']));
			$this->view->assign('currentlicenses', $helper->ListCurrentLicenses($studentCohortData['id_cohort'], $studentData['id']));
		} else {
			$this->view->assign('currentclasses', array());
			$this->view->assign('currentpracticum', array());
			$this->view->assign('currentlicenses', array());
		}

		$q = 'SELECT class_modules.id, class_modules.external_id, class_modules.title
              FROM
              class_modules
              INNER JOIN link_student_class_modules ON link_student_class_modules.class_modules_id = class_modules.id
              INNER JOIN student ON student.id = link_student_class_modules.student_id
              WHERE student.id = ?';

		$this->view->assign('prior_modules', $db->fetchAssoc($q, $studentData['id']));

		$this->view->assign('nationality_dropdown',
			DropDown::generateSelectionFromQuery(
				'select id, nationality as val from lookup_nationalities order by val',
				array('name' => 'nationalityid'),
				$studentData['nationalityid']
			)
		);

		$this->view->assign('primary_qualification',
			DropDown::generateSelectionFromQuery(
				'select id, qualification_phrase as val from person_qualification_option order by val',
				array('name' => 'primary_qualification_option_id'),
				$personData['primary_qualification_option_id']
			)
		);

		$this->view->assign('race_options',
			DropDown::generateSelectionFromQuery(
				'select id, race_phrase as val from person_race_option order by val',
				array('name' => 'race_option_id'),
				$personData['race_option_id']
			)
		);

		$this->view->assign('class_modules', $this->dbfunc()->fetchAssoc('select id, external_id, title from class_modules order by title'));

		$this->view->assign('qualification_name',
			DropDown::generateSelectionFromQuery(
				"select id, reason as val from lookup_reasons where reasontype = 'join' order by val",
				array('name' => 'joinreason', 'id' => 'joinreason'),
				$studentCohortData['joinreason']
			)
		);

		$this->view->assign('cohorts',
			DropDown::generateSelectionFromQuery(
				"select id, cohortname as val from cohort order by val",
				array('name' => 'cohort', 'id' => 'cohort'),
				$studentCohortData['id_cohort']
			)
		);

		$this->view->assign('level',
			DropDown::generateSelectionFromQuery(
				"select id, reason as val from lookup_reasons where reasontype = 'drop' order by val",
				array('name' => 'dropreason', 'id' => 'dropreason'),
				$studentCohortData['dropreason']
			)
		);

		$this->view->assign('cadre',
			DropDown::generateSelectionFromQuery(
				'select id, cadrename as val from cadres order by val',
				array('name' => 'cadre'),
				$studentData['cadre']
			)
		);

		$this->view->assign('student_employed',
			DropDown::generateSelectionFromQuery(
				'select id, studenttype as val from lookup_studenttype order by val',
				array('name' => 'studenttype', 'id' => 'studenttype'),
				$studentData['studenttype']
			)
		);

		$s = $db->select()->from('certificate_issuers', array('id', 'val' => 'issuer_name'));

		$this->view->assign('certificate_issuer',
			DropDown::generateSelectionFromQuery($s,
				array('name' => 'certificate_issuer', 'id' => 'certificate_issuer'),
				$studentCohortData['certificate_issuer_id']
			)
		);

	}
}