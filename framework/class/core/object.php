<?php
//所有类的父类
class object implements ArrayAccess , Countable{
	private static $instances = array();
	
	static public function getInstance($id=""){
		if ($id == ""){
			throw new Exception("Please define getInstance in your class!");
		}
		return isset(self::$instances[$id]) ?  self::$instances[$id] : (self::$instances[$id] = new $id());
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
#define('__MAIN__', 1);
if (defined('__MAIN__')){
	class a extends object {
		static  function getInstance(){
			return parent::getInstance(__CLASS__);
		}
	}
	
	class b extends object {
		static  function getInstance(){
			return parent::getInstance(__CLASS__);
		}
	}
	
	var_dump(a::getInstance());
	var_dump(b::getInstance());
	var_dump(a::getInstance());
	
}

?>