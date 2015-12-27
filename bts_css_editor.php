<?php

    require('includes/application_top.php');

    $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

   require(DIR_WS_INCLUDES . 'template_top.php');
  if (!tep_session_is_registered('customer_id')) {
    //$navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
   
    $OSCOM_CSSEditor = new bts_css_editor();
    $OSCOM_CSSEditor->setRootCategoryID($css_id);
	echo '<div id="dndPlaceHolder">'; 

	
			$content =  '<div class="row">';
			
            $content .=  '<div class="col-md-6 text-center">';
            $content .=  '<div class="btn-group">';
            $content .=  '   <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">+ New Css<span class="caret"></span></button>';            
			$content .=  '   <ul class="dropdown-menu" role="menu">';
            $content .=  '    <li><a data-css-action="new_css" data-css-action-type="default" class="css-action" href="#">New Default css</a></li>';
            $content .=  '    <li class="divider"></li>';
            $content .=  '    <li><a data-css-action="new_css" data-css-action-type="media" class="css-action" href="#">New Media css</a></li>';			
			$content .=  '   </ul>';
			$content .=  '</div>';
			$content .=  '</div>';
            

			
			$content .=  ' <div class="col-md-6 text-center">';
			
            $content .=  '  <a href="bts_header_builder.php" type="button" class="btn btn-danger">Builder</a>';            

			$content .=  ' </div>';
			
			$content .=  '</div><br/>';			

			
     $content .=  '<div class="container">';
	 $content .=  '   <div class="row">';
     $content .=  '<div id="css-result">';  	 
     
	 $content .=  '<div id="css-container">';  
	 $content .=  '     <div class="col-md-12  alert alert-template dragMe"  id="'.(int)$css_id.'" data-element-id="'.(int)$css_id.'" draggable="false">';		  
     $content .=  '       <span class="label label-template">'.$OSCOM_CSSEditor->getData($css_id, 'selectors_name' ).'</span>'; 	
     $content .=  $OSCOM_CSSEditor->cssTree();
	 $content .='        </div>';
	 $content .=  '   </div>';
	 
	 $content .=  '</div>';
	 $content .=  '</div>';
	 $content .=  '</div>';
	 
     
    
	echo $content;
	 echo  '</div>';
    require(DIR_WS_INCLUDES . 'template_bottom.php');
    require(DIR_WS_INCLUDES . 'application_bottom.php');  
?>