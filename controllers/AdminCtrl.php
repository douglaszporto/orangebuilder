<?php

namespace OrangeBuild\Controllers;

use \Exception      as Exception;
use OrangeBuild\DB  as DB;
use OrangeBuild\Env as Env;

Env::loadConfigShop();

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
			$error = new ErrorHandlerCtrl();
			$error->ErrorHandler($e->getMessage());
		}
	}

	public function Listing($ctrl){
		try{
			$this->getCtrlClass($ctrl);

			if(!method_exists($this->instance, "Listview"))
				throw new Exception("Não há listagem difinida para a rotina: ". $className);

			$this->instance->Listview();
		}catch(Exception $e){
			$error = new ErrorHandlerCtrl();
			$error->ErrorHandler($e->getMessage());
		}
	}

	public function FormAdd($ctrl){
		try{
			$this->getCtrlClass($ctrl);

			if(!method_exists($this->instance, "AddForm"))
				throw new Exception("Não há formulario de inclusão difinido para a rotina: ". $className);

			$this->instance->AddForm();
		}catch(Exception $e){
			$error = new ErrorHandlerCtrl();
			$error->ErrorHandler($e->getMessage());
		}
	}

	public function FormEdit($ctrl, $id){
		try{
			$this->getCtrlClass($ctrl);

			if(!method_exists($this->instance, "EditForm"))
				throw new Exception("Não há formulario de edição difinido para a rotina: ". $ctrl);

			$this->instance->EditForm($id);
		}catch(Exception $e){
			$error = new ErrorHandlerCtrl();
			$error->ErrorHandler($e->getMessage());
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
					return;
				}
			}

			if(!method_exists($this->instance, "Insert"))
				throw new Exception("Não há inclusão difinida para a rotina: ". $ctrl);

			$id = $this->instance->Insert();

			if(empty($id))
				throw new Exception("Ocorreu algum erro ao inserir na rotina: ". $ctrl);

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

	public function Edit($ctrl, $id){
		$db = DB::getInstance();

		try{
			$this->getCtrlClass($ctrl);
			$db->BeginTransaction();

			if(method_exists($this->instance, "BeforeUpdate")){
				$validations = $this->instance->BeforeUpdate($id);
				if(is_array($validations) || is_string($validations)){
					$this->instance->setErrors($validations);
					$this->instance->Listview();
					return;
				}
			}

			if(!method_exists($this->instance, "Update"))
				throw new Exception("Não há alteração difinida para a rotina: ". $ctrl);

			$id = $this->instance->Update($id);

			if(empty($id))
				throw new Exception("Ocorreu algum erro ao alterar o registro ".$id." na rotina: ". $ctrl);

			if(method_exists($this->instance, "AfterUpdate"))
				$this->instance->AfterUpdate($id);

			$db->CommitTransaction();
			$this->instance->setMessage("Parabéns, dados alterados com sucesso!");
			$this->instance->Listview(); 
		}catch(Exception $e){
			$db->RollbackTransaction();
			$this->instance->setErrors($e->getMessage());
			$this->instance->Listview(); 
		}
	}
}

?>