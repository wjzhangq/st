<?php
class response extends object {
	private $_var = array();
	
	static function getInstance(){
		return parent::getInstance(__CLASS__);
	}
	
	//肤质
	function assign($key, $val){
		$this->offsetSet($key, $val);
	}
	
	//掉用模板
	function render($tpl, $param=array()){
		
	}
	
	//not found
	function header_404(){
		
	}

	function header_403(){
		header('HTTP/1.1 403 Forbidden', true, 403);
	}
	
	/**
	 * function redirect
	 * Redirects the request to a new URL
	 * @param string $url The URL to redirect to, or omit to redirect to the current url
	 * @param boolean $continue Whether to continue processing the script (default false for security reasons, cf. #749)
	 */
	public static function redirect( $url = '', $continue = false )
	{
		if ( $url == '' ) {
			$url = Page::get_full_url();
		}
		header( 'Location: ' . $url, true, 302 );

		if ( ! $continue ) exit;
	}
	
	
	
	//access for ArrayAccess
	public function offsetExists ($offset) {
		return isset($this->_var[$offset]);
	}
    public function offsetGet ($offset) {
    	return $this->_var[$offset];
    }
    public function offsetSet ($offset, $value) {
    	$this->_var[$offset] = $value;
    }
    public function offsetUnset ($offset) {
    	unset($this->_var);
    }
    //access for count
    public function count(){
    	return count($this->_var);
    }
}
?>