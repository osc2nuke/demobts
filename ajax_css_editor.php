<?php

  require('includes/application_top.php');
 
// No direct access to this file 
if(!IS_AJAX) {die('Restricted access');} 

     if(isset($_POST['action'])) {
      $action = tep_db_prepare_input($_POST['action']);
    }
     if(isset($_POST['selectors_id'])) {
      $selectors_id = tep_db_prepare_input($_POST['selectors_id']);
    }  
     if(isset($_POST['selectors_field'])) {
      $selectors_field = tep_db_prepare_input($_POST['selectors_field']);
    }
     if(isset($_POST['selectors_data'])) {
     //$selectors_data = tep_db_prepare_input($_POST['selectors_data']);
     $selectors_data2 = stripslashes($_POST['selectors_data']);
     $selectors_data2 = str_replace("'",'"',$selectors_data2);

     $purifier = new HTMLPurifier();
     $selectors_data = $purifier->purify($selectors_data2);
    }
     if(isset($_POST['drop_id'])) {
      $drop_id = tep_db_prepare_input($_POST['drop_id']);
    }
     if(isset($_POST['css_action_type'])) {
      $css_action_type = tep_db_prepare_input($_POST['css_action_type']);
    }	
     switch ($action) {
      
	  case 'update_css' : 
		 if ($selectors_field == 'selectors_name') {
            tep_db_query("update " . TABLE_BTS_CSS_SELECTORS . " set selectors_name = '" . $selectors_data . "' where selectors_id = '" . (int)$selectors_id . "'  and customers_id = '" . (int)$customer_id . "'");		 
		 }
		 if ($selectors_field == 'selectors_properties') {
            tep_db_query("update " . TABLE_BTS_CSS_SELECTORS . " set selectors_properties = '" . $selectors_data . "' where selectors_id = '" . (int)$selectors_id . "'  and customers_id = '" . (int)$customer_id . "'");		 
		 }
		 return_css_content();
      break;
	  
      case 'update_sort_order' : 
		 if ($css_action_type == 'increase') {
            tep_db_query("update " . TABLE_BTS_CSS_SELECTORS . " set sort_order = sort_order + 10 where selectors_id = '" . (int)$selectors_id . "'  and customers_id = '" . (int)$customer_id . "'");		 
		 }
		 if ($css_action_type == 'decrease') {
            tep_db_query("update " . TABLE_BTS_CSS_SELECTORS . " set sort_order = sort_order - 10 where selectors_id = '" . (int)$selectors_id . "'  and customers_id = '" . (int)$customer_id . "'");		 
		 }
		 return_css_content();
      break;
	  
      case 'move_header_confirm':
           tep_db_query("update " . TABLE_BTS_CSS_SELECTORS . " set parent_id = '" . (int)$drop_id . "', last_modified = now() where selectors_id = '" . (int)$selectors_id . "'  and customers_id = '" . (int)$customer_id . "'");
      return_css_content();
	  break;	 
		
      case 'new_css' :
	  global $css_id;
        if ($css_action_type == 'media') {	  
		 tep_db_query('insert into ' . TABLE_BTS_CSS_SELECTORS . ' (selectors_name, selectors_type, parent_id, sort_order, customers_id ) values ("notempty", "' . $css_action_type . '", "' . (int)$css_id . '", "0", "' . (int)$customer_id . '")');
        }
        if ($css_action_type == 'default') {	  
		     tep_db_query('insert into ' . TABLE_BTS_CSS_SELECTORS . ' (selectors_name, selectors_properties, selectors_type, parent_id, sort_order, customers_id ) values ("notempty", "notemptyto", "' . $css_action_type . '", "' . (int)$css_id . '", "0", "' . (int)$customer_id . '")');
	   }
		return_css_content();
	  break;
	  
      case 'delete' :
         $selectors = tep_get_css_tree($selectors_id, '', '0', '', true);
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($selectors); $i<$n; $i++) {          
		 tep_db_query("delete from " . TABLE_BTS_CSS_SELECTORS . " where selectors_id = '"  . (int)$selectors[$i]['id'] . "' and customers_id = '" . (int)$customer_id . "'");
		 }
		 return_css_content();
      break; 
      case 'status_toggle':

    	 if ($css_action_type == 'status_enable') {
            tep_db_query("update " . TABLE_BTS_CSS_SELECTORS . " set selectors_status = '1', last_modified = now() where selectors_id = '" . (int)$selectors_id . "'  and customers_id = '" . (int)$customer_id . "'");		 
		 }
		 if ($css_action_type == 'status_disable') {
            tep_db_query("update " . TABLE_BTS_CSS_SELECTORS . " set selectors_status = '0', last_modified = now() where selectors_id = '" . (int)$selectors_id . "'  and customers_id = '" . (int)$customer_id . "'");		 
		 }
		 return_css_content();
        break;  
    }
  
	function return_css_content() {
    global $css_id;
    $OSCOM_CSSEditor = new bts_css_editor();
    $OSCOM_CSSEditor->setRootCategoryID($css_id);
	 
	 $return =  '<div id="css-container">';  
	 $return .=  '     <div class="col-md-12  alert alert-template dragMe"  id="'.(int)$css_id.'" data-element-id="'.(int)$css_id.'" draggable="false">';		  
     $return .=  '       <span class="label label-template">'.$OSCOM_CSSEditor->getData($css_id, 'selectors_name' ).'</span>'; 	
     $return .=  $OSCOM_CSSEditor->cssTree();
	 $return .='        </div>';
	 $return .=  '   </div>';
	 
     header('Content-Type: application/json');										 
  
      echo json_encode($return);

}    
  
?>