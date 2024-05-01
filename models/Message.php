<?php

namespace Models;

use Models\Ride;
use Models\Vehicle;
use Models\User;
use Models\City;

class Message extends Model
{
    //Reprèsente la table cities.
    public $table = "messages";
    public $primaryKey = 'id_message_pk';


    public function insert($message, $rideId, $userId) {
        /**Enregistre un message en bdd */
    
        try {

            $query = $this->db->getPDO()->prepare("INSERT INTO $this->table (message, id_ride_fk, id_user_fk) 
                VALUES (:message, :rideId, :userId)");

            $query->bindParam(':message', $message);
            $query->bindParam(':rideId', $rideId);
            $query->bindParam(':userId', $userId);
    

            $query->execute();
    

            return $this->db->getPDO()->lastInsertId();
        } catch(\PDOException $e) {
 
           return $this->sendError($e);
        }
    }
    
    
    public function getByRide($rideId) {
        try {
            
            $user = new User($this->db);
            
   
            $query = "
                SELECT 
                    message.message, 
                    DATE_FORMAT(message.d_message, '%d-%m-%Y %H:%i:%s') AS d_message, 
                    user.first_name, 
                    user.last_name 
                FROM 
                    $this->table AS message
                JOIN 
                    {$user->table} AS user ON user.{$user->primaryKey}= message.id_user_fk
                WHERE 
                    message.id_ride_fk = :rideId";
    
 
            $statement = $this->db->getPDO()->prepare($query);
            $statement->execute([':rideId' => $rideId]);
    
   
            $results = $statement->fetchAll();
    
 
            return $results;
        } catch(\PDOException $e) {
            // Gérer les exceptions
           return $this->sendError($e);
        }
    }
    
    
}