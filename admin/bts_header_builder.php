<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');



  require(DIR_WS_INCLUDES . 'bootstrap_top.php');

  echo '<div id="dndPlaceHolder">';

  echo '<div id="return-form-result"></div>'; 


$OSCOM_CategoryTree = new category_tree();
$OSCOM_StatusTree = new status_tree();

echo '<div id="header-result" class="container">'
     .'<!-- row start -->'
	 .'<div class="row">'
	 .'<!-- col starts -->'
	 .'<div class="col-md-3">'
	 .'<!-- panel starts -->'
     .'<div id="storage" class="panel panel-default">'
     .'<div class="panel-heading">'
     .'<h3 class="panel-title">'.TEXT_PLACE_HOLDER.'</h3>'
     .'</div>'
     .'<div class="panel-body">'
     .'stuff'
     .'</div>'
	 .'<ul id="header-storage" class="list-group">'
     . $OSCOM_StatusTree->getStatusTree()
	 .'</ul>'	 
     .'</div>'
	 .'<!-- panel ends -->'
	 .'</div>'
	 .'<!-- col ends -->'
	 .'<!-- board starts -->'
	 .'<div class="col-md-9">'
 	 .'<div id="header-container" class="drop_area">'
 	 . $OSCOM_CategoryTree->getTree() 
 	 .'  </div>'
 	 .'</div>'     
	 .'</div>'
	 .'<!-- board ends -->'
     .'</div>'
	 .'<!-- row ends -->'  
     .'</div>';  

	 echo '</div><!-- dndPlaceHolder ends -->';

  require(DIR_WS_INCLUDES . 'bootstrap_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>