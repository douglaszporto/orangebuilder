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

        View::RenderRequest("Products/Products.tpl", array(
            'products' => $products,
            'errors' => $this->errors,
            'success' => $this->success
        ));
    }

    public function AddForm(){
        View::RenderRequest("Products/ProductsAddForm.tpl");
    }

    public function BeforeInsert(){
        $errors = array();

        if(empty($_POST["data-name"]))
            $errors[] = "O nome do produto é obrigatório";
        if(empty($_POST["data-category"]))
            $errors[] = "A categoria do produto é obrigatória";
        if(empty($_POST["data-price"]))
            $errors[] = "O preço de venda é obrigatório";
        if(empty($_POST["data-stock"]))
            $errors[] = "A quantidade em estoque é obrigatória";

        if(count($errors) > 0)
            return $errors;
        
        return true;
    }

    public function Insert(){
        $db = DB::getInstance();
        return true;
    }

    public function AfterInsert(){
        
    }

}


?>