<?php
require_once ('ReportFilterHelpers.php');
require_once ('FacilityController.php');
require_once ('models/table/Person.php');
require_once ('models/table/Facility.php');
require_once ('models/table/OptionList.php');
require_once ('models/table/MultiOptionList.php');
require_once ('models/table/Location.php');
require_once ('models/table/MultiAssignList.php');
require_once ('views/helpers/FormHelper.php');
require_once ('views/helpers/DropDown.php');
require_once ('views/helpers/Location.php');
require_once ('views/helpers/CheckBoxes.php');
require_once ('views/helpers/TrainingViewHelper.php');
require_once ('models/table/Helper.php');
require_once ('models/table/Partner.php');

class DashboardController extends ReportFilterHelpers {

	public function init() {	}

	public function preDispatch() {
		parent::preDispatch ();

		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();

		//if (! $this->setting('module_employee_enabled')){
			//$_SESSION['status'] = t('The employee module is not enabled on this site.');
			//$this->_redirect('select/select');
		//}

		//if (! $this->hasACL ( 'employees_module' )) {
			//$this->doNoAccessError ();
		//}
	}

	public function indexAction() {

		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}

		require_once('models/table/dash-employee.php');
		$this->view->assign('title', $this->translation['Application Name'].space.t('Employee').space.t('Tracking System'));

		// restricted access?? does this user only have acl to view some trainings or people
		// they dont want this, removing 5/01/13
		$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'?
		$allowedWhereClause = $org_allowed_ids ? " partner.organizer_option_id in ($org_allowed_ids) " : "";
		// restricted access?? only show organizers that belong to this site if its a multi org site
		$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
		$allowedWhereClause .= $site_orgs ? " AND partner.organizer_option_id in ($site_orgs) " : "";

		$partners = new DashviewEmployee();
		$details = $partners->fetchdetails($allowedWhereClause);
		$this->view->assign('getins',$details);

		/****************************************************************************************************************/
		/* Attached Files */
		require_once('views/helpers/FileUpload.php');

		$PARENT_COMPONENT = 'employee';

		FileUpload::displayFiles ( $this, $PARENT_COMPONENT, 1, $this->hasACL ( 'admin_files' ) );
		// File upload form
		if ( $this->hasACL ( 'admin_files' ) ) {
			$this->view->assign ( 'filesForm', FileUpload::displayUploadForm ( $PARENT_COMPONENT, 1, FileUpload::$FILETYPES ) );
		}
		/****************************************************************************************************************/
	}
	
	public function tryAction() {}
	public function try1Action() {
	    // include auto-loader class
	    require_once 'Zend/Loader/Autoloader.php';
	    
	    $loader = Zend_Loader_Autoloader::getInstance();
	    
	    try {
	        $pdf = new Zend_Pdf();
	        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_LETTER);
	    
	        // write image to page
	        
	        $imagePath = 'c:\users\rossumg\_tmp\pdf\image.png';
	        
	        list($width, $height, $type, $attr) = getimagesize($imagePath);
	        $image = Zend_Pdf_Image::imageWithPath($imagePath);
	        $page->drawImage($image, 50, 500, 50+$width, 500+$height);
	        
	        // add page to document
	        $pdf->pages[] = $page;
	    
	        // save as file
	        $filename = '\test_image'.date("His").'.pdf';
	        $pdf->save('c:\users\rossumg\_tmp\pdf'.$filename);
	        echo 'SUCCESS: Document saved!';
	    } catch (Zend_Pdf_Exception $e) {
	        die ('PDF error: ' . $e->getMessage());
	    } catch (Exception $e) {
	        die ('Application error: ' . $e->getMessage());
	    }
	}
	

	
	public function try2Action() {
	    // include auto-loader class
	    require_once 'Zend/Loader/Autoloader.php';
	    
	    //if (isset($_POST["submit"])) {
	    if (true) {
	        $target_dir = 'c:/users/rossumg/_tmp/uploads/';
	        
	        file_put_contents('c:\wamp\logs\php_debug.log', 'try2 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        var_dump('$_POST= ', $_POST, 'END');
	        var_dump('$_FILES= ', $_FILES, 'END');
	        $toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	        
	        $target_file = $target_dir . basename(@$_FILES["fileToUpload"]["name"]);
	        $uploadOk = 1;
	        // $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	        
	        // Check if image file is a actual image or fake image
	        if(isset($_POST["submit"])) {
	            $check = getimagesize(@$_FILES["fileToUpload"]["tmp_name"]);
	            if($check !== false) {
	                echo "File is an image - " . $check["mime"] . ".";
	                $uploadOk = 1;
	            } else {
	                echo "File is not an image.";
	                $uploadOk = 0;
	            }
	        }
	        // Check if file already exists
	        //if (file_exists($target_file)) {
	          //  echo "Sorry, file already exists.";
	            //$uploadOk = 0;
	        //}
	        // Check file size
	        if ($_FILES["fileToUpload"]["size"] > 500000) {
	            echo "Sorry, your file is too large.";
	            $uploadOk = 0;
	        }
	        // Allow certain file formats
	        //if($imageFileType != "pdf"  && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                if(false) {
	                echo "Sorry, only PDF, JPG, JPEG, PNG & GIF files are allowed.";
	                $uploadOk = 0;
	            }
	            // Check if $uploadOk is set to 0 by an error
	            if ($uploadOk == 0) {
	                echo "Sorry, your file was not uploaded.";
	                // if everything is ok, try to upload file
	            } else {
	                if (move_uploaded_file(@$_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	                    echo "The file ". basename( @$_FILES["fileToUpload"]["name"]). " has been uploaded.";
	                } else {
	                    echo "Sorry, there was an error uploading your file.";
	                }
	            }
	    //} else {
	     
	    $loader = Zend_Loader_Autoloader::getInstance();
	     
	    try {
	        $pdf = new Zend_Pdf();
	        $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_LETTER);
	        
	        // write image to page
	        //$imagePath = 'c:\users\rossumg\_tmp\pdf\test_image.png';
	        //$fh = fopen('C:\Users\rossumg\Downloads\download', 'a');
	        //fclose($fh);
	        /*
	        $filename = 'C:\Users\rossumg\Downloads\download';
	        file_put_contents('c:\wamp\logs\php_debug.log', 'try2 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        $stat = stat($filename);
	        var_dump('$stat= ', $stat, 'END');
	        $is_uploaded = is_uploaded_file($filename);
	        var_dump('$is_uploaded= ', $is_uploaded, 'END');
	         
	        $toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	        */
	        
            copy('C:\Users\rossumg\_tmp\uploads\download', 'C:\Users\rossumg\_tmp\uploads\image.png');	        
            //rename('C:\Users\rossumg\uploads\download', 'C:\Users\rossumg\_tmp\uploads\image.png');
	        
	        $imagePath = 'c:\users\rossumg\_tmp\uploads\image.png';
	        
	        list($width, $height, $type, $attr) = getimagesize($imagePath);
	        $image = Zend_Pdf_Image::imageWithPath($imagePath);
	        $page->drawImage($image, 50, 500, 50+$width, 500+$height);
	         
	        // add page to document
	        $pdf->pages[] = $page;
	         
	        // save as file
	        $filename = '\image'.date("His").'.pdf';
	        $pdf->save('c:\users\rossumg\_tmp\pdf'.$filename);
	        echo 'SUCCESS: Document saved!';
	    } catch (Zend_Pdf_Exception $e) {
	        //die ('PDF error: ' . $e->getMessage());
	    } catch (Exception $e) {
	        die ('Application error: ' . $e->getMessage());
	    }
	    
	     }
	}
	
	public function dash0Action() {	}
	public function dash990Action() { }
	public function dash991Action() { }
	public function dash992Action() { }
	public function dash993Action() { }
	public function dash994Action() { }
	public function dash995Action() { }
	
	public function dash996Action() { 
	  require_once('models/table/Dashboard-CHAI.php');
	  
	  $method = $this->getSanParam ( 'method' );
	  $request = $this->getRequest ();
	  
	  $title_method = new DashboardCHAI();
	  $title_method = $title_method->fetchTitleMethod($method);
	   
	  $title_date = new DashboardCHAI();
	  $title_date = $title_date->fetchTitleDate();

	  //TA:17:17 format consumption name
	  $consumption_name = $title_method[commodity_name];
	  if($title_method[commodity_name] === "IUCD inserted"){
	  	 $consumption_name = "IUCD inserted";
	  }else{
	  	$consumption_name = strtolower($title_method[commodity_name]);
	  }
	  $this->view->assign('title_date',  $consumption_name .', '. $title_date[month_name].' '. $title_date[year]);
	  
	  $cln_data = new DashboardCHAI();
	  $amc_data = new DashboardCHAI();
	  $tot_data = new DashboardCHAI();
	  
	  // geo selection includes "--choose--" or no selection
	  if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) || 
	      ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) || 
	      ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	      (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
          //get national numbers from refresh
          $cln_details = $cln_data->fetchDashboardData('national_consumption'.$method);
          $amc_details = $amc_data->fetchDashboardData('national_average_monthly_consumption'.$method);
          $tot_details = $tot_data->fetchDashboardData('national_total_consumption'.$method); 
	  }
	  
      if (count($cln_details) > 0 && count($tot_details) > 0 && count($amc_details) > 0  ) { //got all
          
          $this->view->assign('consumption_by_geo', $cln_details);
          $this->view->assign('total_consumption', $tot_details);
          $this->view->assign('average_monthly_consumption_by_geo', $amc_details);
	  
      } else {    

          $where = ' 1=1 ';
       
	    if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	        $where = $where.' and f.location_id in (';
	        foreach ($_POST['region_c_id'] as $i => $value){
	            $geo = explode('_',$value);
	            $where = $where.$geo[2].', ';
	        }
	        $where = $where.') ';
	        $group = new Zend_Db_Expr('L1_location_name, CNO_external_id');
	        $useName = 'L1_location_name';
	        
	    } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	        $where = $where.' and l2.id in (';
	        foreach ($_POST['district_id'] as $i => $value){
	            $geo = explode('_',$value);
	            $where = $where.$geo[1].', ';
	        }
	        $where = $where.') ';
	        $group = new Zend_Db_Expr('L2_location_name, CNO_external_id');
	        $useName = 'L2_location_name';
	        
	    } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ 
	        $where = $where.' and l2.parent_id in (';
	        foreach ($_POST['province_id'] as $i => $value){
	            $geo = explode('_',$value);
	            $where = $where.$geo[0].', ';
	        }
	        $where = $where.') ';
	        $group = new Zend_Db_Expr('L3_location_name, CNO_external_id');
	        $useName = 'L3_location_name';
	    } else { // no geo selection
	       $group = 'CNO_external_id';
	       $useName = 'L1_location_name';
	       $location = 'National';
	    }
	   
	    $where = str_replace(', )', ')', $where);
	    $whereClause = new Zend_Db_Expr($where);
	    	    
	    $amc_details = $amc_data->fetchAMCDetails($whereClause);
	    
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'dash996Action >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('amc_details= ', $amc_details, 'END');
	    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	    // $method is external_id and must be single quoted, meant to be int but had to convert table id to external_id
	    if( "'$method'" != '' ) $where = $where.' and cno.external_id in ( '."'$method'".' )';
	    
	    $cln_details = $cln_data->fetchCLNDetails('location', $id, $where, $group, $useName);
	    
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'dash996Action >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('$cln_details= ', $cln_details, 'END');
	    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	     
	    $total = 0;
	    
	    foreach($cln_details as $i => $row ){
	        
	        if ( $location != 'National' ) {
	            switch($useName){
	                case 'L1_location_name' :
	                    $location = $row['L1_location_name'];
	                    break;
	                case 'L2_location_name' :
	                    $location = $row['L2_location_name'];
	                    break;
	                case 'L3_location_name' :
	                    $location = $row['L3_location_name'];
	                    break;
	            }
	        }
	        
	        $locationNames = $locationNames ? $locationNames.', '.$location : $locationNames.$location;
	         
	        switch($method){
	            case 'w92UxLIRNTl' :
	                $consumption_by_geo[] =array('location' => $location, 'consumption' => $row['consumption1'] );
	                break;
	            case 'H8A8xQ9gJ5b' :
	                $consumption_by_geo[] =array('location' => $location, 'consumption' => $row['consumption2'] );
	                break;
	            case 'ibHR9NQ0bKL' :
	                $consumption_by_geo[] =array('location' => $location, 'consumption' => $row['consumption3'] );
	                break;
	            case 'DiXDJRmPwfh' :
	                $consumption_by_geo[] =array('location' => $location, 'consumption' => $row['consumption4'] );
	                break;
	            case 'yJSLjbC9Gnr' :
	                $consumption_by_geo[] =array('location' => $location, 'consumption' => $row['consumption5'] );
	                break;
	            case 'vDnxlrIQWUo' :
	                $consumption_by_geo[] =array('location' => $location, 'consumption' => $row['consumption6'] );
	                break;
	            case 'krVqq8Vk5Kw' :
	                $consumption_by_geo[] =array('location' => $location, 'consumption' => $row['consumption7'] );
	                break;
	            case '' :
	                //bad
	                break;
	        }
	        
	        $total = $total + $consumption_by_geo[$i]['consumption'];
	        
	    } // foreach cln
	    
	    foreach($amc_details as $i => $row ){
	        
	        switch($method){
	            case 'w92UxLIRNTl' :
	                $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption' => $row['consumption1'] );
	                break;
	            case 'H8A8xQ9gJ5b' :
	                $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption' => $row['consumption2'] );
	                break;
	            case 'ibHR9NQ0bKL' :
	                $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption' => $row['consumption3'] );
	                break;
	            case 'DiXDJRmPwfh' :
	                $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption' => $row['consumption4'] );
	                break;
	            case 'yJSLjbC9Gnr' :
	                $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption' => $row['consumption5'] );
	                break;
	            case 'vDnxlrIQWUo' :
	                $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption' => $row['consumption6'] );
	                break;
	            case 'krVqq8Vk5Kw' :
	                $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption' => $row['consumption7'] );
	                break;
	            case '' :
	                //bad
	                break;
	        }
	         
	    } // foreach amc
	    
	    if (is_null($consumption_by_geo)) {
	        $consumption_by_geo[] = array('location' => 'No Data', 'consumption' => 0 );
	    }
	    
	    if ($total == 0) {
	        $total_consumption[] = array('location' => 'No Data', 'consumption' => 0 );
	    } else {
	        $total_consumption[] = array('location' => $locationNames, 'consumption' => $total );
	    }
	    
	    $this->view->assign('consumption_by_geo', $consumption_by_geo);
	    $this->view->assign('total_consumption', $total_consumption);
	    $this->view->assign('average_monthly_consumption_by_geo', $average_monthly_consumption_by_geo);
	    
	    if ($location == 'National') {
	        $cln_details = $cln_data->insertDashboardData($consumption_by_geo, 'national_consumption'.$method);
	        $amc_details = $amc_data->insertDashboardData($total_consumption, 'national_total_consumption'.$method);
	        $tot_details = $tot_data->insertDashboardData($average_monthly_consumption_by_geo, 'national_average_monthly_consumption'.$method);
	    }
	
	}  // else

	$this->viewAssignEscaped ('locations', Location::getAll() );
	
} // dash996Action

