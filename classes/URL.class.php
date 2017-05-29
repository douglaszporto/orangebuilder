<?php

namespace OrangeBuild;

use \Exception as Exception;

class URL{

	private $url;
	private $map;

	public function __construct($url){
		$this->url = preg_replace('~[^a-zA-Z0-9\-\/\\\\]~i', "", $url);
		$this->url = trim($this->url, "\\\/ \t\n\r\0\x0B");
	}

	public function mapping($route, $method){
		$this->map[$route] = $method;
	}

	public function forward(){
		$found = false;
		foreach($this->map as $route=>$ctrl){
			$params = array();

			if(preg_match_all("~".$route."~i", $this->url, $params) !== 1)
				continue;

			try{
				list($class, $method) = explode('.', $ctrl);

				$filename = dirname(__FILE__) . "/../controllers/" . $class . ".php";
				if(!file_exists($filename))
					throw new Exception("Arquivo da classe mapeada não encontrada: ".$filename);

				require_once $filename;
				$fullClassName = "\\OrangeBuild\\Controllers\\". $class;
				$instance = new $fullClassName();
				
				if(!method_exists($instance, $method))
					throw new Exception("Methodo da classe mapeada não encontrado: ".$class.".".$method);

				call_user_func_array(array($instance, $method), $params[1]);
			}catch (Exception $e){
				echo $e->getMessage();
			}

			$found = true;
			break;
		}

		if(!$found){
			$ctrl = new \OrangeBuild\Controllers\ErrorHandlerCtrl();
			$ctrl->NotFound();
		}
	}
}


?>