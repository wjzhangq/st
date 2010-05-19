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
	
	function header_404(){
		
	}
	
	function header_301(){
		
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