<?php
/* Page ���壬 Page just get and post methed*/

class Page{
	function get($response){
		$response->header_403(); //forbidden
	}

	function post($response){
		$response->header_403(); //forbidden
	}
	
	//����Ĭ��ҳ��
	protected function get_full_url(){
		//
		return '';
	}
}

?>