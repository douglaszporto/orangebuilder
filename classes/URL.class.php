<?php

namespace OrangeBuild;

use \OrangeBuild\Controllers\ErrorHandlerCtrl as ErrorHandlerCtrl;
use \Exception as Exception;

class URL{

	private $url;
	private $map;

	public function __construct($url){
		$this->url = preg_replace('~[^a-zA-Z0-9\-\/\\\\]~i', "", $url);
		$this->url = trim($this->url, "\\\/ \t\n\r\0\x0B");

		//ini_set('display_errors', false);
		//register_shutdown_function([$this, 'fatal_handler']);
		set_error_handler([$this, 'error_handler']);
	}

	public function mapping($route, $method){
		$this->map[$route] = $method;
	}

	public function forward(){
		$found = false;
		foreach($this->map as $route=>$ctrl){
			$params = array();

			if(preg_match_all("~".$route."~i", $this->url, $params, PREG_SET_ORDER) !== 1)
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

				$parameters = $params[0];
				array_shift($parameters);

				call_user_func_array(array($instance, $method), $parameters);
			}catch (Exception $e){
				echo $e->getMessage();
			}

			$found = true;
			break;
		}

		if(!$found){
			$ctrl = new ErrorHandlerCtrl();
			$ctrl->NotFound();
		}
	}


	public function fatal_handler(){
		$error = error_get_last();

		if($error !== NULL) {
			$ctrl = new Controllers\ErrorHandlerCtrl();
			$ctrl->ErrorHandler(sprintf("Erro %s no arquivo %s (%s)<br/>%s", $error["type"], $error["file"], $error["line"], $error["message"]));
			exit;
		}
	}

	public function error_handler($errno, $errstr, $errfile, $errline){
		$ctrl = new Controllers\ErrorHandlerCtrl();
		$ctrl->ErrorHandler(sprintf("Erro %s no arquivo %s (%s)<br/>%s", $errno, $errfile, $errline, $errstr));
		exit;
	}
}

?>