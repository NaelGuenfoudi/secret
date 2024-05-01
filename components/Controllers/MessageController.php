<?php

namespace Components\Controllers;
use Models\Booking;
use Models\Ride;
use Models\Message;

class MessageController extends Controller {


    public function handleMessage(){
        /**Enregistre le message de l'utilisateur */
        $rideId = $_POST['rideId'];
        $msg = $_POST['message'];
        $userId = $_SESSION['user_id'];  
        if (!$this->isLogIn()) {
            header('Location: connexion');
            exit();
        }

        $booking = new Booking($this->getDBConnexion());
        $ride = new Ride($this->getDBConnexion());
        $rideInfos = $ride->getRideInfos($rideId); 
     
        //On vérifie si l'utilsiateur est le proprétaire du trajet
        if($rideInfos['id_owner'] == $userId){
            return $this->sendMessage($msg,$rideId,$userId);
        }else{
        //On vérifie que l'utilisateur possède une réservation sur validée sur ce trajet
            $userBooking = $booking->getByUser($userId,$rideId);
            if(!$userBooking || $userBooking['status'] != "Validée"){
           
                return $this->sendError("Vous n'avez pas le droit d'envoyer de message sur ce trajet");
            }else{
                return $this->sendMessage($msg,$rideId,$userId);
            }
        }   

    }

    public function sendMessage($msg,$rideId,$userId){
        //Le message ne doit pas dépasser 500 caractères
        $message = substr($msg,0,500);
        var_dump($msg);
        $message = new Message($this->getDBConnexion());
        $result =$message->insert($msg,$rideId,$userId);
        if($result){
            header("Location: ".ROOT."/trajet/" . $rideId);
            exit();
        }

        
    }
}