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
      global $languages_id;

      static $_bts_menu_data;

      if ( isset($_bts_menu_data) ) {
        $this->_data = $_bts_menu_data;
      } else {

         $categories_query = tep_db_query("select c.headers_id, c.headers_type, c.headers_url, cd.headers_name, c.headers_class, c.headers_css_id, c.headers_fa_icon, c.parent_id, c.sort_order,  c.headers_status from " . TABLE_HEADERS . " c, " . TABLE_HEADERS_DESCRIPTION . " cd where c.headers_id = cd.headers_id and cd.language_id = '" . (int)$languages_id . "' and c.headers_status = 1 order by c.sort_order, cd.headers_name");

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
          
if($category['pos'] !== 'null'){
$pos = '';
}		  
		  
		 if ( ( $level === 0 ) && isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1 ))) {
          
		  //we need a opener
		  
		  } 

         $link_title = $category['name'];
		 

		 if($category['type'] == 'header-top'){
		  $result .= '			<div class="header-top">';
		  $result .= '				<div class="container">';
		  $result .= '					<!-- Header Links Starts -->';
		  $result .= '						<div class="col-xs-12 col-sm-8 col-md-12 col-lg-12">';
		  $result .= '							<div class="header-links">';  
		  }
		  
		 if($category['type'] == 'row'){
		  $result .= '		<!-- row Starts -->';
		  $result .= '			<div class="row">';		  
		  }
		 if($category['type'] == 'column'){
		  $result .= '		<!-- column Starts -->';
		  $result .= '			<div class="'.$link_title.'">';		  
		  }		  
		 if($category['type'] == 'container'){
		  $result .= '		<!-- Container Starts -->';
		  $result .= '			<div class="container">';		  
		  }
		 if($category['type'] == 'main-header'){
		  
		  $result .= '			<!-- Main Header Starts -->';
		  $result .= '				<div class="main-header">';
		  $result .= '					<div class="row">';
}
		 if( $category['type'] == 'main-menu'){
		  
		  $result .= '			<!-- Main Menu Starts -->';
		  $result .= '				<nav id="main-menu" class="navbar gradient-yellow" role="navigation">';
		  $result .= '				<!-- Nav Header Starts -->';
		  $result .= '					<div class="navbar-header">';
		  $result .= '						<button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-cat-collapse">';
		  $result .= '							<span class="sr-only">Toggle Navigation</span>';
		  $result .= '							<i class="fa fa-bars"></i>';
		  $result .= '						</button>';
		  $result .= '					</div>';
		  $result .= '				<!-- Nav Header Ends -->';
		  $result .= '				<!-- Navbar Cat collapse Starts -->';
		  $result .= '					<div class="collapse navbar-collapse navbar-cat-collapse">';		 
		 		  
}
		 if($category['type'] == 'Logo'){
		  $result .= '					<!-- Logo Starts -->';
		  $result .= '							<div id="logo" class="hidden-xs">';
		  $result .= '								<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME, '', '', 'class="img-responsive"') . '</a>';
		  $result .= '							</div>';
		  $result .= '					<!-- Logo Ends -->';

		  }
