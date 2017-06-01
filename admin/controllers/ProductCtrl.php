<?php

use OrangeBuild\View       as View;
use OrangeBuild\Controller as Controller;
use OrangeBuild\DB         as DB;

require_once dirname(__FILE__) . "/../config.php";

class ProductCtrl extends Controller{

    public function __construct(){
        $this->route = SHOP_DOMAIN . "/admin/produtos";
    }

    public function Listview(){
        $db = DB::getInstance();
        $db->Query("
            SELECT 
                products.id     as id, 
                products.name   as name, 
                products.price  as price, 
                products.stock  as stock, 
                categories.name as category
            FROM 
                products 
                LEFT JOIN
                    categories
                ON
                    categories.id = products.category_id
            WHERE 
                products.shop_id = '2'
        ");

        $products = array();
        while($result = $db->Fetch()){
            $result["price"] = number_format($result["price"], 2, "," , ".");
            $products[] = $result;
        }

        View::RenderRequest("Products/Products.tpl", $this->mergeContext(array(
            'products' => $products
        )));
    }

    public function AddForm(){
        $categories = DB::GetValues('categories', 'id', 'name', '', 'name ASC');
        View::RenderRequest("Products/ProductsAddForm.tpl", $this->mergeContext(array(
            'categories' => $categories
        )));
    }

    public function EditForm($id){
        $categories = DB::GetValues('categories', 'id', 'name', '', 'name ASC');

        $db = DB::getInstance();
        $db->Query("
            SELECT id as id, name, category_id, price, stock FROM products WHERE id = '".((int)$id)."'
        ");
        $result = $db->Fetch();
        if(empty($result))
            throw new Exception("Registro #".$id." não encontrado. Ele pode ter sido removido por outro usuário");

        View::RenderRequest("Products/ProductsEditForm.tpl", $this->mergeContext(array(
            'data'       => $result,
            'categories' => $categories
        )));
    }

    public function ValidateData(){
        $errors = array();
        if(empty($_POST["data-name"]))
            $errors[] = "O nome do produto é obrigatório";
        if(empty($_POST["data-category"]))
            $errors[] = "A categoria do produto é obrigatória";
        if(empty($_POST["data-price"]))
            $errors[] = "O preço de venda é obrigatório";
        if(empty($_POST["data-stock"]))
            $errors[] = "A quantidade em estoque é obrigatória";

        return $errors;
    }

    public function BeforeInsert(){
        $errors = $this->ValidateData();
        if(count($errors) > 0)
            return $errors;

        $this->data["name"]        = $_POST["data-name"];
        $this->data["category_id"] = $_POST["data-category"];
        $this->data["price"]       = $_POST["data-price"];
        $this->data["stock"]       = $_POST["data-stock"];
        $this->data["shop_id"]     = 2;
        
        return true;
    }

    public function Insert(){
        $db = DB::getInstance();
        $success = $db->QueryInsert('products', $this->data);

        return !$success ? false : $db->InsertedId();
    }

    public function BeforeUpdate($id){
        $errors = $this->ValidateData();
        if(count($errors) > 0)
            return $errors;

        $this->data["name"]        = $_POST["data-name"];
        $this->data["category_id"] = $_POST["data-category"];
        $this->data["price"]       = $_POST["data-price"];
        $this->data["stock"]       = $_POST["data-stock"];
        $this->data["shop_id"]     = 2;
        
        return true;
    }

    public function Update($id){
        $db = DB::getInstance();
        $success = $db->QueryUpdate('products', $this->data, "id = '".((int)$id)."'");

        return !$success ? false : $id;
    }


}


?>