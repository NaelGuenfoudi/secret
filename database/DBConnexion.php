<?php

namespace Database;

use PDO;
class DBConnexion{
    //Gère la connexion à la base de donnée
    private $dbname;
    private $user;
    private $host;
    private $password;
    private $pdo;
    
    public function __construct($dbname,$host,$user,$password)
    {
        $this->dbname = $dbname;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    public function getPDO(){
        //Créer et retourne une instance pdo si elle n'existe pas
        if($this->pdo == NULL){
            $dsn = "mysql:dbname={$this->dbname};host={$this->host}";
            $this->pdo = new PDO($dsn,$this->user,$this->password);

        } 
        return $this->pdo;
    }
}