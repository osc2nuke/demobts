<?php

    require('includes/application_top.php');
// No direct access to this file 
if(!IS_AJAX) {die('Restricted access');}
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_BTS_HEADER_BUILDER);

     if (isset($_POST['action'])) $action = tep_db_prepare_input($_POST['action']);
	  
     if(isset($_POST['drag_id'])) {
      $drag_id = tep_db_prepare_input($_POST['drag_id']);
    }  
     if(isset($_POST['drop_id'])) {
      $drop_id = tep_db_prepare_input($_POST['drop_id']);
    }
     if(isset($_POST['selected_id'])) {
      $headers_id = tep_db_prepare_input($_POST['selected_id']);
    } 
     if (isset($_POST['move_action']))	{
      $move_action = tep_db_prepare_input($_POST['move_action']);
    }		  

     switch ($action) {
      case 'reload':

		return_content();
        break;
      case 'status_enable':
      case 'status_disable':
        if (isset($_POST['form_type'])) $headers_id = tep_db_prepare_input($_POST['form_type']);         
		 if ($action == 'status_enable') {
            tep_db_query("update " . TABLE_HEADERS . " set headers_status = '1', last_modified = now() where headers_id = '" . (int)$headers_id . "' and customers_id = '" . (int)$customer_id . "'");		 
		 }
		 if ($action == 'status_disable') {
            tep_db_query("update " . TABLE_HEADERS . " set headers_status = '0', last_modified = now() where headers_id = '" . (int)$headers_id . "' and customers_id = '" . (int)$customer_id . "'");		 
		 }
              return_content();
        break;
		
      case 'insert_header':
      case 'update_header':
        if (isset($_POST['headers_id'])) $headers_id = tep_db_prepare_input($_POST['headers_id']);
        if (isset($_POST['parent_id'])) $parent_id = tep_db_prepare_input($_POST['parent_id']);

        $sort_order = tep_db_prepare_input($_POST['sort_order']);
        $headers_type = tep_db_prepare_input($_POST['headers_type']);
        $headers_url = tep_db_prepare_input($_POST['headers_url']);
        $headers_class = tep_db_prepare_input($_POST['headers_class']);
        $headers_css_id = tep_db_prepare_input($_POST['headers_css_id']);
        $headers_fa_icon = tep_db_prepare_input($_POST['headers_fa_icon']);

        $sql_data_array = array('headers_url' => $headers_url,
		                        'headers_class' => $headers_class,
		                        'headers_css_id' => $headers_css_id,
		                        'headers_fa_icon' => $headers_fa_icon,
		                        'sort_order' => (int)$sort_order,
								'customers_id' => (int)$customer_id);
        
		if ($action == 'insert_header') {
        $insert_sql_data = array('headers_type' => $headers_type,
		                         'parent_id' => $parent_id,
                                 'date_added' => 'now()',
								 'headers_status' => '3');
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_HEADERS, $sql_data_array);

          $headers_id = tep_db_insert_id();
		  
        } elseif ($action == 'update_header') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_HEADERS, $sql_data_array, 'update', "headers_id = '" . (int)$headers_id . "'");
        }
        
		

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $headers_name_array = tep_db_prepare_input($_POST['headers_name']);

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('headers_name' => tep_db_prepare_input($headers_name_array[$language_id]));

          if ($action == 'insert_header') {
            $insert_sql_data = array('headers_id' => $headers_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_HEADERS_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_header') {
            tep_db_perform(TABLE_HEADERS_DESCRIPTION, $sql_data_array, 'update', "headers_id = '" . (int)$headers_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }
		return_content();
        break;

        case 'create_header':
        case 'edit_header':
        
	if(isset($_POST["form_type"])){
     $header = tep_db_prepare_input($_POST["form_type"]);
    }

    if ($action == 'edit_header') {
	  
     $headers_query = tep_db_query("select c.headers_id, c.headers_type, c.headers_url, cd.headers_name, c.headers_class, c.headers_css_id, c.headers_fa_icon, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.headers_status, c.customers_id from " . TABLE_HEADERS . " c, " . TABLE_HEADERS_DESCRIPTION . " cd where c.headers_id = '" . (int)$header . "' and c.headers_id = cd.headers_id and c.customers_id = '" . (int)$customer_id . "' and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.headers_name");
     $headers = tep_db_fetch_array($headers_query);
     $hInfo = new objectInfo($headers);	    
     $headersType = $hInfo->headers_type;
     $formID = "update_header";
	  }
	  
    if ($action == 'create_header') {
     $headersType = tep_db_prepare_input($_POST["form_type"]);
	 $formID = "insert_header";
    }

    	//primary headers array
		$header_type_check_static = array('div','container','row','column', 'ul');
	    //primary contents array
		$header_type_check_dynamic = array('dropdown', 'dropdowncat', 'singlecat', 'link', 'text', 'button', 'form', 'formsearch', 'formlogin');
	    //url field
		$header_type_check_url_field = array('link', 'text', 'button', 'dropdowncat');
   	    //icon field
		$header_type_check_icon_field = array('link', 'text', 'button');

     $return = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
     $return .= '  <div class="modal-dialog">';
     $return .= '    <div class="modal-content">';
     $return .= '      <div class="modal-header">';
     $return .= '        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
     $return .= '        <h4 class="text-center modal-title" id="myModalLabel">'.$headersType.'</h4>';
     $return .= '      </div>';		

     $return .= '      <div class="modal-body">';


     $return .= '<form id="'.$formID.'" name="cart_quantity" class="form-horizontal" role="form">';
     $return .= '  <div class="form-group">';
     $return .= '    <label for="headers_type" class="col-sm-4 control-label">Element Type : </label>';
     $return .= '    <div class="col-sm-8">';
     $return .= tep_draw_input_field('headers_type', $headersType, 'class="form-control" placeholder="e.g: col-md-12 someClass" disabled');

	if ($action == 'edit_header') {
     $return .= tep_draw_hidden_field('headers_id', $hInfo->headers_id);
    }

     $return .= '      <input type="hidden" name="headers_type" id="headers_type" value="' . $headersType . '">';
     $return .= '    </div>';
     $return .= '  </div>';

    if (in_array($headersType, $header_type_check_dynamic, true)) {
     $languages = tep_get_languages();
     $return .= '<div class="alert alert-info thumbnail">';

    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {	 
     //return TITLE input [DYNAMIC]
     $return .= '  <div class="form-group">';
     $return .= '    <label for="headers_name" class="col-sm-4 control-label">Title : </label>';
     $return .= '    <div class="col-sm-7 inputGroupContainer">';
     $return .= '      <div class="input-group">';
     $return .= '<span class="input-group-addon">'.tep_image(tep_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name']).'</span>';
    if ($action == 'edit_header') {
     $return .= tep_draw_input_field('headers_name[' . $languages[$i]['id'] . ']', tep_get_header_name($hInfo->headers_id, $languages[$i]['id']), 'class="form-control" placeholder="e.g: link/text/button name" required');
	}
    if ($action == 'create_header') {
     $return .= tep_draw_input_field('headers_name[' . $languages[$i]['id'] . ']', '', 'class="form-control" placeholder="e.g: link/text/button name" required');
    }
     $return .= '      </div>';
     $return .= '    </div>';
     $return .= '  </div>';
    }
     $return .= '  </div>';
   }
 

 
     //return ID input [STATIC]
     $return .= '  <div class="form-group">';
     $return .= '    <label for="headers_css_id" class="col-sm-4 control-label">Css #iD : </label>';
     $return .= '    <div class="col-sm-8">';
    if ($action == 'edit_header') {
     $return .= tep_draw_input_field('headers_css_id', $hInfo->headers_css_id, 'class="form-control" placeholder="e.g: e.g: someId_99"');
    }
    if ($action == 'create_header') {
     $return .= tep_draw_input_field('headers_css_id', '', 'class="form-control" placeholder="e.g: e.g: someId_99"');
    }
     $return .= '    </div>';
     $return .= '  </div>';

     //return CLASS input [STATIC]
     $return .= '  <div class="form-group">';
     $return .= '    <label for="headers_class" class="col-sm-4 control-label">Css .Class : </label>';
     $return .= '    <div class="col-sm-8">';
    if ($action == 'edit_header') {
     $return .= tep_draw_input_field('headers_class', $hInfo->headers_class, 'class="form-control" placeholder="e.g: col-md-12 someClass"');
    }
    if ($action == 'create_header') {
     $return .= tep_draw_input_field('headers_class', '', 'class="form-control" placeholder="e.g: col-md-12 someClass"');
    }
     $return .= '    </div>';
     $return .= '  </div>';

    // $return .= '  </div>';
     //$return .= '<div class="col-md-6"><hr/>';

    if (in_array($headersType, $header_type_check_url_field, true)) {
    if ($headersType == 'dropdowncat') {
$label = 'Cat ID : ';
$placeholder = 'placeholder="e.g: 3"';
$input_Type = 'number';
}else{
$label = 'Url/Link : ';
$placeholder = 'placeholder="e.g: http://www.oscommerce.com"';
$inputType = 'text';
} 	
     //return URL input [DYNAMIC]
     $return .= '  <div class="form-group">';
     $return .= '    <label for="headers_url" class="col-sm-4 control-label">' . $label . '</label>';
     $return .= '    <div class="col-sm-8">';
    if ($action == 'edit_header') {
     $return .= tep_draw_input_field('headers_url', $hInfo->headers_url, 'class="form-control" ' . $placeholder . ' required', $inputType);
    }
    if ($action == 'create_header') {
     $return .= tep_draw_input_field('headers_url', '', 'class="form-control" ' . $placeholder . ' required', $inputType);
    }
     $return .= '    </div>';
     $return .= '  </div>';
  }

    if (in_array($headersType, $header_type_check_icon_field, true)) {
     //return FA-ICON input [DYNAMIC]
     $return .= '  <div class="form-group">';
     $return .= '    <label for="headers_fa_icon" class="col-sm-4 control-label">Icon : </label>';
     $return .= '    <div class="col-sm-8">';
    if ($action == 'edit_header') {
     
	 $return .= '<button type="button" id="convert" class="btn btn-primary" data-icon="'.$hInfo->headers_fa_icon.'" style="margin-left: 30px;"></button>';
	 //$return .= tep_draw_input_field('headers_fa_icon', $hInfo->headers_fa_icon, 'class="form-control" placeholder="e.g: fa-refresh"');
    tep_draw_hidden_field('headers_fa_icon', $hInfo->headers_fa_icon);
	}
    if ($action == 'create_header') {
	 $return .= '<button type="button" id="convert" class="btn btn-primary" data-icon="" style="margin-left: 30px;"></button>';
    // $return .= tep_draw_input_field('headers_fa_icon', '', 'class="form-control" placeholder="e.g: fa-refresh" required');
    // $return .= tep_draw_hidden_field('headers_fa_icon', '');

	}
     $return .= '    </div>';
     $return .= '  </div>';
  }
 
     //return SORT ORDER input [STATIC]
     $return .= '  <div class="form-group">';
     $return .= '    <label for="sort_order" class="col-sm-4 control-label">Sort order : </label>';
     $return .= '    <div class="col-sm-4">';
    if ($action == 'edit_header') {
     $return .=  tep_draw_input_field('sort_order', $hInfo->sort_order, 'min="10" max="990" step="10" class="form-control" required', 'number');
    }
    if ($action == 'create_header') {
     $return .= tep_draw_input_field('sort_order', '', 'min="10" max="990" step="10" class="form-control" placeholder="e.g: 220" required', 'number');
    }
     $return .= '    </div>';
     $return .= '  </div>';

     //return SUBMIT & CANCEL BUTTON [STATIC]
     $return .= '  <div class="form-group">';
     $return .= '    <div class="col-sm-offset-2 col-sm-10">';

	if ($action == 'create_header') {
     $return .= '      <button type="submit" class="btn btn-success btn-lg btn-block">Submit Entry</button>';
	}
	if ($action == 'edit_header') {
     $return .= '      <button type="submit" class="btn btn-primary btn-lg btn-block">Save Changes</button>';
	}
     $return .= '      <button type="reset" class="btn btn-default btn-lg btn-block cancel" data-dismiss="modal">Cancel Form</button>';

     $return .= '    </div>';
     $return .= '  </div>';

    // $return .= '  </div>';

     $return .= '</form>';

     $return .= '</div>';

     $return .= '    </div>';
     $return .= '  </div>';
     $return .= '</div>';
     $return .= '</div>'; 
	 
    header('Content-Type: application/json');	

      echo json_encode($return);
        break;
        
		case 'move_header_confirm':
          $status = tep_db_prepare_input($_POST['status']);
          
		 if ($move_action == 'keep_grouped') {
          tep_db_query("update " . TABLE_HEADERS . " set parent_id = '" . (int)$drop_id . "', last_modified = now(), headers_status = '" . (int)$status . "' where headers_id = '" . (int)$drag_id . "' and customers_id = '" . (int)$customer_id . "'");

		 }else{
		  
		  $headers = tep_get_header_tree($drag_id, '', '0', '', true);
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($headers); $i<$n; $i++) {
		  
          tep_db_query("update " . TABLE_HEADERS . " set parent_id = '" . (int)$drop_id . "', last_modified = now(), headers_status = '" . (int)$status . "' where headers_id = '" . (int)$headers[$i]['id'] . "' and customers_id = '" . (int)$customer_id . "'");
		   }
		}
		return_content();
        break;

        case 'delete_header_confirm':
        if (isset($_POST['form_type'])) {
          $headers_id = tep_db_prepare_input($_POST['form_type']);

          $headers = tep_get_header_tree($headers_id, '', '0', '', true);

          // removing headers can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($headers); $i<$n; $i++) {
            tep_remove_header($headers[$i]['id'], (int)$customer_id);
          }
        }
		return_content();
        break;		
		
		
  }
	function return_content() {
global $template_id;
$OSCOM_StatusTree = new status_tree();
$OSCOM_CategoryDemoTree = new category_demo_tree();
$OSCOM_CategoryDemoTree->setRootCategoryID($template_id);

//$OSCOM_StatusTree = new status_tree();
//$OSCOM_StatusTree->setRootCategoryID($template_id);
     $return = '<!-- row start -->';
     $return .= '<div class="row">';
     $return .= '<!-- col starts -->';
     $return .= '<div class="col-md-3">';
     $return .= '<!-- panel starts -->';
     $return .= '<div id="storage" class="panel panel-default">';
     $return .= '<div class="panel-heading">';
     $return .= '<h3 class="panel-title">'.TEXT_PLACE_HOLDER.'</h3>';
     $return .= '</div>';
     //$return .= '<div class="panel-body">';
     //$return .= 'stuff';
     //$return .= '</div>';
     $return .= '<ul id="header-storage" class="list-group">';
     $return .=   $OSCOM_StatusTree->getStatusTree();
     $return .= '</ul>';
     $return .= '</div>';
     $return .= '<!-- panel ends -->';
     $return .= '</div>';
     $return .= '<!-- col ends -->';
     $return .= '<!-- board starts -->';
     $return .= '<div class="col-md-9">';
     $return .= '  <div id="header-container" class="drop_area">';
	 $return .= '<!-- template Starts -->';
	 $return .= ' <div class="col-md-12 breadcrumb alert alert-template dragMe" data-element-id="'.$template_id.'" id="'.$template_id.'" draggable="false">';		  
     $return .= '  <span class="label label-template">' . $OSCOM_CategoryDemoTree->getData($template_id, 'name' ) . '</span>'; 
 	 $return .=  $OSCOM_CategoryDemoTree->getTree(); 
     $return .= ' </div>';
	 $return .= '<!-- template Ends -->';
     $return .= '  </div>';
     $return .= '</div>';
     $return .= '<!-- board ends -->';
     $return .= '</div>';
     $return .= '<!-- row ends -->';  
      
     header('Content-Type: application/json');										 
  
      echo json_encode($return);

}  
	