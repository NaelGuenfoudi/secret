<?php

namespace Models;
use Models\Color;

class Vehicle extends Model{

    //Représente la table users
    //Utilisez les variables ci dessous dans vos requete pour éviter de tout changer en cas de modification
    public $table = "vehicles";
    public $primaryKey = "id_vehicle_pk";


    public function insert(array $data){
        try{
            $query = $this->db->getPDO()->prepare("INSERT INTO $this->table (id_user_fk, name, seat_number, color) 
            VALUES (:user_id, :model, :seat_number, :color)");

            $query->execute($data);
        }catch(\PDOException $e){
            $this->sendError($e);
        }

    }

    public function getByUser(int $id, bool $all = false) {
        /**Retourne la liste des véhicules d'un utilisateur
         * Si all est set à true on retourne les véhicules archivés
         */
        try {
            $color = new Color($this->getDB());
            $query = "SELECT $this->primaryKey, id_user_fk, vehicle.name, seat_number, color.name as color
                      FROM $this->table as vehicle
                      JOIN {$color->getTable()} AS color ON vehicle.color = color.{$color->getPrimaryKey()}
                      WHERE id_user_fk = :id";
            if(!$all) {
                $query .= " AND vehicle.status = 1";
            }
            $stmt = $this->db->getPDO()->prepare($query);
            $stmt->execute([':id' => $id]); 
    
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            $this->sendError($e);
        }
    }

    public function getByUserAndName($idUser, $nameVehicle)
    {
        try {
            $color = new Color($this->getDB());
            $query = "SELECT {$this->primaryKey}, id_user_fk, vehicle.name, seat_number, color.name as color
                  FROM {$this->table} as vehicle
                  JOIN {$color->getTable()} AS color ON vehicle.color = color.{$color->getPrimaryKey()}
                  WHERE id_user_fk = :idUser AND vehicle.name = :nameVehicle";

            $stmt = $this->db->getPDO()->prepare($query);
            $stmt->execute([':idUser' => $idUser, ':nameVehicle' => $nameVehicle]);

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            $this->sendError($e);
        }
    }



    public function vehicleRideCount($vehicleId){
        /**Retourne le nombre de trajet associé à un véhicule */
        try {
            $ride = new Ride($this->getDB());
            $query = $this->db->getPDO()->prepare("
            SELECT count(*) as count
            FROM $this->table as vehicle
            JOIN {$ride->table} AS ride ON ride.id_vehicle_fk =  vehicle.$this->primaryKey
            WHERE vehicle.$this->primaryKey = :vehicleId");
            $query->execute([':vehicleId' => $vehicleId]);
            $result = $query->fetch();
            return $result['count'];
        } catch (\PDOException $e) {
            $this->sendError($e);
        }
    }





}