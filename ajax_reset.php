<?php

  require('includes/application_top.php');
  
// No direct access to this file 
if(!IS_AJAX) {die('Restricted access');}
  
     if(isset($_POST['action'])) {
      $action = tep_db_prepare_input($_POST['action']);
    }


     switch ($action) {
  
      case 'reset_header' :
      global $oscTemplate, $cPath, $template_id, $css_id;

	  $OSCOM_CSS = new bts_css_tree();
      $OSCOM_BtsMenu = new bts_menu_view();
	  $OSCOM_BtsMenu->setMenuRootCategoryID($template_id);
	  $OSCOM_CSS->setRootCategoryID($css_id);

	  $css = $OSCOM_CSS->cssOutPutTree();
      $header_data = $OSCOM_BtsMenu->getHeaderTree();

		 	   header('Content-Type: application/json');										 
               echo json_encode($css .'|'. $header_data );
      break;
	  

  
    }
  
 
  
?>