public function dash996allAction() {
    require_once('models/table/Dashboard-CHAI.php');
     
    // enclosing single quotes added later
	$method = "w92UxLIRNTl','H8A8xQ9gJ5b','ibHR9NQ0bKL','DiXDJRmPwfh','yJSLjbC9Gnr','vDnxlrIQWUo','krVqq8Vk5Kw";
    $request = $this->getRequest ();
    
     
    $cln_data = new DashboardCHAI();
    $amc_data = new DashboardCHAI();
     
    // geo selection includes "--choose--" or no selection
    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
        //get national numbers from refresh
        $cln_details = $cln_data->fetchDashboardData('national_consumption_by_method');
        $amc_details = $amc_data->fetchDashboardData('national_average_monthly_consumption_all');
    }
     
    if ( count($cln_details) > 0 && count($amc_details ) > 0  ) { //got all

        $this->view->assign('national_consumption_by_method', $cln_details);
        $this->view->assign('average_monthly_consumption_by_geo', $amc_details);
         
    } else {

        $where = ' 1=1 ';
         
        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
            $where = $where.' and f.location_id in (';
            foreach ($_POST['region_c_id'] as $i => $value){
                $geo = explode('_',$value);
                $where = $where.$geo[2].', ';
            }
            $where = $where.') ';
            $group = new Zend_Db_Expr('L1_location_name, CNO_external_id');
            $useName = 'L1_location_name';
             
        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
            $where = $where.' and l2.id in (';
            foreach ($_POST['district_id'] as $i => $value){
                $geo = explode('_',$value);
                $where = $where.$geo[1].', ';
            }
            $where = $where.') ';
            $group = new Zend_Db_Expr('L2_location_name, CNO_external_id');
            $useName = 'L2_location_name';
             
        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
            $where = $where.' and l2.parent_id in (';
            foreach ($_POST['province_id'] as $i => $value){
                $geo = explode('_',$value);
                $where = $where.$geo[0].', ';
            }
            $where = $where.') ';
            $group = new Zend_Db_Expr('L3_location_name, CNO_external_id');
            $useName = 'L3_location_name';
        } else { // no geo selection
            $group = 'CNO_external_id';
            $useName = 'L1_location_name';
            $location = 'National';
        }

        $where = str_replace(', )', ')', $where);
        $whereClause = new Zend_Db_Expr($where);
        
        if( "'$method'" != '' ) $where = $where.' and cno.external_id in ( '."'$method'".' )';
        $cln_details = $cln_data->fetchCLNDetails('location', $id, $where, $group, $useName);
        
        foreach($cln_details as $i => $row ){
            // remove single quotes and explode method
            $bad_chars = array("'");
            $method = str_replace($bad_chars, "", $method);
            $methods =  array( explode(',', $method) );
             
            // lookup commodity_names
            $title_method = new DashboardCHAI();
            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][0]));
            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][1]));
            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][2]));
            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][3]));
            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][4]));
            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][5]));
            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][6]));
        
            $national_consumption_by_method[] =array('method' => $CNO[0][0]['commodity_name'], 'consumption' => $row['consumption1'] );
            $national_consumption_by_method[] =array('method' => $CNO[1][0]['commodity_name'], 'consumption' => $row['consumption2'] );
            $national_consumption_by_method[] =array('method' => $CNO[2][0]['commodity_name'], 'consumption' => $row['consumption3'] );
            $national_consumption_by_method[] =array('method' => $CNO[3][0]['commodity_name'], 'consumption' => $row['consumption4'] );
            $national_consumption_by_method[] =array('method' => $CNO[4][0]['commodity_name'], 'consumption' => $row['consumption5'] );
            $national_consumption_by_method[] =array('method' => $CNO[5][0]['commodity_name'], 'consumption' => $row['consumption6'] );
            $national_consumption_by_method[] =array('method' => $CNO[6][0]['commodity_name'], 'consumption' => $row['consumption7'] );
        
        } // foreach cln
        
        $this->view->assign('national_consumption_by_method', $national_consumption_by_method);

        $amc_details = $amc_data->fetchAMCDetails($whereClause);
     
        foreach($amc_details as $i => $row ){

            $average_monthly_consumption_by_geo[] =array('month' => $row['month'], 'consumption1' => $row['consumption1'], 'consumption2' => $row['consumption2'], 'consumption3' => $row['consumption3'], 'consumption4' => $row['consumption4'], 'consumption5' => $row['consumption5'], 'consumption6' => $row['consumption6'], 'consumption7' => $row['consumption7'] );
            
        }

        $this->view->assign('average_monthly_consumption_by_geo', $average_monthly_consumption_by_geo);
         
        if ($location == 'National') {
            $cln_details = $cln_data->insertDashboardData($national_consumption_by_method, 'national_consumption_by_method');
            $amc_details = $amc_data->insertDashboardData($average_monthly_consumption_by_geo, 'national_average_monthly_consumption_all');
        }
        
    }  // else

    $this->viewAssignEscaped ('locations', Location::getAll() );

} // dash996allAction

	
	public function dash997Action() { }
	public function dash998Action() { }
	public function dash999Action() { }
	public function dash9991Action() { }
	public function dash9992Action() { }
	public function dash9993Action() { }
	

	
	public function dash1Action() {
	
		//if (! $this->hasACL ( 'employees_module' )) {
			//$this->doNoAccessError ();
		//}
	
		require_once('models/table/dash-employee.php');
		$this->view->assign('title', $this->translation['Application Name'].space.t('Employee').space.t('Tracking System'));
	
		// restricted access?? does this user only have acl to view some trainings or people
		// they dont want this, removing 5/01/13
		$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'?
		$allowedWhereClause = $org_allowed_ids ? " partner.organizer_option_id in ($org_allowed_ids) " : "";
		// restricted access?? only show organizers that belong to this site if its a multi org site
		$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
		$allowedWhereClause .= $site_orgs ? " AND partner.organizer_option_id in ($site_orgs) " : "";
	
		$partners = new DashviewEmployee();
		$details = $partners->fetchdetails($allowedWhereClause);
		$this->view->assign('getins',$details);
	
		/****************************************************************************************************************/
		/* Attached Files */
		require_once('views/helpers/FileUpload.php');
	
		$PARENT_COMPONENT = 'employee';
	
		FileUpload::displayFiles ( $this, $PARENT_COMPONENT, 1, $this->hasACL ( 'admin_files' ) );
		// File upload form
		if ( $this->hasACL ( 'admin_files' ) ) {
			$this->view->assign ( 'filesForm', FileUpload::displayUploadForm ( $PARENT_COMPONENT, 1, FileUpload::$FILETYPES ) );
		}
		/****************************************************************************************************************/
	}
	
	public function dash2Action() {
	
		//if (! $this->hasACL ( 'employees_module' )) {
			//$this->doNoAccessError ();
		//}
	
		require_once('models/table/dash-employee.php');
		$this->view->assign('title', $this->translation['Application Name'].space.t('Employee').space.t('Tracking System'));
	
		// restricted access?? does this user only have acl to view some trainings or people
		// they dont want this, removing 5/01/13
		$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'?
		$allowedWhereClause = $org_allowed_ids ? " partner.organizer_option_id in ($org_allowed_ids) " : "";
		// restricted access?? only show organizers that belong to this site if its a multi org site
		$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
		$allowedWhereClause .= $site_orgs ? " AND partner.organizer_option_id in ($site_orgs) " : "";
	
		$partners = new DashviewEmployee();
		$details = $partners->fetchdetails($allowedWhereClause);
		$this->view->assign('getins',$details);
	
		/****************************************************************************************************************/
		/* Attached Files */
		require_once('views/helpers/FileUpload.php');
	
		$PARENT_COMPONENT = 'employee';
	
		FileUpload::displayFiles ( $this, $PARENT_COMPONENT, 1, $this->hasACL ( 'admin_files' ) );
		// File upload form
		if ( $this->hasACL ( 'admin_files' ) ) {
			$this->view->assign ( 'filesForm', FileUpload::displayUploadForm ( $PARENT_COMPONENT, 1, FileUpload::$FILETYPES ) );
		}
		/****************************************************************************************************************/
	}
	
	public function dash3Action() {
	
		require_once('models/table/Dashboard-CHAI.php');
		$this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
		
		$id = $this->getSanParam ( 'id' );
		
		$whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
		$geo_data = new DashboardCHAI();
		$details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
		$this->view->assign('geo_data',$details);
		
		//file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 190>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		//var_dump('id=', $id);
		//var_dump('count(details)=', count($details));
		//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
		
		$whereClause = ($id ==  "") ? 'l3.tier = 1' : 'l2.parent_id = ' . $id ;
		$groupClause = ($id == "") ? 'L2_id' : 'L1_id';
		$useName = ($id == "") ? 'L3_location_name' : 'L2_location_name';
		
		
		$locconsumption_by_geoardCHAI();
		$details = $location_data->fetchConsumptionDetails('location', $id, $whereClause, $groupClause, $useName);
		$this->view->assign('latest_consumption_data',$details);
		
		if (count($details) == 0) { // count is 0 then facility
		  $whereClause = 'l1.parent_id = ' . $id;
		  $groupClause = 'F_id';
		  $useName = 'L1_location_name';
		
		  $facility_data = new DashboardCHAI();
		  $details = $facility_data->fetchConsumptionDetails('facility', $id, $whereClause, $groupClause, $useName);
		  $this->view->assign('latest_consumption_data',$details);
		  $this->view->assign('geo_data',array()); // at bottom
		}

	}
	
	public function dash3aAction() {
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 190>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id);
	    //var_dump('count(details)=', count($details));
	    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	
	    $whereClause = ($id ==  "") ? 'l3.tier = 1' : 'l2.parent_id = ' . $id ;
	    $groupClause = ($id == "") ? 'L2_id' : 'L1_id';
	    $useName = ($id == "") ? 'L3_location_name' : 'L2_location_name';
	
	
	    $location_data = new DashboardCHAI();
	    $details = $location_data->fetchConsumptionDetails('location', $id, $whereClause, $groupClause, $useName);
	    $this->view->assign('latest_consumption_data',$details);
	
	    if (count($details) == 0) { // count is 0 then facility
	        $whereClause = 'l1.parent_id = ' . $id;
	        $groupClause = 'F_id';
	        $useName = 'L1_location_name';
	
	        $facility_data = new DashboardCHAI();
	        $details = $facility_data->fetchConsumptionDetails('facility', $id, $whereClause, $groupClause, $useName);
	        $this->view->assign('latest_consumption_data',$details);
	        $this->view->assign('geo_data',array()); // at bottom
	    }
	
	}
	
	public function dash4Action() {
	
		//if (! $this->hasACL ( 'employees_module' )) {
			//$this->doNoAccessError ();
		//}
	
		require_once('models/table/dash-employee.php');
		$this->view->assign('title', $this->translation['Application Name'].space.t('Employee').space.t('Tracking System'));
	
		// restricted access?? does this user only have acl to view some trainings or people
		// they dont want this, removing 5/01/13
		$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'?
		$allowedWhereClause = $org_allowed_ids ? " partner.organizer_option_id in ($org_allowed_ids) " : "";
		// restricted access?? only show organizers that belong to this site if its a multi org site
		$site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
		$allowedWhereClause .= $site_orgs ? " AND partner.organizer_option_id in ($site_orgs) " : "";
	
		$partners = new DashviewEmployee();
		$details = $partners->fetchdetails($allowedWhereClause);
		$this->view->assign('getins',$details);
	
		/****************************************************************************************************************/
		/* Attached Files */
		require_once('views/helpers/FileUpload.php');
	
		$PARENT_COMPONENT = 'employee';
	
		FileUpload::displayFiles ( $this, $PARENT_COMPONENT, 1, $this->hasACL ( 'admin_files' ) );
		// File upload form
		if ( $this->hasACL ( 'admin_files' ) ) {
			$this->view->assign ( 'filesForm', FileUpload::displayUploadForm ( $PARENT_COMPONENT, 1, FileUpload::$FILETYPES ) );
		}
		/****************************************************************************************************************/
	}
	
	public function dash5Action() {
	
		//if (! $this->hasACL ( 'edit_employee' )) {
			//$this->doNoAccessError ();
		//}
		
		require_once('models/table/Dashboard-CHAI.php');
		$this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
		
		$id = $this->getSanParam ( 'id' );
		
		$whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
		
		$geo_data = new DashboardCHAI();
		$details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
		$this->view->assign('geo_data',$details);
		

		
		$whereClause = ($id ==  "") ? 'l3.tier = 1' : 'l2.parent_id = ' . $id ;
		$groupClause = ($id == "") ? 'L2_id' : 'L1_id';
		$useName = ($id == "") ? 'L3_location_name' : 'L2_location_name';
		
		
		$location_data = new DashboardCHAI();
		$details = $location_data->fetchConsumptionDetails('location', $id, $whereClause, $groupClause, $useName);
		$this->view->assign('latest_consumption_data',$details);
		
		if (count($details) == 0) { // count is 0 then facility
		    $whereClause = 'l1.parent_id = ' . $id;
		    $groupClause = 'F_id';
		    $useName = 'L1_location_name';
		
		    $facility_data = new DashboardCHAI();
		    $details = $facility_data->fetchConsumptionDetails('facility', $id, $whereClause, $groupClause, $useName);
		    $this->view->assign('latest_consumption_data',$details);
		    $this->view->assign('geo_data',array()); // at bottom
		}
		
		
		$amc_data = new DashboardCHAI();
		$details = $amc_data->fetchAMCDetails();
		$this->view->assign('AMC_data',$details);
		
		//file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
		//var_dump('id=', $id);
		//var_dump('details=', $details);
		//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	
	public function dash5aAction() {
	
	    //if (! $this->hasACL ( 'edit_employee' )) {
	    //$this->doNoAccessError ();
	    //}
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	
	
	    $whereClause = ($id ==  "") ? 'l3.tier = 1' : 'l2.parent_id = ' . $id ;
	    $groupClause = ($id == "") ? 'L2_id' : 'L1_id';
	    $useName = ($id == "") ? 'L3_location_name' : 'L2_location_name';
	
	
	    $location_data = new DashboardCHAI();
	    $details = $location_data->fetchConsumptionDetails('location', $id, $whereClause, $groupClause, $useName);
	    $this->view->assign('latest_consumption_data',$details);
	
	    if (count($details) == 0) { // count is 0 then facility
	        $whereClause = 'l1.parent_id = ' . $id;
	        $groupClause = 'F_id';
	        $useName = 'L1_location_name';
	
	        $facility_data = new DashboardCHAI();
	        $details = $facility_data->fetchConsumptionDetails('facility', $id, $whereClause, $groupClause, $useName);
	        $this->view->assign('latest_consumption_data',$details);
	        $this->view->assign('geo_data',array()); // at bottom
	    }
	
	
	    $amc_data = new DashboardCHAI();
	    $details = $amc_data->fetchAMCDetails();
	    $this->view->assign('AMC_data',$details);
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id);
	    //var_dump('details=', $details);
	    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	
	public function dash5bAction() {
	    
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	    
	    $fp_data = new DashboardCHAI();
	    $details = $fp_data->fetchDashboardData('average_monthly_consumption');
	     
	    if(count($details) > 0){
	        $this->view->assign('AMC_data',$details);
	    }
	    else {
	
	       $amc_data = new DashboardCHAI();
	       $details = $amc_data->fetchAMCDetails();
	       $amc_data->insertDashboardData($details, 'average_monthly_consumption');
	       $this->view->assign('AMC_data',$details);
	    }
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id);
	    //var_dump('details=', $details);
	    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	
	public function dash6Action() {
	
	    //if (! $this->hasACL ( 'edit_employee' )) {
	    //$this->doNoAccessError ();
	    //}
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    $hcwt_data = new DashboardCHAI();
	    $details = $hcwt_data->fetchHCWTDetails();
	    $this->view->assign('HCWT_data',$details);
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id);
	    //var_dump('details=', $details);
	    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	
	public function dash6aAction() {
	    //TA:17:17: 01/16/2015
	    //Trained persons chart
	    require_once('models/table/Dashboard-CHAI.php');
	    $tp_data = new DashboardCHAI();
	    $tp_details = $tp_data->fetchTPDetails(date('Y'), 3);
	    
	    $this->view->assign('tp_date', date('F Y'));
	    //GNR:use max commodity date
	    $tDate = new DashboardCHAI();
	    $sDate = $tDate->fetchTitleDate();
	    $this->view->assign('tp_date', $sDate['month_name'].' '.$sDate['year']);
	    
	    $this->view->assign('tp_data', $tp_details);
	    
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	}
	
	    
	    
	public function dash7Action() {
	    require_once('models/table/Dashboard-CHAI.php');
        $larc_data = new DashboardCHAI();
	    $fp_data = new DashboardCHAI();
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	    
	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $larc_details = $larc_data->fetchDashboardData('national_percent_facilities_hw_trained_larc');
	        $fp_details = $fp_data->fetchDashboardData('national_percent_facilities_hw_trained_fp');
	    }
	     
	    if (count($larc_details) > 0 && count($fp_details) > 0 ) { //got all
	    
	        $this->view->assign('larc_data', $larc_details);
	        $this->view->assign('fp_data', $fp_details);
	    } else {
	    
	        $where = ' 1=1 ';
	         
	        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	            $where = $where.' and f.location_id in (';
	            foreach ($_POST['region_c_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[2].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('l1.id');
	            $useName = 'L1_location_name';
	             
	        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	            $where = $where.' and l2.id in (';
	            foreach ($_POST['district_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[1].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('l2.id');
	            $useName = 'L2_location_name';
	             
	        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	            $where = $where.' and l2.parent_id in (';
	            foreach ($_POST['province_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[0].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('l3.id');
	            $useName = 'L3_location_name';
	        } else { // no geo selection
	            $group = 'l3.id';
	            $useName = 'L3_location_name';
	            $location = 'National';
	        }
	        
	    $geoWhere = str_replace(', )', ')', $where);
	    $trainingWhere = ' t.training_title_option_id = 1 ';
	    
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('$trainingWhere=', $trainingWhere);
	    //var_dump('$geoWhere=', $geoWhere);
	    //var_dump('$group=', $group);
	    //var_dump('$useName=', $useName);
	    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	    $larc_details = $larc_data->fetchPercentFacHWTrainedDetails($trainingWhere, $geoWhere, $group, $useName);
	    $this->view->assign('larc_data',$larc_details);
	    
	    $trainingWhere =  ' t.training_title_option_id = 2 ';
	    $fp_details = $fp_data->fetchPercentFacHWTrainedDetails($trainingWhere, $geoWhere, $group, $useName);
	    $this->view->assign('fp_data',$fp_details);
	    
	    } //else
	    	

	   	//$this->view->assign('date', date('F Y', strtotime("-1 months"))); //TA:17:18: take last month
	    //GNR:use max commodity date
	    $tDate = new DashboardCHAI();
	    $sDate = $tDate->fetchTitleDate();
	    $this->view->assign('date', $sDate['month_name'].' '.$sDate['year']); 
	    	
	    $this->viewAssignEscaped ('locations', Location::getAll() );

	}
	
	public function dash8Action() {
	    require_once('models/table/Dashboard-CHAI.php');
	    $larc_data = new DashboardCHAI();
	    $fp_data = new DashboardCHAI();
	    $inject_data = new DashboardCHAI();
	    
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	    
	    $id = $this->getSanParam ( 'id' );
	    
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	    
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	     
	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $larc_details = $larc_data->fetchDashboardData('national_percent_facilities_providing_larc');
	        $fp_details = $fp_data->fetchDashboardData('national_percent_facilities_providing_fp');
	        $inject_details = $inject_data->fetchDashboardData('national_percent_facilities_providing_inject');
	    }
	    
	    if (count($larc_details) > 0 && count($fp_details) > 0 && count($inject_details) > 0) { //got all
	         
	        $this->view->assign('larc_data', $larc_details);
	        $this->view->assign('fp_data', $fp_details);
	        $this->view->assign('inject_data', $inject_details);
	    
	    } else {
	         
	        $where = ' 1=1 ';
	    
	        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	            $where = $where.' and f.location_id in (';
	            foreach ($_POST['region_c_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[2].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('l1.id');
	            $useName = 'l1.location_name';
	    
	        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	            $where = $where.' and l2.id in (';
	            foreach ($_POST['district_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[1].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('l2.id');
	            $useName = 'l2.location_name';
	    
	        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	            $where = $where.' and l2.parent_id in (';
	            foreach ($_POST['province_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[0].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('l3.id');
	            $useName = 'l3.location_name';
	        } else { // no geo selection
	            $group = 'l3.id';
	            $useName = 'l3.location_name';
	            $location = 'National';
	        }
	         
	        $geoWhere = str_replace(', )', ')', $where);
	        
	        $dateWhere = 
" c.date between (select min(date) from commodity where month(date) = (select month(max(date)) from commodity) and year(date) = (select year(max(date)) from commodity) )
and (select max(date) from commodity where month(date) = (select month(max(date)) from commodity) and year(date) = (select year(max(date)) from commodity) ) ";
	        
	        //  larc   and ( cno.external_id in ('DiXDJRmPwfh', 'yJSLjbC9Gnr') and c.consumption > 0 ) -- Implant, IUCD inserted
	        // 	all    and ( cno.external_id in ('w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'DiXDJRmPwfh', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') and c.consumption > 0 ) -- Any FP
	        // 	inject and ( cno.external_id in ('ibHR9NQ0bKL') and c.consumption > 0 ) -- Family Planning Injections
	         
	        $cnoWhere = " cno.external_id in ('DiXDJRmPwfh', 'yJSLjbC9Gnr') and c.consumption > 0 ";
	        $larc_details = $larc_data->fetchPercentProvidingDetails($cnoWhere, $geoWhere, $dateWhere, $group, $useName);
	        $this->view->assign('larc_data',$larc_details);
	         
	        $cnoWhere = " cno.external_id in ('w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'DiXDJRmPwfh', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') and c.consumption > 0 ";
	        $fp_details = $fp_data->fetchPercentProvidingDetails($cnoWhere, $geoWhere, $dateWhere, $group, $useName);
	        $this->view->assign('fp_data',$fp_details);
	        
	        $cnoWhere = " cno.external_id in ('ibHR9NQ0bKL') and c.consumption > 0 ";
	        $inject_details = $inject_data->fetchPercentProvidingDetails($cnoWhere, $geoWhere, $dateWhere, $group, $useName);
	        $this->view->assign('inject_data',$inject_details);
	        
	        if ($location == 'National') {
	            $larc_details = $larc_data->insertDashboardData($larc_details, 'national_percent_facilities_providing_larc');
	            $fp_details = $fp_data->insertDashboardData($fp_details, 'national_percent_facilities_providing_fp');
	            $inject_details = $inject_data->insertDashboardData($inject_details, 'national_percent_facilities_providing_inject');
	        }
	        	    
	        //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController dash8Action >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        //var_dump('$cnoWhere=', $cnoWhere);
	        //var_dump('$geoWhere=', $geoWhere);
	        //var_dump('$group=', $group);
	        //var_dump('$useName=', $useName);
	        //var_dump('$fp_details=', $fp_details);
	        //var_dump('$larc_details=', $larc_details);
	        //var_dump('$inject_details=', $inject_details);
	        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	         
	    } //else
	     
	    //$this->view->assign('date', date('F Y', strtotime("-1 months"))); //TA:17:18: take last month
	    //GNR:use max commodity date
	    $tDate = new DashboardCHAI();
	    $sDate = $tDate->fetchTitleDate();
	    $this->view->assign('date', $sDate['month_name'].' '.$sDate['year']); 
	    
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	    
	}
	
	public function dash9Action() {
	
	    //if (! $this->hasACL ( 'edit_employee' )) {
	    //$this->doNoAccessError ();
	    //}
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    $whereClause =  "c.stock_out = 'Y'";
	
	    $stockOut_data = new DashboardCHAI();
	    $details = $stockOut_data->fetchPercentProvidingDetails($whereClause);
	    $this->view->assign('stockOut_data',$details);
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id);
	    //var_dump('details=', $details);
	    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	
	
	public function dash9aAction() {
	
	    //if (! $this->hasACL ( 'edit_employee' )) {
	    //$this->doNoAccessError ();
	    //}
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $fp_data = new DashboardCHAI();
	    $details = $fp_data->fetchDashboardData('percent_facilities_hw_trained_larc');
	    
	    if(count($details) > 0){
	       
	        $this->view->assign('larc_data11',$details);
	         
	        $fp_data = new DashboardCHAI();
	        $details = $fp_data->fetchDashboardData('percent_facilities_hw_trained_fp');
	        $this->view->assign('fp_data12',$details);
	         
	        $fp_data = new DashboardCHAI();
	        $details = $fp_data->fetchDashboardData('percent_facilities_providing_larc');
	        $this->view->assign('larc_data13',$details);
	         
	        $fp_data = new DashboardCHAI();
	        $details = $fp_data->fetchDashboardData('percent_facilities_providing_fp');
	        $this->view->assign('fp_data14',$details);
	    
	    } else {

	        $id = $this->getSanParam ( 'id' );
    	
    	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
    	
    	    //$geo_data = new DashboardCHAI();
    	    //$details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
    	    //$this->view->assign('geo_data',$details);
    	
    	    $whereClause =  't.training_title_option_id = 3 and pt.award_id in (1,2)';
    	
    	    $larc_data = new DashboardCHAI();
    	    $details = $larc_data->fetchPercentFacHWTrainedDetails($whereClause);
    	    $larc_data->insertDashboardData($details, 'percent_facilities_hw_trained_larc');
    	    $this->view->assign('larc_data11',$details);
    	    
    	    $whereClause =  't.training_title_option_id = 5 and pt.award_id in (1,2)';
    	    
    	    $fp_data = new DashboardCHAI();
    	    $details = $fp_data->fetchPercentFacHWTrainedDetails($whereClause);
    	    $fp_data->insertDashboardData($details, 'percent_facilities_hw_trained_fp');
    	    $this->view->assign('fp_data12',$details);
    	    
    	    $whereClause =  'cto.id in (6) and c.consumption <> 0';
    	    
    	    $larc_data = new DashboardCHAI();
    	    $details = $larc_data->fetchPercentProvidingDetails($whereClause);
    	    $larc_data->insertDashboardData($details, 'percent_facilities_providing_larc');
    	    $this->view->assign('larc_data13',$details);
    	    
    	    $whereClause =  'cto.id in (5) and c.consumption <> 0';
    	    
    	    $fp_data = new DashboardCHAI();
    	    $details = $fp_data->fetchPercentProvidingDetails($whereClause);
    	    $fp_data->insertDashboardData($details, 'percent_facilities_providing_fp');
    	    $this->view->assign('fp_data14',$details);
	    }
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id);
	    //var_dump('details=', $details);
	    //$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
	}
	
	
	public function dash9bAction() {
	    require_once('models/table/Dashboard-CHAI.php');
	    
	    // enclosing single quotes added later
	    $method = "w92UxLIRNTl','H8A8xQ9gJ5b','ibHR9NQ0bKL','DiXDJRmPwfh','yJSLjbC9Gnr','vDnxlrIQWUo','krVqq8Vk5Kw";
	    $request = $this->getRequest ();
	    
	    //$title_method = new DashboardCHAI();
	    //$title_method = $title_method->fetchTitleMethod($method);
	     
	    $title_date = new DashboardCHAI();
	    $title_date = $title_date->fetchTitleDate();
	     
	    $this->view->assign('title_date',  $title_method[commodity_name].', '. $title_date[month_name].' '. $title_date[year]);
	    
	    $cln_data = new DashboardCHAI();
	    $pfp_data = new DashboardCHAI();
	    $pfso_data = new DashboardCHAI();
	    $cs_data = new DashboardCHAI();
	    
	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $cln_details = $cln_data->fetchDashboardData('national_consumption_by_method');
	        $pfp_details = $pfp_data->fetchDashboardData('national_percent_facilities_providing');
	        $pfso_details = $pfso_data->fetchDashboardData('national_percent_facilities_stock_out');
	        $cs_details = $cs_data->fetchDashboardData('national_coverage_summary');
	    }
	    
	    if (count($cln_details) > 0 && count($pfp_details) > 0 && count($pfso_details) > 0 && isset($cs_details[last_date]) ) { //got all
	         
	        $this->view->assign('national_consumption_by_method', $cln_details);
	        $this->view->assign('national_percent_facilities_providing', $pfp_details);
	        $this->view->assign('national_percent_facilities_stock_out', $pfso_details);
	         
	        $this->view->assign('cs_fp_facility_count', round($cs_details['fp_facility_count']/$cs_details['total_facility_count_month'], 2));
	        $this->view->assign('cs_larc_facility_count', round($cs_details['larc_facility_count']/$cs_details['total_facility_count_month'], 2));
	        $this->view->assign('cs_fp_consumption_facility_count', round($cs_details['fp_consumption_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_larc_consumption_facility_count', round($cs_details['larc_consumption_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_larc_stock_out_facility_count', round($cs_details['larc_stock_out_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_fp_stock_out_facility_count', round($cs_details['fp_stock_out_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_date', date_format(date_create($cs_details['last_date']), 'F Y'));
	    
	    } else {
	         
	        $where = ' 1=1 ';
	    
	        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	            $where = $where.' and f.location_id in (';
	            foreach ($_POST['region_c_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[2].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L1_location_name, CNO_external_id');
	            $useName = 'L1_location_name';
	    
	        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	            $where = $where.' and l2.id in (';
	            foreach ($_POST['district_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[1].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L2_location_name, CNO_external_id');
	            $useName = 'L2_location_name';
	    
	        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	            $where = $where.' and l2.parent_id in (';
	            foreach ($_POST['province_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[0].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L3_location_name, CNO_external_id');
	            $useName = 'L3_location_name';
	        } else { // no geo selection
	            $group = 'CNO_external_id';
	            $useName = 'C_date';
	            $location = 'National';
	        }
	         
	        $where = str_replace(', )', ')', $where);
	        $whereClause = new Zend_Db_Expr($where);
	         
	        //$amc_details = $amc_data->fetchAMCDetails($whereClause);
	    
	        //file_put_contents('c:\wamp\logs\php_debug.log', 'dash996Action >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        //var_dump('amc_details= ', $amc_details, 'END');
	        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	        // $method is external_id and must be single quoted, likely meant to be int but had to convert table id to external_id
	        if( "'$method'" != '' ) $where = $where.' and cno.external_id in ( '."'$method'".' )';
	    
	        $cln_details = $cln_data->fetchCLNDetails('location', $id, $where, $group, $useName);
	         
	        //any
	        $where = " 1=1 and cno.external_id in ( 'w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'DiXDJRmPwfh', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') ";
	        $pfp_any_details = $pfp_data->fetchPFPDetails( $where );
	         
	        //larc
	        $where = " 1=1 and cno.external_id in ( 'DiXDJRmPwfh', 'yJSLjbC9Gnr')";
	        $pfp_larc_details = $pfp_data->fetchPFPDetails( $where );
	    
	        file_put_contents('c:\wamp\logs\php_debug.log', 'indexAction >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        var_dump('$pfp_any_details= ', $pfp_any_details, 'END');
	        var_dump('$pfp_larc_details= ', $pfp_larc_details, 'END');
	        $toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	         
	        $where = " 1=1 and (cno.external_id in ( 'DiXDJRmPwfh') and c.stock_out = 'Y') or  (cno.external_id in ( 'JyiR2cQ6DZT') and c.consumption = 1) ";
	        $pfso_details = $pfso_data->fetchPFSODetails( $where );
	    
	        $total = 0;
	         
	        foreach($pfp_any_details as $i => $row ){
	            $national_percent_facilities_providing[] =array('month' => $row['month'], 'year' => $row['year'], 'fp_percent' => $row['percent'], 'larc_percent' => $pfp_larc_details[$i]['percent'] );
	        }
	         
	        //file_put_contents('c:\wamp\logs\php_debug.log', 'dash9bAction >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        //var_dump('$pfso_details= ', $pfso_details, 'END');
	        //var_dump('$method= ', $method, 'END');
	        //var_dump('$national_percent_facilities_providing= ', $national_percent_facilities_providing, 'END');
	        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	         
	        foreach($pfso_details as $i => $row ){
	            $national_percent_facilities_stock_out[] =array('month' => $row['month'], 'year' => $row['year'], 'implant_percent' => $row['implant_percent'], 'seven_days_percent' => $row['seven_days_percent'] );
	        }
	    
	        foreach($cln_details as $i => $row ){
	    
	            if ( $location != 'National' ) {
	                switch($useName){
	                    case 'L1_location_name' :
	                        $location = $row['L1_location_name'];
	                        break;
	                    case 'L2_location_name' :
	                        $location = $row['L2_location_name'];
	                        break;
	                    case 'L3_location_name' :
	                        $location = $row['L3_location_name'];
	                        break;
	                }
	            }
	    
	            $locationNames = $locationNames ? $locationNames.', '.$location : $locationNames.$location;
	             
	            // remove single quotes and explode method
	            $bad_chars = array("'");
	            $method = str_replace($bad_chars, "", $method);
	            $methods =  array( explode(',', $method) );
	             
	            // lookup commodity_names
	            $title_method = new DashboardCHAI();
	            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][0]));
	            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][1]));
	            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][2]));
	            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][3]));
	            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][4]));
	            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][5]));
	            $CNO[] = array ($title_method->fetchTitleMethod($methods[0][6]));
	    
	            $national_consumption_by_method[] =array('method' => $CNO[0][0]['commodity_name'], 'consumption' => $row['consumption1'] );
	            $national_consumption_by_method[] =array('method' => $CNO[1][0]['commodity_name'], 'consumption' => $row['consumption2'] );
	            $national_consumption_by_method[] =array('method' => $CNO[2][0]['commodity_name'], 'consumption' => $row['consumption3'] );
	            $national_consumption_by_method[] =array('method' => $CNO[3][0]['commodity_name'], 'consumption' => $row['consumption4'] );
	            $national_consumption_by_method[] =array('method' => $CNO[4][0]['commodity_name'], 'consumption' => $row['consumption5'] );
	            $national_consumption_by_method[] =array('method' => $CNO[5][0]['commodity_name'], 'consumption' => $row['consumption6'] );
	            $national_consumption_by_method[] =array('method' => $CNO[6][0]['commodity_name'], 'consumption' => $row['consumption7'] );
	    
	            //file_put_contents('c:\wamp\logs\php_debug.log', 'dash996Action >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	            //var_dump('$methods= ', $methods, 'END');
	            //var_dump('$CNO= ', $CNO, 'END');
	            //var_dump('$consumption_by_method= ', $consumption_by_method, 'END');
	            //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	            $total = $total + $consumption_by_geo[$i]['consumption'];
	    
	        } // foreach cln
	    
	        if (is_null($national_consumption_by_method)) {
	            $consumption_by_geo[] = array('location' => 'No Data', 'consumption' => 0 );
	        }
	    
	        if ($total == 0) {
	            $total_consumption[] = array('location' => 'No Data', 'consumption' => 0 );
	        } else {
	            $total_consumption[] = array('location' => $locationNames, 'consumption' => $total );
	        }
	         
	         
	        $cs_data = new DashboardCHAI();
	        // specify date by "2014-12-01" or leave empty to get data for the last month
	        $cs_details = $cs_data->fetchCSDetails(null);
	        $this->view->assign('cs_fp_facility_count', round($cs_details['fp_facility_count']/$cs_details['total_facility_count_month'], 2));
	        $this->view->assign('cs_larc_facility_count', round($cs_details['larc_facility_count']/$cs_details['total_facility_count_month'], 2));
	        $this->view->assign('cs_fp_consumption_facility_count', round($cs_details['fp_consumption_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_larc_consumption_facility_count', round($cs_details['larc_consumption_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_larc_stock_out_facility_count', round($cs_details['larc_stock_out_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_fp_stock_out_facility_count', round($cs_details['fp_stock_out_facility_count']/$cs_details['total_facility_count'], 2));
	        $this->view->assign('cs_date', date_format(date_create($cs_details['last_date']), 'F Y'));
	         
	         
	        //file_put_contents('c:\wamp\logs\php_debug.log', 'dash9bAction >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        //var_dump('$cs_details= ', $cs_details, 'END');
	        //var_dump('$method= ', $method, 'END');
	        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	    
	        $this->view->assign('national_consumption_by_method', $national_consumption_by_method);
	        $this->view->assign('national_percent_facilities_providing', $national_percent_facilities_providing);
	        $this->view->assign('national_percent_facilities_stock_out', $national_percent_facilities_stock_out);
	    
	        if ($location == 'National') {
	            $cln_details = $cln_data->insertDashboardData($national_consumption_by_method, 'national_consumption_by_method');
	            $pfp_details = $pfp_data->insertDashboardData($national_percent_facilities_providing, 'national_percent_facilities_providing');
	            $pfso_details = $pfso_data->insertDashboardData($national_percent_facilities_stock_out, 'national_percent_facilities_stock_out');
	            $cs_details = $cs_data->insertDashboardData($cs_details, 'national_coverage_summary');
	        }
	         
	    }  // else
	     
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	    
	} // dash9bAction
	
	   
	
	public function dash10Action() {
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	    
	    $tier_data = new DashboardCHAI();
	    $tier = ($id != "") ? $tier = $tier_data->fetchTier( $id): "";
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	    
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    $whereClause = ($id ==  "") ? 'l3.tier = 1' : 'l2.parent_id = ' . $id ;
	    
	    $groupClause = ($id == "") ? 
	      new Zend_Db_Expr("L1_id, CNO_id") 
	    : 
	      new Zend_Db_Expr("L2_id, CNO_id");
	    
	    $useName = ($id == "") ? 'L3_location_name' : 'L2_location_name';
	    
	    $location_data = new DashboardCHAI();
	    $details = $location_data->fetchCLNDetails('location', $id, $whereClause, $groupClause, $useName);
	    
	    $this->view->assign('latest_consumption_data',$details);
	    
	    if ($id != '') $tier = $tier_data->fetchTier( $id);
	    
	    if (count($details) == 0) { 
	        
	        if ($tier == 3){
	            //use facility
	            $whereClause = 'f.location_id = ' . $id;
	            $groupClause = new Zend_Db_Expr("F_id, CNO_id");
	            //$groupClause = 'F_facility_name';
	            $useName = 'F_facility_name';
	        }
	        else {
	           $whereClause = 'l1.parent_id = ' . $id;
	           $groupClause = new Zend_Db_Expr("F_id, CNO_id");
	           //$groupClause = 'L1_location_name';
	           $useName = 'L1_location_name';
	        //$this->view->assign('geo_data',array()); // at bottom
	        }
	        
	        $facility_data = new DashboardCHAI();
	        $details = $facility_data->fetchCLNDetails('facility', $id, $whereClause, $groupClause, $useName);
	        $this->view->assign('latest_consumption_data',$details);
	        
	    }
	    
	    $title_data = new DashboardCHAI();
	    $details = $title_data->fetchTitleData();
	    
	     
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 654>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id); var_dump('tier=', $tier);
	    //var_dump('details=', $details);
	    //var_dump('title=', $details[C_date]);
	    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	    
	    $this->view->assign('title_data', $details[month_name].', '. $details[year]);
	}
	
	public function dash10aAction() {
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	     
	    $tier_data = new DashboardCHAI();
	    $tier = ($id != "") ? $tier = $tier_data->fetchTier( $id): "";
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	     
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    $whereClause = ($id ==  "") ? 'l3.tier = 1' : 'l2.parent_id = ' . $id ;
	     
	    $groupClause = ($id == "") ?
	    new Zend_Db_Expr("L1_id, CNO_id")
	    :
	    new Zend_Db_Expr("L2_id, CNO_id");
	     
	    $useName = ($id == "") ? 'L3_location_name' : 'L2_location_name';
	     
	    $location_data = new DashboardCHAI();
	    $details = $location_data->fetchCLNDetails('location', $id, $whereClause, $groupClause, $useName);
	     
	    $this->view->assign('latest_consumption_data',$details);
	     
	    if ($id != '') $tier = $tier_data->fetchTier( $id);
	     
	    if (count($details) == 0) {
	         
	        if ($tier == 3){
	            //use facility
	            $whereClause = 'f.location_id = ' . $id;
	            $groupClause = new Zend_Db_Expr("F_id, CNO_id");
	            //$groupClause = 'F_facility_name';
	            $useName = 'F_facility_name';
	        }
	        else {
	            $whereClause = 'l1.parent_id = ' . $id;
	            $groupClause = new Zend_Db_Expr("F_id, CNO_id");
	            //$groupClause = 'L1_location_name';
	            $useName = 'L1_location_name';
	            //$this->view->assign('geo_data',array()); // at bottom
	        }
	         
	        $facility_data = new DashboardCHAI();
	        $details = $facility_data->fetchCLNDetails('facility', $id, $whereClause, $groupClause, $useName);
	        $this->view->assign('latest_consumption_data',$details);
	         
	    }
	     
	    $title_data = new DashboardCHAI();
	    $details = $title_data->fetchTitleData();
	     
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 654>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('id=', $id); var_dump('tier=', $tier);
	    //var_dump('details=', $details);
	    //var_dump('title=', $details[C_date]);
	    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	     
	    $this->view->assign('title_data', $details[month_name].', '. $details[year]);
	}
	
	public function dash11Action() {
	    //if (! $this->hasACL ( 'edit_employee' )) {
	    //$this->doNoAccessError ();
	    //}
	    
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	    
	    $fp_data = new DashboardCHAI();
	    $details = $fp_data->fetchDashboardData('percent_facilities_hw_trained_larc');
	    $this->view->assign('larc_data11',$details);
	    
	    $fp_data = new DashboardCHAI();
	    $details = $fp_data->fetchDashboardData('percent_facilities_hw_trained_fp');
	    $this->view->assign('fp_data12',$details);
	    
	    $fp_data = new DashboardCHAI();
	    $details = $fp_data->fetchDashboardData('percent_facilities_providing_larc');
	    $this->view->assign('larc_data13',$details);
	    
	    $fp_data = new DashboardCHAI();
	    $details = $fp_data->fetchDashboardData('percent_facilities_providing_fp');
	    $this->view->assign('fp_data14',$details);
	  
	}
	
	public function dash12Action() {
	    
	    require_once('models/table/Dashboard-CHAI.php');
	    $larc_data = new DashboardCHAI();
	    $fp_data = new DashboardCHAI();
	    
	    	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $larc_details = $larc_data->fetchDashboardData('national_larc_coverage');
	        $fp_details = $fp_data->fetchDashboardData('national_fp_coverage');
	    }
	    
	    if (count($larc_details) > 0 && count($fp_details) > 0 ) { //got all
	         
	        $this->view->assign('larc_coverage', $larc_details);
	        $this->view->assign('fp_coverage', $fp_details);
	    
	    } else {
	    
	    $where = ' 1=1 ';
	     
	    if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	        $where = $where.' and f.location_id in (';
	        foreach ($_POST['region_c_id'] as $i => $value){
	            $geo = explode('_',$value);
	            $where = $where.$geo[2].', ';
	        }
	        $where = $where.') ';
	        $group = new Zend_Db_Expr('l1.id');
	        $useName = 'l1.location_name';
	         
	    } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	        $where = $where.' and l2.id in (';
	        foreach ($_POST['district_id'] as $i => $value){
	            $geo = explode('_',$value);
	            $where = $where.$geo[1].', ';
	        }
	        $where = $where.') ';
	        $group = new Zend_Db_Expr('l2.id');
	        $useName = 'l2.location_name';
	         
	    } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	        $where = $where.' and l2.parent_id in (';
	        foreach ($_POST['province_id'] as $i => $value){
	            $geo = explode('_',$value);
	            $where = $where.$geo[0].', ';
	        }
	        $where = $where.') ';
	        $group = new Zend_Db_Expr('l3.id');
	        $useName = 'l3.location_name';
	    } else { // no geo selection
	        $group = 'l3.id';
	        $useName = 'l3.location_name';
	        $location = 'National';
	    }

	    $pftp_data = new DashboardCHAI();
	    
	    $geoWhere = str_replace(', )', ')', $where);
	    $dateWhere = '1=1';
	     
	    $cnoWhere = " 1=1 and cno.external_id in ( 'DiXDJRmPwfh', 'yJSLjbC9Gnr') and c.consumption > 0 "; // implants, IUD's = LARC 
	    $ttoWhere = " 1=1 and t.training_title_option_id = 1";
	    $stockoutWhere = " cno.external_id in ( 'DiXDJRmPwfh' ) and c.stock_out = 'Y' "; 

	   $pftp_details = $pftp_data->fetchPFTPDetails( $cnoWhere, $ttoWhere, $geoWhere, $dateWhere, $stockoutWhere, $group, $useName );
	     
	    // pivot
	    //TA:17:17 add year to the result
	    foreach ($pftp_details as $i => $row){
	        $larc_coverage[] = array('month' => $pftp_details[$i]['month'], 'year' => $pftp_details[$i]['year'], 
	            'tp_percent' => $pftp_details[$i]['tp_percent'], 
	            'tso_percent' => $pftp_details[$i]['tso_percent'], 
	            'tt_percent' => $pftp_details[$i]['tt_percent']);
	    }
	    
	    $cnoWhere = " 1=1 and cno.external_id in ( 'w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'DiXDJRmPwfh', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') and c.consumption > 0 ";
	    $ttoWhere = " 1=1 and t.training_title_option_id = 2 ";
	    $stockoutWhere = " cno.external_id in ( 'JyiR2cQ6DZT' ) ";
	    $pftp_details = $pftp_data->fetchPFTPDetails( $cnoWhere, $ttoWhere, $geoWhere, $dateWhere, $stockoutWhere, $group, $useName );
	
	    // pivot
	    //TA:17:17 add year to the result
	    foreach ($pftp_details as $i => $row){
	        $fp_coverage[] = array('month' => $pftp_details[$i]['month'], 'year' => $pftp_details[$i]['year'],
	            'tp_percent' => $pftp_details[$i]['tp_percent'],
	            'tso_percent' => $pftp_details[$i]['tso_percent'],
	            'tt_percent' => $pftp_details[$i]['tt_percent']);
	    }
	
	    //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController action12 >'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	    //var_dump('$pfp_details= ', $pfp_details,"END");
	    //var_dump('$pftp_details= ', $pftp_details,"END");
	    //var_dump('$pft_details= ', $pft_details,"END");
	    //var_dump('$larc_coverage= ', $larc_coverage,"END");
	    //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	     
	    $this->view->assign('larc_coverage',array_reverse($larc_coverage));
	    $this->view->assign('fp_coverage',array_reverse($fp_coverage));
	    
	    if ($location == 'National') {
	        $larc_details = $larc_data->insertDashboardData(array_reverse($larc_coverage), 'national_larc_coverage');
	        $fp_details = $fp_data->insertDashboardData(array_reverse($fp_coverage), 'national_fp_coverage');
	    }
	    
	    } // else
	     
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	}
	

	
	
	public function dash13Action() {
	    require_once('models/table/Dashboard-CHAI.php');
	    $larc_data = new DashboardCHAI();
	    $fp_data = new DashboardCHAI();
	     
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	     
	    $id = $this->getSanParam ( 'id' );
	     
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	     
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $larc_details = $larc_data->fetchDashboardData('PercentFacHWTrainedProvidingLarc');
	        $fp_details = $fp_data->fetchDashboardData('PercentFacHWTrainedProvidingFP');
	    }
	     
	    if (count($larc_details) > 0 && count($fp_details) > 0 ) { //got all
	
	        $this->view->assign('larc_data', $larc_details);
	        $this->view->assign('fp_data', $fp_details);
	         
	    } else {
	
	        $where = ' 1=1 ';
	         
	        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	            $where = $where.' and f.location_id in (';
	            foreach ($_POST['region_c_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[2].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L1_location_name, CNO_external_id');
	            $useName = 'L1_location_name';
	             
	        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	            $where = $where.' and l2.id in (';
	            foreach ($_POST['district_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[1].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L2_location_name, CNO_external_id');
	            $useName = 'L2_location_name';
	             
	        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	            $where = $where.' and l2.parent_id in (';
	            foreach ($_POST['province_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[0].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L3_location_name, CNO_external_id');
	            $useName = 'L3_location_name';
	        } else { // no geo selection
	            $group = 'CNO_external_id';
	            $useName = 'L3_location_name';
	            $location = 'National';
	        }
	
	        $geoWhere = str_replace(', )', ')', $where);
	        $trainingWhere = ' t.training_title_option_id = 1 ';
	        $cnoWhere = " cno.external_id in ('DiXDJRmPwfh') and c.consumption > 0 ";
	
	        $larc_details = $larc_data->fetchPercentFacHWTrainedProvidingDetails($trainingWhere, $cnoWhere, $geoWhere, $group, $useName);
	        $this->view->assign('larc_data',$larc_details);
	
	        $trainingWhere =  ' t.training_title_option_id = 2 ';
	        $cnoWhere = " cno.external_id in ('w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'DiXDJRmPwfh', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') and c.consumption > 0 ";
	        $fp_details = $fp_data->fetchPercentFacHWTrainedProvidingDetails($trainingWhere, $cnoWhere, $geoWhere, $group, $useName);
	        $this->view->assign('fp_data',$fp_details);
	
	        if ($location == 'National') {
	            $larc_details = $larc_data->insertDashboardData($larc_details, 'PercentFacHWTrainedProvidingLarc');
	            $fp_details = $fp_data->insertDashboardData($fp_details, 'PercentFacHWTrainedProvidingFP');
	        }
	        
	        //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        //var_dump('$trainingWhere=', $trainingWhere);
	        //var_dump('$geoWhere=', $geoWhere);
	        //var_dump('$group=', $group);
	        //var_dump('$useName=', $useName);
	        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	        	    
	
	    } //else
	
	   //$this->view->assign('date', date('F Y', strtotime("-1 months"))); //TA:17:18: take last month
	    //GNR:use max commodity date
	    $tDate = new DashboardCHAI();
	    $sDate = $tDate->fetchTitleDate();
	    $this->view->assign('date', $sDate['month_name'].' '.$sDate['year']); 
	    
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	     
	     
	} //dashAction13
	
	public function dash14Action() {
	    require_once('models/table/Dashboard-CHAI.php');
	
	    $request = $this->getRequest ();
	
	    //$title_method = new DashboardCHAI();
	    //$title_method = $title_method->fetchTitleMethod($method);
	     
	    $title_date = new DashboardCHAI();
	    $title_date = $title_date->fetchTitleDate();
	     
	    $this->view->assign('title_date',  $title_method[commodity_name].', '. $title_date[month_name].', '. $title_date[year]);
	
	    $pfso_data = new DashboardCHAI();
	
	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $pfso_details = $pfso_data->fetchDashboardData('national_percent_facilities_stock_out');
	    }
	
	    if (count($pfso_details) > 0 ) { //got all
	         
	        $this->view->assign('national_percent_facilities_stock_out', $pfso_details);
	
	    } else {
	         
	        $where = ' 1=1 ';
	
	        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	            $where = $where.' and f.location_id in (';
	            foreach ($_POST['region_c_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[2].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L1_location_name, CNO_external_id');
	            $useName = 'L1_location_name';
	
	        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	            $where = $where.' and l2.id in (';
	            foreach ($_POST['district_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[1].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L2_location_name, CNO_external_id');
	            $useName = 'L2_location_name';
	
	        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	            $where = $where.' and l2.parent_id in (';
	            foreach ($_POST['province_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[0].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L3_location_name, CNO_external_id');
	            $useName = 'L3_location_name';
	        } else { // no geo selection
	            $group = 'CNO_external_id';
	            $useName = 'C_date';
	            $location = 'National';
	        }
	         
	        $where = str_replace(', )', ')', $where);
	        $whereClause = new Zend_Db_Expr($where);
	         
	        $where = " 1=1 and (cno.external_id in ( 'DiXDJRmPwfh') and c.stock_out = 'Y') or  (cno.external_id in ( 'JyiR2cQ6DZT') and c.consumption = 1) ";
	        $pfso_details = $pfso_data->fetchPFSODetails( $where );
	
	        foreach($pfso_details as $i => $row ){
	            $national_percent_facilities_stock_out[] =array('month' => $row['month'], 'year' => $row['year'], 'implant_percent' => $row['implant_percent'], 'seven_days_percent' => $row['seven_days_percent'] );
	        }
	
	        $this->view->assign('national_percent_facilities_stock_out', $national_percent_facilities_stock_out);
	
	        if ($location == 'National') {
	            $pfso_details = $pfso_data->insertDashboardData($national_percent_facilities_stock_out, 'national_percent_facilities_stock_out');
	        }
	         
	    }  // else
	     
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	
	} // dash14Action
		
	public function dash15Action() {
	    require_once('models/table/Dashboard-CHAI.php');
	    $larc_data = new DashboardCHAI();
	    $fp_data = new DashboardCHAI();
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $larc_details = $larc_data->fetchDashboardData('PercentFacHWTrainedStockOutLarc');
	        $fp_details = $fp_data->fetchDashboardData('PercentFacHWTrainedStockOutFP');
	    }
	
	    if (count($larc_details) > 0 && count($fp_details) > 0 ) { //got all
	
	        $this->view->assign('larc_data', $larc_details);
	        $this->view->assign('fp_data', $fp_details);
	
	    } else {
	
	        $where = ' 1=1 ';
	
	        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	            $where = $where.' and f.location_id in (';
	            foreach ($_POST['region_c_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[2].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L1_location_name, CNO_external_id');
	            $useName = 'l1.location_name';
	
	        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	            $where = $where.' and l2.id in (';
	            foreach ($_POST['district_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[1].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L2_location_name, CNO_external_id');
	            $useName = 'l2.location_name';
	
	        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	            $where = $where.' and l2.parent_id in (';
	            foreach ($_POST['province_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[0].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L3_location_name, CNO_external_id');
	            $useName = 'l3.location_name';
	        } else { // no geo selection
	            $group = 'CNO_external_id';
	            $useName = 'l3.location_name';
	            $location = 'National';
	        }
	
	        $geoWhere = str_replace(', )', ')', $where);
	        
	        $cnoStockOutWhere =  " cno.external_id in ('DiXDJRmPwfh') and c.stock_out = 'Y' and c.date = (select max(date) from commodity) ";
	        $trainingWhere = " t.training_title_option_id = 1 ";
	        $sixMonthWhere = "cno.external_id in ('DiXDJRmPwfh') and c.consumption > 0 and c.date between date_sub(now(), interval 182 day) and now() ";
	        
	        $larc_details = $larc_data->fetchPercentFacHWTrainedStockOutDetails($trainingWhere, $cnoStockOutWhere, $sixMonthWhere, $geoWhere, $group, $useName);
	        $this->view->assign('larc_data',$larc_details);
	        
	        $cnoStockOutWhere =  " cno.external_id in ('JyiR2cQ6DZT') and c.date = (select max(date) from commodity) ";
	        $trainingWhere = " t.training_title_option_id = 2 ";
	        $sixMonthWhere = "cno.external_id in ('w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'DiXDJRmPwfh', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') and c.consumption > 0 and c.date between date_sub(now(), interval 182 day) and now() ";
	        
	        $fp_details = $fp_data->fetchPercentFacHWTrainedStockOutDetails($trainingWhere, $cnoStockOutWhere, $sixMonthWhere, $geoWhere, $group, $useName);
	        $this->view->assign('fp_data',$fp_details);
	        
	        if ($location == 'National') {
	            $larc_details = $larc_data->insertDashboardData($larc_details, 'PercentFacHWTrainedStockOutLarc'); 
	            $fp_details = $fp_data->insertDashboardData($fp_details, 'PercentFacHWTrainedStockOutFP');
	        }
	        
	        //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        //var_dump('$trainingWhere=', $trainingWhere);
	        //var_dump('$geoWhere=', $geoWhere);
	        //var_dump('$group=', $group);
	        //var_dump('$useName=', $useName);
	        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	
	
	    } //else
	    //$this->view->assign('date', date('F Y', strtotime("-1 months"))); //TA:17:18: take last month
	    //GNR:use max commodity date
	    $tDate = new DashboardCHAI();
	    $sDate = $tDate->fetchTitleDate();
	    $this->view->assign('date', $sDate['month_name'].' '.$sDate['year']); 
	    
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	
	
	} //dashAction15
	
	public function dash16Action() {
	    require_once('models/table/Dashboard-CHAI.php');
	    $larc_data = new DashboardCHAI();
	    $fp_data = new DashboardCHAI();
	
	    require_once('models/table/Dashboard-CHAI.php');
	    $this->view->assign('title',$this->t['Application Name'].space.t('CHAI').space.t('Dashboard'));
	
	    $id = $this->getSanParam ( 'id' );
	
	    $whereClause = ($id ==  "") ? 'tier = 1' : 'parent_id = ' . $id ;
	
	    $geo_data = new DashboardCHAI();
	    $details = $geo_data->fetchConsumptionDetails('geo', $id, $whereClause);
	    $this->view->assign('geo_data',$details);
	
	    // geo selection includes "--choose--" or no selection
	    if( ( isset($_POST["region_c_id"] ) && $_POST["region_c_id"][0] == "" ) ||
	        ( isset($_POST["district_id"] ) && $_POST["district_id"][0] == "" ) ||
	        ( isset($_POST["province_id"] ) && $_POST["province_id"][0] == "" ) ||
	        (!isset($_POST["region_c_id"] ) && !isset($_POST["district_id"] ) && !isset($_POST["province_id"] ) ) ){
	        //get national numbers from refresh
	        $larc_details = $larc_data->fetchDashboardData('PercentFacHWProvidingStockOutLarc');
	        $fp_details = $fp_data->fetchDashboardData('PercentFacHWProvidingStockOutFP');
	    }
	
	    if (count($larc_details) > 0 && count($fp_details) > 0 ) { //got all
	
	        $this->view->assign('larc_data', $larc_details);
	        $this->view->assign('fp_data', $fp_details);
	
	    } else {
	
	        $where = ' 1=1 ';
	
	        if( isset($_POST["region_c_id"]) ){ // CHAINigeria LGA
	            $where = $where.' and f.location_id in (';
	            foreach ($_POST['region_c_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[2].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L1_location_name');
	            $useName = 'L1_location_name';
	
	        } else if( isset($_POST['district_id']) ){ // CHAINigeria state
	            $where = $where.' and l2.id in (';
	            foreach ($_POST['district_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[1].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L2_location_name');
	            $useName = 'L2_location_name';
	
	        } else if( isset($_POST['province_id']) ){ //province_id is a Trainsmart internal name, represents hightest CHAINigeria level = GPZ
	            $where = $where.' and l2.parent_id in (';
	            foreach ($_POST['province_id'] as $i => $value){
	                $geo = explode('_',$value);
	                $where = $where.$geo[0].', ';
	            }
	            $where = $where.') ';
	            $group = new Zend_Db_Expr('L3_location_name');
	            $useName = 'L3_location_name';
	        } else { // no geo selection
	            $group = 'L3_location_name';
	            $useName = 'L3_location_name';
	            $location = 'National';
	        }
	
	        $geoWhere = str_replace(', )', ')', $where);
	        
	        $cnoStockOutWhere =  " (cno.external_id in ('DiXDJRmPwfh') and c.stock_out = 'Y' and c.date = (select max(date) from commodity) 
	                               or cno.external_id in ('DiXDJRmPwfh') and c.consumption > 0 and c.date between date_sub(now(), interval 182 day) and now()) ";
	         $larc_details = $larc_data->fetchPercentFacHWProvidingStockOutDetails($cnoStockOutWhere, $geoWhere, $group, $useName);
	         $this->view->assign('larc_data',$larc_details);
	        
	        $cnoStockOutWhere =  " (cno.external_id in ('JyiR2cQ6DZT') and c.date = (select max(date) from commodity)
	                               or cno.external_id in ('w92UxLIRNTl', 'H8A8xQ9gJ5b', 'ibHR9NQ0bKL', 'DiXDJRmPwfh', 'yJSLjbC9Gnr', 'vDnxlrIQWUo', 'krVqq8Vk5Kw') and c.consumption > 0 and c.date between date_sub(now(), interval 182 day) and now()) ";
	        $fp_details = $fp_data->fetchPercentFacHWProvidingStockOutDetails($cnoStockOutWhere, $geoWhere, $group, $useName);
	        $this->view->assign('fp_data',$fp_details);
	        
	        if ($location == 'National') {
	            $larc_details = $larc_data->insertDashboardData($larc_details, 'PercentFacHWProvidingStockOutLarc');
	            $fp_details = $fp_data->insertDashboardData($fp_details, 'PercentFacHWProvidingStockOutFP');
	        }
	
	        //file_put_contents('c:\wamp\logs\php_debug.log', 'DashboardController 297>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
	        //var_dump('$trainingWhere=', $trainingWhere);
	        //var_dump('$geoWhere=', $geoWhere);
	        //var_dump('$group=', $group);
	        //var_dump('$useName=', $useName);
	        //$toss = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $toss .PHP_EOL, FILE_APPEND | LOCK_EX);
	
	
	    } //else
	    //$this->view->assign('date', date('F Y', strtotime("-1 months"))); //TA:17:18: take last month
	    //GNR:use max commodity date
	    $tDate = new DashboardCHAI();
	    $sDate = $tDate->fetchTitleDate();
	    $this->view->assign('date', $sDate['month_name'].' '.$sDate['year']);
	     
	    $this->viewAssignEscaped ('locations', Location::getAll() );
	
	
	} //dashAction16
	
	public function reportsAction() {
		
	}

	/**
	 * AJAX course add/delete/edit ... for employee_edit
	 *
	 * see: employee_course_table.phtml
	 */
	public function coursesAction()
	{
		try {
			if (! $this->hasACL ( 'employees_module' )) {
				if($this->_getParam('outputType') == 'json') {
					$this->sendData(array('msg'=>'Not Authorized'));
					exit();
					return;
				}
				$this->doNoAccessError ();
			}

			$ret    = array();
			$params = $this->getAllParams();

			if ($params['mode'] == 'addedit') {
				// add or update a record based on $params[id]
				if( empty($params['id']) )
					unset( $params['id'] ); // unset ID (primary key) for Zend if 0 or '' (insert new record)
				$id = $this->_findOrCreateSaveGeneric('employee_to_course', $params); // wrapper for find or create
				$params['id'] = $id;
				if($id){
					// saved
					// reload the data
					$db = $this->dbfunc();
					$ret = $db->fetchRow("select * from employee_to_course where id = $id");
					$ret['msg'] = 'ok';
				} else {
					$ret['errored'] = true;
					$ret['msg']     = t('Error creating record.');
				}
			}
			else if($params['mode'] == 'delete' && $params['id']) {
				// delete a record
				try {
					$course_link_table = new ITechTable ( array ('name' => 'employee_to_course' ) );
					$num_rows = $course_link_table->delete('id = ' . $params['id']);
					if (! $num_rows )
						$ret['msg'] = t('Error finding that record in the database.');
					$ret['num_rows'] = $num_rows;
				} catch (Exception $e) {
					$ret['errored'] = true;
					$ret['msg']     = t('Error finding that record in the database.');
				}
			}

			if(strtolower($params['outputType']) == 'json'){
				$this->sendData($ret); // probably always json no need to check for output
			}

		}
		catch (Exception $e) {
			if($this->_getParam('outputType') == 'json') {
				$this->sendData(array('errored' => true, 'msg'=>'Error: ' . $e->getMessage()));
				return;
			} else {
				echo $e->getMessage();
			}
		}
	}

	private function getCourses($employee_id){
		if (!$employee_id)
			return;

		$db = $this->dbfunc();
		$sql = "SELECT * FROM employee_to_course WHERE employee_id = $employee_id";
		$rows = $db->fetchAll($sql);
		return $rows ? $rows : array();
	}
	
	public function addFunderToEmployeeAction() {
		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}
	
		require_once('models/table/Partner.php');
		require_once('views/helpers/Location.php'); // funder stuff
	
		$db     = $this->dbfunc();
		$status = ValidationContainer::instance ();
		$params = $this->getAllParams();
		$id     = $params['id'];
			
		if ($id) {
			$helper = new Helper();
				
			if ( $this->getRequest()->isPost() ) {
	
				//$params['funding_end_date'] = $this->_array_me($params['funding_end_date']);
				//foreach ($params['funding_end_date'] as $i => $value) $params['funding_end_date'][$i] = $this->_euro_date_to_sql($value);
	
				//file_put_contents('c:\wamp\logs\php_debug.log', 'empCont isPost 172> isPost'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
				//var_dump($params);
				//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);			
				
				// test for all values
				if(!($params['subPartner'] && $params['partnerFunder'] && $params['mechanism'] && $params['percentage']))
					$status->addError('', t ( 'All fields' ) . space . t('are required'));
	
				if ( $status->hasError() )
					$status->setStatusMessage( t('That funding mechanism could not be saved.') );
					
				else {
					//save
					$epsfm = new ITechTable(array('name' => 'employee_to_partner_to_subpartner_to_funder_to_mechanism'));
					$psfmArr = explode('_', $params[mechanism]); // eg: 13_13_3_106
					$psfm_id = $helper->getPsfmId($psfmArr);
					$data = array(
							'partner_to_subpartner_to_funder_to_mechanism_id' => $psfm_id['id'],
							'employee_id' => $params['id'],
							'partner_id' => $psfmArr[0],
							'subpartner_id'  => $psfmArr[1],
							'partner_funder_option_id' => $psfmArr[2],
							'mechanism_option_id' => $psfmArr[3],
							'percentage' => $params['percentage'],
					);
						
					//file_put_contents('c:\wamp\logs\php_debug.log', 'empCont isPost 192> isPost'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
					//var_dump($data);
					//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
						
					$insert_result = $epsfm->insert($data);
					$status->setStatusMessage( t('The funding mechanism was saved.') );
					//$this->_redirect("admin/employee-build_funding");
					//$this->_redirect("partner/edit/" . $params['id']);
				}
			}
				
			//exclude current funders
			$employee = $helper->getEmployee($id);
			$this->viewAssignEscaped ( 'employee', $employee );
			
			$partner = $helper->getPsfmPartnerExclude($id);
			$this->viewAssignEscaped ( 'partner', $partner );
				
			$subPartner = $helper->getPsfmSubPartnerExclude($id, $employee[0]['partner_id']);
			$this->viewAssignEscaped ( 'subPartner', $subPartner );
				
			$partnerFunder = $helper->getPsfmFunderExclude($id, $employee[0]['partner_id']);
			$this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );
				
			$mechanism = $helper->getPsfmMechanismExclude($id, $employee[0]['partner_id']);
			$this->viewAssignEscaped ( 'mechanism', $mechanism );
				
			//file_put_contents('c:\wamp\logs\php_debug.log', 'empCont 219>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
			//var_dump($subPartner);
			//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
				
		} // if ($id)
	
		//validate
		$this->view->assign ( 'status', $status );
	}

	public function addAction() {
		$this->view->assign('mode', 'add');
		$this->view->assign ( 'pageTitle', t ( 'Add New' ).' '.t( 'Employee' ) );
		return $this->editAction ();
	}

	public function deleteAction() {
		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}

		require_once('models/table/Employee.php');
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );

		if ($id) {
			$employee = new Employee ( );
			$rows = $employee->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$employee->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That employee was deleted.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That employee could not be found.' ) );
		}

		//validate
		$this->view->assign ( 'status', $status );

	}
	
	public function deleteFunderAction() {
		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}
	
		require_once('models/table/Partner.php');
		require_once('views/helpers/Location.php'); // funder stuff
	
		$db     = $this->dbfunc();
		$status = ValidationContainer::instance ();
		$params = $this->getAllParams();
			
		if ($params['id']) {
			$recArr = explode('_', $params['id']);
			
				//find in epsfm, should find to delete
				$sql = 'SELECT * FROM employee_to_partner_to_subpartner_to_funder_to_mechanism  WHERE '; // .$id.space.$orgWhere;
				$where = "employee_id = $recArr[0] and  partner_id = $recArr[1] and subpartner_id = $recArr[2] and partner_funder_option_id = $recArr[3] and mechanism_option_id = $recArr[4] and is_deleted = false";
				$sql .= $where;
					
				$row = $db->fetchRow( $sql );
				if (! $row){
					$status->setStatusMessage ( t('Cannot find that record in the database.') );
					//file_put_contents('c:\wamp\logs\php_debug.log', 'That record could not be found.'.PHP_EOL, FILE_APPEND | LOCK_EX);
				}
					
				else { // found, safe to delete
	
					//file_put_contents('c:\wamp\logs\php_debug.log', 'Ready to delete '.$row['id'].PHP_EOL, FILE_APPEND | LOCK_EX);
					$update_result = $db->update('employee_to_partner_to_subpartner_to_funder_to_mechanism', array('is_deleted' => 1), 'id = '.$row['id']);
					var_dump($update_result);
	
					if($update_result){
						$status->setStatusMessage ( t ( 'That mechanism was deleted.' ) );
						//file_put_contents('c:\wamp\logs\php_debug.log', 'That record was deleted.'.PHP_EOL, FILE_APPEND | LOCK_EX);
					}
					else{
						$status->setStatusMessage ( t ( 'That mechanism was not deleted.' ) );
						//file_put_contents('c:\wamp\logs\php_debug.log', 'That record was not deleted.'.PHP_EOL, FILE_APPEND | LOCK_EX);
					}
				}
				
			//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
		}
		$this->_redirect("employee/edit/id/" . $row['employee_id']);
	}
	

	public function editAction() {
		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}

		$db = $this->dbfunc();
		$status = ValidationContainer::instance ();
		$params = $this->getAllParams();
		$id = $params['id'];
		$partner_id = $params['partner_id'];

		// restricted access?? only show partners by organizers that we have the ACL to view
		$org_allowed_ids = allowed_org_access_full_list($this);               // doesnt have acl 'training_organizer_option_all'
		if ($org_allowed_ids && $this->view->mode != 'add' && $id != "") {    // doesnt have acl & is edit mode.
			if ($partnerID = $db->fetchOne("SELECT partner_id FROM employee WHERE employee.id = ?", $id)) {
				$validID = $db->fetchCol("SELECT partner.id FROM partner WHERE partner.id = ? AND partner.organizer_option_id in ($org_allowed_ids)", $partnerID); // check for both
				if(empty($validID))
					$this->doNoAccessError ();
			}
		}

		if ( $this->getRequest()->isPost() )
		{
			//validate then save
			$params['location_id'] = regionFiltersGetLastID( '', $params );
			$params['dob']                      = $this->_euro_date_to_sql( $params['dob'] );
			$params['agreement_end_date']       = $this->_euro_date_to_sql( $params['agreement_end_date'] );
			$params['transition_date']          = $this->_euro_date_to_sql( $params['transition_date'] );
			$params['transition_complete_date'] = $this->_euro_date_to_sql( $params['transition_complete_date'] );
			$params['site_id']                  = $params['facilityInput'];
			$params['option_nationality_id']    = $params['lookup_nationalities_id'];
			$params['facility_type_option_id']  = $params['employee_site_type_option_id'];
			$params['race_option_id']           = $params['person_race_option_id'];

			// $status->checkRequired ( $this, 'first_name', t ( 'Frist Name' ) );
			// $status->checkRequired ( $this, 'last_name',  t ( 'Last Name' ) );
			
			$status->checkRequired ( $this, 'employee_code', t('Employee').space.t('Code'));
			
			$status->checkRequired ( $this, 'dob', t ( 'Date of Birth' ) );
			
			if($this->setting('display_employee_nationality'))
				$status->checkRequired ( $this, 'lookup_nationalities_id', t('Employee Nationality'));
			
			$status->checkRequired ( $this, 'employee_qualification_option_id', t ( 'Staff Cadre' ) );
			
			if($this->setting('display_gender'))
				$status->checkRequired ( $this, 'gender', t('Gender') );
			if($this->setting('display_employee_race'))
				$status->checkRequired ( $this, 'person_race_option_id', t('Race') );
			if($this->setting('display_employee_disability')) {
				$status->checkRequired ( $this, 'disability_option_id', t('Disability') );
				if ($params['disability_option_id'] == 1)
					$status->checkRequired ( $this, 'disability_comments', t('Nature of Disability') );
			}
			if($this->setting('display_employee_salary'))
				$status->checkRequired ( $this, 'salary', t('Salary') );
			if($this->setting('display_employee_benefits'))
				$status->checkRequired ( $this, 'benefits', t('Benefits') );
			if($this->setting('display_employee_additional_expenses'))
				$status->checkRequired ( $this, 'additional_expenses', t('Additional Expenses') );
			if($this->setting('display_employee_stipend'))
				$status->checkRequired ( $this, 'stipend', t('Stipend') );
			if ( $this->setting('display_employee_partner') )
				$status->checkRequired ( $this, 'partner_id', t ( 'Partner' ) );
			//if($this->setting('display_employee_sub_partner'))
				//$status->checkRequired ( $this, 'subpartner_id', t ( 'Sub Partner' ) );
			if($this->setting('display_employee_intended_transition'))
				$status->checkRequired ( $this, 'employee_transition_option_id', t ( 'Intended Transition' ) );
			if(($this->setting('display_employee_base') && !$params['employee_base_option_id']) || !$this->setting('display_employee_base')) // either one is OK, javascript disables regions if base is on & has a value choice
				$status->checkRequired ( $this, 'province_id', t ( 'Region A (Province)' ).space.t('or').space.t('Employee Based at') );
			if($this->setting('display_employee_base') && !$params['province_id'])
				$status->checkRequired ( $this, 'employee_base_option_id', t('Employee Based at').space.t('or').space.t('Region A (Province)') );
			if($this->setting('display_employee_primary_role'))
				$status->checkRequired ( $this, 'employee_role_option_id', t ( 'Primary Role' ) );

			$status->checkRequired ( $this, 'funded_hours_per_week', t ( 'Funded hours per week' ) );
			if($this->setting['display_employee_contract_end_date'])
				$status->checkRequired ( $this, 'agreement_end_date', t ( 'Contract End Date' ) );
			

			$params['subPartner'] = $this->_array_me($params['subPartner']);
			$params['partnerFunder'] = $this->_array_me($params['partnerFunder']);
			$params['mechanism'] = $this->_array_me($params['mechanism']);
			
			$total_percent = 0;
			foreach($params['percentage'] as $i => $val){
				$total_percent = $total_percent + $params['percentage'][$i];
			}
			if ($total_percent > 100) $status->setStatusMessage ( t(' Warn: Total Funded Percentage > 100 ') );
		
			// set partner specific unique employee number. (auto-increment ID for each employee, starting at 1, per-partner)
			if($id) { // reset if change partner_id
				$oldPartnerId = $db->fetchOne("SELECT partner_id FROM employee WHERE id = ?", $id);
				if ($params['partner_id'] != $oldPartnerId || $params['partner_id'] == "")
					$params['partner_employee_number'] = null;
			}
			if ($params['partner_id'] && $params['partner_employee_number'] == "") { // generate a new id
				$max = $db->fetchOne("SELECT MAX(partner_employee_number) FROM employee WHERE partner_id = ?", $params['partner_id']);
				$params['partner_employee_number'] = $max ? $max + 1 : 1; // max+1 or default to 1
			}
			
			// save
			if (! $status->hasError() ) {
				$id = $this->_findOrCreateSaveGeneric('employee', $params);
				
				if(!$id) {
					$status->setStatusMessage( t('That person could not be saved.') );
				} else {

					# converted to optionlist, link table not needed TODO. marking for removal.
					#MultiOptionList::updateOptions ( 'employee_to_role', 'employee_role_option', 'employee_id', $id, 'employee_role_option_id', $params['employee_role_option_id'] );
					
					// delete all
					$epsfm = new ITechTable(array('name' => 'employee_to_partner_to_subpartner_to_funder_to_mechanism'));
					$where = "employee_id = $id";
		
					$delete_result = $epsfm->delete($where, false);
					
					//file_put_contents('c:\wamp\logs\php_debug.log', 'empCont 300>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
					//var_dump($params['subPartner']);
					//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
					
					// insert from view
					foreach($params['subPartner'] as $i => $val){
						
						if($id && $partner_id && $params['subPartner'][$i] &&$params['partnerFunder'][$i] && $params['mechanism'][$i] && $params['percentage'][$i]) {
						  $data = array(
								'employee_id' => $id,
								'partner_id'  => $partner_id,
						  		'subpartner_id'=> $params['subPartner'][$i],
								'partner_funder_option_id' => $params['partnerFunder'][$i],
								'mechanism_option_id' => $params['mechanism'][$i],
								'percentage' => $params['percentage'][$i],
						  );
							
						  $insert_result = $epsfm->insert($data);
						}
					}
					
					$status->setStatusMessage( t('The person was saved.') );
					$this->_redirect("employee/edit/id/$id");
				}
			} 
		}
		
		if ( $id && !$status->hasError() )  // read data from db
		{
			$sql = 'SELECT * FROM employee WHERE employee.id = '.$id;
			$row = $db->fetchRow( $sql );
			if ( ! $row)
			{
				$status->setStatusMessage ( t('Error finding that record in the database.') );
			}
			else 
        	{
            	$params = $row; // reassign form data
            	
            	$region_ids = Location::getCityInfo($params['location_id'], $this->setting('num_location_tiers'));
            	$region_ids = Location::regionsToHash($region_ids);
            	$params = array_merge($params, $region_ids);
            	#$params['roles'] = $db->fetchCol("SELECT employee_role_option_id FROM employee_to_role WHERE employee_id = $id");
            	
            	//get linked table data from option tables
            	$sql = "SELECT partner_to_subpartner_to_funder_to_mechanism_id, employee_id, partner_id, subpartner_id, partner_funder_option_id, mechanism_option_id, percentage
            	FROM employee_to_partner_to_subpartner_to_funder_to_mechanism WHERE is_deleted = false and employee_id = $id";
            	$params['funder'] = $db->fetchAll($sql);
            	
 
            	
            	$helper = new Helper();
            	
            	$subPartner = $helper->getEmployeeSubPartner($id);
            	$this->viewAssignEscaped ( 'subPartner', $subPartner );
            	
            	$partnerFunder = $helper->getEmployeeFunder($id);
            	$this->viewAssignEscaped ( 'partnerFunder', $partnerFunder );
            	
            	$mechanism = $helper->getEmployeeMechanism($id);
            	$this->viewAssignEscaped ( 'mechanism', $mechanism );
            	
            	//file_put_contents('c:\wamp\logs\php_debug.log', 'empCont 511>'.PHP_EOL, FILE_APPEND | LOCK_EX);	ob_start();
            	//var_dump($params['funder']);
            	//var_dump($mechanism);
            	//$result = ob_get_clean(); file_put_contents('c:\wamp\logs\php_debug.log', $result .PHP_EOL, FILE_APPEND | LOCK_EX);
            	
			}
		}

		
		// make sure form data is valid for display
		if (empty($params['funder']))
			$params['funder'] = array(array());
		
		if (empty($params['subpartner']))
			$params['subpartner'] = array(array());
		if (empty($params['funder']))
			$params['funder'] = array(array());
		if (empty($params['mechanism_option_id']))
			$params['mechanism_option_id'] = array(array());
		

		// assign form drop downs
		$params['dob']                          = formhelperdate($params['dob']);
		$params['agreement_end_date']           = formhelperdate($params['agreement_end_date']);
		$params['transition_date']              = formhelperdate($params['transition_date']);
		$params['transition_complete_date']     = formhelperdate($params['transition_complete_date']);
		$params['courses']                      = $this->getCourses($id);
		$params['lookup_nationalities_id']      = $params['option_nationality_id'];
		$params['employee_site_type_option_id'] = $params['facility_type_option_id'];
		$params['person_race_option_id']        = $params['race_option_id'];
		$this->viewAssignEscaped ( 'employee', $params );
		$validCHWids = $db->fetchCol("select id from employee_qualification_option qual
										inner join (select id as success from employee_qualification_option where qualification_phrase in ('Community Based Worker','Community Health Worker','NC02 -Community health workers')) parentIDs
										on (parentIDs.success = qual.id)");
		$this->view->assign('validCHWids', $validCHWids);
		$this->view->assign('expandCHWFields', !(array_search($params['employee_qualification_option_id'],$validCHWids) === false)); // i.e $validCHWids.contains($employee[qualification])
		$this->view->assign('status', $status);
		$this->view->assign ( 'pageTitle', $this->view->mode == 'add' ? t ( 'Add').space.t('Employee' ) : t('Edit').space.t('Employee' ) );
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
		$titlesArray = OptionList::suggestionList ( 'person_title_option', 'title_phrase', false, 9999);
		$this->view->assign ( 'titles',      DropDown::render('title_option_id', $this->translation['Title'], $titlesArray, 'title_phrase', 'id', $params['title_option_id'] ) );
		
		$this->view->assign ( 'partners',    DropDown::generateHtml   ( 'partner', 'partner', $params['partner_id'], false, $this->view->viewonly, false ) );
		
		//$this->view->assign ( 'funder_mechanisms', DropDown::generateHtml( $params['funder_mechanism'], 'funder_mechanism_option', $params['funder_mechanism'], false, $this->view->viewonly, false ) );
		/*
		$this->view->assign ( 'funders',
		DropDown::generateHtml (
		'partner_funder_option',
		'funder_phrase',
		$params['partner_funder_option_id'],
		false,
		$this->viewonly,
		false
		));
		*/
		
		//$this->view->assign ( 'subpartners', DropDown::generateHtml   ( 'partner', 'partner', $params['subpartner_id'], false, $this->view->viewonly, false, false, array('name' => 'subpartner_id'), true ) );
		$this->view->assign ( 'bases',       DropDown::generateHtml   ( 'employee_base_option', 'base_phrase', $params['employee_base_option_id']) );
		$this->view->assign ( 'site_types',  DropDown::generateHtml   ( 'employee_site_type_option', 'site_type_phrase', $params['facility_type_option_id']) );
		$this->view->assign ( 'cadres',      DropDown::generateHtml   ( 'employee_qualification_option', 'qualification_phrase', $params['employee_qualification_option_id']) );
		$this->view->assign ( 'categories',  DropDown::generateHtml   ( 'employee_category_option', 'category_phrase', $params['employee_category_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'fulltime',    DropDown::generateHtml   ( 'employee_fulltime_option', 'fulltime_phrase', $params['employee_fulltime_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'roles',       DropDown::generateHtml   ( 'employee_role_option', 'role_phrase', $params['employee_role_option_id'], false, $this->view->viewonly, false ) );
		#$this->view->assign ( 'roles',       CheckBoxes::generateHtml ( 'employee_role_option', 'role_phrase', $this->view, $params['roles'] ) );
		$this->view->assign ( 'transitions', DropDown::generateHtml   ( 'employee_transition_option', 'transition_phrase', $params['employee_transition_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'transitions_complete', DropDown::generateHtml ( 'employee_transition_option', 'transition_phrase', $params['employee_transition_complete_option_id'], false, $this->view->viewonly, false, false, array('name' => 'employee_transition_complete_option_id'), true ) );
		$helper = new Helper();
		$this->viewAssignEscaped ( 'facilities', $helper->getFacilities() );
		$this->view->assign ( 'relationships', DropDown::generateHtml ( 'employee_relationship_option', 'relationship_phrase', $params['employee_relationship_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'referrals',     DropDown::generateHtml ( 'employee_referral_option', 'referral_phrase', $params['employee_referral_option_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'provided',      DropDown::generateHtml ( 'employee_training_provided_option', 'training_provided_phrase', $params['employee_training_provided_option_id'], false, $this->view->viewonly, false ) );
		$employees = OptionList::suggestionList ( 'employee', array ('first_name' ,'CONCAT(first_name, CONCAT(" ", last_name)) as name' ), false, 99999 );
		$this->view->assign ( 'supervisors',   DropDown::render('supervisor_id', $this->translation['Supervisor'], $employees, 'name', 'id', $params['supervisor_id'] ) );
		$this->view->assign ( 'nationality',   DropDown::generateHtml ( 'lookup_nationalities', 'nationality', $params['lookup_nationalities_id'], false, $this->view->viewonly, false ) );
		$this->view->assign ( 'race',          DropDown::generateHtml ( 'person_race_option', 'race_phrase', $params['race_option_id'], false, $this->view->viewonly, false ) );
	}

	public function searchAction()
	{
		$this->view->assign('pageTitle', 'Search Employees');
		if (! $this->hasACL ( 'employees_module' )) {
			$this->doNoAccessError ();
		}

		$criteria = $this->getAllParams();

		if ($criteria['go'])
		{
			// process search
			$where = array();

			list($a, $location_tier, $location_id) = $this->getLocationCriteriaValues($criteria);
			list($locationFlds, $locationsubquery) = Location::subquery($this->setting('num_location_tiers'), $location_tier, $location_id, true);

			$sql = "SELECT DISTINCT
    employee.id,
    employee.employee_code,
    employee.gender,
    employee.national_id,
    employee.other_id,
    employee.location_id,
    ".implode(',',$locationFlds).",
    CONCAT(supervisor.first_name,
    CONCAT(' ', supervisor.last_name)) as supervisor,
    qual.qualification_phrase as staff_cadre,
    site.facility_name,
    category.category_phrase as staff_category,
GROUP_CONCAT(subp.partner) as subPartner,
GROUP_CONCAT( partner_funder_option.funder_phrase) as partnerFunder,
GROUP_CONCAT(mechanism_option.mechanism_phrase) as mechanism,
    GROUP_CONCAT(funders.percentage) as percentage
FROM    employee        
LEFT JOIN    ($locationsubquery) as l ON l.id = employee.location_id
LEFT JOIN   employee supervisor ON supervisor.id = employee.supervisor_id
LEFT JOIN   facility site ON site.id = employee.site_id
LEFT JOIN   employee_qualification_option qual ON qual.id = employee.employee_qualification_option_id
LEFT JOIN   employee_category_option category ON category.id = employee.employee_category_option_id
LEFT JOIN   partner ON partner.id = employee.partner_id
LEFT JOIN	employee_to_partner_to_subpartner_to_funder_to_mechanism funders on (funders.employee_id = employee.id and funders.partner_id = partner.id )
LEFT JOIN 	partner_funder_option on funders.partner_funder_option_id = partner_funder_option.id
LEFT JOIN 	mechanism_option on funders.mechanism_option_id = mechanism_option.id 
LEFT JOIN 	partner subp on subp.id = funders.subpartner_id 
					";

			#if ($criteria['partner_id']) $sql    .= ' INNER JOIN partner_to_subpartner subp ON partner.id = ' . $criteria['partner_id'];

			// restricted access?? only show partners by organizers that we have the ACL to view
			$org_allowed_ids = allowed_org_access_full_list($this); // doesnt have acl 'training_organizer_option_all'
			if($org_allowed_ids)
				$where[] = " partner.organizer_option_id in ($org_allowed_ids) ";

			if ($locationWhere = $this->getLocationCriteriaWhereClause($criteria, '', '')) {
				$where[] = $locationWhere;
			}

			// if ($criteria['first_name'])                        $where[] = "employee.first_name   = '{$criteria['first_name']}'";
			// if ($criteria['last_name'])                         $where[] = "employee.last_name    = '{$criteria['last_name']}'";
			if ($criteria['employee_code'])                        $where[] = "employee.employee_code    like '%{$criteria['employee_code']}%'";
			
			if ($criteria['partner_id'])                        $where[] = 'employee.partner_id   = '.$criteria['partner_id']; //todo
			
			if ($criteria['facilityInput'])                     $where[] = 'employee.site_id      = '.$criteria['facilityInput'];
			if ($criteria['employee_qualification_option_id'])  $where[] = 'employee.employee_qualification_option_id    = '.$criteria['employee_qualification_option_id'];
			if ($criteria['category_option_id'])                $where[] = 'employee.staff_category_id = '.$criteria['category_option_id'];

			if ( count ($where) )
				$sql .= ' WHERE ' . implode(' AND ', $where);
			
			$sql .= ' GROUP BY employee.id ';
			
			$db = $this->dbfunc();
			$rows = $db->fetchAll( $sql );

			$locations = Location::getAll();
			// hack #TODO - seems Region A -> ASDF, Region B-> *Multiple Province*, Region C->null Will not produce valid locations with Location::subquery
			// the proper solution is to add "Default" districts under these subdistricts, not sure if i can at this point the table is 12000 rows, todo later
			foreach ($rows as $i => $row) {
				if ($row['province_id'] == "" && $row['location_id']){ // empty province
					$updatedRegions = Location::getCityandParentNames($row['location_id'], $locations, $this->setting('num_location_tiers'));
					$rows[$i] = array_merge($row, $updatedRegions);
				}
			}

			$this->viewAssignEscaped('results', $rows);
			$this->viewAssignEscaped('count', count($rows));

			if ($criteria ['outputType'] && $rows) {
				$this->sendData ( $this->reportHeaders ( false, $rows ) );
			}
		}
		// assign form drop downs
		$helper = new Helper();
		$this->view->assign('status', $status);
		$this->viewAssignEscaped ( 'criteria', $criteria );
		$this->viewAssignEscaped ( 'locations', $locations );
		$this->view->assign ( 'partners',    DropDown::generateHtml ( 'partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, false ) );
		
		//$this->view->assign ( 'subpartners', DropDown::generateHtml ( 'partner', 'partner', $criteria['partner_id'], false, $this->view->viewonly, false, false, array('name' => 'subpartner_id'), true ) );
		$this->view->assign ( 'cadres',      DropDown::generateHtml ( 'employee_qualification_option', 'qualification_phrase', $criteria['employee_qualification_option_id'], false, $this->view->viewonly, false ) );
		$this->viewAssignEscaped ( 'sites', $helper->getFacilities() );
		$this->view->assign ( 'categories',  DropDown::generateHtml ( 'employee_category_option', 'category_phrase', $criteria['employee_category_option_id'], false, $this->view->viewonly, false ) );
	}



public function loginAction() {
	require_once ('Zend/Auth/Adapter/DbTable.php');

	$request = $this->getRequest ();
	$validateOnly = $request->isXmlHttpRequest ();

	$userObj = new User ( );
	$userRow = $userObj->createRow ();

	if ($validateOnly)
		$this->setNoRenderer ();

	$status = ValidationContainer::instance ();

	if ($request->isPost ()) {
		// if a user's already logged in, send them to their account home page
		$auth = Zend_Auth::getInstance ();

		if ($auth->hasIdentity ()){
			#				$this->_redirect ( 'select/select' );
		}

		$request = $this->getRequest ();



		// determine the page the user was originally trying to request
		$redirect = $this->_getParam ( 'redirect' );

		//if (strlen($redirect) == 0)
		//    $redirect = $request->getServer('REQUEST_URI');
		if (strlen ( $redirect ) == 0){
			if($this->hasACL('pre_service')){
				#					$redirect = 'select/select';
			}
		}

		// initialize errors
		$status = ValidationContainer::instance ();

		// process login if request method is post
		if ($request->isPost ()) {

			// fetch login details from form and validate them
			$username = $this->getSanParam ( 'username' );
			$password = $this->_getParam ( 'password' );
			if (! $status->checkRequired ( $this, 'username', t ( 'Login' ) ) or (! $this->_getParam ( 'send_email' ) and ! $status->checkRequired ( $this, 'password', t ( 'Password' ) )))
				$status->setStatusMessage ( t ( 'The system could not log you in.' ) );

			if (! $status->hasError ()) {

				// setup the authentication adapter
				$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
				$adapter = new Zend_Auth_Adapter_DbTable ( $db, 'user', 'username', 'password', 'md5(?)' );
				$adapter->setIdentity ( $username );
				$adapter->setCredential ( $password );

				// try and authenticate the user
				$result = $auth->authenticate ( $adapter );

				if ($result->isValid ()) {
					$user = new User ( );
					$userRow = $user->find ( $adapter->getResultRowObject ()->id )->current ();

					if($user->hasPS($userRow->id)){
						$redirect = $redirect ? $redirect : "dashboard/dash0";
					}

					if ( $userRow->is_blocked ) {
						$status->setStatusMessage( t('That user account has been disabled.'));
						$auth->clearIdentity ();
					} else {
						// create identity data and write it to session
						$identity = $user->createAuthIdentity ( $userRow );
						$auth->getStorage ()->write ( $identity );

						// record login attempt
						$user->recordLogin ( $userRow );

						// send user to page they originally request
						$this->_redirect ( $redirect );

					}

				} else {

					$auth->clearIdentity ();
					switch ($result->getCode ()) {

						case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND :
							$status->setStatusMessage ( t ( 'That username or password is invalid.' ) );

							break;

						case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID :
							$status->setStatusMessage ( t ( 'That username or password is invalid.' ) );

							break;

						default :
							throw new exception ( 'login failure' );
							break;
					}
				}

			}
		}

	}

	if ($validateOnly) {
		$this->sendData ( $status );
	} else {
		$this->view->assign ( 'status', $status );
	}

}

}

?>