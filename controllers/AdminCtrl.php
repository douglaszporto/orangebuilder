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

	public function Operations($ctrl, $operation){
		if($operation === 'incluir'){
			$this->Add($ctrl);
			return;
		}

		if($operation === 'editar'){
			$this->Edit($ctrl);
			return;
		}

		if($operation === 'remover'){
			$this->Delete($ctrl);
			return;
		}

		try{
			$this->getCtrlClass($ctrl);

			if(!method_exists($this->instance, "callMethodByOperation"))
				throw new Exception("Não há operações definidas para a rotina: ". $className);

			$this->instance->callMethodByOperation($operation);
		}catch(Exception $e){
			$error = new ErrorHandlerCtrl();
			$error->ErrorHandler($e->getMessage());
		}
	}

	public function Forms($ctrl, $id = null){
		return empty($id) ? $this->FormAdd($ctrl) : $this->FormEdit($ctrl, $id);
	}

	public function Listing($ctrl){
		try{
			$this->getCtrlClass($ctrl);

			if(!method_exists($this->instance, "Listview"))
				throw new Exception("Não há listagem definida para a rotina: ". $className);

			$filter   = $_POST["filter"] ?? $_GET["filter"] ?? null;
			$orderBy  = $_POST["orderby"] ?? $_GET["orderby"] ?? null;
			$orderDir = $_POST["orderdir"] ?? $_GET["orderdir"] ?? null;
			$bypage   = $_POST["bypage"] ?? $_GET["bypage"] ?? 10;
			$page     = $_POST["page"] ?? $_GET["page"] ?? 1;

			$this->instance->setFilter($filter);
			$this->instance->setOrderBy($orderBy);
			$this->instance->setOrderDir($orderDir);
			$this->instance->setByPage($bypage);
			$this->instance->setPage($page);
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
				throw new Exception("Não há formulario de inclusão definido para a rotina: ". $className);

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
				throw new Exception("Não há formulario de edição definido para a rotina: ". $ctrl);

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
				throw new Exception("Não há inclusão definida para a rotina: ". $ctrl);

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

	public function Edit($ctrl){
		$db = DB::getInstance();

		try{
			$this->getCtrlClass($ctrl);
			$db->BeginTransaction();

			if(method_exists($this->instance, "BeforeUpdate")){
				$validations = $this->instance->BeforeUpdate();
				if(is_array($validations) || is_string($validations)){
					$this->instance->setErrors($validations);
					$this->instance->Listview();
					return;
				}
			}

			if(!method_exists($this->instance, "Update"))
				throw new Exception("Não há alteração definida para a rotina: ". $ctrl);

			$id = $this->instance->Update();

			if(empty($id))
				throw new Exception("Ocorreu algum erro ao alterar o registro ".$id." na rotina: ". $ctrl);

			if(method_exists($this->instance, "AfterUpdate"))
				$this->instance->AfterUpdate();

			$db->CommitTransaction();
			$this->instance->setMessage("Parabéns, dados alterados com sucesso!");
			$this->instance->Listview(); 
		}catch(Exception $e){
			$db->RollbackTransaction();
			$this->instance->setErrors($e->getMessage());
			$this->instance->Listview(); 
		}
	}

	public function Delete($ctrl){
		$db = DB::getInstance();

		try{

			if(!isset($_POST["datagrid-selected-items"]))
				throw new Exception("Não há items selecionados para remoção");
			
			$ids = array_values($_POST["datagrid-selected-items"]);
			if(empty($ids))
				throw new Exception("Não há items selecionados para remoção");
			
			$ids = array_map(function($item){
				return DB::Clean($item);
			}, $ids);

			$this->getCtrlClass($ctrl);
			$db->BeginTransaction();

			if(method_exists($this->instance, "BeforeDelete")){
				$validations = $this->instance->BeforeDelete($ids);
				if(is_array($validations) || is_string($validations)){
					$this->instance->setErrors($validations);
					$this->instance->Listview();
					return;
				}
			}

			if(!method_exists($this->instance, "Delete"))
				throw new Exception("Não há remoção definida para a rotina: ". $ctrl);

			$result = $this->instance->Delete($ids);

			if($result !== true)
				throw new Exception("Ocorreu algum erro ao remover os registros na rotina: ". $ctrl);

			if(method_exists($this->instance, "AfterDelete"))
				$this->instance->AfterDelete($ids);

			$db->CommitTransaction();
			$this->instance->setMessage("Parabéns, dados removidos com sucesso!");
			$this->instance->Listview(); 
		}catch(Exception $e){
			$error = new ErrorHandlerCtrl();
			$error->ErrorHandler($e->getMessage());
		}
	}
}

?>