<?php

function __autoload($className) { 
    if(preg_match("/^OrangeBuild/i", $className) !== 1)
        return false;

    $className = str_replace("OrangeBuild\\", "", $className);
	$filename = dirname(__FILE__) . '/classes/' . $className . '.class.php';

	if (file_exists($filename)) { 
		require_once $filename; 
		return true; 
	} 
	return false; 
}

?>