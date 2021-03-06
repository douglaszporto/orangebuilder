<?php

use OrangeBuild\View       as View;
use OrangeBuild\Controller as Controller;
use OrangeBuild\DB         as DB;
use OrangeBuild\Env as Env;

Env::loadConfigShop();

class ProductCtrl extends Controller{

    public function __construct(){
        $this->route = SHOP_DOMAIN . "/admin/produtos";
    }

    public function Listview(){
        $db = DB::getInstance();

        $filter   = "";
        $orderBy  = "1";
        $orderDir = "asc";

        if(strlen($this->filter) > 0)
            $filter = "AND products.name LIKE ".DB::Clean('%'.$this->filter.'%');
        if(strlen($this->orderBy) > 0){
            $possibleColumns = array(
                'name'     => 'products.name',
                'price'    => 'products.price',
                'stock'    => 'products.stock',
                'category' => 'categories.name'
            );
            $orderBy = in_array($this->orderBy, array_keys($possibleColumns)) ? $possibleColumns[$this->orderBy] : '1';
        }
    
        $this->orderDir = $this->orderDir == 'desc' ? 'desc' : 'asc';

        $db->Query("SELECT COUNT(id) as count FROM products WHERE shop_id = ".DB::Clean(SHOP_ID)." ".$filter);
        $count = $db->Fetch();
        $this->setRegCount($count["count"]);
        $this->sanitizePagination();

        $sql = sprintf('
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
                products.shop_id = %s
                %s
            ORDER BY
                %s %s
            LIMIT %s,%s
        ', DB::Clean(SHOP_ID), $filter, $orderBy, $this->orderDir, $this->bypage*($this->page-1), $this->bypage);

        $db->Query($sql);

        $products = array();
        while($result = $db->Fetch()){
            $result["price"] = number_format($result["price"], 2, "," , ".");
            $products[] = $result;
        }

        View::RenderRequest("Products/Products.tpl", $this->mergeContextListview(array(
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
        $this->data["description"] = "";
        $this->data["shop_id"]     = SHOP_ID;
        
        return true;
    }

    public function Insert(){
        $db = DB::getInstance();
        $success = $db->QueryInsert('products', $this->data);

        return !$success ? false : $db->InsertedId();
    }

    public function BeforeUpdate(){
        parent::validateCSRF($_POST["data-id"], $_POST["data-csrf"]);

        $errors = $this->ValidateData();
        if(count($errors) > 0)
            return $errors;

        $this->data["id"]          = $_POST["data-id"];
        $this->data["name"]        = $_POST["data-name"];
        $this->data["category_id"] = $_POST["data-category"];
        $this->data["price"]       = $_POST["data-price"];
        $this->data["stock"]       = $_POST["data-stock"];
        $this->data["description"] = "";
        $this->data["shop_id"]     = SHOP_ID;
        
        return true;
    }

    public function Update(){
        $db = DB::getInstance();
        $success = $db->QueryUpdate('products', $this->data, "id = '".((int)$this->data["id"])."'");

        return !$success ? false : $this->data["id"];
    }


    public function Delete($ids){
        $db = DB::getInstance();
        $success = $db->QueryDelete('products', "id IN (".implode(',', $ids).") AND shop_id = '".SHOP_ID."'");

        return $success !== false;
    }


}


?>