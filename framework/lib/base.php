<?php

function path_trim($path){
	$relative = trim(str_replace(APP, '', $path), '/');
	
	return $relative;
}

function split_by_type($path){
	$len = strlen($path);
	$pos = strpos($path, '.', $len -5);
	if ($pos !== false){
		$ret = array(substr($path, 0, $pos), substr($path, $pos+1));
	}else{
		$ret = array($path, '');
	}
	
	return $ret;
}
?>