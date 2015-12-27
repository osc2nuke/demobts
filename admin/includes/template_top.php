<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="robots" content="noindex,nofollow">
<title><?php echo TITLE; ?></title>
<base href="<?php echo HTTP_SERVER . DIR_WS_ADMIN; ?>" />
<!--[if IE]><script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/excanvas.min.js'); ?>"></script><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo tep_catalog_href_link('ext/jquery/ui/redmond/jquery-ui-1.8.22.css'); ?>">
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/jquery-1.8.0.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/ui/jquery-ui-1.8.22.min.js'); ?>"></script>

<script type="text/javascript">
// fix jQuery 1.8.0 and jQuery UI 1.8.22 bug with dialog buttons; http://bugs.jqueryui.com/ticket/8484
if ( $.attrFn ) { $.attrFn.text = true; }
</script>

<?php
  if (tep_not_null(JQUERY_DATEPICKER_I18N_CODE)) {
?>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/ui/i18n/jquery.ui.datepicker-' . JQUERY_DATEPICKER_I18N_CODE . '.js'); ?>"></script>
<script type="text/javascript">
$.datepicker.setDefaults($.datepicker.regional['<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>']);
</script>
<?php
  }
?>

<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/general.js"></script>
<script>
$(document).ready(function() {
$('input').attr('size',40);
    var max_fields      = 10; //maximum input boxes allowed
	
//new fields when create new selector	
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
	
//new fields when edit
    var editNew         = $(".input_new_edit_fields_wrap"); //Fields wrapper
    var add_new_edit_button      = $(".add_new_edit_field_button"); //Add button ID
  
    var x = 1; //initlal text box count

    $(add_new_edit_button).click(function(e){ //on add input button click
        e.preventDefault();
		var selector_id = $(this).attr('id');
$.post( "ajax_new_css_field.php", { action: "new_field", sel_id: selector_id }, function( data ) {
            $(editNew).append(data); //add input box
 
}, "json");

    });	
    $(editNew).on("click",".remove_new_edit_field", function(e){ //user click on remove text
        e.preventDefault();
var field_id = $(this).parent().parent().attr('id');
$.post( "ajax_new_css_field.php", { action: "delete_field", field_id: field_id});	
$(this).parent().parent().remove();
	
    })












    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<tr><td><input type="text" name="property_element[]"/></td><td> : </td><td><input type="text" name="property_value[]"/></td><td><a href="#" class="remove_field">Remove</a></td></tr>'); //add input box
 
 }
    });
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
		$(this).parent().parent().remove(); 
		x--;
    })
	
	
	
	
	
	
	
});
</script>
</head>
<body>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

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
  echo '-----' . basename($PHP_SELF);
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

<div id="contentText">
