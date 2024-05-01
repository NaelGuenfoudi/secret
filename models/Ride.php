<?php

namespace Models;

use Models\User;
use Models\Vehicle;
use Models\Booking;
use Models\Color;

class Ride extends Model
{

    //Représente la table ride
    //Utilisez les variables ci dessous dans vos requete pour éviter de tout changer en cas de modification
    public $table = "rides";
    public $primaryKey = "id_ride_pk";


    public function insert(array $data)
    {
        try {
            $query = $this->db->getPDO()->prepare("INSERT INTO $this->table (id_user_fk, start_city, end_city, vehicle_id_fk, seat_number, start_date, end_date, description) 
        VALUES (:user_id, :start_city, :end_city, :vehicle_id, :seat_number, :start_date, :end_date, :description)");

            $query->execute($data);
        } catch(\PDOException $e) {
            $this->sendError($e);
        }
    }


    public function search($options)
    {
        try {
            // Requete de base retournant tout les trajets
            $query = "SELECT ride.*, vehicle.name, vehicle.color, user.first_name, user.last_name, user.id_user_pk FROM {$this->table} as ride";

            //jointure de la table vehicule et user  pour associer les informations du conducteur
            $vehicle = new Vehicle($this->db);
            $user = new User($this->db);
            $query .= " JOIN {$vehicle->table} as vehicle ON vehicle.{$vehicle->primaryKey} = ride.id_vehicle_fk";
            $query .= " JOIN {$user->table} as user ON user.{$user->primaryKey} = vehicle.id_user_fk";
            $query .= " WHERE 1"; //clause where par default, aucun critère défini

            // Ajoute les critères renseignés par l'utilisateur dans la clause where
            if (isset($options['startCity'])) {
                $query .= " AND id_start_city_fk = :startCity";
            }
            if (isset($options['endCity'])) {
                $query .= " AND id_end_city_fk = :endCity";
            }

            $query .= " AND d_start >= :dateStart";


            $statement = $this->db->getPDO()->prepare($query);

            // Lie les paramètres de la requête s'ils sont fournis dans les options
            if (isset($options['startCity'])) {
                $statement->bindParam(':startCity', $options['startCity']);
            }
            if (isset($options['endCity'])) {
                $statement->bindParam(':endCity', $options['endCity']);
            }
            if (isset($options['date'])) {
                $statement->bindParam(':dateStart', $options['date']);
            } else {
                //prend tout les trajet à partir de la date courante
                $currentDate = date("Y-m-d H:i:s");
                $statement->bindParam(':dateStart', $currentDate);
            }

            $query .= "ORDER BY d_start ASC";

            $statement->execute();


            return $statement->fetchAll();
        } catch (\PDOException $e) {
            $this->sendError($e);
        }
    }


    public function getRidesForUser($idUser)
    {
        try {
            $vehicle = new Vehicle($this->db);
            $query = "
            SELECT rides.id_ride_pk,
            rides.id_vehicle_fk,
            rides.id_start_city_fk,
            rides.id_end_city_fk,
            rides.status,
            rides.seat_number,
            rides.d_start,
            rides.d_end,
            rides.desc_message
            FROM {$vehicle->table} AS vehicle
            JOIN rides ON rides.id_vehicle_fk = vehicle.id_vehicle_pk
            WHERE vehicle.id_user_fk = :idUser
        ";

            $statement = $this->db->getPDO()->prepare($query);
            $statement->execute(['idUser' => $idUser]);

            $result = $statement->fetchAll();
            return $result;
        } catch (\PDOException $e) {
            $this->sendError($e);
        }
    }



public
function getRideWithBookingSinceDate($userId, $startDate = null)
{
    /** Retourne la liste des trajet pour lesquelles un utilisateur possède une réservation avec toute les infos nécéssaire */
    try {
        $booking = new Booking($this->db);
        $vehicle = new Vehicle($this->db);
        $user = new User($this->db);
        $query = "
                SELECT ride.id_ride_pk as id_ride_pk,
                ride.id_start_city_fk as id_start_city_fk,
                ride.id_end_city_fk as id_end_city_fk,
                ride.d_start as d_start,
                ride.d_end as d_end,
                booking.id_booking_pk as id_booking_pk,
                booking.id_user_fk as id_user_fk,
                booking.status as booking_status,
                user.first_name,
                user.last_name,
                user.profil_image
                FROM $this->table as ride
                JOIN {$booking->table} as booking ON booking.id_ride_fk = ride.{$this->primaryKey}
                JOIN {$vehicle->table} as vehicle ON ride.id_vehicle_fk = vehicle.{$vehicle->primaryKey}
                JOIN {$user->table} as user ON vehicle.id_user_fk = user.{$user->primaryKey}
                ";


        if ($startDate != null) {
            // Si une date de début est spécifiée, on récupère les réservations à partir de cette date
            $query .= " WHERE booking.id_user_fk = :userId AND ride.d_end >= :startDate";

            $statement = $this->db->getPDO()->prepare($query);
            $statement->execute(array('userId' => $userId, 'startDate' => $startDate));
        } else {
            // Si aucune date de début n'est spécifiée, on récupère toutes les réservations de l'utilisateur
            $query .= " WHERE booking.id_user_fk = :userId";
            $statement = $this->db->getPDO()->prepare($query);
            $statement->execute(array('userId' => $userId));
        }

        $result = $statement->fetchAll();
        return $result;
    } catch (\PDOException $e) {
        $this->sendError($e);
    }
}

public
function getRideInfos($rideId)
{
    // Retourne la liste des demandes de réservation sur les trajets d'un utilisateur 
    // Retourne les infos des réservations et de l'utilisateur à l'origine de la demande
    try {


        $vehicle = new Vehicle($this->db);
        $user = new User($this->db);
        $city = new City($this->db);
        $color = new Color($this->db);
        $query = $this->db->getPDO()->prepare("
            SELECT  
            ride.id_ride_pk,ride.desc_message,
            d_start,
            d_end,
            TIME_FORMAT(ride.d_start, '%H:%i') as d_start_hour, TIME_FORMAT(ride.d_end, '%H:%i') as d_end_hour, 
            TIME_FORMAT(TIMEDIFF(ride.d_end, ride.d_start), '%H:%i')  AS travel_time,
            ride.seat_number,
             start_city.name AS start_city_name, end_city.name AS end_city_name,
            
            vehicle.name as vehicle_name, color.name as vehicle_color,vehicle.id_user_fk as id_owner,
            user.first_name, user.last_name, user.phone, user.mail, user.profil_image, user.address,user_city.name as user_city

            
            FROM $this->table AS ride
  
            

            JOIN {$vehicle->table} AS vehicle ON vehicle.{$vehicle->primaryKey} = ride.id_vehicle_fk
            JOIN {$color->table} AS color ON color.{$color->primaryKey} = vehicle.color
            JOIN {$user->table} AS user ON user.{$user->primaryKey} = vehicle.id_user_fk
            JOIN {$city->table} AS start_city ON start_city.{$city->primaryKey} = ride.id_start_city_fk
            JOIN {$city->table} AS end_city ON end_city.{$city->primaryKey} = ride.id_end_city_fk
            JOIN {$city->table} AS user_city ON user_city.{$city->primaryKey} = user.city
            WHERE $this->primaryKey = :rideId          ");

        $query->execute(
            array(
                'rideId' => $rideId
            )
        );

        $result = $query->fetch();

        if ($result) {
            return $result;
        } else {
            return false;
        }
    } catch (\PDOException $e) {
        return $this->sendError($e);
    }
}

}
