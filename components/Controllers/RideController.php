<?php

namespace Components\Controllers;
use Models\Booking;
use Models\Ride;
use Models\City;
use Models\User;
use Models\Vehicle;
use Models\Message;
use DateTime;
use Services\Validator;

class RideController extends Controller {

    public function validSearch(){
        /**Cette fonction contrôle la saisie du formulaire de recherche. Si le formulaire est bien complété, la recherche est lancée */
        if(!$this->isLogIn()){
            header('Location:'.ROOT.'/connexion');
            exit();
        }
        $userRequest = $_POST;
        //Verifie si une ville de départ et de destination ont bien été saisie
        if(!isset($userRequest['startCity']) || $userRequest['startCity'] ==''|| !isset($userRequest['endCity'])|| $userRequest['endCity'] ==''){
            return $this->render('index.php',['searchMsg'=>"Veuillez saisir une ville de départ et d'arrivée"]);
        }
        //On récupére les identifiants des villes demandées par l'utilisateur. Si la ville n'existe pas on attribue la valeur "-" par défault
        //On stocke les informatio nde recherche en session pour pouvoir s'en reservir sans redemander à l'utilateur une nouvelle saisie
        $_SESSION['currentSearch'] = array();
        //Ville de départ
        $city = new City($this->getDBConnexion());
        $cityId = $city->exist($userRequest['startCity']);
        if($city){
            $_SESSION['currentSearch']['startCityName'] = $userRequest['startCity'];
            $_SESSION['currentSearch']['startCity'] = $cityId;
        }else{
            $_SESSION['currentSearch']['startCityName'] = '-';
            $_SESSION['currentSearch']['startCity'] = '-';
        }
        
        //ville d'arrivée
        $cityId = $city->exist($userRequest['endCity']);
            if($city){
                $_SESSION['currentSearch']['endCityName'] = $userRequest['endCity'];
                $_SESSION['currentSearch']['endCity'] = $cityId;
                
            }else{
                $_SESSION['currentSearch']['endCityName'] = '-';
                $_SESSION['currentSearch']['endCity'] = '-';
            }
        //date du trajet
        if(isset($userRequest['date']) && $userRequest['date'] !=''){
    
    
            $_SESSION['currentSearch']['date'] = $userRequest['date'];
                
            }else{
                $_SESSION['currentSearch']['date'] = null;
            }
            
        $search = $_SESSION['currentSearch'];
        return $this->searchRides($search);
    }

    /**
     * Renders the user's rides in a view.
     * Redirects to the login page if the user is not logged in.
     * Retrieves the user's rides using the getRidesForUser method and displays them.
     *
     * @return void Redirects or outputs the ride list view
     */
    public function renderRidesUser() {
        if (!$this->isLogin()) {
            return header("Location: " . ROOT . '/connexion');
        }

        $ride = new Ride($this->getDBConnexion());
        $userId = $_SESSION['user_id'];
        $rides = $ride->getRidesForUser($userId);
        $vehicle = new Vehicle($this->getDBConnexion());
        $user = new User($this->getDBConnexion());
        $city = new City($this->getDBConnexion());
        $booking = new Booking($this->getDBConnexion());

        foreach ($rides as &$ride) {
            $startDate = new DateTime($ride['d_start']);
            $endDate = new DateTime($ride['d_end']);
            $ride['fullDateString'] = $this->getTextDate($ride['d_start']);
            $ride['startHour'] = date("H:i", strtotime($ride['d_start']));
            $ride['endHour'] = date("H:i", strtotime($ride['d_end']));
            $dateDelta = $startDate->diff($endDate);
            $ride['travelTime'] = $dateDelta->format('%H:%I');
            $ride["vehicleInfos"] = $vehicle->getById($ride['id_vehicle_fk']);
            $ride["userInfos"] = $user->getUserInfos($ride["vehicleInfos"]['id_user_fk']);
            $ride['seats'] = $booking->getBookingCountByStatus($ride['id_ride_pk']);
            $ride['seats']['remaining_seat'] = $ride['seat_number'] - $ride['seats']['valide_count'];
            // Assuming that $params['searchParams'] are available or set somewhere in your application
            $searchParams = null;
            $searchParams['endCityName'] = $city->getName($ride["id_end_city_fk"]);
            $searchParams['startCityName'] = $city->getName($ride["id_start_city_fk"]);
        }

        $section_name = "section_rides";
        $isSearch = false;
        return $this->render('profil/main_section.php', compact('rides', 'section_name','searchParams','isSearch'));
    }

