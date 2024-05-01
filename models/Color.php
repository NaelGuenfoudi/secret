<?php

namespace Models;


class Color extends Model{

    //Représente la table users
    //Utilisez les variables ci dessous dans vos requete pour éviter de tout changer en cas de modification
    public $table = "colors";
   
    public $primaryKey = "id_color_pk";

    public function getTable(){

    return $this->table;
    }
    public function getPrimaryKey(){

        return $this->primaryKey;
        }

    public function getColor($id){
        try {
            $query = $this->db->getPDO()->prepare("SELECT name FROM $this->table WHERE $this->primaryKey = :id");
            $query->execute([':id' => $id]);
            $result = $query->fetch();
            return $result[0];
        } catch (\PDOException $e) {
            $this->sendError($e);
        }

    }


}