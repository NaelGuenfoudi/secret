<?php

namespace Components\Controllers;

use Models\Ride;
use Models\City;
use Models\User;
use Models\Vehicle;
use DateTime;
use Models\Booking;

class BookingController extends Controller
{

    
   
    public function controlBookingRequest($rideId, $userId)
    {
        /**Cette fonction vérifie la faisabilité d'une réservation en fontion des règles de gestion en vigueur */
        if (isset($_POST['seat_number'])) {
            $seatNumber = $_POST['seat_number'];
            if ($seatNumber <= 0) {
                $seatNumber = 1;
            }
        } else {
            return $this->sendError("Une erreur est survenue lors de l'envoi de votre réservation : Nombre de place incorrect");
        }

        //On récupère les infos du trajet 
        $ride = new Ride($this->getDBConnexion());
        $rideInfos = $ride->getById($rideId);

        //On vérifie que le trajet existe bien
        if (!$rideInfos) {
            return $this->sendError("Ce trajet n'existe pas ou à été supprimé");
        }
        //On récupére le compte de réservation "en attente" et "validée
        $booking = new Booking($this->getDBConnexion());
        $bookingCounts = $booking->getBookingCountByStatus($rideId);

        //On contrôle si une nouvelle réservation est possible
        $remainingSeats = $rideInfos['seat_number'] - $bookingCounts['valide_count'];


        if ($remainingSeats < $seatNumber) {
            //Retourner un message d'erreur
            return $this->sendError("Il ne reste plus de place disponible pour ce trajet.");
        }

        //On empêche la création de  réservation sur un trajet en cours ou passé
        $currentDate = date("Y-m-d H:i:s");

        if ($currentDate >= $rideInfos['d_start']) {
            //Retourner un message d'erreur
            return $this->sendError("Ce trajet n'est plus d'actualité");
        }
        return true;
    }
    function addBooking($rideId)
    {
        /**Action déclenchée lrosuqe l'utilisateur souhaite faire une réservation sur le trajet cible ($id). Cette fonction contrôle si la réservation est possible à partir des règle de gestion définie
         *  */
        if (!$this->isLogIn()) {
            header('Location:' . ROOT . '/connexion');
            exit();
        }
        $userId = $_SESSION['user_id'];
        $requestIsValid = $this->controlBookingRequest($rideId, $userId);
        if ($requestIsValid) {
            $booking = new Booking($this->getDBConnexion());

            //vérification de la présence d'un réservation existante
            $userHasBooking = $booking->userHasBooking($userId, $rideId);
            if ($userHasBooking['booking_count'] != 0) {
                //Un utilisateur ne peux faire qu'une réservation par trajet

                return $this->sendError("Vous avez déja une Réservation pour ce trajet.");
            } else {

                $insertedBookingId = $booking->addBooking($rideId, $userId, $_POST['seat_number']);
                return $this->sendSuccess("Votre réservation à bien été envoyée au conducteur. Consulter votre profil pour voir si elle a été validée");
            }


        }



    }
    public function getBookingInfos($bookingId)
    {
        $userId = $_SESSION['user_id'];

        //On vérifie si la réservation existe bien et est rattaché à l'utilisateur connecté
        $booking = new Booking($this->getDBConnexion());
        $bookingInfos = $booking->getById($bookingId);
        if (!isset($bookingInfos['id_user_fk']) || $bookingInfos['id_user_fk'] != $userId) {
            return $this->sendError('Erreur lors du chargement de votre réservation. ID de réservation introuvable');
        }



        return $bookingInfos;




    }


    function updateBooking($bookingId)
    {
        /** */
        if (!$this->isLogIn()) {
            header('Location:' . ROOT . '/connexion');
            exit();
        }
        $bookingInfos = $this->getBookingInfos($bookingId);
        $this->controlBookingRequest($bookingInfos['id_ride_fk'], $bookingInfos['id_user_fk']);

        $booking = new Booking($this->getDBConnexion());
        $dataset = [
            'seat_number' => $_POST['seat_number']

        ];
        $booking->update($bookingId, $dataset);
        return $this->sendSuccess("Votre réservation à bien été mise à jour");
    }

    public function getBookingList($all = false)
    {
        /**Récupére la liste des réservation et retourne la section réservation de l'espace personnel
         * si le parametre all est défini, on récupére aussi la liste des anciennes réservations
         */
        if (!$this->isLogIn()) {
            header('Location:' . ROOT . '/connexion');
            exit();
        }
        $userId = $_SESSION['user_id'];
        $ride = new Ride($this->getDBConnexion());
        if ($all) {
            $bookingList = $ride->getRideWithBookingSinceDate($userId);
        } else {
            $currentDate = date("Y-m-d H:i:s");
            $bookingList = $ride->getRideWithBookingSinceDate($userId, $currentDate);
        }
        $city = new City($this->getDBConnexion());

        foreach ($bookingList as &$booking) {
            $startDate = new DateTime($booking['d_start']);
            $endDate = new DateTime($booking['d_end']);

            $booking['startHour'] = $startDate->format('H:i');
            $booking['endHour'] = $endDate->format('H:i');
            $dateDelta = $startDate->diff($endDate);
            $booking['travelTime'] = $dateDelta->format('%H:%I');
            $booking['startCityName'] = $city->getName($booking['id_start_city_fk']);
            $booking['endCityName'] = $city->getName($booking['id_end_city_fk']);
            $booking['fullDateString'] = $this->getTextDate($booking['d_start']);
        }
        $section_name = "section_booking";

        return $this->render('profil/main_section.php', compact('bookingList', 'section_name'));
    }