    public function getAddRideForm()
    {


        if(!$this->isLogin()){

            return header("Location: " . ROOT.'/connexion');

        }
        $action ="add";
        $vehicle = new Vehicle($this->getDBConnexion());
        $vehiclesForUser = $vehicle->getByUser( $_SESSION['user_id']);
        return $this->render('ride_form.php', compact('action','vehiclesForUser'));


    }

    public function addRide()
    {
        $vehicle = new Vehicle($this->getDBConnexion());
        $city = new City($this->getDBConnexion());
        $ride = new Ride($this->getDBConnexion());

        if(!$this->isLogin()){
            return header('Location: connexion');
        }

        $dataset = $_POST;

        // On contrôle le formulaire
        $error = (new Validator($dataset,'RIDES'))->validate();
        if ($error) {
            return $this->render('ride_form.php', compact("error"));
        }

        // Vérification de l'existence des villes de départ et d'arrivée
        if (!$city->exist($dataset['start_city']) || !$city->exist($dataset['end_city'])) {
            $error = "Les villes de départ et d'arrivée doivent être valides.";
            return $this->render('ride_form.php', compact("error"));
        }

        // Vérification du nombre de places disponibles dans le véhicule
        $vehicleInfo = $vehicle->getByUserAndName($_SESSION['user_id'], $dataset['vehicle']);
        if (!$vehicleInfo || $vehicleInfo['seat_number'] < $dataset['seat_number']) {
            $error = "Le nombre de places sélectionné dépasse la capacité du véhicule.";
            return $this->render('ride_form.php', compact("error"));
        }

        // Vérification de la validité des dates de départ et d'arrivée
        $startDate = strtotime($dataset['start_date']);
        $endDate = strtotime($dataset['end_date']);
        $currentDate = time();

        if ($startDate <= $currentDate) {
            $error = "La date de départ doit être postérieure à la date actuelle.";
            return $this->render('ride_form.php', compact("error"));
        }

        if ($endDate <= $startDate) {
            $error = "La date d'arrivée doit être postérieure à la date de départ.";
            return $this->render('ride_form.php', compact("error"));
        }

        // Limiter la description à 300 caractères
        $dataset['description'] = substr($dataset['description'], 0, 300);

        // Insérer les données dans la base de données
        $dataset['user_id'] = $_SESSION['user_id'];
        $ride->insert($dataset);

        // Redirection vers la page de gestion des trajets de l'utilisateur
        return header("Location: " . ROOT.'/mon_espace/trajets');
    }


    public function returnToSearch(){
        if(!$this->isLogIn()){
            header('Location:'.ROOT.'/connexion');
            exit();
        }
        if(isset($_SESSION['currentSearch'])){
            return $this->searchRides($_SESSION['currentSearch']);
        }
    }

