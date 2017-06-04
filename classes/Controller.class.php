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
    public function EditForm($id){}
    public function BeforeInsert(){}
    public function Insert(){}
    public function AfterInsert(){}
    public function BeforeUpdate($id){}
    public function Update($id){}
    public function AfterUpdate($id){}
    public function BeforeDelete($ids){}
    public function Delete($ids){}
    public function AfterDelete($ids){}


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