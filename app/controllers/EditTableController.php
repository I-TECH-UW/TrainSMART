<?php
/*
 * Created on Mar 17, 2008
 *
 *  Built for itechweb
 *  Fuse IQ -- jonah.ellison@fuseiq.com
 *
 */

require_once('ITechController.php');

class EditTableController extends ITechController { //Zend_Controller_Action
  public $table;    // table name
  public $fields;   // array of (field name => label)
  public $label;    // label for the "type" (used when adding a new row)
  public $viewVar = 'editTable';  // name of variable to add to view
  public $noEdit = false; // disable editing/adding links    
  public $noDelete = false; // disable delete link
  public $where = false; // optional SQL where
  public $insertExtra = false; // optional array of column/values
  public $customColDef = array(); // field name => def data

  public $dependencies = array(); // field name => table
  
  public $rowHook = ''; // function name to pass rows to modify

  public $allowMerge = false; // add "merge" checkbox
  public $allowDefault = false; // add "default" radio
  
  private $controller;

  public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array() )
  {
      // moved to setParentController, ZF-1.8.0 requires constructor signature match, gnr
      //$this->controller = $parentController;
      //$this->view = $parentController->view;
         
      parent::__construct($request, $response, $invokeArgs = array ());
  }

  public function init()
  {
  }
  
  public function setParentController($parentController)
  {
      $this->controller = $parentController;
      $this->view = $parentController->view;
  }

  public function execute(Zend_Controller_Request_Abstract $request) {

    $params = $this->_getAllParams();

    if(isset($params['merge']) && $this->allowMerge) {
      $this->merge();
      return;
    }

    if(isset($params['default']) && $this->allowDefault) {
      $this->setDefault();
    }

    if(isset($params['redirect']) and $params['redirect'] ) { // editTable is part of a "wizard" (redirect to the next step)
      header("Location: {$params['redirect']}");
      exit;
    } elseif(isset($params['saveonly'])) {
      $status = ValidationContainer::instance();
      $status->setStatusMessage('Your settings have been updated.');
    }

    require_once('models/table/EditTable.php');
    $editTable = new EditTable(array('name' => $this->table));

  	//$request = $this->controller->getRequest();
  	$validateOnly = $request->isXmlHttpRequest();

    // Delete, insert, or update?
    if($validateOnly) {

      //$id = $params['id'];
      $id = (isset($_POST['id']) && is_numeric($_POST['id'])) ? $_POST['id'] : null;

      // Get field to update
      foreach($this->fields as $key => $devnull) {
        if(isset($params[$key])) {
          $fieldEdit = $key;
          $fieldValue = $params[$key];
          break;
        }
      }

      if($id == 0 && isset($params['undelete'])) { // undelete record

        try {

          $row = $editTable->undelete($fieldEdit, $fieldValue);
          $sendRay['insert'] = $row->id;
          $sendRay['undelete'] = $row->$fieldEdit;

          $this->sendData($sendRay);

        } catch(Zend_Exception $e) {
          $this->sendData(array("insert" => 0, 'error' => $e->getMessage()));
        }

      } elseif($id == 0) { // user added new record

        try {

          if(!$this->insertExtra) {
            $insert = $editTable->insertUnique($fieldEdit, $fieldValue);
          } else {
            $data = array($fieldEdit => $fieldValue);
            $insert = $editTable->insert(array_merge($data, $this->insertExtra));
          }

          $sendRay['insert'] = "$insert";
          if($insert == -1) $sendRay['error'] = t('A record already exists with this value.');
          if($insert == -2) $sendRay['error'] = t('"%s" already exists, but was deleted.  Would you like to undelete?');

          $this->sendData($sendRay);

        } catch(Zend_Exception $e) {
          $this->sendData(array("insert" => 0, 'error' => $e->getMessage()));
        }

      } elseif($id > 0) { // update or delete

        if(isset($params['delete'])) {

          try {

            $delete = $editTable->delete("id=$id",true);//force the delete, changed 06/16/08 Todd W
            $this->sendData(array("delete" => $delete));

          } catch(Zend_Exception $e) {
            $this->sendData(array("delete" => 0, 'error' => $e->getMessage()));
          }

        } elseif(isset($fieldEdit)) { // update

            try {

              $update = $editTable->update(array($fieldEdit => $fieldValue), "id=$id");
              $this->sendData(array("update" => $id));

            } catch(Zend_Exception $e) {

              if(strpos($e->getMessage(),t('Duplicate entry')) !== false) {
                $this->sendData(array("update" => 0, 'error' => t('A record already exists with this value.')));
              } else {
                $this->sendData(array("update" => 0, 'error' => $e->getMessage()));
              }

            }
        }
      }


    } else { // view
    
      $selectFields = array_keys($this->fields);
      
      if($this->allowDefault) {
        $selectFields[] = 'is_default';
      }

      require_once('views/helpers/EditTableHelper.php');
      $rowRay = $editTable->getRowsSingle($this->table, $selectFields, $this->where);
      
      foreach($rowRay as $key => $row ) {
      	foreach($selectFields as $field) {
      		if ( $field != 'id' ) {
      			$rowRay[$key][$field] = htmlspecialchars($row[$field]);
      		}
      	}
      }
      
      // Modify rows     
      if ($this->rowHook) {
        $func_name = $this->rowHook;
        eval('$rowRay = ' . $func_name . "(unserialize('".serialize($rowRay)."'));");
      }      
      

      $noDelete = array();

      // look up dependencies
      if(!empty($this->dependencies)) {
        foreach($this->dependencies as $colDependent =>  $tableDependent) {
          if(is_numeric($colDependent)) $colDependent =  $this->table . '_id';
          if(is_array($tableDependent)) { // in case multiple tables use the same field name
            $colDependent = key($tableDependent);
            $tableDependent = current($tableDependent);
          }

          $ray = $editTable->getDependencies($this->table, $tableDependent, $colDependent);
          $noDelete += array_merge($noDelete, $ray);
        }
        $noDelete = array_unique($noDelete);
      }
      
      // disable delete on all rows
      if($this->noDelete) {
        foreach($rowRay as $key => $row) {
          $noDelete[$row['id']] = $row['id'];  
        }        
      }

      // merge checkbox
      if($this->allowMerge) {
        foreach($rowRay as $key => $row) {
          $rowRay[$key]['merge'] = '
          <input type="checkbox" name="merge[]" value="'.$row['id'].'" id="merge'.$row['id'].'">';
        }
        $this->customColDef['merge'] = 'editor:false';
        $this->fields['merge'] = 'Merge?';
      }
      
      // default radio
      if($this->allowDefault) {
        foreach($rowRay as $key => $row) {
          $isChecked = ($row['is_default']) ? ' checked="checked"' : '' ;
          $rowRay[$key]['default'] = '
          <input type="radio" name="default" value="'.$row['id'].'" id="merge'.$row['id'].'"'.$isChecked.'>';
        }
        $this->customColDef['default'] = 'editor:false';
        $this->fields['default'] = t('Default') . '?';
      }

      
      $html = '';
      
      if($this->allowMerge) {
        $mergehtml = '
        <input type="hidden" name="table_option" value="'.$this->table.'">
        <input type="hidden" name="table_dependent" value="'.implode(',',$this->dependencies).'">
        <input type="submit" name="mergesubmit" value="' . t('Merge Selected') . '" class="submitArrow">';
        $html .= $mergehtml;  
      }  
      
      if($html) {
        $html .= '<div class="clear"></div><br>';
      }

      $html .= EditTableHelper::generateHtml($this->label, $rowRay, $this->fields, $this->customColDef, $noDelete, $this->noEdit);

      // merge form
      if($this->allowMerge) {
        $html .= $mergehtml;
      }

      $this->controller->view->assign($this->viewVar, $html);
    }

  }

  public function merge() {
    require_once('models/table/EditTable.php');

    $params = $this->_getAllParams();

    if(!isset($params['mergeto']) && is_array($params['merge'])) {
      $fields = array_keys($this->fields);

      $rows = EditTable::getRowsSingle($this->table, $fields, 'id IN('.implode(',', $params['merge']).')');

      $html = t('The phrases below will be merged into one.  Please select the primary phrase you wish to use:') . '<br><br>';
      foreach($rows as $row) {
        $html .= '<p><input type="radio" name="mergeto" value="'.$row['id'].'" id="merge'.$row['id'].'"><label for="merge'.$row['id'].'">' . $row[$fields[0]] .'</label></p>';
      }
      $html .= '
      <input type="hidden" name="merge" value="'.implode(',', $params['merge']).'">
      <input type="submit" class="submitNoArrow" value="' . t('Merge into one') . '" style="float:none;">
      ';
    } elseif(isset($params['mergeto'])) {
      $mergeids = explode(',', $params['merge']);

      EditTable::merge($this->table, $this->dependencies[0], $mergeids, $params['mergeto']);
      header("Location: " . $_SERVER['REQUEST_URI']);
    } else {
      $html = t('Unable to merge phrases.');
    }


    $this->controller->view->assign($this->viewVar, $html);
  }


  public function setDefault() {
    require_once('models/table/EditTable.php');
    
    $params = $this->_getAllParams();
    
    if(is_numeric($params['default'])) {
      EditTable::setDefault($this->table, $params['default'], $this->where);
    }
  }
  
}