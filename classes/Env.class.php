<?php

namespace OrangeBuild;

class Env{

    static function loadConfigShop(){
        try{
            $slug = (explode(".",$_SERVER["HTTP_HOST"]))[0];
            $path = "/../admin/config.php";

            if(stripos($slug,"localhost") === FALSE)
                $path = "/../shops/".$slug."/config.php";

            $configFile = dirname(__FILE__) . $path;

            if(file_exists($configFile))
                require_once $configFile;
            else
                throw new Exception("(Loja invalida: ".$slug.")");
        }catch(Exception $e){
            throw new Exception("A loja ".$slug." não está configurada corretamente. " . $e->getMessage());
        }
    }

}