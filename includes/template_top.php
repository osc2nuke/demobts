<?php
    /*
        $Id$
        
        osCommerce, Open Source E-Commerce Solutions
        http://www.oscommerce.com
        
        Copyright (c) 2012 osCommerce
        
        Released under the GNU General Public License
    */
    
    $oscTemplate->buildBlocks();
    
    if (!$oscTemplate->hasBlocks('boxes_column_left')) {
        $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
    }
    
    if (!$oscTemplate->hasBlocks('boxes_column_right')) {
        $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
    }
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
        <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
        
        <!-- Bootstrap -->
        <link href="ext/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="ext/font-awesome-4.2.0/css/font-awesome.min.css">
        
        <!-- iconpicker styles--> 
        <link rel="stylesheet" href="ext/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css">
        <!-- Google Web Fonts -->
        <link href="http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        
        <!-- Yamm styles-->
        <link href="ext/yamm/yamm.css" rel="stylesheet">
        <!-- Custom -->
        <link href="custom.css" rel="stylesheet">
        <style id="css-area">
            <?php
                /* we load the css data */
                global $css_id;
                $OSCOM_CSS = new bts_css_tree();
                $OSCOM_CSS->setRootCategoryID($css_id);
                echo $OSCOM_CSS->cssOutPutTree();
            ?>
        </style>
        <!-- User -->
        <link href="user.css" rel="stylesheet">
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="ext/bootstrap/js/respond.min.js"></script>
        <![endif]-->
        
        <script src="ext/jquery/jquery-1.11.0.min.js"></script>
        
        <?php echo $oscTemplate->getBlocks('header_tags'); ?>
    </head>
    
    <body>
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        
        <div id="bodyWrapper" class="container-fluid">
            
            <div class="row">
                
                <?php
                    if ( basename( $PHP_SELF ) !== 'bts_header_builder.php'  && basename( $PHP_SELF ) !== 'bts_css_editor.php') {
                    ?>
                    <div id="bodyContent" class="col-md-<?php echo $oscTemplate->getGridContentWidth(); ?> <?php echo ($oscTemplate->hasBlocks('boxes_column_left') ? 'col-md-push-' . $oscTemplate->getGridColumnWidth() : ''); ?>">
                        <?php
                            }else{
                        ?>
                        <div id="bodyContent" class="col-md-<?php echo $oscTemplate->getGridContentWidth(); ?>">
                            <?php
                            }
                        ?>                        