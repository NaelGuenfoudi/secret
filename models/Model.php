<?php
    namespace Models;

    class Model{
        //Classe regroupant des fonctionnalités communes à tout les modèles. Possibilité de mettre en place des requetes générique fonctionnant sur tout les  modèless
        protected $db;
        protected $primaryKey ="";
        protected $table = ""; //permet d'instancier un modèle avec le nom d'un table afin de pouvoir construire des requetes génériques.
        protected $fields = []; // Défini la liste des champs d'une table. Permet de construire des requetes génériques
        public function __construct($db)
        {

                $this->db = $db;


                
        }

        public function getDB(){
            return $this->db;
            }

        protected function sendError($error)
        {
            // Vérifier si une session est déjà active
            if (session_status() == PHP_SESSION_NONE) {
            session_start();
            }
        $_SESSION['error_msg'] = $error->getMessage();
        header("Location: " . ROOT.'/error');
        exit();
        }

        public function getAll(){
            //fonction générique qui permet de récupérer tout les résultats d'une table
            $query = $this->db->getPDO()->query("SELECT * FROM ".$this->table."");
            return $query->fetchAll();
        }

        public function getAllById(int $id, string $fieldName) {
            try {
                $query = $this->db->getPDO()->prepare("SELECT * FROM $this->table WHERE $fieldName = :id");
                $query->execute([':id' => $id]);
        
                return $query->fetchAll();
            } catch (\PDOException $e) {
                $this->sendError($e);
            }
        }

        public function getById(int $id) {
            try {
                $query = $this->db->getPDO()->prepare("SELECT * FROM $this->table WHERE $this->primaryKey = :id");
                $query->execute([':id' => $id]);
        
                return $query->fetch();
            } catch (\PDOException $e) {
                $this->sendError($e);
            }
        }

        public function update(int $id, array $data) {
            try {
                //construction de la liste de champ à mettre à jour
                $fieldList = '';
                foreach ($data as $key => $value) {
                    $fieldList .= "$key = :$key, ";
                }
                $fieldList = rtrim($fieldList, ', ');
        
                $query = $this->db->getPDO()->prepare("UPDATE $this->table SET $fieldList WHERE $this->primaryKey = :id");
                $data['id'] = $id;
        

                $query->execute($data);
            } catch (\PDOException $e) {
                $this->sendError($e);
            }
        }

        public function delete(int $id) {

            try {
             
                $query = $this->db->getPDO()->prepare("DELETE FROM $this->table WHERE $this->primaryKey = :id");
                $query->execute([':id' => $id]);
        
                // Vérification du nombre de ligne supprimée
                $rowCount = $query->rowCount();
        
                // Si au moins une ligne a été supprimée, retourner vrai
                if ($rowCount > 0) {
                    return true;
                } else {
                    // Si aucune ligne n'a été supprimée, retourner faux
                    return false;
            }   
            } catch (\PDOException $e) {
                $this->sendError($e);
            }
        }
        
        
    }
?>