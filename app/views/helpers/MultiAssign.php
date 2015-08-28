<?php
/*
 *
 * Admin helper to assign multiple options data fields
 *
 */

require_once 'models/table/OptionList.php';
require_once 'models/table/MultiAssignList.php';
require_once 'views/helpers/CheckBoxes.php';
require_once 'views/helpers/DropDown.php';


class MultiAssign {

  public $table;
  
  public $option_table;
  public $option_field = array();
  
  public $parent_table;
  public $parent_field = array();
  
  private $controller;
  private $url;

  public function init(&$controller) {
    $this->controller = $controller;
    
    $this->url = $this->controller->getRequest()->getRequestUri();
    $this->url = str_replace("/outputType/".$this->controller->getSanParam('outputType'),"",$this->url);         
    $this->url = str_replace("/edit/".$this->controller->getSanParam('edit'),"",$this->url);
    $this->url = str_replace("/delete/".$this->controller->getSanParam('delete'),"",$this->url);    

    $output = '';  
    $checked = array();
    
    // for json table    
    if($this->controller->getSanParam('outputType')) {
      return $this->json();
    }

    // saving
    if($this->controller->getRequest()->isPost()) { // Update db
      MultiAssignList::save(
        $this->table, 
        $this->parent_table, 
        $this->option_table, 
        $this->controller->getSanParam("{$this->parent_table}_id"),
        $this->controller->getSanParam("{$this->option_table}_id"));        
    }
       
    // 
    
    // deleting   
    
    // editing
    if($edit_id = $this->controller->getSanParam('edit')) {
      $assignedArray = MultiAssignList::getAssigned($this->table, $this->parent_table, $this->option_table, $edit_id);
      foreach($assignedArray as $row) {
        $checked[] = $row["{$this->option_table}_id"];
      }
    }
    
   
    
    

    $output .= '
  	<div id="jsonTableHolder"></div>
  	<script type="text/javascript">
  		var multiColumnDefs = [
  		    {key:"'.key($this->parent_field).'", label: "'.current($this->parent_field).'", sortable:true, resizeable:true},
  		    {key:"'.key($this->option_field).'", label: "'.current($this->option_field).'", sortable:true, resizeable:true},
  		    {key:"edit", label: "' . t('Edit') . '", sortable:true, resizeable:true}
  		];
  		var action = "'. $this->url.'/outputType/json";
  		makeJSONDataTable("jsonTableHolder", null, action, multiColumnDefs);
  	</script>
  	<a name="edit"></a>
  	<div class="hrGrey"></div>       
    ';   
    
    // drop-down
    $attributes['onchange'] = "document.location = '{$this->url}/edit/' + this.value";
    $output .='<div class="label">'.current($this->parent_field).'</div>';
    $output .= DropDown::generateHtml($this->parent_table, key($this->parent_field), $this->controller->getSanParam('edit'), false, false, false, false, $attributes);
    $output .= '<br><br>';
    //$options = OptionList::suggestionList($this->option_table, $this->option_field, false, false);
    //print_r($options);
    
    $output .= Checkboxes::generateHtml($this->option_table, key($this->option_field), $view, $checked);
    return $output;
  }
  
  public function json() {
    $rowArray = MultiAssignList::adminList(
      $this->table,
      $this->parent_table,
      key($this->parent_field),
      $this->option_table,
      key($this->option_field));
        
    foreach($rowArray as $key => $row) {
      $rowArray[$key]['edit'] = '<a href="' . $this->url . '/edit/'. $row["{$this->parent_table}_id"] . '#edit">' . t('edit') . '</a>&nbsp;' .
      '<a href="' . $this->url . '/delete/'. $row["{$this->parent_table}_id"] . '" onclick="return confirm(\'' . t('Are you sure you wish to unasign this?') . '\')">' . t('delete') . '</a>';
    }
    
    return $rowArray;    
  }  
}

?>  