    function deleteBooking($rideId, $bookingId)
    {
        if (!$this->isLogIn()) {
            header('Location:' . ROOT . '/connexion');
            exit();
        }
        //On vérifie que la réservation est bien associé à l'utilisateur connecté
        $userId = $_SESSION['user_id'];
        $booking = new Booking($this->getDBConnexion());
        $bookingInfos = $booking->getById($bookingId);
        if ($bookingInfos['id_user_fk'] != $userId) {
            return $this->sendError('Vous ne pouvez pas supprimer cette réservation');
        }
        $ride = new Ride($this->getDBConnexion());
        $rideInfos = $ride->getById($rideId);
        $currentDate = date("Y-m-d H:i:s");
        //On empeche la suppression d'une réservation sur un trajet en cours ou passé
        if ($rideInfos['d_start'] <= $currentDate) {
            return $this->sendError('Ce trajet est clôturé. Impossible de supprimer votre réservation');

        }
        $result = $booking->delete($bookingId);

        if ($result) {
            return $this->sendSuccess("Votre réservation a été supprimée avec succès");
        }
        return $this->sendError('Votre réservation ne peux pas être supprimée');

    }

    public function getBookingRequests($msg = null)
    {
        /**Récupére la liste des demandes de réservation à traiter
         * $msg permet d'ajouter un message à afficher dans la page Utilse pour rafraichir la page après avoir effectué une action         */
        if (!$this->isLogin()) {
            return header("Location: " . ROOT . '/connexion');
        }

        //Récupérer la liste des demande de réversation
        $booking = new Booking($this->getDBConnexion());
        $userId = $_SESSION['user_id'];
        $requestList = $booking->getRequestList($userId);
        if($requestList){
                
            foreach ($requestList as &$request) {
                $request['fullDateString'] = $this->getTextDate($request['d_start']);
    
            }
        }
        //Pour chaque réservation en enrichi les résultats avec des infos en +


        $section_name = "section_requests";
        //si un message est set
        if($msg != null){
            return $this->render('profil/main_section.php', compact('requestList', 'section_name', 'msg'));
        }
        return $this->render('profil/main_section.php', compact('requestList', 'section_name'));
    }

    public function askToBookingRequest($action,$bookingId){
        /**Cette fonction prend en paramètre le choix de l'utilsateur (0,1 - refuser, valider) et l'id de la réservation et lance l'action correspondante
         * 
         */

         //Contrôle de la requete
         if (!$this->isLogin()) {
            return header("Location: " . ROOT . '/connexion');
        }
        //La requete de confirmation d'uen réservation ne peux être faite que par le propriétaire du trajet
        //Le traitement suivant permet aussi d'éviter les requetes sur des reservation inexistante
        $booking = new booking($this->getDBConnexion());
        $userId = $_SESSION['user_id'];
      
        $bookingInfos = $booking->getRideByBookingId($bookingId);

        if($userId != $bookingInfos['owner_id']){
            return $this->sendError('Vous ne pouvez pas valider cette réservation : Vous n\'avez pas de droit sur ce trajet');
        }
        //On ne peut traiter une réservation que si elle est en attente
        if($bookingInfos['status'] != 'En attente' ){
            return $this->sendError('Vous ne pouvez pas valider cette réservation : Validation déjà traitée');
        }
        //On ne peut pas traiter une réservation d'un trajet passé ou en cours
        $currentDate = date("Y-m-d H:i:s");
        if($currentDate >= $bookingInfos['d_start']){
            return $this->sendError('Vous ne pouvez plus traiter de réservation pour ce trajet.');
        }
        //On aiguille le traitement en fonction du type d'action

        //Cas de la confirmation
            // On vérifie que la réservation soit encore possible (capacité place)
        if($action =="1"){
            //On le nombre de réservation
            
            
            $bookingCount =  $booking->getBookingCountByStatus($bookingInfos['id_ride_pk']);    
            if( $bookingInfos['seat_number'] - $bookingCount['valide_count'] <= 0){
                //Dans ce cas on ne peux pas faire de validation, on refuse automatiquement la réservation et on en informe le conducteur
                $dataset = ['status' =>"Refusée" ];
                $msg = [
                        'type' => "error",
                        'message' => "Le nombre de place restant n'as pas permis la validation du message"

                ];
                $booking->update($bookingId,$dataset);
                return $this->getBookingRequests($msg);
            }else{
                //On valide la réservation
                $dataset = ['status' =>"Validée" ];
                $msg = [
                        'type' => "success",
                        'message' => "La réservation à bien été validée"

                ];
                $booking->update($bookingId,$dataset);
                return $this->getBookingRequests($msg);
            }
        }

        if($action == 0){
            //On refuse la réservation
            $dataset = ['status' =>"Refusée" ];
            $msg = [
                    'type' => "success",
                    'message' => "La réservation à bien été refusée"

            ];
            $booking->update($bookingId,$dataset);
            return $this->getBookingRequests($msg);

        }
        
        //Si on arrive ici c'est que l'action est invalide
        return $this->sendError('Action impossible : [' .$action . '] Valeur inconnue');

    }

}
