<?php

namespace OrangeBuild;

use \Exception as Exception;

abstract class Controller{

    protected $route;
    protected $data;
    protected $filter;
    protected $page = 1;
    protected $bypage = 10;
    protected $orderBy;
    protected $orderDir;
    protected $regCount;
    protected $totalReg;

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

    public function setOrderBy($orderBy){
        $this->orderBy = $orderBy;
    }
    public function setOrderDir($orderDir){
        $this->orderDir = $orderDir;
    }

    public function setErrors($error){
        $this->errors = is_string($error) ? array($error) : $error;
    }

    public function setMessage($msg){
        $this->success = $msg;
    }

    public function setRegCount($regCount){
        $this->totalReg = $regCount;
        $this->regCount = $regCount . ($regCount == 1 ? " registro" : " registros");
    }

    public function setByPage($num){
        $this->bypage = (int)$num > 0 ? (int)$num : 1;
    }

    public function setPage($page){
        $this->page = (int)$page > 0 ? (int)$page : 1;
    }

    public function sanitizePagination(){
        $numOfPages = ceil($this->totalReg / $this->bypage);

        if($this->page > $numOfPages)
            $this->setPage($numOfPages);
    }

    public function mergeContextListview(array $context){

        if(isset($context["data"]) && is_array($context["data"]) && isset($context["data"]["id"]))
            $context["data"]["csrf"] = password_hash($context["data"]["id"] . SHOP_SECRET_KEY, PASSWORD_BCRYPT);

        $numOfPages = ceil($this->totalReg / ($this->bypage > 0 ? $this->bypage : 1));

        $startPage = max(1,$this->page - 2);
        $endPage   = min($this->page + 2, $numOfPages);

        if($endPage - $startPage < 5){
            if($startPage < 3)
                $endPage += 4 - ($endPage - $startPage);
            else if($endPage > $numOfPages - 3)
                $startPage -= 4 - ($endPage - $startPage);
        }

        $startPage = max($startPage, 1);
        $endPage   = min($endPage, $numOfPages);


        $defaultContext = array(
            'errors'          => $this->errors,
            'success'         => $this->success,
            'filter'          => $this->filter,
            'orderBy'         => $this->orderBy,
            'orderDir'        => $this->orderDir,
            'regCount'        => $this->regCount,
            'isfirstPage'     => $this->page == 1,
            'isLastPage'      => $this->page == $numOfPages,
            'bypage'          => $this->bypage,
            'pages'           => $numOfPages,
            'page'            => $this->page,
            'initialPage'     => $startPage,
            'finalPage'       => $endPage,
        );

        return array_merge($defaultContext, $context);
    }

    public function mergeContext(array $context){

        if(isset($context["data"]) && is_array($context["data"]) && isset($context["data"]["id"]))
            $context["data"]["csrf"] = password_hash($context["data"]["id"] . SHOP_SECRET_KEY, PASSWORD_BCRYPT);

        $defaultContext = array(
            'errors'          => $this->errors,
            'success'         => $this->success
        );

        return array_merge($defaultContext, $context);
    }

    public function validateCSRF($id, $hash){
        if(!password_verify($id . SHOP_SECRET_KEY, $hash))
            throw new Exception("O registro informado está corrompido ou invalido");
    }
}


?>