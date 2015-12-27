<?php

  require('includes/application_top.php');
 // No direct access to this file 
if(!IS_AJAX) {die('Restricted access');} 
     if(isset($_POST['action'])) {
      $action = tep_db_prepare_input($_POST['action']);
    }
     if(isset($_POST['sel_id'])) {
      $selector_id = tep_db_prepare_input($_POST['sel_id']);
    }  
     if(isset($_POST['field_id'])) {
      $field_id = tep_db_prepare_input($_POST['field_id']);
    }

     switch ($action) {
  
      case 'new_field' : 
		 tep_db_query('insert into ' . TABLE_BTS_CSS_PROPERTIES . ' (selectors_id ) values ("' . $selector_id . '")');
          $new_id = tep_db_insert_id();
      break;
	  
      case 'delete_field' :
		 tep_db_query('delete from ' . TABLE_BTS_CSS_PROPERTIES . ' where properties_id = "' . $field_id . '"');
      break; 
  
    }
  
     if ($action == 'new_field') {

      $return  =  '<tr id="'.$new_id.'">';
	  $return .=  '  <td><input type="text" name="property_element['.$new_id.']" class="form-control" /></td>';
	  $return .=  '  <td><input type="text" name="property_value['.$new_id.']" class="form-control" /></td>';
	  $return .=  '  <td><a href="#" class="remove_new_edit_field">Remove</a></td>';
	  $return .=  '</tr>';
       
	   header('Content-Type: application/json');										 
       echo json_encode($return);
     
	 }    
  
?>