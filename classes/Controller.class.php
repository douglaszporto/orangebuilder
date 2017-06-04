<?php

namespace OrangeBuild;

use \Exception as Exception;

abstract class Controller{

    protected $route;
    protected $data;
    protected $filter;
    protected $page;
    protected $orderBy;
    protected $orderDirection;

    protected $errors;
    protected $success;
    protected $warning;

    public function Listview(){}
    public function AddForm(){}
    public function EditForm($id){}
    public function BeforeInsert(){}
    public function Insert(){}
    public function AfterInsert(){}
    public function BeforeUpdate(){}
    public function Update(){}
    public function AfterUpdate(){}
    public function BeforeDelete($ids){}
    public function Delete($ids){}
    public function AfterDelete($ids){}

    public function setFilter($filter){
        $this->filter = $filter;
    }

    public function setErrors($error){
        $this->errors = is_string($error) ? array($error) : $error;
    }

    public function setMessage($msg){
        $this->success = $msg;
    }

    public function mergeContext(array $context){

        if(isset($context["data"]) && is_array($context["data"]) && isset($context["data"]["id"]))
            $context["data"]["csrf"] = password_hash($context["data"]["id"] . SHOP_SECRET_KEY, PASSWORD_BCRYPT);

        $defaultContext = array(
            'errors'  => $this->errors,
            'success' => $this->success,
            'filter'  => $this->filter
        );

        return array_merge($defaultContext, $context);
    }

    public function validateCSRF($id, $hash){
        if(!password_verify($id . SHOP_SECRET_KEY, $hash))
            throw new Exception("O registro informado está corrompido ou invalido");
    }
}


?>