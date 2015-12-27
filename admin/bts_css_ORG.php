<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

//copy of tep_get_languages
  function tep_get_properties($selector_id) {
    $property_query = tep_db_query("select properties_id, property_element, property_value from " . TABLE_BTS_CSS_PROPERTIES . " where selectors_id ='".$selector_id."'");
    while ($property = tep_db_fetch_array($property_query)) {
      $property_array[] = array( 'properties_id' => $property['properties_id'],
	                             'property_element' => $property['property_element'],
                                 'property_value' => $property['property_value']);
    }

    return $property_array;
  }

//copy of tep_get_manufacturers_url
  function tep_get_property_element($selectors_id, $properties_id) {
    $css_query = tep_db_query("select property_element from " . TABLE_BTS_CSS_PROPERTIES . " where selectors_id = '" . (int)$selectors_id . "' and properties_id = '" . (int)$properties_id . "'");
    $css = tep_db_fetch_array($css_query);

    return $css['property_element'];
  }
  
//copy of tep_get_manufacturers_url  
  function tep_get_property_value($selectors_id, $properties_id) {
    $css_query = tep_db_query("select property_value from " . TABLE_BTS_CSS_PROPERTIES . " where selectors_id = '" . (int)$selectors_id . "' and properties_id = '" . (int)$properties_id . "'");
    $css = tep_db_fetch_array($css_query);

    return $css['property_value'];
  }

  
  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':

	  //insert SELECTOR START
	  $sql_data_array = array();
          $selectors_name = tep_db_prepare_input($HTTP_POST_VARS['selectors_name']);
          $property_element = $HTTP_POST_VARS['property_element'];
          $property_value = $HTTP_POST_VARS['property_value'];        
		  $insert_sql_data = array('selectors_name' => $selectors_name,
                                 'date_added' => 'now()');
        
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
	  
        tep_redirect(tep_href_link(FILENAME_BTS_CSS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'selID=' . $selectors_id));

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
          $property_element_array = $HTTP_POST_VARS['property_element'];
          $property_value_array = $HTTP_POST_VARS['property_value'];

          $property_id = $properties[$i]['properties_id'];

          $sql_data_array = array('property_element' => tep_db_prepare_input($property_element_array[$property_id]),
		                          'property_value' => tep_db_prepare_input($property_value_array[$property_id]));


        tep_db_perform(TABLE_BTS_CSS_PROPERTIES, $sql_data_array, 'update', "properties_id = '" . (int)$property_id . "' and selectors_id = '" . (int)$selectors_id . "'");
          
        }



        tep_redirect(tep_href_link(FILENAME_BTS_CSS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'selID=' . $selectors_id));
        break;
      case 'deleteconfirm':
        $selectors_id = tep_db_prepare_input($HTTP_GET_VARS['selID']);


        tep_db_query("delete from " . TABLE_BTS_CSS_SELECTORS . " where selectors_id = '" . (int)$selectors_id . "'");
        tep_db_query("delete from " . TABLE_BTS_CSS_PROPERTIES . " where selectors_id = '" . (int)$selectors_id . "'");



        if (USE_CACHE == 'true') {
          tep_reset_cache_block('css');
        }

        tep_redirect(tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'bootstrap_top.php');
