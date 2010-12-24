<?php

require '../init.php';

class page_user_index extends page{
	function get($respone){
		echo 'pp';
	}
	
}

$page = new page_user_index();
$page->display();

?>