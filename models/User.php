<?php

namespace Models;


class User extends Model{

    //Représente la table users
    //Utilisez les variables ci dessous dans vos requete pour éviter de tout changer en cas de modification
    public $table = "users";
    public $primaryKey = "id_user_pk";
    protected $fields = [
        //Ne sert pas pour le moment. Peut-être nécéssaire pour mettre en place des requêtes génériques
        "id"=> ["type"=> "integer"],
        "first_name" => ["type"=> "string"],
        "last_name"=> ["type"=> "string"],
        "email"=> ["type"=> "string"],
        "phone"=> ["type"=> "string"],
        "address" => ["type"=> "string"],
        "account_status" => ["type"=> "boolean"],
        "genre"=> ["type"=> "string"]
        
    ];

    public function getUserByMail($mail)
    {
        $query = $this->db->getPDO()->prepare("SELECT * FROM $this->table WHERE mail LIKE :mail");
        $query->execute(array(':mail' => $mail));
        $result =$query->fetch();
        return $result; 
    }

    public function exist($mail){
        $query = $this->db->getPDO()->prepare("SELECT COUNT(*) FROM $this->table WHERE mail LIKE :mail");
        $query->execute(array('mail' => $mail));
        $result = $query->fetch();

        if($result[0] == 1){
            return true;
        }
        return false;
    }

    public function insert(array $data){
        try{
            $query = $this->db->getPDO()->prepare("INSERT INTO $this->table (first_name, last_name, mail, phone, address, city, gender, password,profil_image) 
            VALUES (:firstname, :lastname, :mail, :phone, :address, :city, :gender, :password, :profil_image)");

            $query->execute($data);
            $lastInsertId = $this->db->getPDO()->lastInsertId();
            return $lastInsertId;
            
        }catch(\PDOException $e){
            return $this->sendError($e);
            
        }

    }


    public function getUserInfos($id){
        try {
            $query = $this->db->getPDO()->prepare("SELECT first_name, last_name, profil_image FROM $this->table WHERE $this->primaryKey = :id");
            $query->execute([':id' => $id]);
    
            return $query->fetch();
        } catch (\PDOException $e) {
            $this->sendError($e);
        }
    }
    


    public function registerUser($postData)
    {

        $jsonData = json_decode($postData);

        $first_name = $jsonData['first_name'];
        $last_name = $jsonData['last_name'];
        $mail = $jsonData['mail'];
        $phone = $jsonData['phone'];
        $address = $jsonData['address'];
        $city = $jsonData['city'];
        $gender = $jsonData['gender'];
        $password = $jsonData['password'];
        $profil_image = $jsonData['profil_image'];

        try{
            $query = $this->db->getPDO()->prepare("INSERT INTO $this->table (first_name, last_name, mail, phone, address, city, gender, password, profil_image) VALUES ($first_name, $last_name, $mail, $phone, $address, $city, $gender, $password, $profil_image)");
            $query->execute();
            $result =$query->fetchAll();
            return $result; 
        }catch(\PDOException $e){
            return $this->sendError($e);
        }
    }

}

