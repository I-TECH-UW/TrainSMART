<?php
/*
 * Created on Dec 11, 2009
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

require_once('ITechController.php');
require_once('models/table/File.php');
require_once('views/helpers/FileUpload.php');


class FileController extends ITechController
{
   public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {

    }
    
    /**
     * Check if upload directory exists and create if not
     * @return return directory
     */ 
    public function getUploadDir()
    {
      $fileDir = Globals::$BASE_PATH . 'files';
      $uploadDir = $fileDir . '/' . Settings::$DB_DATABASE; 
       
      if(!file_exists($fileDir)) {
        mkdir($fileDir);  
      }
      if(!file_exists($uploadDir)) {
        mkdir($uploadDir);
      }
      
      return $uploadDir;
    }    

    /**
     * Check if upload directory exists and if filename exists
     * @return new filename
     */ 
    public function checkFile($filename)
    {
      $uploadDir = $this->getUploadDir();

      $parts = pathinfo($filename);
      $pos = 1;
      
      while(file_exists("$uploadDir/$filename")) {
        $filename = "{$parts['basename']}_{$pos}.{$parts['extension']}";
        $pos++;
      }
      
      return $filename;      
    }
  

     /**
     * Uploaded files are POSTed here
     */ 
    public function uploadAction()
    {
  		  require_once 'models/table/File.php';
        $return = array();
        
        if(isset($_FILES['upload'])) {
          
          if(!$_FILES['upload']['error']) {
            
            // Check for upload directory
            $uploadDir = $this->getUploadDir();
            $name = $this->checkFile($_FILES['upload']['name']);
            move_uploaded_file($_FILES['upload']['tmp_name'], "$uploadDir/$name");
            
            $data['parent_id'] = $_POST['parent_id'];
            $data['parent_table'] = $_POST['parent_table'];
            
            $data['filemime'] = $_FILES['upload']['type'];
            $data['filesize'] = $_FILES['upload']['size'];;
            $data['filename'] = $name;
            
            
            $fileTable = new File();            
            $data['id'] = $fileTable->insert($data);
            $data['creator_name'] = $this->view->identity->first_name.' '.$this->view->identity->last_name;
            $dataArray = FileUpload::modifyRows(array($data));
            $return = $dataArray[0];
            
            // Strange JSON decoding error when sending hyperlink
            $return['filename'] = strip_tags($return['filename']);

          } else {
            $return['error'] = 'Error uploading file. id: ' . $_FILES['upload']['error'];
          }
          
        }
        
        require_once 'Zend/Json.php';
        echo Zend_Json::encode($return);
        exit;
    }
    
    /**
     * Download a file
     * @todo validate user?
     */ 
    public function downloadAction() {
      $id = $this->getSanParam ( 'id' );
      
      if ( !$id ) return;
      
      $fileObj = new File();
      $file = $fileObj->find($id)->current();
      
      $source = Globals::$BASE_PATH . 'files/' . Settings::$DB_DATABASE . "/" . $file->filename;
      
      if(!$file->id || !file_exists($source)) {
        print 'File not found.'; exit;
      }
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: private",false);
      header("Content-Transfer-Encoding: binary");

      // Optional cache if not changed -- new, testing this
      header('Last-Modified: '.gmdate('D, d M Y H:i:s', strtotime($file->timestamp_updated)).' GMT');
      // Optional send not modified -- new, testing this..
      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) and 
          strtotime($file->timestamp_updated) == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']))
      {
          header('HTTP/1.1 304 Not Modified');
      }
      
      header('Content-type: application/force-download');
      header('Content-type: ' . $file->filemime);
      header('Content-length: '.filesize($source));
      header('Content-Disposition: attachment; filename="'.str_replace('"', "", $file->filename).'"');
      
      // Transfer file in 1024 byte chunks to save memory usage.
      if ($fd = fopen($source, 'rb')) {
        while (!feof($fd)) {
          print fread($fd, 1024);
        }
        fclose($fd);
      }
      exit;

    }    
    
}
