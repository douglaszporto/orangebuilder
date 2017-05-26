<?php

namespace OrangeBuild;

class Model{

    protected $id;

    public function __get($name){
        return $this->$name;
    }

    public function __set($name, $value){
        if($name === 'id')
            $this->Read($value);
        else
            $this->$name = $value;
    }

    public function Create($value){
        echo "Criar o registro $value";
    }

    public function Read($value){
        echo "Obter o registro $value";
    }

    public function Update($value){
        echo "Atualizar o registro $value";
    }

    public function Delete($value){
        echo "Deletar o registro $value";
    }

}

?>