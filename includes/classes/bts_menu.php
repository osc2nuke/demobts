<?php
    /**
        * For osCommerce Online Merchant
        * 
        * @copyright Copyright (c) 2014 osCommerce.solutions; http://www.oscommerce.solutions
    */
    
    class bts_menu_view {
        protected $_data = array();
        
        var $root_category_id = 0,
        $max_level = 0;
        
        
        public function __construct() {
            global $languages_id, $customer_id;
            
            static $_bts_menu_data;
            
            if ( isset($_bts_menu_data) ) {
                $this->_data = $_bts_menu_data;
                } else {
                
                $categories_query = tep_db_query("select c.headers_id, c.headers_type, c.headers_url, cd.headers_name, c.headers_class, c.headers_css_id, c.headers_fa_icon, c.parent_id, c.sort_order,  c.headers_status from " . TABLE_HEADERS . " c, " . TABLE_HEADERS_DESCRIPTION . " cd where c.headers_id = cd.headers_id and cd.language_id = '" . (int)$languages_id . "' and c.customers_id = '" . (int)$customer_id . "' and c.headers_status = 1 order by c.sort_order, cd.headers_name");
                
                while ( $categories = tep_db_fetch_array($categories_query) ) {
                    $this->_data[$categories['parent_id']][$categories['headers_id']] = array('type' => $categories['headers_type'],
                    'headers_url' => $categories['headers_url'],
                    'name' => $categories['headers_name'],
                    'headers_class' => $categories['headers_class'],
                    'headers_css_id' => $categories['headers_css_id'],
                    'headers_fa_icon' => $categories['headers_fa_icon']);
                }
                
                $_bts_menu_data = $this->_data;
            }
        }
        
        //Build header menu
        protected function _buildHeaderBranch($parent_id, $level = 0) {
            $result = '';
            $OSCOM_CategoryTree = new category_tree();
            
            if ( isset($this->_data[$parent_id]) ) {
                foreach ( $this->_data[$parent_id] as $category_id => $category ) {
                    $category_link = $category_id;
                    
                    
                    
                    if ( ( $level === 0 ) && isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1 ))) {
                        
                        //we need a opener
                        
                    } 
                    
                    $link_title = $category['name'];
                    
                    if (!empty($category['headers_class'])) { 
                        $headers_class_extra = $category['headers_class'];
                        $headers_class = 'class=" ' . $category['headers_class'] . ' "';
                        }else{
                        $headers_class_extra = null;
                        $headers_class = null;
                    }
                    
                    if (!empty($category['headers_css_id'])) { 
                        $headers_css_id_extra = $category['headers_css_id'];
                        $headers_css_id = 'id="' . $category['headers_css_id'] . '"';
                        }else{
                        $headers_css_id_extra = null;
                        $headers_css_id = null;
                    }
                    
                    if (!empty($category['headers_fa_icon'])) { 
                        $headers_fa_icon = '<i class="fa '.$category['headers_fa_icon'].'"></i>'; 
                        }else{ 
                        $headers_fa_icon = null;
                    }
                    
                    if($category['type'] == 'div'){
                        $result .= '		<!-- div Startsabc -->';
                        $result .= '			<div ' . $headers_css_id . ' ' . $headers_class . '>';		  
                    }
                    
                    if($category['type'] == 'row'){
                        $result .= '		<!-- row Starts -->';
                        $result .= '			<div ' . $headers_css_id . ' class=" row ' . $headers_class_extra . ' ">';		  
                    }
                    if($category['type'] == 'column'){
                        $result .= '		<!-- column Starts -->';
                        $result .= '			<div ' . $headers_css_id . ' class=" ' . $headers_class_extra . ' ">';		  
                    }		  
                    if($category['type'] == 'container'){
                        $result .= '		<!-- Container Starts -->';
                        $result .= '			<div ' . $headers_css_id . ' class=" container ' . $headers_class_extra . ' ">';		  
                    }
                    if($category['type'] == 'ul'){
                        $result .= '		<!-- Ul Starts -->';
                        $result .= '			<ul ' . $headers_css_id . ' class=" ' . $headers_class_extra . ' ">';		  
                    }
                    if($category['type'] == 'nav'){
                        $result .= '		<!-- nav Starts -->';
                        $result .= '			<nav ' . $headers_css_id . ' class=" ' . $headers_class_extra . ' " role="navigation">';		  
                    }
                    if($category['type'] == 'logo'){
                        $result .= '					<!-- Logo Starts -->';
                        $result .= '							<div ' . $headers_css_id . ' ' . $headers_class . '>';
                        $result .= '								<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME, '', '', 'class="img-responsive"') . '</a>';
                        $result .= '							</div>';
                        $result .= '					<!-- Logo Ends -->';
                        
                    }
                    
                    if($category['type'] == 'link'){
                        $result .= '<li ' . $headers_css_id . ' ' . $headers_class . '><a href="' . tep_href_link($category['headers_url'], '', 'SSL') . '">'.$headers_fa_icon.' ' . $link_title . '</a></li>';
                    }
                    if($category['type'] == 'singlecat'){
                        $result .= '<p class="' . $headers_class . ' ">';
                        $result .=  '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $OSCOM_CategoryTree->getData($category['headers_url'], 'id' )) . '">';
                        $result .= $OSCOM_CategoryTree->getData($category['headers_url'], 'categories_name' );
                        $result .=  '</a>';
                        $result .=  '</p>';
                    }         
                    if($category['type'] == 'text'){
                        $result .= '<p class="' . $headers_class . ' ">' . $link_title . '</p>';
                    }
                    if($category['type'] == 'button'){
                        $result .= '<button type="button" ' . $headers_css_id . ' class="btn  ' . $headers_class_extra . ' "><a href="'.$category['headers_url'].'">' . $link_title . '</a></button>';
                    }
                    if($category['type'] == 'cart'){
                        // create the shopping cart
                        global $cart, $currencies;
                        
                        
                        $result .= '<div ' . $headers_css_id . ' class="' . $headers_class_extra . '">';
                        $result .= '    <a href="' . tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL') . '">';
                        $result .= '	 <img src="images/header_cart.png" alt="" class="img-responsive hidden-xs" style="float:left;" height="50" width="44"></a>';
                        $result .= '        <p class="text-left" style="white-space: nowrap;"><strong>Cart Contents (' . $cart->count_contents() . ') items</strong></p>';
                        $result .= '        <p class="text-left" style="white-space: nowrap;">Total: ' .$currencies->format($cart->show_total()) . '</p>';
                        $result .= '</div>';
                    }
                    
                    //dropdown language
                    if($category['type'] == 'language'){
                        global $lng, $language, $PHP_SELF, $currencies, $HTTP_GET_VARS, $request_type, $currency;
                        $result .= '        <li class="dropdown ' . $headers_class_extra . '">';
                        $result .= '          <a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                        $result .= '						<img id="current_lang" src="'. DIR_WS_LANGUAGES . $language . '/images/icon.gif' .'" width="16" height="11" alt="'. $language .'" /> <span class="hidden-xs">';
                        $result .= sprintf(USER_LOCALIZATION, ucwords($language), $currency); 
                        $result .= '</span>';
                        $result .= '<span class="caret"></span></a>';
                        $result .= '<ul class="dropdown-menu" role="menu">';
                        
                        
                        if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {

                            if (count($lng->catalog_languages) > 1) {
                                //echo '<li class="divider"></li>';
                                reset($lng->catalog_languages);
                                while (list($key, $value) = each($lng->catalog_languages)) {
                                    $result .=  '<li><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . $value['name'] .  '</a></li>';
                                }
                            }
                            // currencies
                            if (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1)) {
                                $result .= '<li class="divider"></li>';
                                reset($currencies->currencies);
                                $currencies_array = array();
                                while (list($key, $value) = each($currencies->currencies)) {
                                    $currencies_array[] = array('id' => $key, 'text' => $value['title']);
                                    $result .= '<li><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'currency=' . $key, $request_type) . '">' . $value['title'] . '</a></li>';
                                    
                                }
                            }
                        }
                        $result .= '          </ul>';
                        $result .= '        </li>';
                        $result .= '				<!-- /LANGUAGE -->';
                    }
                    //formsearch
                    if($category['type'] == 'formsearch'){
                        global $request_type;
                        $result .= '<div ' . $headers_css_id . ' ' . $headers_class . '>';
                        $result .=     tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', $request_type, false), 'get');
                        $result .= '            <div class="input-group custom-search-form">';
                        $result .=     tep_draw_input_field('keywords', '', 'required placeholder="' . TEXT_SEARCH_PLACEHOLDER . '"');
                        $result .= '              <span class="input-group-btn">';
                        $result .= '              <button class="btn btn-default" type="submit">';
                        $result .= '              <i class="fa fa-search"></i>';
                        $result .= '             </button>';
                        $result .= '             </span>';
                        $result .= '             </div><!-- /input-group -->';
                        $result .=  tep_draw_hidden_field('search_in_description', '1') . tep_hide_session_id();
                        $result .= '</form>';			
                        $result .= '</div>';
                        
                    }
                    
                    if($category['type'] == 'formdefault'){
                        $result .= '<form id="search" class="navbar-form  ' . $headers_class . ' " role="search">';
                        $result .= '  <div class="form-group">';
                        $result .= '<input type="text" class="form-control" placeholder="' . $link_title . '">';
                        $result .= '  </div>';
                        $result .= '</form>';
                    }
                    
                    if($category['type'] == 'formsubmit'){
                        $result .= '<form class="navbar-form  ' . $headers_class . ' " role="search">';
                        $result .= '  <div class="form-group">';
                        $result .= '<input type="text" class="form-control" placeholder="' . $link_title . '">';
                        $result .= '  </div>';
                        $result .= '  <button type="submit" class="btn btn-default">Submit</button>';
                        $result .= '</form>';
                    } 
                    //For navbar CONTENT only End
                    
                    if($category['type'] == 'navtoggle'){
                        $result .= '<button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".' . $headers_class_extra . '">';
                        $result .= '<span class="sr-only">Toggle Navigation</span>';
                        $result .= '	<i class="fa fa-bars"></i>';
                        $result .= '</button>';	
                    }			
                    //We need to check dropdowncat content Start	
                    if($category['type'] == 'dropdowncat'){
                        
                        $result .= '<ul ' . $headers_css_id . ' class="nav navbar-nav  ' . $headers_class_extra . ' ">';
                        $result .= '    <li class="dropdown">';
                        $result .= '           <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $OSCOM_CategoryTree->getData($category['headers_url'], 'categories_name' ) . '<span class="caret"></span></a>';
                        $result .= '        <ul class="dropdown-menu" role="menu">';
                        $result .=  $OSCOM_CategoryTree->setRootCategoryID($category['headers_url']);
                        $result .=  $OSCOM_CategoryTree->getTree();
                        $result .= '        </ul>';
                        $result .= '    </li>';
                        $result .= '</ul>';   
                        
                    }
                    
                    //We need to check dropdown content Start	
                    if($category['type'] == 'dropdown'){
                        $result .= '        <li class="dropdown ' . $headers_class_extra . '">';
                        $result .= '          <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $link_title . '<span class="caret"></span></a>';
                        $result .= '<ul class="dropdown-menu" role="menu">';
                    }
                    
                    
                    
                    
                    if($category['type'] == 'nestedlink' && $level > 1){
                        if($category['pos'] == 'dropdown-submenu' && $level > 1){		  
                            $result .= '<li class=" ' . $headers_class . ' "><a href="#">' . $link_title . '</a><ul class="dropdown-menu">';
                            }else{
                            if($category['type'] == 'nestedlink' && $level > 1){          	
                                
                                $result .= '<li class=" ' . $headers_class . ' "><a href="#">' . $link_title . '</a></li>';
                            }
                        }
                    }
                    
                    
                    
                    
                    if($category['type'] == 'nestedtext' && $level > 1){          	
                        if($category['pos'] == 'dropdown-submenu' && $level > 1){		  
                            $result .= '<li class=" ' . $headers_class . ' "><a href="#">' . $link_title . '</a><ul class="dropdown-menu">';
                            }else{
                            if($category['type'] == 'nestedtext' && $level > 1){          	
                                
                                $result .= '<li class=" ' . $headers_class . ' "><a href="#">' . $link_title . '</a></li>';
                            }
                        }
                    }
                    
                    if ( isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1)) ) {
                        $result .= $this->_buildHeaderBranch($category_id, $level+1);
                    }
                    if($category['pos'] == 'dropdown-submenu' && $level > 1){		  
                        $result .= '</li></ul>';
                    }
                    //We need to check dropdown content End	
                    if($category['type'] == 'dropdown'){
                        
                        $result .= '          </ul>';
                        $result .= '        </li>';
                    }
                    //We need to close ul's End	
                    if(($category['type'] == 'ul')){
                        $result .= '			</ul>';		  
                        $result .= '		<!-- '.$category['type'].' Ends -->';
                    }	
                    //We need to close navbar's End	
                    if(($category['type'] == 'nav')){
                        $result .= '			</nav>';		  
                        $result .= '		<!-- '.$category['type'].' Ends -->';
                    }		  
                    
                    
                    //we need a closer
                    if(($category['type'] == 'div') || ($category['type'] == 'container') || ($category['type'] == 'row') || ($category['type'] == 'column')){
                        $result .= '			</div>';		  
                        $result .= '		<!-- '.$category['type'].' Ends -->';
                    }                                        
                    
                    if ( ( $level === 0 ) && isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1 ))) {
                        
                        
                    }
                    
                    
                }
            }
            
            $result .= '';
            
            return $result;
        }
        
        //End build header menu
        
        
        //Get the header tree menu
        public function getHeaderTree() {
            return $this->_buildHeaderBranch($this->root_category_id);
        }
        function setMenuRootCategoryID($root_category_id) {
            $this->root_category_id = $root_category_id;
        }
    }