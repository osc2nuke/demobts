<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class bm_logo {
    var $code = 'bm_logo';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function bm_logo() {
      $this->title = MODULE_BOXES_LOGO_TITLE;
      $this->description = MODULE_BOXES_LOGO_DESCRIPTION;

      if ( defined('MODULE_BOXES_LOGO_STATUS') ) {
        $this->sort_order = MODULE_BOXES_LOGO_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_LOGO_STATUS == 'True');

        $this->group = 'boxes_header';
      }
    }

    function execute() {
      global $request_type, $oscTemplate;

      $data = '<div class="col-sm-6"><a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME) . '</a></div>';

      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_LOGO_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Logo Module', 'MODULE_BOXES_LOGO_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_LOGO_SORT_ORDER', '10000', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_LOGO_STATUS', 'MODULE_BOXES_LOGO_SORT_ORDER');
    }
  }