    public function searchRides(array $searchParams) {
    /**Effectue une recherche de trajet à partir des critères renseigné par l'utilisateur */
    if(!$this->isLogIn()){
        header('Location:'.ROOT.'/connexion');
        exit();
    }

    $ride = new Ride($this->getDBConnexion());
    $rideList = $ride->search($searchParams);
    $vehicle = new Vehicle($this->getDBConnexion());
    $user = new User($this->getDBConnexion());
    $booking = new Booking($this->getDBConnexion());
    //Itération sur chaque trajets identifié afin d'enrichir la vue avec les bon formats de date et les horaires + la durée du trajet
    foreach ($rideList as &$ride) {
      
        $startDate = new DateTime($ride['d_start']);
        $endDate = new DateTime($ride['d_end']);
    
        $ride['startHour'] = $startDate->format('H:i');
        $ride['endHour'] = $endDate->format('H:i');
        $dateDelta = $startDate->diff($endDate);
        $ride['travelTime'] = $dateDelta->format('%H:%I');
        $ride['fullDateString'] = $this->getTextDate($ride['d_start']);

        //Recherche du véhicule associé au trajet
        $ride["vehicleInfos"] = $vehicle->getById($ride['id_vehicle_fk']);
        //Recherche de l'utilisateur associé au véhicule
        $ride["userInfos"] = $user->getUserInfos($ride["vehicleInfos"]['id_user_fk']);
        //Recherche du nombre de réservation pour ce trajet
        $ride['seats'] = $booking->getBookingCountByStatus($ride['id_ride_pk']);
        $ride['seats']['remaining_seat'] = $ride['seat_number'] - $ride['seats']['valide_count'];
    }
        $isSearch = true;
   


    return $this->render('search-result.php',compact('rideList','searchParams','isSearch'));






}

public  function consultRidePage($rideId,$search=false){
    /**Certte fonction récupère les informations d'u ntrajet et les retourne à l'utilisateur. Cette foncton retourne des ifnos en fonction du status de l'utilisateur
     * Si l'utilisateur consulte le trajet il n'a que les infos essentielles
     * Si l'tilisateur possède une réservation sur le trajet i lpeux la mmodifier
     * Si l'utilsateur est passagé du trajet il peut consulter la liste des passagers et les infos complète du trajet
     * $search permet de préciser si le trajet est consulté depuis un résultat de recherche. Permet d'ajouter une option de retour à la recherche    */
    if(!$this->isLogIn()){
        header('Location:'.ROOT.'/connexion');
        exit();
    }
    
     
    $booking = new Booking($this->getDBConnexion());
    $ride = new Ride($this->getDBConnexion());
    $rideInfos = $ride->getRideInfos($rideId); 
    //cas si le trajet n'existe pas
    if(!$rideInfos){
        $this->sendError("Erreur : Trajet introuvable");
    }
    //récupération du nombre de place 
    $rideInfos['seats'] = $booking->getBookingCountByStatus($rideId);
    $rideInfos['seats']['remaining_seat'] = $rideInfos['seat_number'] - $rideInfos['seats']['valide_count'];
    //formatage des dates
    $rideInfos['title'] = $this->getTextDate($rideInfos['d_start']);

    //Récupération des messages associés au trajet :
    $message = new Message($this->getDBConnexion());
    $messageList = $message->getByRide($rideId);

   
    //Définition du niveau de visibilité
        //On vérifie si l'utilsateur aura accès au détails sur le trajet (soit il est propriétaire soit il a une réservation validée)
    // $accessLevel défini le niveau de visibilité de l'utilisateur (0 aucune détail 1- aucun détail mais peux voir l'état de sa réservation 2- peu voir les membres d'un trajet 3- peux voir les coordonnées de tout le monde)



    $userId = $_SESSION['user_id'];
    if($rideInfos['id_owner'] == $userId){
        $accessLevel = 3;
        $bookingList = $booking->getBookingList($rideId);
        return $this->render('consult_ride.php',compact('rideInfos','bookingList','accessLevel','search','messageList'));
    }

     //On vérifie que l'utilsateur possède une réservation sur ce trajet 

    $userBooking = $booking->getByUser($userId,$rideId);
    if(!$userBooking){
        $accessLevel = 0;
        return $this->render('consult_ride.php',compact('rideInfos','accessLevel','search'));
    }


    if($userBooking['status'] != "Validée"){
        $accessLevel = 1;
        return $this->render('consult_ride.php',compact('rideInfos','userBooking','accessLevel','search'));
    }else{
        $accessLevel = 2;
        $bookingList = $booking->getBookingList($rideId,"Validée");
        return $this->render('consult_ride.php',compact('rideInfos','userBooking','bookingList','accessLevel','search','messageList'));
    }

    
    //récupération des autres réservations
 


}
}




?>