<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_BTS_CSS);

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

   require(DIR_WS_INCLUDES . 'template_top.php');
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

            $content =  '<div class="container"><div class="row">';
			$content =  '<div class="col-md-4 text-center">';
            $content .=  HEADING_TITLE;            
			$content .=  '</div>';			
			$content .=  '<div class="col-md-4 text-center">';
           if (empty($action)) {
            $content .=  '<a href="'.tep_href_link(FILENAME_BTS_CSS, 'action=new').'" class="btn btn-sm btn-primary" role="button">+ New Selector</a>';
           }
			$content .=  '</div>';
			$content .=  '<div class="col-md-4 text-center">';
            $content .=  '<a href="bts_header_builder.php" type="button" class="btn btn-danger">Builder</a>';            
			$content .=  '</div>';
			$content .=  '</div></div><br/>';			

			
  echo $content;  

  


  if (tep_not_null($action)) {
    switch ($action) {
	
      case 'insert':

	  //insert SELECTOR START
	  $sql_data_array = array();
          $selectors_name = tep_db_prepare_input($HTTP_POST_VARS['selectors_name']);
          $property_element = tep_db_prepare_input($HTTP_POST_VARS['property_element']);
          $property_value = tep_db_prepare_input($HTTP_POST_VARS['property_value']);        
		  $insert_sql_data = array('selectors_name' => $selectors_name,
                                   'date_added' => 'now()',
								   'customers_id' => (int)$customer_id);
		tep_db_perform(TABLE_BTS_CSS_SELECTORS, $insert_sql_data);
          $selectors_id = tep_db_insert_id();
		//insert SELECTOR END
        
		
		$properties_array = array('property_element' => $property_element,
		                         'property_value' => $property_value);

	  //Start inserting multiple PROPERTY fields
        
		//i think this is not the good way  
        for ($i=0, $n=sizeof($property_element); $i<$n; $i++) {

         tep_db_query('insert into ' . TABLE_BTS_CSS_PROPERTIES . ' (selectors_id, property_element, property_value ) values ("' . $selectors_id . '", "' . $property_element[$i] . '", "' . $property_value[$i] . '")');
        
		}
	  //END inserting multiple PROPERTY fields
	  
        tep_redirect(tep_href_link(FILENAME_BTS_CSS));

        break;
      case 'save':
        if (isset($HTTP_GET_VARS['selID'])) $selectors_id = tep_db_prepare_input($HTTP_GET_VARS['selID']);
        $selectors_name = tep_db_prepare_input($HTTP_POST_VARS['selectors_name']);

        $sql_data_array = array('selectors_name' => $selectors_name);


          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_BTS_CSS_SELECTORS, $sql_data_array, 'update', "selectors_id = '" . (int)$selectors_id . "'");
        

        $properties = tep_get_properties($selectors_id);
        for ($i=0, $n=sizeof($properties); $i<$n; $i++) {
          $property_element_array = tep_db_prepare_input($HTTP_POST_VARS['property_element']);
          $property_value_array = tep_db_prepare_input($HTTP_POST_VARS['property_value']);

          $property_id = tep_db_prepare_input($properties[$i]['properties_id']);

          $sql_data_array = array('property_element' => $property_element_array[$property_id],
		                          'property_value' => $property_value_array[$property_id]);


        tep_db_perform(TABLE_BTS_CSS_PROPERTIES, $sql_data_array, 'update', "properties_id = '" . (int)$property_id . "' and selectors_id = '" . (int)$selectors_id . "'");
          
        }
        tep_redirect(tep_href_link(FILENAME_BTS_CSS));
        break;
      case 'deleteconfirm':
        if (isset($HTTP_GET_VARS['selID'])) $selectors_id = tep_db_prepare_input($HTTP_GET_VARS['selID']);

        tep_db_query("delete from " . TABLE_BTS_CSS_SELECTORS . " where selectors_id = '" . (int)$selectors_id . "'");
        tep_db_query("delete from " . TABLE_BTS_CSS_PROPERTIES . " where selectors_id = '" . (int)$selectors_id . "'");

        tep_redirect(tep_href_link(FILENAME_BTS_CSS));
        break;

    
	case 'new':
      $result = '<div class="container thumbnail">';
	  $result .= '<strong>' . TEXT_HEADING_NEW_CSS . '</strong>';
	  	  
      $result .=  tep_draw_form('css', tep_href_link(FILENAME_BTS_CSS, tep_get_all_get_params(array('action')). 'action=insert', 'NONSSL'), 'post');

      //$result .= tep_draw_form('css', FILENAME_BTS_CSS, 'action=insert', 'post', ' enctype="multipart/form-data"');
      $result .= '<p>' . TEXT_NEW_INTRO . '</p>';
      
	  $result .= '<div class="form-group">';
      $result .= '<div class="row">';
	  
	  $result .= '<div class="col-md-10">' . tep_draw_input_field('selectors_name', '',  'class="form-control" placeholder="' . TEXT_CSS_SELECTOR_NAME . '"') . '</div>';
	  //$result .= '<div class="col-md-4 text-center"><button class="btn btn-sm btn-success add_field_button"> + </button></div>';
	  
	  $result .= '</div>';
	  $result .= '</div>';
	  
	  $result .= '<div class="input_fields_wrap">';      
	  
	  $result .= '<div class="form-group">';
	  $result .= '<div class="row">';
      
	  $result .= '<div class="col-md-5">' .  tep_draw_input_field('property_element[]', '',  'class="form-control" placeholder="property element"') .  '</div>';
	  $result .= '<div class="col-md-5">' . tep_draw_input_field('property_value[]', '',  'class="form-control" placeholder="property value"') . '</div>';      
	  $result .= '<div class="col-md-2 text-center"><button class="btn btn-sm btn-success add_field_button"> + </button></div>';
	  
	  $result .= '</div>';
	  $result .= '</div>';
      
	  $result .= '</div>';
	  $result .= '<div class="form-group"><div class="btn-toolbar text-center" role="toolbar">';
      $result .= tep_draw_button('Save', 'glyphicon-ok', null, 'primary', NULL, 'btn-primary') . ' ' .  tep_draw_button('Cancel', 'glyphicon-remove', tep_href_link(FILENAME_BTS_CSS), 'primary', NULL, 'btn-warning');
      $result .= '</div></div>';
	  
      $result .= '</form>';
      $result .= '</div>';

      echo $result;
	  break;		
    }
  }


    $OSCOM_CSSTree = new bts_css_tree();
    echo '<div class="container">' . $OSCOM_CSSTree->cssTree() . '</div>'; 

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
