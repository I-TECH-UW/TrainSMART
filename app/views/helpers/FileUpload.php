<?php

require_once 'views/helpers/EditTableHelper.php';


class FileUpload {   
  
	public static $FILETYPES = array ('doc', 'docx', 'xls', 'xlsx', 'pdf' );
	
  /**
   * @var $allowed_ext = allowed extensions (e.g., doc, docx, pdf)
   */ 
  public static function displayFiles(&$controller, $parent_table, $parent_id, $can_delete = TRUE) {

    if(!$parent_id) {
      return;
    }
    
    // Only allow delete 
    $request = $controller->getRequest ();    
    $response = $controller->getResponse ();
    if ($request->isPost () && !$controller->getSanParam('edittabledelete')) {
      return;  
    }
    
	require_once ('controllers/EditTableController.php');
	$editTable = new EditTableController($request, $response);
	$editTable->setParentController($controller);
	$editTable->table = 'file';
    $editTable->viewVar = 'editTableFiles';
	$editTable->fields = array ('filename' => t ( 'Filename' ), 'filesize' => t ( 'Size' ), 'creator_name' => t ( 'Author' ), /*'filemime' => t('Type') ,*/ 'timestamp_created' => t('Upload Date') );
	$editTable->label = t('Attached Documents');
	$editTable->where = "parent_table = '$parent_table' AND parent_id = $parent_id";
    $editTable->noEdit = true;
    
    if(!$can_delete) {
      $editTable->noDelete = true;    
    }    
    
    $editTable->rowHook = "FileUpload::modifyRows";
		$editTable->execute ($request);
  }
  
  public static function modifyRows($rowRay) {
    foreach($rowRay as $index => $row) {
      $rowRay[$index]['link_url'] = Settings::$COUNTRY_BASE_URL .'/file/download/id/'.$row['id'];
      $rowRay[$index]['filename'] = '<a href="'. $rowRay[$index]['link_url'] .'">' . $row['filename'] . '</a>';
      $rowRay[$index]['filesize'] = round($row['filesize'] / 1024 / 1024, 2) . 'MB'; 
    //  $rowRay[$index]['created_by'] = $row['created_by']; 
      $rowRay[$index]['creator_name'] = $row['creator_name']; 
      
      if(!isset($rowRay[$index]['timestamp_created'])) {
        $rowRay[$index]['timestamp_created'] = date('Y-m-d H:i:s');
      } 
      
    }
    //unset($rowRay[0]);
    return $rowRay;
  }
  
  public static function displayUploadForm($parent_table, $parent_id, $allowed_ext = array(), $showUploadMax = true) {

    $html = '
    <br>
    <form name="uploadFileForm" id="uploadFileForm" action="' . Settings::$COUNTRY_BASE_URL . '/file/upload/outputType/json" enctype="multipart/form-data" method="post">
      <input type="file" name="upload" />
      <input type="hidden" name="parent_table" value="'.$parent_table.'" />
      <input type="hidden" name="parent_id" value="'.$parent_id.'" />
      <input type="button" id="uploadButton" value="Upload"/><br>
      ' .       
       ($allowed_ext ? t('Allowed file extensions').': &nbsp;<i>' . implode(' &nbsp;', $allowed_ext) . '</i><br>' : '') .
        ($showUploadMax?t('Max upload size: ') . ini_get('upload_max_filesize'):'') .      
      '<script language="javascript">
      var fileupload_allowed = ' . json_encode($allowed_ext) . ';
      </script>
      
    </form>
    ';
        
      
    return $html;    
  }

  
}