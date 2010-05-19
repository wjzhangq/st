<?php
//所有类的父类
class object implements ArrayAccess , Countable{
	private static $instances = array();
	
	static public function getInstance(){
		var_dump(get_class());
		return isset(self::$instances[__CLASS__]) ?  self::$instances[__CLASS__] : (self::$instances[__CLASS__] = new self);
		//return is_null(self::$instance)? (self::$instance=new self):self::$instance;
	}
	
	//access for ArrayAccess
	public function offsetExists ($offset) {
		throw new splAccessException('method "offsetExists" is not defined!');
	}
    public function offsetGet ($offset) {
    	throw new splAccessException('method "offsetExists" is not defined!');
    }
    public function offsetSet ($offset, $value) {
    	throw new splAccessException('method "offsetExists" is not defined!');
    }
    public function offsetUnset ($offset) {
    	throw new splAccessException('method "offsetExists" is not defined!');
    }
    //access for count
    public function count(){
    	return 0;
    }
}

class splAccessException extends Exception {}

//测试代码
//define('__MAIN__', 1);
if (defined('__MAIN__')){
	class a extends object {
	}
	
	class b extends object {
		
	}
	
}

?>