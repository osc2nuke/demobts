<?php
/**
 * osCommerce Online Merchant
 * 
 * @copyright Copyright (c) 2014 osCommerce; http://www.oscommerce.com
 * @license GNU General Public License; http://www.oscommerce.com/gpllicense.txt
 */

  class category_tree {
    protected $_data = array();

    var $root_category_id = 0,
        $max_level = 0,
        $root_start_string = '',
        $root_end_string = '',
        $parent_start_string = '',
        $parent_end_string = '',
        $parent_group_start_string = '',
        $parent_group_end_string = '',
        $parent_group_apply_to_root = false,
        $child_start_string = '<li>',
        $child_end_string = '</li>',
        $breadcrumb_separator = '_',
        $breadcrumb_usage = true,
        $spacer_string = '',
        $spacer_multiplier = 1,
        $follow_cpath = false,
        $cpath_array = array(),
        $cpath_start_string = '',
        $cpath_end_string = '';

    public function __construct() {
      global $languages_id;

      static $_category_tree_data;

      if ( isset($_category_tree_data) ) {
        $this->_data = $_category_tree_data;
      } else {

         $categories_query = tep_db_query("select c.headers_id, c.headers_type, c.headers_url, cd.headers_name, c.headers_class, c.headers_css_id, c.headers_fa_icon, c.parent_id, c.sort_order,  c.headers_status from " . TABLE_HEADERS . " c, " . TABLE_HEADERS_DESCRIPTION . " cd where c.headers_id = cd.headers_id and cd.language_id = '" . (int)$languages_id . "' and (c.headers_status = 1 || c.headers_status = 0) order by c.sort_order, cd.headers_name");

        while ( $categories = tep_db_fetch_array($categories_query) ) {
          $this->_data[$categories['parent_id']][$categories['headers_id']] = array('type' => $categories['headers_type'],
		                                                                            'headers_url' => $categories['headers_url'],
																					'name' => $categories['headers_name'],
																					'headers_class' => $categories['headers_class'],
																					'headers_css_id' => $categories['headers_css_id'],
																					'headers_fa_icon' => $categories['headers_fa_icon'],
																					'sort_order' => $categories['sort_order'],
																					'headers_status' => $categories['headers_status']);
        }

        $_category_tree_data = $this->_data;
      }
    }

    protected function _buildBranch($parent_id, $level = 0) {
    $result = ((($level === 0) && ($this->parent_group_apply_to_root === true)) || ($level > 0)) ? $this->parent_group_start_string : null;

	//$OSCOM_CategoryTree = new category_tree();

	if ( isset($this->_data[$parent_id]) ) {
        foreach ( $this->_data[$parent_id] as $category_id => $category ) {
            $category_link = $category_id;
          
		  
		  
		 if ( ( $level === 0 ) && isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1 ))) {
          
		  //we need a opener
		  
		  } 
 $data_string = 'data-headers-name="'.strip_tags($category['name']).'" data-class="'.$category['headers_class'].'" data-css-id="'.$category['headers_css_id'].'" data-url="'.$category['headers_url'].'" data-fa-icon="'.$category['headers_fa_icon'].'" data-sort-order="'.$category['sort_order'].'" data-element-id="'.$category_id.'" data-type="'.$category['type'].'" id="'.$category_id.'"'; 

         $link_title = $category['name'];
		 $headers_status = null;
		 if($category['headers_status'] == '1'){
		 $headers_status = '<span class="action fa fa-eye" data-action="status_disable"></span>';
		 }
		 if($category['headers_status'] == '0'){
		 $headers_status = '<span class="action fa fa-eye-slash" data-action="status_enable"></span>';
		 }
         //elements
		 if($category['type'] == 'div'){
		  $result .= '		<!-- div Starts -->';
		  $result .= '			<div class="col-md-12 breadcrumb alert alert-success dragMe" '.$data_string.' draggable="true">';		  
		  $result .= '<span class="label label-success">'.$category['type'].'</span>';
		  $result .= $headers_status; 		  
		  $result .= '<span class="action glyphicon glyphicon-pencil" data-action="edit_header"></span>'; 
		  }		  
		 if($category['type'] == 'row'){
		  $result .= '		<!-- row Starts -->';
		  $result .= '			<div class="row alert alert-info dragMe" '.$data_string.' draggable="true">';
		  $result .= '<span class="label label-primary">'.$category['type'].'</span>';		  
		  $result .= $headers_status; 		  
		  $result .= '<span class="action glyphicon glyphicon-pencil" data-action="edit_header"></span>';
		  }
		 if($category['type'] == 'column'){
		  $result .= '		<!-- column Starts -->';
		  $result .= '			<div class="dragMe breadcrumb '.$category['headers_class'].'" '.$data_string.' draggable="true">';
		  $result .= '<span class="label label-info">'.$category['type'].'</span>';		  
		  $result .= $headers_status; 		  
		  $result .= '<span class="action glyphicon glyphicon-pencil" data-action="edit_header"></span>';
		  }		  
		 if($category['type'] == 'container'){
		  $result .= '		<!-- Container Starts -->';
		  $result .= '			<div class="col-md-12 breadcrumb alert alert-warning dragMe" '.$data_string.' draggable="true">';		  
		  $result .= '<span class="label label-warning">'.$category['type'].'</span>';		  
		  $result .= $headers_status; 		  
		  $result .= '<span class="action glyphicon glyphicon-pencil" data-action="edit_header"></span>';
		  }
		 if($category['type'] == 'nav'){
		  $result .= '		<!-- nav Starts -->';
		  $result .= '			<div class="col-md-12 breadcrumb alert alert-default dragMe" '.$data_string.' draggable="true">';		  
		  $result .= $headers_status; 		  
		  $result .= '<span class="label label-default">'.$category['type'].'</span>';		  
		  $result .= '<span class="action glyphicon glyphicon-pencil" data-action="edit_header"></span>';
		  }		  
		 if($category['type'] == 'ul'){
		  $result .= '		<!-- Ul Starts -->';
		  $result .= '			<div class="col-md-12 breadcrumb alert alert-danger dragMe" '.$data_string.' draggable="true">';		  
		  $result .= $headers_status; 		  
		  $result .= '<span class="label label-danger">'.$category['type'].'</span>';		  
		  $result .= '<span class="action glyphicon glyphicon-pencil" data-action="edit_header"></span>';
		  }
		  
         //Advanced Items
		 if($category['type'] == 'dropdown'){
		  $result .= '		<!-- dropdown Starts -->';
		  $result .= '			<div class="col-md-12 breadcrumb alert alert-dropdown dragMe" '.$data_string.' draggable="true">';		  
		  $result .= '<span class="label label-dropdown">'.$category['type'].'</span>';
		  $result .= $headers_status; 		  
		  $result .= '<span class="action glyphicon glyphicon-pencil" data-action="edit_header"></span>'; 
		  }	
		  
         //Items
		 if($category['type'] == 'text'){
		  $result .= '		<!-- text Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-text-width" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- text Ends -->';
          }
		 if($category['type'] == 'link'){
		  $result .= '		<!-- link Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-link" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- link Ends -->';
		  }
		  if($category['type'] == 'form'){
		  $result .= '		<!-- form Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-tasks" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- form Ends -->';
		  }
		  if($category['type'] == 'formsearch'){
		  $result .= '		<!-- formsearch Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-search" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- formsearch Ends -->';
		  }		  
		  if($category['type'] == 'dropdowncat'){
		  $result .= '		<!-- dropdown Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-download" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- dropdown Ends -->';
		  }
		  if($category['type'] == 'navtoggle'){
		  $result .= '		<!-- navtoggle Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-bars" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- navtoggle Ends -->';
		  }		  
		 if($category['type'] == 'logo'){
		  $result .= '		<!-- logo Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-photo" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- logo Ends -->';
		  }		  
		 if($category['type'] == 'cart'){
		  $result .= '		<!-- cart Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-shopping-cart" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- cart Ends -->';
		  }			  
//button sample start
		 if($category['type'] == 'button'){
		  $result .= '		<!-- button Starts -->';
		  $result .= '			<div class="col-xs-1 dragMe" '.$data_string.' draggable="true"><i class="action pover fa fa-bicycle" data-action="edit_header"></i></div>';		  
		  $result .= '		<!-- button Ends -->';
		  }	

//button sample end		  

			
          if ( isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1)) ) {
          $result .= $this->_buildBranch($category_id, $level+1);
          }

		
         if(($category['type'] == 'ul')){
		    $result .= '			</div>';		  
		    $result .= '		<!-- '.$category['type'].' Ends -->';
		  }		

         if(($category['type'] == 'div') || ($category['type'] == 'container') || ($category['type'] == 'row') || ($category['type'] == 'column') || ($category['type'] == 'nav') || ($category['type'] == 'dropdown')){
		    $result .= '			</div>';		  
		    $result .= '		<!-- '.$category['type'].' Ends -->';
		  }
	  

		  
		  if ( ( $level === 0 ) && isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1 ))) {
           
		   //we need a closer
		  
		  }
		  
		  
        }
      }

      $result .= ((($level === 0) && ($this->parent_group_apply_to_root === true)) || ($level > 0)) ? $this->parent_group_end_string : null;

      return $result;
    }

