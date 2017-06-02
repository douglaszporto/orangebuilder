<?php

spl_autoload_register(
	function ($className) {
		if(preg_match("~^OrangeBuild~i", $className) !== 1)
			return false;

		if(preg_match('~^OrangeBuild\\\\Controllers~i', $className) === 1){
			$className = str_replace("OrangeBuild\\Controllers", "", $className);
			$filename = dirname(__FILE__) . '/controllers/' . $className . '.php';
		} else {
			$className = str_replace("OrangeBuild\\", "", $className);
			$filename = dirname(__FILE__) . '/classes/' . $className . '.class.php';
		}

		if (file_exists($filename)) {
			require_once $filename; 
			return true; 
		} 
		return false; 
	}
);

?>