?>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SELECTORS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $css_query_raw = "select selectors_id, selectors_name, selectors_image, date_added, last_modified from " . TABLE_BTS_CSS_SELECTORS . " order by selectors_name";
  $css_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $css_query_raw, $css_query_numrows);
  $css_query = tep_db_query($css_query_raw);
  while ($css = tep_db_fetch_array($css_query)) {
    if ((!isset($HTTP_GET_VARS['selID']) || (isset($HTTP_GET_VARS['selID']) && ($HTTP_GET_VARS['selID'] == $css['selectors_id']))) && !isset($cssInfo) && (substr($action, 0, 3) != 'new')) {

      $cssInfo = new objectInfo($css);
    }

    if (isset($cssInfo) && is_object($cssInfo) && ($css['selectors_id'] == $cssInfo->selectors_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $css['selectors_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $css['selectors_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $css['selectors_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cssInfo) && is_object($cssInfo) && ($css['selectors_id'] == $cssInfo->selectors_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $css['selectors_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $css_split->display_count($css_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CSS_SELECTORS); ?></td>
                    <td class="smallText" align="right"><?php echo $css_split->display_links($css_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo tep_draw_button(IMAGE_INSERT, 'plus', tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $cssInfo->selectors_id . '&action=new')); ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<strong>' . TEXT_HEADING_NEW_CSS . '</strong>');

      $contents = array('form' => tep_draw_form('css', FILENAME_BTS_CSS, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_CSS_SELECTOR_NAME . '<br />' . tep_draw_input_field('selectors_name'));
      
      $css_inputs_string = '<table class="input_fields_wrap">';
      $css_inputs_string .= '<tr><th>'.TEXT_PROPERTY_ELEMENT.'</td><th> : </th><th>'.TEXT_PROPERTY_VALUE.'</th><th><button class="add_field_button"> + </button></th></tr>';
      $css_inputs_string .= '<tr><td>' .  tep_draw_input_field('property_element[]') .  '</td><td>:</td><td>' . tep_draw_input_field('property_value[]') . '</td></tr>';
      $css_inputs_string .= '</table>';
	  
      $contents[] = array('text' => '<br />' . $css_inputs_string);
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $HTTP_GET_VARS['selID'])));
      break;
    case 'edit':
      $heading[] = array('text' => '<strong>' . TEXT_HEADING_EDIT_CSS . '</strong>');

      $contents = array('form' => tep_draw_form('css', FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $cssInfo->selectors_id . '&action=save', 'post'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_CSS_SELECTOR_NAME . '<br />' . tep_draw_input_field('selectors_name', $cssInfo->selectors_name));
      
	  $css_inputs_string = '<table class="input_new_edit_fields_wrap">';
      $css_inputs_string .= '<tr><th>'.TEXT_PROPERTY_ELEMENT.'</td><th> : </th><th>'.TEXT_PROPERTY_VALUE.'</th><th><button id="' . $cssInfo->selectors_id . '" class="add_new_edit_field_button"> + </button></th></tr>';

      $properties = tep_get_properties($cssInfo->selectors_id);
      for ($i=0, $n=sizeof($properties); $i<$n; $i++) {
        $css_inputs_string .= '<tr id="' . $properties[$i]['properties_id'] . '"><td>' . tep_draw_input_field('property_element[' . $properties[$i]['properties_id'] . ']', tep_get_property_element($cssInfo->selectors_id, $properties[$i]['properties_id'])) . '</td><td>:</td><td> ' . tep_draw_input_field('property_value[' . $properties[$i]['properties_id'] . ']', tep_get_property_value($cssInfo->selectors_id, $properties[$i]['properties_id'])) . '</td><td><a href="#" class="remove_new_edit_field">Remove</a></td></tr>';
      }
      $css_inputs_string .= '</table>';
      $contents[] = array('text' => '<br />' . $css_inputs_string);
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $cssInfo->selectors_id)));
      break;
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_HEADING_DELETE_CSS . '</strong>');

      $contents = array('form' => tep_draw_form('css', FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $cssInfo->selectors_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br /><strong>' . $cssInfo->selectors_name . '</strong>');


      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $cssInfo->selectors_id)));
      break;
    default:
      if (isset($cssInfo) && is_object($cssInfo)) {
        $heading[] = array('text' => '<strong>' . $cssInfo->selectors_name . '</strong>');

        $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $cssInfo->selectors_id . '&action=edit')) . tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_BTS_CSS, 'page=' . $HTTP_GET_VARS['page'] . '&selID=' . $cssInfo->selectors_id . '&action=delete')));
        $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . tep_date_short($cssInfo->date_added));
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="50%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table>

<?php
  require(DIR_WS_INCLUDES . 'bootstrap_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
