<?php
/**
 * osCommerce Online Merchant
 * 
 * @copyright Copyright (c) 2014 osCommerce; http://www.oscommerce.com
 * @license GNU General Public License; http://www.oscommerce.com/gpllicense.txt
 */

  class bts_css_tree {
    protected $_data = array();

    var $root_category_id = 0,
        $max_level = 0,
        $root_start_string = '',
        $root_end_string = '',
        $parent_start_string = '',
        $parent_end_string = '',
        $parent_group_start_string = '<div class="panel-group" id="accordion">',
        $parent_group_end_string = '</div>',
        $parent_group_apply_to_root = true,
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

      static $_bts_css_tree_data;

      if ( isset($_bts_css_tree_data) ) {
        $this->_data = $_bts_css_tree_data;
      } else {
        $categories_query = tep_db_query("select selectors_id, parent_id, selectors_name from " . TABLE_BTS_CSS_SELECTORS . " order by parent_id, sort_order, selectors_name");

        while ( $categories = tep_db_fetch_array($categories_query) ) {
          $this->_data[$categories['parent_id']][$categories['selectors_id']] = array('selectors_name' => $categories['selectors_name']);
        }

        $_bts_css_tree_data = $this->_data;
      }
    }

    protected function _buildBranch($parent_id, $level = 0) {
      $result = ((($level === 0) && ($this->parent_group_apply_to_root === true)) || ($level > 0)) ? $this->parent_group_start_string : null;

      if ( isset($this->_data[$parent_id]) ) {
        foreach ( $this->_data[$parent_id] as $category_id => $category ) {

          




            
          
		   $result .=  '<div class="panel panel-default">';
		   $result .=  '  <div class="panel-heading">';
           $result .=  '    <h2 class="panel-title">';
           $result .=  '      <a data-toggle="collapse" data-parent="#accordion" href="#collapse_'.$category_id.'">';
           $result .=           $category['selectors_name'];
           $result .=  '      </a>';
		   $result .=  tep_draw_form('delete_css', FILENAME_BTS_CSS, 'selID=' . $category_id . '&action=deleteconfirm', 'post', 'class="pull-right"') . tep_draw_button(IMAGE_DELETE, 'trash', null, 'primary') . '</form>';
           $result .=  '    </h2>';
           $result .=  '  </div>';
           $result .=  '  <div id="collapse_'.$category_id.'" class="panel-collapse collapse">';
           $result .=  '    <div class="panel-body">';
           $result .=  tep_draw_form('css', FILENAME_BTS_CSS, 'selID=' . $category_id . '&action=save', 'post', 'id="' . $category_id . '"');
           $result .=  TEXT_CSS_SELECTOR_NAME . ' ' . tep_draw_input_field('selectors_name', $category['selectors_name'],  'class="form-control"') . '<br />';

		   $result .= '<table class="table table-hover table-striped table-bordered input_new_edit_fields_wrap">';
           $result .= '<tr><th>'.TEXT_PROPERTY_ELEMENT.'</td><th>'.TEXT_PROPERTY_VALUE.'</th><th><button id="' . $category_id . '" class="add_new_edit_field_button"> + </button></th></tr>';
      
	        $properties = tep_get_properties($category_id);
      
	      for ($i=0, $n=sizeof($properties); $i<$n; $i++) {
           
		   $result .= '<tr id="' . $properties[$i]['properties_id'] . '">';
		   $result .= '  <td>' . tep_draw_input_field('property_element[' . $properties[$i]['properties_id'] . ']', tep_get_property_element($category_id, $properties[$i]['properties_id']),  'class="form-control"') . '</td>';
		   $result .= '  <td>' . tep_draw_input_field('property_value[' . $properties[$i]['properties_id'] . ']', tep_get_property_value($category_id, $properties[$i]['properties_id']),  'class="form-control"') . '</td>';
		   $result .= '  <td><a href="#" class="remove_new_edit_field">Remove</a></td>';
		   $result .= '</tr>';
           
		   }
      
		   $result .=  '    </table>';
		   $result .=  '<br />' . tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_BTS_CSS));
		   $result .=  '    </form>';
		   $result .=  '    </div>';
		   $result .=  '  </div>';
		   $result .=  '</div>';








          if ( isset($this->_data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1)) ) {

		   $result .= $this->_buildBranch($category_id, $level+1);
            
          }
          

			}
      }

		   $result .= ((($level === 0) && ($this->parent_group_apply_to_root === true)) || ($level > 0)) ? $this->parent_group_end_string : null;

      return $result;
    }

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
                            'title' => str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $category['selectors_name']);

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

    public function cssTree() {
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
      return $this->cssTree();
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
                          'selectors_name' => $info['selectors_name'],
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