//End build header menu

    function buildBranchArray($parent_id, $level = 0, $result = '') {
      if (empty($result)) {
        $result = array();
      }

      if (isset($this->_data[$parent_id])) {
        foreach ($this->_data[$parent_id] as $category_id => $category) {
          if ($this->breadcrumb_usage == true) {
            $category_link = $this->buildBreadcrumb($category_id);
          } else {
            $category_link = $category_id;
          }

          $result[] = array('id' => $category_link,
                            'title' => str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $category['name']);

          if (isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
            if ($this->follow_cpath === true) {
              if (in_array($category_id, $this->cpath_array)) {
                $result = $this->buildBranchArray($category_id, $level+1, $result);
              }
            } else {
              $result = $this->buildBranchArray($category_id, $level+1, $result);
            }
          }
        }
      }

      return $result;
    }

    function buildBreadcrumb($category_id, $level = 0) {
      $breadcrumb = '';

      foreach ($this->_data as $parent => $categories) {
        foreach ($categories as $id => $info) {
          if ($id == $category_id) {
            if ($level < 1) {
              $breadcrumb = $id;
            } else {
              $breadcrumb = $id . $this->breadcrumb_separator . $breadcrumb;
            }

            if ($parent != $this->root_category_id) {
              $breadcrumb = $this->buildBreadcrumb($parent, $level+1) . $breadcrumb;
            }
          }
        }
      }

      return $breadcrumb;
    }

