<?php
/*
 * Created on Jul 15, 2009
 *
 *  Built for itechweb
 *  Fuse IQ -- jonah.ellison@fuseiq.com
 *
 * Creates a list of multiple checkboxes for reports
 *
 */

class Checkboxes {
  
  public static function generateHtml($table, $column, &$view, $checked = array(), $where = false) {
    $html = '<div class="checkboxHelper" ><table cellspacing="0">';
    $rows = OptionList::suggestionList($table,$column,false,false,false,$where);
    if (! $rows )
      return '<input type="text" readonly="readonly" disabled="disabled" /> ';
     
    foreach($rows as $r) {
      $isChecked =  
         (isset($_GET["{$table}_id"]) && in_array($r['id'], $_GET["{$table}_id"])) || 
         (!empty($checked) && in_array($r['id'], $checked))   
         ? ' checked' : ''; 
      $html .= '
      <tr class="'.$isChecked.'">
        <td valign="top"><input type="checkbox" value="'.$r['id'].'" name="'.$table.'_id[]" id="'.$table.$r['id'].'"'.$isChecked.'></td>
        <td valign="top"><label for="'.$table.$r['id'].'"> ' . $r[$column] . '</label></td>
      </tr>' . "\n";      
    }
    $html .= '</table></div>';
    
    return $html;  
  }  
  
}