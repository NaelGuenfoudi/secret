<?php

namespace Models;

use Models\Ride;
use Models\Vehicle;
use Models\User;
use Models\City;

class Booking extends Model
{
    //Reprèsente la table cities.
    public $table = "bookings";
    public $primaryKey = 'id_booking_pk';



    public function getBookingCountByStatus($rideId)
    {
        /**Cette fonction retourne le nombre de réservation validée et en attente pour le trajet cible */
        try {

            $query = $this->db->getPDO()->prepare("
            SELECT 
                SUM(CASE WHEN status = 'Validée' THEN seat_number ELSE 0 END) AS valide_count,
                SUM(CASE WHEN status = 'En attente' THEN seat_number ELSE 0 END) AS pending_count
            FROM $this->table AS bookings
            WHERE bookings.id_ride_fk = :id
        ");
            $query->execute(array('id' => $rideId));
            $result = $query->fetch();
            //Si il n"y a aucune réservation, le résultat de la somme est null. On set la valeur à 0 dans ce cas
            if ($result['valide_count'] == null) {
                $result['valide_count'] = 0;
            }
            if ($result['pending_count'] == null) {
                $result['pending_count'] = 0;
            }
            return $result;
        } catch (\PDOException $e) {
            $this->sendError($e);
        }
    }

    public function addBooking($rideId, $userId, $seatNumber = 1, $status = "En attente")
    {
        try {
            $currentDate = date("Y-m-d H:i:s");

            $query = $this->db->getPDO()->prepare("
                INSERT INTO $this->table (id_user_fk, id_ride_fk, status, booking_date, seat_number)
                VALUES (:userId, :rideId, :status, :currentDate, :seatNumber)
            ");
            $query->execute(
                array(
                    'userId' => $userId,
                    'rideId' => $rideId,
                    'status' => $status,
                    'currentDate' => $currentDate,
                    'seatNumber' => $seatNumber
                )
            );
            $lastInsertId = $this->db->getPDO()->lastInsertId();
            return $lastInsertId;
        } catch (\PDOException $e) {
            return $this->sendError($e);
        }
    }
    public function insert(array $data)
    {
        try {
            $query = $this->db->getPDO()->prepare("INSERT INTO $this->table (first_name, last_name, mail, phone, address, city, gender, password,profil_image) 
            VALUES (:firstname, :lastname, :mail, :phone, :address, :city, :gender, :password, :profil_image)");

            $query->execute($data);
            $lastInsertId = $this->db->getPDO()->lastInsertId();
            return $lastInsertId;

        } catch (\PDOException $e) {
            return $this->sendError($e);

        }

    }
    public function userHasBooking($userId, $rideId)
    {
        /**Retourne le nombre de réservation d'un utilisateur sur un trajet */
        try {
            $query = $this->db->getPDO()->prepare("
                SELECT COUNT(*) AS booking_count 
                FROM $this->table 
                WHERE id_user_fk = :userId 
                AND id_ride_fk = :rideId
            ");
            $query->execute(
                array(
                    'userId' => $userId,
                    'rideId' => $rideId
                )
            );
            $result = $query->fetch();


            return $result;

        } catch (\PDOException $e) {
            return $this->sendError($e);
        }
    }

    public function exist($userId, $bookingId)
    {
        /**Vérifie si une réservation existe bien*/
        try {
            $query = $this->db->getPDO()->prepare("
                SELECT COUNT(*) AS booking_count 
                FROM $this->table 
                WHERE id_user_fk = :userId 
                AND id_booking_pk = :bookingId
            ");
            $query->execute(
                array(
                    'userId' => $userId,
                    'bookingId' => $bookingId
                )
            );
            $result = $query->fetch();

            if ($result[0] == 1) {
                return true;
            } else {
                return false;
            }


        } catch (\PDOException $e) {
            return $this->sendError($e);
        }
    }
    public function getRequestList($userId)
    {
        // Retourne la liste des demandes de réservation sur les trajets d'un utilisateur 
        // Retourne les infos des réservations et de l'utilisateur à l'origine de la demande
        try {
            $ride = new Ride($this->db);
            $vehicle = new Vehicle($this->db);
            $user = new User($this->db);
            $city = new City($this->db);

            $query = $this->db->getPDO()->prepare("
                SELECT booking.*, vehicle.id_vehicle_pk, vehicle.id_user_fk, user.first_name, user.last_name, user.phone, user.mail, user.profil_image, user.address,user_city.name as user_city,
                ride.d_start, ride.d_end, 
                TIME_FORMAT(TIMEDIFF(ride.d_end, ride.d_start), '%H:%i')  AS travel_time, 
                start_city.name AS start_city_name, end_city.name AS end_city_name
                FROM $this->table AS booking
                JOIN {$ride->table} AS ride ON ride.{$ride->primaryKey} = booking.id_ride_fk
                JOIN {$vehicle->table} AS vehicle ON vehicle.{$vehicle->primaryKey} = ride.id_vehicle_fk
                JOIN {$user->table} AS user ON user.{$user->primaryKey} = booking.id_user_fk
                JOIN {$city->table} AS start_city ON start_city.{$city->primaryKey} = ride.id_start_city_fk
                JOIN {$city->table} AS end_city ON end_city.{$city->primaryKey} = ride.id_end_city_fk
                JOIN {$city->table} AS user_city ON user_city.{$city->primaryKey} = user.city
                WHERE vehicle.id_user_fk = :userId and booking.status like 'En attente'           ");

            $query->execute(
                array(
                    'userId' => $userId
                )
            );

            $result = $query->fetchAll();

            if ($result) {
                return $result;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            return $this->sendError($e);
        }
    }

    public function getRideByBookingId($bookingId){
        /**Retourne l'id du propriétaire du trajet auquel appartient une réservation */
        try {
            $ride = new  ride($this->db);
            $vehicle = new Vehicle($this->db);

            $query = $this->db->getPDO()->prepare("
                SELECT vehicle.id_user_fk as owner_id, ride.seat_number,ride.d_start, ride.id_ride_pk, booking.status
                FROM $this->table AS booking
                JOIN {$ride->table} AS ride ON booking.id_ride_fk = {$ride->primaryKey}
                JOIN {$vehicle->table} AS vehicle ON vehicle.{$vehicle->primaryKey} = ride.id_vehicle_fk

                WHERE $this->primaryKey = :bookingId
            ");

            $query->execute(
                array(
                    'bookingId' => $bookingId
                )
            );

            $result = $query->fetch();

          
            return $result;
          
      
        } catch (\PDOException $e) {
            return $this->sendError($e);
        }
    }
    public function getByUser($userId, $rideId){
        /**Retourne la réservation d'un utilisateur spécifique sur un trajet spécifique */

        try {

            $query = $this->db->getPDO()->prepare("
                SELECT booking.id_booking_pk, booking.status,booking.seat_number
                FROM $this->table AS booking


                WHERE booking.id_ride_fk = :rideId and booking.id_user_fk = :userId
            ");

            $query->execute(
                array(
                    'userId' => $userId,
                    'rideId' => $rideId
                )
            );

            $result = $query->fetch();

          
            return $result;
          
      
        } catch (\PDOException $e) {
            return $this->sendError($e);
        }
    }
    public function getBookingList($rideId, $status = null)
    {
        /**Retourne la liste des réservations sur un trajet */
        try {
            $user = new User($this->db);
            $city = new City($this->db);
            $query = "
                SELECT booking.id_booking_pk, booking.status, booking.seat_number, user.first_name, user.last_name, user.profil_image, user.mail, user.address, user.phone, city.name as user_city
                FROM $this->table AS booking
                JOIN {$user->table} AS user ON user.{$user->primaryKey} = booking.id_user_fk
                JOIN {$city->table} AS city ON city.{$city->primaryKey} = user.city
                WHERE booking.id_ride_fk = :rideId";
    
            // Si le statut est spécifié, on ajoute la clause where associée
            if ($status !== null) {
                $query .= " AND booking.status LIKE :status";
            }
    
            $query .= " ORDER BY booking.status DESC";
            $stmt = $this->db->getPDO()->prepare($query);
    

            $stmt->bindValue(':rideId', $rideId);
    
      
            if ($status !== null) {
                $stmt->bindValue(':status', $status);
            }
    

            $stmt->execute();
    

            $result = $stmt->fetchAll();
    
            return $result;
        } catch (\PDOException $e) {
            return $this->sendError($e);
        }
    }
    
}
