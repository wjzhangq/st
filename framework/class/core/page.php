<?php
/* Page 定义， Page just get and post methed*/

class Page{
	static $page_list = array();
	
	function __construct(){
		$page_list[] = $this;
	}
	
	function display(){
		$response = response::getInstance();
		$method = uri::get_method();
		$this->$method($response);
	}
	
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
	
	static function dispatch($path=''){
		if ($path){
			//获取当前class
			$pair = split_by_type(path_trim($path));
			$class_name = str_replace('/', '_', $pair[0]);
			
			$response = response::getInstance();
			if (!class_exists($class_name)){
				$response->error_msg(sprintf('class "%s" can not found in %s', $class_name, $path));
			}else{
				self::$current = new $class_name();
				$method = uri::get_method();
				self::$current->$method($response);
			}
		}
		// $query = @uri::parse();
		// $response = response::getInstance();
		// if (!is_file($query['path'])){
		// 	$response->header_404($query['path'] . ' is not exist!');
		// }else{
		// 	require_once $query['path'];
		// 	if (!class_exists($query['class'])){
		// 		$response->error_msg(sprintf('class "%s" can not found in %s', $query['class'], $query['path']));
		// 	}else{
		// 		self::$current = new $query['class']();
		// 		$method = uri::get_method();
		// 		self::$current->$method($response);
		// 	}
		// }

	}
}

?>