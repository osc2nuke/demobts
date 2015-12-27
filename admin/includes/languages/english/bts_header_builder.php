<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Headers');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_HEADERS_TYPE', 'Headers Type');

define('TABLE_HEADING_HEADERS_PRODUCTS', 'Headers name');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');
define('TEXT_URL_FIELD', 'URL or ID:');
define('TEXT_LEVEL', 'Level:');
define('TEXT_HEADERS', 'Headers:');
define('TEXT_SUBHEADERS', 'Sub-headers:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_HEADERS_OR_PRODUCTS', 'Please insert a new header in this level.');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_HEADERS_ID', 'Header ID:');
define('TEXT_EDIT_HEADERS_NAME', 'Header Name:');
define('TEXT_EDIT_HEADERS_IMAGE', 'Header Image:');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');

define('TEXT_INFO_CURRENT_HEADERS', 'Current Headers:');

define('TEXT_INFO_HEADING_NEW_HEADER', 'New Header');
define('TEXT_INFO_HEADING_EDIT_HEADER', 'Edit Header');
define('TEXT_INFO_HEADING_DELETE_HEADER', 'Delete Header');
define('TEXT_INFO_HEADING_MOVE_HEADER', 'Move Header');

define('TEXT_DELETE_HEADER_INTRO', 'Are you sure you want to delete this header?');

define('TEXT_DELETE_WARNING_CHILDS', '<strong>WARNING:</strong> There are %s (child-)headers still linked to this header!');

define('TEXT_MOVE_HEADERS_INTRO', 'Please select which header you wish <strong>%s</strong> to reside in');
define('TEXT_MOVE', 'Move <strong>%s</strong> to:');

define('TEXT_NEW_HEADER_INTRO', 'Please fill out the following information for the new header');
define('TEXT_HEADERS_NAME', 'Header Name:');
define('TEXT_HEADERS_IMAGE', 'Header Image:');
define('TEXT_SORT_ORDER', 'Sort Order:');


define('EMPTY_HEADER', 'Empty Header');
define('TEXT_PLACE_HOLDER', '<span class="glyphicon glyphicon-briefcase"></span>  Place Holder');

//buttons primary
define('IMAGE_NEW_HEADER_TOP', 'New Header-Top');
define('IMAGE_NEW_HEADER_CONTAINER', 'New Container');
define('IMAGE_NEW_HEADER_MAIN', 'New Main-Header');
define('IMAGE_NEW_HEADER_MENU', 'New Menu-Header');
//buttons content
define('IMAGE_NEW_DROPDOWN', 'New Dropwdown');
define('IMAGE_NEW_DROPDOWNCAT', 'New Cat-Dropdown');
define('IMAGE_NEW_LINK', 'New Link');
define('IMAGE_NEW_TEXT', 'New Text');
define('IMAGE_NEW_SINGLE_CAT', 'New Single-Cat');
define('IMAGE_NEW_BUTTON', 'New Button');
define('IMAGE_NEW_FORM_SEARCH', 'New SearchForm');
define('IMAGE_NEW_FORM_LOGIN', 'New LoginForm');

define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CANNOT_MOVE_HEADER_TO_PARENT', 'Error: Header cannot be moved into child header.');
?>
