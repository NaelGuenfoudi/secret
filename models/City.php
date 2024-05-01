<?php

namespace Models;


class City extends Model{
    //Reprèsente la table cities.
    public $table = "cities";
    public $primaryKey ='id_city_pk';



    public function exist($name){
        $query = $this->db->getPDO()->prepare("SELECT $this->primaryKey FROM $this->table WHERE name LIKE :name");
        $query->execute(array('name' => $name));
        $result = $query->fetch();
        if($result){
        return $result[0];
        }else{
            return false;
        }
    }
    public function getName($id){
        try{
            $query = $this->db->getPDO()->prepare("SELECT name FROM $this->table WHERE $this->primaryKey LIKE :id");
            $query->execute(array('id' => $id));
            $result = $query->fetch();
            return $result['name'];
        }catch (\PDOException $e) {
            $this->sendError($e);
        }


    }

    
    
}
