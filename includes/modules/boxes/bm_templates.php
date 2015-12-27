<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  class bm_templates {
    var $code = 'bm_templates';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function bm_templates() {
      $this->title = MODULE_BOXES_TEMPLATES_TITLE;
      $this->description = MODULE_BOXES_TEMPLATES_DESCRIPTION;

      if ( defined('MODULE_BOXES_TEMPLATES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_TEMPLATES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_TEMPLATES_STATUS == 'True');

        $this->group = ((MODULE_BOXES_TEMPLATES_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      }
    }

    function getData() {
      global $HTTP_GET_VARS, $request_type, $oscTemplate, $template_id;

      $data = '';
    $content = tep_draw_form('goto', 'bts_header_builder.php', '', 'get');
    $content .=  tep_draw_pull_down_menu('template_id', tep_get_templates_tree(), $current_category_id, 'onchange="this.form.submit();"');
    $content .= tep_hide_session_id() . '</form>';

        $data = '<div class="panel panel-default">' .
                  '  <div class="panel-heading">' . MODULE_BOXES_TEMPLATES_BOX_TITLE . '</div>' .
                  '  <div class="panel-body">' . $content . '</div>' .
                  '</div>';
      

      return $data;
    }

    function execute() {
      global $SID, $oscTemplate;


        $output = $this->getData();
      

      $oscTemplate->addBlock($output, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TEMPLATES_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Manufacturers Module', 'MODULE_BOXES_TEMPLATES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TEMPLATES_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TEMPLATES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_TEMPLATES_STATUS', 'MODULE_BOXES_TEMPLATES_CONTENT_PLACEMENT', 'MODULE_BOXES_TEMPLATES_SORT_ORDER');
    }
  }
?>
