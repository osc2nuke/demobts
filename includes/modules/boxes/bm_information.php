<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_information {
    var $code = 'bm_information';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function bm_information() {
      $this->title = MODULE_BOXES_INFORMATION_TITLE;
      $this->description = MODULE_BOXES_INFORMATION_DESCRIPTION;

      if ( defined('MODULE_BOXES_INFORMATION_STATUS') ) {
        $this->sort_order = MODULE_BOXES_INFORMATION_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_INFORMATION_STATUS == 'True');

        switch (MODULE_BOXES_INFORMATION_CONTENT_PLACEMENT) {
          case 'Left Column':
          $this->group = 'boxes_column_left';
          break;
          case 'Footer':
          $this->group = 'boxes_footer';
          break;
          default:
          $this->group = 'boxes_column_right';
        }
      }
    }

    function execute() {
      global $oscTemplate;

      if ($this->group == 'boxes_footer') {
        $data =  '<div class="col-sm-3 col-lg-2">' .
                 '  <div class="footerbox information">' .
                 '    <h2>' . MODULE_BOXES_INFORMATION_BOX_TITLE . '</h2>' .
                 '    <ul class="list-unstyled">';
      }
      else {
        $data =  '<div class="panel panel-default">' .
                 '  <div class="panel-heading">' . MODULE_BOXES_INFORMATION_BOX_TITLE . '</div>' .
                 '  <div class="panel-body">' .
                 '    <ul class="list-unstyled">';
      }
      
      $data .=   '      <li><a href="' . tep_href_link(FILENAME_SHIPPING) . '">' . MODULE_BOXES_INFORMATION_BOX_SHIPPING . '</a></li>' .
                 '      <li><a href="' . tep_href_link(FILENAME_PRIVACY) . '">' . MODULE_BOXES_INFORMATION_BOX_PRIVACY . '</a></li>' .
                 '      <li><a href="' . tep_href_link(FILENAME_CONDITIONS) . '">' . MODULE_BOXES_INFORMATION_BOX_CONDITIONS . '</a></li>' .
                 '      <li><a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . MODULE_BOXES_INFORMATION_BOX_CONTACT . '</a></li>' .
                 '    </ul>';
                 
      $data .=   '  </div>' .
                 '</div>';

      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_INFORMATION_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Information Module', 'MODULE_BOXES_INFORMATION_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_INFORMATION_CONTENT_PLACEMENT', 'Footer', 'Should the module be loaded in the left or right column or directly in the footer?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Footer\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_INFORMATION_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_INFORMATION_STATUS', 'MODULE_BOXES_INFORMATION_CONTENT_PLACEMENT', 'MODULE_BOXES_INFORMATION_SORT_ORDER');
    }
  }