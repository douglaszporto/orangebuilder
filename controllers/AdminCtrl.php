<?php

namespace OrangeBuild\Controllers;

use \Exception     as Exception;
use OrangeBuild\DB as DB;

require_once dirname(__FILE__) . "/../admin/config.php";

class AdminCtrl{

	private $instance = null;

	public function getCtrlClass($ctrl){
		try{
			$adminPath = dirname(__FILE__) . "/../admin";
			
			$config = json_decode(file_get_contents( $adminPath. "/config.json"), true);

			$className = isset($config["routines"][$ctrl]) ? $config["routines"][$ctrl] : "";
			$filename = $adminPath. "/controllers/". $className. ".php";

			if($className === "" || !file_exists($filename))
				throw new Exception("Arquivo não encontrado para admin: ". $filename);
			
			require_once $filename;

			$this->instance = new $className();

		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	public function Listing($ctrl){
		try{
			$this->getCtrlClass($ctrl);

			if(!method_exists($this->instance, "Listview"))
				throw new Exception("Não há listagem difinida para a rotina: ". $className);

			$this->instance->Listview();
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	public function Add($ctrl){
		$db = DB::getInstance();

		try{
			$this->getCtrlClass($ctrl);
			$db->BeginTransaction();

			if(method_exists($this->instance, "BeforeInsert")){
				$validations = $this->instance->BeforeInsert();
				if(is_array($validations) || is_string($validations)){
					$this->instance->setErrors($validations);
					$this->instance->Listview();
				}
			}

			if(!method_exists($this->instance, "Insert"))
				throw new Exception("Não há inclusão difinida para a rotina: ". $className);

			$id = $this->instance->Insert();

			if(empty($id))
				throw new Exception("Ocorreu algum erro ao inserir na rotina: ". $className);

			if(method_exists($this->instance, "AfterInsert"))
				$this->instance->AfterInsert();

			$db->CommitTransaction();
			$this->instance->setMessage("Parabéns, dados inseridos com sucesso!");
			$this->instance->Listview(); 
		}catch(Exception $e){
			$db->RollbackTransaction();
			$this->instance->setErrors($e->getMessage());
			$this->instance->Listview(); 
		}
	}
}

?>