/**
 * Return a formated string representation of the category structure relationship data
 *
 * @access public
 * @return string
 */

    public function getTree() {
      return $this->_buildBranch($this->root_category_id);
    }

/**
 * Magic function; return a formated string representation of the category structure relationship data
 *
 * This is used when echoing the class object, eg:
 *
 * echo $osC_CategoryTree;
 *
 * @access public
 * @return string
 */

    public function __toString() {
      return $this->getTree();
    }

    function getArray($parent_id = '') {
      return $this->buildBranchArray((empty($parent_id) ? $this->root_category_id : $parent_id));
    }

    function exists($id) {
      foreach ($this->_data as $parent => $categories) {
        foreach ($categories as $category_id => $info) {
          if ($id == $category_id) {
            return true;
          }
        }
      }

      return false;
    }

    function getChildren($category_id, &$array = array()) {
      foreach ($this->_data as $parent => $categories) {
        if ($parent == $category_id) {
          foreach ($categories as $id => $info) {
            $array[] = $id;
            $this->getChildren($id, $array);
          }
        }
      }

      return $array;
    }

/**
 * Return category information
 *
 * @param int $id The category ID to return information of
 * @param string $key The key information to return (since v3.0.2)
 * @return mixed
 * @since v3.0.0
 */

    public function getData($id, $key = null) {
      foreach ( $this->_data as $parent => $categories ) {
        foreach ( $categories as $category_id => $info ) {
          if ( $id == $category_id ) {
            $data = array('id' => $id,
                          'name' => $info['name'],
                          'parent_id' => $parent,
                          'image' => $info['image']);

            return ( isset($key) ? $data[$key] : $data );
          }
        }
      }

      return false;
    }

/**
 * Return the parent ID of a category
 *
 * @param int $id The category ID to return the parent ID of
 * @return int
 * @since v3.0.2
 */

    public function getParentID($id) {
      return $this->getData($id, 'parent_id');
    }

    function setRootCategoryID($root_category_id) {
      $this->root_category_id = $root_category_id;
    }

    function setMaximumLevel($max_level) {
      $this->max_level = $max_level;
    }

    function setRootString($root_start_string, $root_end_string) {
      $this->root_start_string = $root_start_string;
      $this->root_end_string = $root_end_string;
    }

    function setParentString($parent_start_string, $parent_end_string) {
      $this->parent_start_string = $parent_start_string;
      $this->parent_end_string = $parent_end_string;
    }

    function setParentGroupString($parent_group_start_string, $parent_group_end_string, $apply_to_root = false) {
      $this->parent_group_start_string = $parent_group_start_string;
      $this->parent_group_end_string = $parent_group_end_string;
      $this->parent_group_apply_to_root = $apply_to_root;
    }

    function setChildString($child_start_string, $child_end_string) {
      $this->child_start_string = $child_start_string;
      $this->child_end_string = $child_end_string;
    }

    function setBreadcrumbSeparator($breadcrumb_separator) {
      $this->breadcrumb_separator = $breadcrumb_separator;
    }

    function setBreadcrumbUsage($breadcrumb_usage) {
      if ($breadcrumb_usage === true) {
        $this->breadcrumb_usage = true;
      } else {
        $this->breadcrumb_usage = false;
      }
    }

    function setSpacerString($spacer_string, $spacer_multiplier = 2) {
      $this->spacer_string = $spacer_string;
      $this->spacer_multiplier = $spacer_multiplier;
    }

    function setCategoryPath($cpath, $cpath_start_string = '', $cpath_end_string = '') {
      $this->follow_cpath = true;
      $this->cpath_array = explode($this->breadcrumb_separator, $cpath);
      $this->cpath_start_string = $cpath_start_string;
      $this->cpath_end_string = $cpath_end_string;
    }

    function setFollowCategoryPath($follow_cpath) {
      if ($follow_cpath === true) {
        $this->follow_cpath = true;
      } else {
        $this->follow_cpath = false;
      }
    }

    function setCategoryPathString($cpath_start_string, $cpath_end_string) {
      $this->cpath_start_string = $cpath_start_string;
      $this->cpath_end_string = $cpath_end_string;
    }
  }

