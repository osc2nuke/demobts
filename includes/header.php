<?php
    /*
        $Id$
        
        osCommerce, Open Source E-Commerce Solutions
        http://www.oscommerce.com
        
        Copyright (c) 2010 osCommerce
        
        Released under the GNU General Public License
    */
    
    if ($messageStack->size('header') > 0) {
        echo '<div class="col-md-12">' . $messageStack->output('header') . '</div>';
    }
    /* We load the compiled template per ID */
    global $oscTemplate, $cPath;
    $OSCOM_BtsMenu = new bts_menu_view();
    $OSCOM_BtsMenu->setMenuRootCategoryID($template_id);
    $data = '<header class="yamm" id="header-area">' . $OSCOM_BtsMenu->getHeaderTree() . '</header>';
    echo $data;
    
    
?>
<div class="clearfix"></div>

<?php if ($oscTemplate->hasBlocks('boxes_header')) echo $oscTemplate->getBlocks('boxes_header'); ?>

<div class="clearfix"></div>

<?php
    if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
    ?>
    <div class="clearfix"></div>
    <div class="col-xs-12">
        <div class="alert alert-danger">
            <a href="#" class="close glyphicon glyphicon-remove" data-dismiss="alert"></a>
            <?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?>
        </div>
    </div>
    <?php
    }
    
    if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
    ?>
    <div class="clearfix"></div>
    <div class="col-xs-12">
        <div class="alert alert-info">
            <a href="#" class="close glyphicon glyphicon-remove" data-dismiss="alert"></a>
            <?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?>
        </div>
    </div>
    <?php
    }
?>
