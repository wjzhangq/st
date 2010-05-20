<?php
/* Page 定义， Page just get and post methed*/

class Page{
	function get($response){
		$response->header_403(); //forbidden
	}

	function post($response){
		$response->header_403(); //forbidden
	}
	
	//返回默认页面
	protected function get_full_url(){
		//
		return '';
	}
}

?>