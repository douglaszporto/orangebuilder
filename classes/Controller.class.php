<?php

namespace OrangeBuild;

abstract class Controller{

    protected $route;

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
}


?>