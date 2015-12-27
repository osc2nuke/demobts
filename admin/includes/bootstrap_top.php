<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo TITLE; ?></title>    
    
	<!-- Bootstrap SELECT -->

    <!-- Bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?php echo tep_catalog_href_link('ext/bootstrap/css/bootstrap.css', '', 'SSL'); ?>">
<link rel="stylesheet" href="<?php echo tep_catalog_href_link('ext/font-awesome-4.2.0/css/font-awesome.min.css', '', 'SSL'); ?>">

<!-- Optional theme -->
<!-- FontAwesome -->
<!-- Custom styles for this template -->
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/general.js"></script>
<script src="<?php echo tep_catalog_href_link('ext/bootstrap/js/jquery-1.11.0.min.js', '', 'SSL'); ?>"></script>
<script src="<?php echo tep_catalog_href_link('ext/bootstrap/js/bootstrap.js', '', 'SSL'); ?>"></script>
<script src="<?php echo tep_catalog_href_link('ext/bootstrap/js/jquery.dnd_page_scroll.js', '', 'SSL'); ?>"></script>
	

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

 </head>
  <body>
<?php
  if (tep_session_is_registered('admin')) {
  if ((basename($PHP_SELF) == FILENAME_BTS_CSS) || (basename($PHP_SELF) == FILENAME_BTS_HEADER_BUILDER)){
  ?>
<style>
#contentText {
  margin-left: 0;
}
</style>
<?php   
  }else{
    include(DIR_WS_INCLUDES . 'column_left.php');
	}
  } else {
?>

<style>
#contentText {
  margin-left: 0;
}
</style>

<?php
  }
?> 
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
	  <?php echo '<a class="navbar-brand" href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'oscommerce.png', 'osCommerce Online Merchant v' . tep_get_version(), '', '', 'class="img-responsive"') . '</a>'; ?>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Documentation</a></li>
        <li><a href="#">Quick help</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">+ New Element<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a data-action="create_header" data-type="nav" class="action "href="#">New nav</a></li>
            <li><a data-action="create_header" data-type="navtoggle" class="action "href="#">New *NavToggle</a></li>
            <li class="divider"></li>
            <li><a data-action="create_header" data-type="div" class="action "href="#">New divider</a></li>
            <li><a data-action="create_header" data-type="container" class="action "href="#">New Conainer</a></li>
            <li><a data-action="create_header" data-type="row" class="action "href="#">New row</a></li>
            <li><a data-action="create_header" data-type="column" class="action "href="#">New column</a></li>			
            <li><a data-action="create_header" data-type="ul" class="action "href="#">New ul</a></li>			
			</ul>
        </li>
		
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">+ New Item<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a data-action="create_header" data-type="dropdown" class="action "href="#">New Dropdown</a></li>
            <li><a data-action="create_header" data-type="dropdowncat" class="action "href="#">New Cat dropdown</a></li>			
		   <li class="divider"></li>
			<li><a data-action="create_header" data-type="form" class="action "href="#">New Form</a></li>
            <li><a data-action="create_header" data-type="formsearch" class="action "href="#">New Search Form</a></li>
           <li class="divider"></li>
			<li><a data-action="create_header" data-type="text" class="action "href="#">New Text</a></li>
            <li><a data-action="create_header" data-type="link" class="action "href="#">New Link</a></li>			
            <li><a data-action="create_header" data-type="logo" class="action "href="#">New Logo</a></li>			
            <li><a data-action="create_header" data-type="cart" class="action "href="#">New *cart</a></li>
            <li><a data-action="create_header" data-type="button" class="action "href="#">New Button</a></li>			
			
          </ul>
        </li>		
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
 
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>



<div id="contentText">  