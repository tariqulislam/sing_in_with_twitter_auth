<?php
class Category2{
	/* database connection*/
	private $con = null;
	/* category array is creating when object is created */
	var $categories = array();
	/* input type either checkbox or radio default checkbox*/
	var $inputType = 'checkbox';
	/*already selected values */
	var $checked =array();

	function __construct($con)
	{
		$this->con=$con;
		$query ="SELECT category_id,category_name,category_parent_id FROM categories";
		$ulLiStringesult =mysqli_query($this->con,$query);
		$data = array();
		while($ulLiStringow = mysqli_fetch_assoc($ulLiStringesult))
		{
			$data[]= $ulLiStringow;
		}
		$this->categories = $data;
	}
	/*
	*Create category array to tree array 
	*@return array(); 
	*/
	function getTreeArray($src_arr, $parent_id = 0, $tree = array())
	{
	    foreach($src_arr as $categorydx => $ulLiStringow)
	    {
	            foreach($ulLiStringow as $k => $v)
	            $tree[$ulLiStringow['category_id']][$k] = $v;
	    }
	    ksort($tree);
        return $tree;
	
	}
	/*
	*Create <ul> <li> from Tree Array
	*Expected paramiter $categoryArray(array), $parent_id(parent_category_id)
	*@return string;
	*
	*/
    function getUlLiFromTreeArray( $categoryArray, $parent_id) {
			$inputString = '' ;
			   foreach ( $categoryArray as $category ) {
			       if ($category['category_parent_id'] == $parent_id ) {

			          $inputString .= '<li title="'.$category['category_name'].'">';
			          if (in_array($category['category_id'], $this->checked)) {
						$inputString .= '<input  checked="checked"   type="'.$this->inputType.'" name="categories[]" value="'.$category['category_id'] .'" /><a></a>'. $category['category_name'] ;
			          
			          }else{
			          	$inputString .= '<input  type="'.$this->inputType.'" name="categories[]" value="'.$category['category_id'] .'" /><a></a>'. $category['category_name'] ;
			          }
			          $inputString .= '<ul>';
			           $inputString .= $this->getUlLiFromTreeArray( $categoryArray, $category['category_id'] );
			           $inputString .= '</ul></li>';
			       }
			   }                                                                         
			   return ($inputString==''?'':"". $inputString  );
    }
 
	/*
	*Create checkbox or radio box to category operations  
	*Expected $parent_id(parent_category_id)
	*@return string;
	*/
	function viewTree($parent_id=0)
	{
		
           return $this->getInputBoxFromTreeArray($this->getTreeArray($this->categories),0);
	}


	/*======================Start beckend function for sq-group=========================*/
	
	/*
	*Create checkbox or radio box from Tree Array
	*Expected paramiter $categoryArray(array), $parent_id(parent_category_id)
	*@return string;
	*
	*/

    function getInputBoxFromTreeArray( $categoryArray, $parent_id) {
			   $inputString = '' ;
			   foreach ( $categoryArray as $category ) {
			       if ($category['category_parent_id'] == $parent_id ) {

			          $inputString .= '<li title="'.$category['category_name'].'">';
			          if (in_array($category['category_id'], $this->checked)) {
						$inputString .= '<input  checked="checked"   type="'.$this->inputType.'" name="categories[]" value="'.$category['category_id'] .'" /><a></a>'. $category['category_name'] ;
			          
			          }else{
			          	$inputString .= '<input  type="'.$this->inputType.'" name="categories[]" value="'.$category['category_id'] .'" /><a></a>'. $category['category_name'] ;
			          }
			          $inputString .= '<ul>';
			           $inputString .= $this->getUlLiFromTreeArray( $categoryArray, $category['category_id'] );
			           $inputString .= '</ul></li>';
			       }
			   }                                                                         
			   return ($inputString==''?'':"". $inputString  );
    }
	/*======================End beckend function for sq-group=========================*/

   
}

?>