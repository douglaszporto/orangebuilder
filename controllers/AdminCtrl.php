<?php

namespace OrangeBuild\Controllers;

use \Exception as Exception;

class AdminCtrl{

	public function Listing($ctrl){
		try{
			$adminPath = dirname(__FILE__) . "/../admin";
			
			$config = json_decode(file_get_contents( $adminPath. "/config.json"), true);

			$className = isset($config["routines"][$ctrl]) ? $config["routines"][$ctrl] : "";
			$filename = $adminPath. "/controllers/". $className. ".php";

			if($className === "" || !file_exists($filename))
				throw new Exception("Arquivo não encontrado para admin: ". $filename);
			
			require_once $filename;

			$instance = new $className();

			if(!method_exists($instance, "Listview"))
				throw new Exception("Não há listagem difinida para a rotina: ". $className);

			$instance->Listview();

		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
}

?>