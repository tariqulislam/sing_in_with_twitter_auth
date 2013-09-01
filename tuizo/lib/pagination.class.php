<?php
class Pagination
{
    var $page;
    var $page_items;
    var $total_items;
    var $total_pages;
    var $limit;
    function __construct( $page_items = NULL )
    {
        global $config;
        
        $this->page         = ( isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
        $this->page         = ( $this->page < 1 ) ? $this->page = 1 : $this->page;
        $this->page_items   = ( isset($page_items) && is_numeric($page_items) ) ? $page_items : $config['items_per_page'];
        settype($this->page, 'integer');
        settype($this->page_items, 'integer');
    }
    
    function Pagination( $page_items = NULL )
    {
        $this->__construct($page_items);
    }
    
    function getLimit( $total_items )
    {
        $this->total_items = ( $total_items == 0 ) ? 1 : $total_items;
        $this->total_pages = ceil($this->total_items/$this->page_items);
        settype($this->total_pages, 'integer');    
        if( $this->page > $this->total_pages )
            $this->page = $this->total_pages;
        $this->limit        = $this->page_items;
        
        if ( $this->page >= 2 )
            $this->limit = ($this->page - 1) * $this->page_items. ', ' .$this->page_items;
        
        return $this->limit;
    }
    
    function getPagination( $url = NULL, $index = 3)
    {
        global $config;
        

        $url            = $this->stripPage();        
        $separator      = ( strstr($url, '?') ) ? '&' : '?';
        $output         = array();
        $prev_page      = ( $this->page > 1 ) ? $this->page - 1: 1;
        $next_page      = $this->page+1;
        
        if ( $this->page != 1 )
            $output[]   = '<a href="' .$url . $separator. 'page=' .$prev_page. '">&laquo;</a>';
        if ( $this->total_pages > (($index*2)+3) && $this->page >= ($index+3) ) {
            $output[]   = '<a href="' .$url . $separator. 'page=1">1</a>';
            $output[]   = '<a href="' .$url . $separator. 'page=2">2</a>';
        }
        if ( $this->page > $index+3 )
            $output[]   = '<span style="border:0; background: transparent; color: #888;">..</span>';
        for ( $i=1; $i<=$this->total_pages; $i++ ) {
            if ( $this->page == $i )
                    $output[] = '<span class="pagingnav">' .$this->page. '</span>';
            elseif ( ($i >= ($this->page-$index) && $i < $this->page) or ($i <= ($this->page+$index) && $i > $this->page) )
                    $output[]   = '<a href="' .$url . $separator. 'page=' .$i. '">' .$i. '</a>';
        }
        
        if ( $this->page < ($this->total_pages-6) )
            $output[]   = '<span style="border:0; background: transparent; color: #888;">..</span>';              
        if ( $this->total_pages > (($index*2)+3) && $this->page <= $this->total_pages-($index+3) ) {
            $output[]   = '<a href="' .$url . $separator. 'page=' .($this->total_pages-2). '">' .($this->total_pages-2). '</a>';
            $output[]   = '<a href="' .$url . $separator. 'page=' .($this->total_pages-1). '">' .($this->total_pages-1). '</a>';        
        }
        if ( $this->page != $this->total_pages )
            $output[]   = '<a href="' .$url . $separator. 'page=' .$next_page. '">&raquo;</a>';
        
        return implode('', $output);
    }
    
    function getPaginationSEO( $url, $index = 3 )
    {
        $output         = array();
        $prev_page      = ( $this->page > 1 ) ? $this->page - 1: 1;
        $next_page      = $this->page+1;

        if ( $this->page != 1 )
            $output[]   = '<a href="' .$this->setPage($url, $prev_page). '">&laquo;</a>';
        if ( $this->total_pages > (($index*2)+3) && $this->page >= ($index+3) ) {
            $output[]   = '<a href="' .$this->setPage($url, 1). '">1</a>';
            $output[]   = '<a href="' .$this->setPage($url, 2). '">2</a>';
        }
        if ( $this->page > $index+3 )
            $output[]   = '<span style="border:0; background: transparent; color: #888;">..</span>';        
        for ( $i=1; $i<=$this->total_pages; $i++ ) {
            if ( $this->page == $i )
                    $output[] = '<span class="pagingnav">' .$this->page. '</span>';
            elseif ( ($i >= ($this->page-$index) && $i < $this->page) or ($i <= ($this->page+$index) && $i > $this->page) )
                    $output[]   = '<a href="' .$this->setPage($url, $i). '">' .$i. '</a>';
        }
        
        if ( $this->page < ($this->total_pages-6) )
            $output[]   = '<span style="border:0; background:transparent; color: #888;">..</span>';              
        if ( $this->total_pages > (($index*2)+3) && $this->page <= $this->total_pages-($index+3) ) {
            $output[]   = '<a href="' .$this->setPage($url, $this->total_pages-2). '">' .($this->total_pages-2). '</a>';
            $output[]   = '<a href="' .$this->setPage($url, $this->total_pages-1). '">' .($this->total_pages-1). '</a>';        
        }
        if ( $this->page != $this->total_pages )
            $output[]   = '<a href="' .$this->setPage($url, $next_page). '">&raquo;</a>';
        
        return implode('', $output);        
    }
    
    function setPage( $url, $page )
    {
        return str_replace('{#PAGE#}', $page, $url);
    }
    
    function getStartItem()
    {
        $start_item = 1;
        if ( $this->page >= 2 )
            $start_item = (($this->page - 1) * $this->page_items)+1;
        if ( $start_item >= $this->total_items )
            $start_item = $this->total_items;
        
        return $start_item;
    }
    
    function getEndItem()
    {
        $end_item = $this->getStartItem();
        $end_item = ($end_item + $this->page_items)-1;
        if ( $end_item >= $this->total_items )
            $end_item = $this->total_items;
        
        return $end_item;
    }
    
	
	
	function ajaxPagination( $url = NULL, $location=NULL, $index = 3 )
	{
		global $config;
        $output         = array();
        $prev_page      = ( $this->page > 1 ) ? $this->page - 1: 1;
        $next_page      = $this->page+1;

       
            $output[]   = '<li><a href="javascript:void(0);" onClick="ajaxPagination(\''.$this->setPage($url, $prev_page).'\',\''.$location.'\');">&laquo;&nbsp;Previous</a></li>';
        if ( $this->total_pages > (($index*2)+3) && $this->page >= ($index+3) ) {
            $output[]   = '<li class="link-li"><a class="link-nav" href="javascript:void(0);"  onClick="ajaxPagination(\''.$this->setPage($url, 1).'\',\''.$location.'\');">1</a></li>';
            $output[]   = '<li class="link-li"><a class="link-nav" href="javascript:void(0);"  onClick="ajaxPagination(\''.$this->setPage($url, 2).'\',\''.$location.'\');">2</a></li>';
        }
        if ( $this->page > $index+3 )
            $output[]   = '<li class="link-li"><span style="position:relative; top:-8px; padding:0 2px;" >...</span></li>';        
        for ( $i=1; $i<=$this->total_pages; $i++ ){
            if ( $this->page == $i )
             
					 $output[]   = '<li class="link-li"><a class="link-nav current" href="javascript:void(0);"  onClick="ajaxPagination(\''.$this->setPage($url, $i).'\',\''.$location.'\');" >' .$i. '</a></li>';
					
            elseif ( ($i >= ($this->page-$index) && $i < $this->page) or ($i <= ($this->page+$index) && $i > $this->page) )
                    $output[]   = '<li class="link-li"><a class="link-nav" href="javascript:void(0);"  onClick="ajaxPagination(\''.$this->setPage($url, $i).'\',\''.$location.'\');" >' .$i. '</a></li>';
        }
        
        if ( $this->page < ($this->total_pages-6) )
            $output[]   = '<li class="link-li"><span style="position:relative; top:-8px; padding:0 2px;" >...</span></li>';              
        if ( $this->total_pages > (($index*2)+3) && $this->page <= $this->total_pages-($index+3) ) {
            $output[]   = '<li class="link-li"><a class="link-nav" href="javascript:void(0);"  onClick="ajaxPagination(\''.$this->setPage($url, $this->total_pages-2).'\',\''.$location.'\');" >' .($this->total_pages-2). '</a></li>';
            $output[]   = '<li class="link-li"><a class="link-nav" href="javascript:void(0);"  onClick="ajaxPagination(\''.$this->setPage($url, $this->total_pages-1).'\',\''.$location.'\');" >' .($this->total_pages-1). '</a></li>';        
        }

            $output[]   = '<li><a  href="javascript:void(0);"  onClick="ajaxPagination(\''.$this->setPage($url, $next_page).'\',\''.$location.'\');" >Next&nbsp;&raquo;</a></li>';

        return implode('', $output); 
	}
	
	
	
    function getAdminPagination( $remove=NULL, $url =NULL, $index =3 )
    {
        $url            = $this->stripPage();
        $url            = ( $remove ) ? str_replace($remove, '', $url) : $url;        
        $separator      = ( strstr($url, '?') ) ? '&' : '?';
        $output         = array();
        $prev_page      = ( $this->page > 1 ) ? $this->page - 1: 1;
        $next_page      = $this->page+1;
        
        $output[]   = '<span>Total Items: <b>' .$this->total_items. '</b> - Displaying page <b>' .$this->page. '</b> of <b>' .$this->total_pages. '</b>&nbsp;</span>';
        if ( $this->page != 1 )
            $output[]   = '<a href="' .$url . $separator. 'page=' .$prev_page. '">&laquo;</a>';
        if ( $this->total_pages > (($index*2)+3) && $this->page >= ($index+3) ) {
            $output[]   = '<a href="' .$url . $separator. 'page=1">1</a>';
            $output[]   = '<a href="' .$url . $separator. 'page=2">2</a>';
        }
        if ( $this->page > $index+3 )
            $output[]   = '<span>..</span>';        
        for ( $i=1; $i<=$this->total_pages; $i++ ) {
            if ( $this->page == $i )
                    $output[] = '<a href="' .$url . $separator. 'page=' .$this->page. '" class="active">' .$this->page. '</a>';
            elseif ( ($i >= ($this->page-$index) && $i < $this->page) or ($i <= ($this->page+$index) && $i > $this->page) )
                    $output[]   = '<a href="' .$url . $separator. 'page=' .$i. '">' .$i. '</a>';
        }
        
        if ( $this->page < ($this->total_pages-6) )
            $output[]   = '<span>..</span>';              
        if ( $this->total_pages > (($index*2)+3) && $this->page <= $this->total_pages-($index+3) ) {
            $output[]   = '<a href="' .$url . $separator. 'page=' .($this->total_pages-2). '">' .($this->total_pages-2). '</a>';
            $output[]   = '<a href="' .$url . $separator. 'page=' .($this->total_pages-1). '">' .($this->total_pages-1). '</a>';        
        }
        if ( $this->page != $this->total_pages )
            $output[]   = '<a href="' .$url . $separator. 'page=' .$next_page. '">&raquo;</a>';
        
        return implode('', $output);
    }
    
    function getPage()
    {
        return $this->page;
    }
    
    function getTotalPages()
    {
        return $this->total_pages;
    }
    
    function stripPage( $query_string = null )
    {
        global $config;    
    
        foreach ( $_GET as $key => $value ) {
            if ( $key != 'page' )
                $query_string .= '&' .$key. '=' .$value;
        }
        
        return $config['BASE_URL'].$_SERVER['SCRIPT_NAME']. ( $query_string ) ? '?' .substr($query_string, 1) : '';
    }
}
?>