/*
         //For navbar only Start 
		 if($category['type'] == 'Logo'){
		    $result .= '<div class="navbar-header">';
			
            $result .= '<a class="navbar-brand" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME, '', '', 'class="img-responsive"') . '</a>';
		    $result .= '</div>';
		  }		 
		 if($category['type'] == 'Brand'){
		    $result .= '<div class="navbar-header">';
            $result .= '<a class="navbar-brand" href="#">' . $link_title . '</a>';
		    $result .= '</div>';
		  }
		 if($category['type'] == 'Parent'){
		    $result .= '';
		  }		  
		  //For navbar only End
		  */
         //For navbar CONTENT only Start
		 if($category['type'] == 'Link'){
		    $result .= '<p class="' . $pos . ' "><a href="' . $category['headers_url'] . '">' . $link_title . '</a></p>';
			}
		 if($category['type'] == 'SingleCat'){
		    $result .= '<p class="' . $pos . ' ">';
			$result .=  '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $OSCOM_CategoryTree->getData($category['headers_url'], 'id' )) . '">';
			$result .= $OSCOM_CategoryTree->getData($category['headers_url'], 'categories_name' );
			$result .=  '</a>';
			$result .=  '</p>';
			}         
		 if($category['type'] == 'Text'){
		    $result .= '<p class="' . $pos . ' ">' . $link_title . '</p>';
			}
         
		 if($category['type'] == 'Button'){
		    $result .= '<button type="button" class="btn btn-info navbar-btn  ' . $pos . ' ">' . $link_title . '</button>';
			}
 		 if($category['type'] == 'Cart'){

		    $result .= '<div class="text-right" style="margin: 0px 20px;">';
		    $result .= '    <a href="shopping_cart.php">';
		    $result .= '	 <img src="images/header_cart.png" alt="" class="img-responsive hidden-xs" style="float:left;" height="50" width="44"></a>';
		    $result .= '        <p class="text-left" style="white-space: nowrap;"><strong>Cart Contents</strong></p>';
		    $result .= '        <p class="text-left" style="white-space: nowrap;">0 item(s) totalling $0.00</p>';
		    $result .= '</div>';
			}

		if($category['type'] == 'FormSearch'){
		    $result .= '<div id="search" class="pull-left">';
		    $result .= '	<div class="row">';
		    $result .= '		<div class="col-lg-12">';
		    $result .= '            <div class="input-group custom-search-form">';
		    $result .= '              <input type="text" class="form-control" placeholder="Search">';
		    $result .= '              <span class="input-group-btn">';
		    $result .= '              <button class="btn btn-default" type="button">';
		    $result .= '              <i class="fa fa-search"></i>';
		    $result .= '             </button>';
		    $result .= '             </span>';
		    $result .= '             </div><!-- /input-group -->';
		    $result .= '        </div>';
		    $result .= '	</div>';
		    $result .= '</div>';
	 
		 }

		 if($category['type'] == 'FormDefault'){
		    $result .= '<form id="search" class="navbar-form  ' . $pos . ' " role="search">';
		    $result .= '  <div class="form-group">';
		    $result .= '<input type="text" class="form-control" placeholder="' . $link_title . '">';
		    $result .= '  </div>';
		    $result .= '</form>';
			}
			
		 if($category['type'] == 'FormSubmit'){
		    $result .= '<form class="navbar-form  ' . $pos . ' " role="search">';
		    $result .= '  <div class="form-group">';
		    $result .= '<input type="text" class="form-control" placeholder="' . $link_title . '">';
		    $result .= '  </div>';
		    $result .= '  <button type="submit" class="btn btn-default">Submit</button>';
		    $result .= '</form>';
			} 
         //For navbar CONTENT only End
		
		//We need to check dropdown content Start	
		 if($category['type'] == 'DropdownCat'){

		    $result .= '<ul class="nav navbar-nav  ' . $pos . ' ">';
		    $result .= '        <li class="dropdown">';
		    $result .= '          <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">' . $OSCOM_CategoryTree->getData($category['headers_url'], 'categories_name' ) . '</a>';
		    $result .= '<ul class="dropdown-menu" role="menu">';
			$result .=  $OSCOM_CategoryTree->setRootCategoryID($category['headers_url']);
			$result .=  $OSCOM_CategoryTree->getTree();
			$result .= '          </ul>';
		    $result .= '        </li>';
 		    $result .= '     </ul>';		    
			
			}
			
		//We need to check dropdown content Start	
		 if($category['type'] == 'Dropdown'){
		    $result .= '<ul class="nav navbar-nav  ' . $pos . ' ">';
		    $result .= '        <li class="dropdown">';
		    $result .= '          <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $link_title . '<span class="caret"></span></a>';
		    $result .= '<ul class="dropdown-menu" role="menu">';
		    }



			
		  if($category['type'] == 'nestedlink' && $level > 1){
          if($category['pos'] == 'dropdown-submenu' && $level > 1){		  
          $result .= '<li class=" ' . $pos . ' "><a href="#">' . $link_title . '</a><ul class="dropdown-menu">';
          }else{
		  if($category['type'] == 'nestedlink' && $level > 1){          	
		  
          $result .= '<li class=" ' . $pos . ' "><a href="#">' . $link_title . '</a></li>';
          }
		  }
		  }
		  
		  
		  
		  
          if($category['type'] == 'nestedtext' && $level > 1){          	
          if($category['pos'] == 'dropdown-submenu' && $level > 1){		  
          $result .= '<li class=" ' . $pos . ' "><a href="#">' . $link_title . '</a><ul class="dropdown-menu">';
          }else{
		  if($category['type'] == 'nestedtext' && $level > 1){          	
		  
          $result .= '<li class=" ' . $pos . ' "><a href="#">' . $link_title . '</a></li>';
          }
		  }
		  }
			
          if ( isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1)) ) {
          $result .= $this->_buildHeaderBranch($category_id, $level+1);
          }
          if($category['pos'] == 'dropdown-submenu' && $level > 1){		  
          $result .= '</li></ul>';
          }

		 if($category['type'] == 'Dropdown'){
		    
			$result .= '          </ul>';
		    $result .= '        </li>';
 		    $result .= '     </ul>';
			}
		//We need to check dropdown content End	
		
		 if($category['type'] == 'main-menu'){
		    $result .= '					</div>';		 
		    $result .= '				<!-- Navbar Cat collapse Ends -->';
		    $result .= '				</nav>';
		    $result .= '			<!-- Main Menu Ends -->';
}		
		 if($category['type'] == 'main-header'){
		  
		    $result .= '				</div>';
		    $result .= '					</div>';
		    $result .= '			<!-- Main Header Ends -->';
		  }
         if($category['type'] == 'Container'){
		    $result .= '			</div>';		  
		    $result .= '		<!-- Container Ends -->';
		  }
         if($category['type'] == 'Col'){
		    $result .= '			</div>';		  
		    $result .= '		<!-- Column Ends -->';
		  }			  
         if($category['type'] == 'Row'){
		    $result .= '			</div>';		  
		    $result .= '		<!-- Row Ends -->';
		  }		  
		 if($category['type'] == 'header-top'){
		    $result .= '							</div>';
		    $result .= '						</div>';
		    $result .= '						<!-- Header Links Ends -->';
		    $result .= '						</div>';
		    $result .= '					</div>';
		  }
		  
		  if ( ( $level === 0 ) && isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1 ))) {
           
		   //we need a closer
		  
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

  }

