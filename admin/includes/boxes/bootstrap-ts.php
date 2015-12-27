<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
    'heading' => BOX_HEADING_BTS,
    'apps' => array(
      array(
        'code' => FILENAME_BTS_HEADER_BUILDER,
        'title' => BOX_HEADING_BTS_HEADER_BUILDER,
        'link' => tep_href_link('bts_header_builder.php')
      ),
      array(
        'code' => FILENAME_BTS_CSS,
        'title' => BOX_CSS_EDITOR,
        'link' => tep_href_link('bts_css.php')
      )
    )
  );
?>
