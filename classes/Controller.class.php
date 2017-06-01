<?php

namespace OrangeBuild;

abstract class Controller{

    protected $route;
    protected $data;

    protected $errors;
    protected $success;
    protected $warning;

    public function Listview(){}
    public function AddForm(){}
    public function EditForm(){}
    public function BeforeInsert(){}
    public function Insert(){}
    public function AfterInsert(){}
    public function BeforeUpdate(){}
    public function Update(){}
    public function AfterUpdate(){}
    public function BeforeDelete(){}
    public function Delete(){}
    public function AfterDelete(){}


    public function setErrors($error){
        $this->errors = is_string($error) ? array($error) : $error;
    }

    public function setMessage($msg){
        $this->success = $msg;
    }

    public function mergeContext(array $context){
        $defaultContext = array(
            'errors' => $this->errors,
            'success' => $this->success
        );

        return array_merge($defaultContext, $context);
    }
}


?>