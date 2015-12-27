<?php

    
    require('includes/application_top.php');
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_BTS_HEADER_BUILDER);
    
    require(DIR_WS_INCLUDES . 'template_top.php');
    
    /* check if customer is logged */
    if (!tep_session_is_registered('customer_id')) {
        tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
    }
	
    
    /* compile placeholder and reference new elements*/
    echo '<div id="dndPlaceHolder">';
    
    $content =  '<div class="row">';
    $content .=  '<div class="col-md-4 text-center">';
    
    $content .=  '<div class="btn-group">';
    $content .=  '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">+ New Element<span class="caret"></span></button>';            
    $content .=  '<ul class="dropdown-menu" role="menu">';
    $content .=  '<li><a data-action="create_header" data-type="nav" class="action" href="#">New nav</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="navtoggle" class="action" href="#">New *NavToggle</a></li>';
    $content .=  '<li class="divider"></li>';
    $content .=  '<li><a data-action="create_header" data-type="div" class="action" href="#">New divider</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="container" class="action" href="#">New Container</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="row" class="action" href="#">New row</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="column" class="action" href="#">New column</a></li>';			
    $content .=  '<li><a data-action="create_header" data-type="ul" class="action" href="#">New ul</a></li>';			
    $content .=  '</ul>';
    $content .=  '</div>';
    $content .=  '</div>';
    
    $content .=  '<div class="col-md-4 text-center">';
    
    $content .=  '<div class="btn-group">';
    $content .=  '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">+ New Item<span class="caret"></span></button>';            
    $content .=  '<ul class="dropdown-menu" role="menu">';
    $content .=  '<li><a data-action="create_header" data-type="dropdown" class="action "href="#">New Dropdown</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="dropdowncat" class="action "href="#">New Cat dropdown</a></li>';			
    $content .=  '<li class="divider"></li>';
    $content .=  '<li><a data-action="create_header" data-type="form" class="action "href="#">New Form</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="formsearch" class="action "href="#">New Search Form</a></li>';
    $content .=  '<li class="divider"></li>';
    $content .=  '<li><a data-action="create_header" data-type="text" class="action "href="#">New Text</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="link" class="action "href="#">New Link</a></li>';			
    $content .=  '<li><a data-action="create_header" data-type="logo" class="action "href="#">New Logo</a></li>';			
    $content .=  '<li><a data-action="create_header" data-type="cart" class="action "href="#">New *cart</a></li>';
    $content .=  '<li><a data-action="create_header" data-type="button" class="action "href="#">New Button</a></li>';			
    $content .=  '<li><a data-action="create_header" data-type="language" class="action "href="#">New language</a></li>';			
    $content .=  '</ul>';
    $content .=  '</div>';
    $content .=  '</div>';
    
    $content .=  '<div class="col-md-4 text-center">';
    
    $content .=  '<a href="bts_css_editor.php" type="button" class="btn btn-danger">Css Editor</a>';            
    
    $content .=  '</div>';
    
    $content .=  '</div><br/>';			
    
    
    echo $content;
    
    echo '<div id="return-form-result"></div>'; 
    
    /* call the template builder class and set the default template id*/
    $OSCOM_CategoryDemoTree = new category_demo_tree();
    $OSCOM_CategoryDemoTree->setRootCategoryID($template_id);
    
    /* Compile the unused element tree structure -left sidebar*/
    $OSCOM_StatusTree = new status_tree();
    
    echo '<div class="contentContainer"><div id="header-result" class="container">'
        .'<!-- row start -->'
        .'<div class="row">'
        .'<!-- col starts -->'
        .'<div class="col-md-3">'
        .'<!-- panel starts -->'
        .'<div id="storage" class="panel panel-default">'
        .'<div class="panel-heading">'
        .'<h3 class="panel-title">'.TEXT_PLACE_HOLDER.'</h3>'
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
        .'<!-- template Starts -->'
        .'<div class="col-md-12 alert alert-template dragMe" data-element-id="'.$template_id.'" id="'.$template_id.'" draggable="false">'		  
        .'<span class="label label-template">' . $OSCOM_CategoryDemoTree->getData($template_id, 'name' ) . '</span>' 
        . $OSCOM_CategoryDemoTree->getTree() 
        .'  </div><!-- template Ends -->'
        .'  </div>'
        .'</div>'     
        .'</div>'
        .'<!-- board ends -->'
        .'</div>'
        .'<!-- row ends -->'  
        .'</div>';  
    
    echo '</div><!-- dndPlaceHolder ends -->';
    
    require(DIR_WS_INCLUDES . 'template_bottom.php');
    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>