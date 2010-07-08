<?php
/* Page 定义， Page just get and post methed*/

class Page{
	static $current = null;
	
	function get($response){
		$response->header_403('function get not found'); //forbidden
	}

	function post($response){
		$response->header_403('function post not found'); //forbidden
	}
	
	//返回默认页面
	protected function get_full_url(){
		//
		return '';
	}
	
	static function dispatch(){
		$query = uri::parse();
		$response = response::getInstance();
		if (!is_file($query['path'])){
			$response->header_404($query['path']);
		}else{
			require_once $query['path'];
			if (!class_exists($query['class'])){
				$response->error_msg(sprintf('class "%s" can not found in %s', $query['class'], $query['path']));
			}else{
				self::$current = new $query['class']();
				$method = uri::get_method();
				self::$current->$method($response);
			}
		}

	}
}

?>