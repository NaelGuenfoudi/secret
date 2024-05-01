<?php

namespace Components\Controllers;
use Models\Color;
use Services\Validator;
use Models\Vehicle;
use Models\Ride;

class VehicleController extends Controller {

public function addVehicle()
{
    if(!$this->isLogin()){
        return header('Location: connexion');
    }

    $dataset = $_POST;


    // On contrôle le formulaire
    $error = (new Validator($dataset,'VEHICLE'))->validate();
    if ($error) {
        return $this->render('vehicle_form.php', compact("error")); 
    }

    
    $dataset['user_id'] = $_SESSION['user_id'];
    $vehicle = new Vehicle($this->getDBConnexion());
    $vehicle->insert($dataset);
    //renvoi vers l'index pour le moment, devra rediriger vers la section mes vehicules de l'espace personnel
    return header("Location: " . ROOT.'/mon_espace/vehicule');

}


public function getAddVehicleForm()
{
    if(!$this->isLogin()){
        
        return header("Location: " . ROOT.'/connexion');
        
    }
    $action ="add";
    return $this->render('vehicle_form.php', compact('action'));

    
}

public function getVehicleList(){
    if(!$this->isLogin()){
        return header("Location: " . ROOT.'/connexion');
    }

    //Récupérer la liste des véhicules
    $vehicle = new Vehicle($this->getDBConnexion());
    $userId =$_SESSION['user_id'];
    $vehicleList = $vehicle->getByUser($userId);
    
    $section_name = "section_vehicle";
    
    return $this->render('profil/main_section.php',compact('vehicleList','section_name'));
}

public function deleteVehicle($id){
    /**Gère le traitenement d'un véhicule lorsque un utilisateur veut le supprimer */
    if(!$this->isLogin()){
        return header("Location: " . ROOT.'/connexion');
    }
    $vehicle = new Vehicle($this->getDBConnexion());
    //On vérifie que le véhicule appartient bien à l'utilisateur connecté
    $vehicleInfos = $vehicle->getById($id);
    $userId = $_SESSION['user_id'];
    if($userId != $vehicleInfos['id_user_fk']){
        return $this->sendError("Vous n'avez pas le droit de modifier ce véhicule");
    }
    //On vérifie si le véhicule est associé à un trajet

    $vehicleRideCount = $vehicle->vehicleRideCount($id);

    if($vehicleRideCount > 0){
        //Sil le véhicule à déjà été utilsié on ne le supprime pas mais on l'archive en l'anonymisant
        $dataset = [
            "name" => "Ancien véhicule",
            "color" => 1,
            "status" => 0
        ];
        $vehicle->update($id,$dataset);
    }else{
        $vehicle->delete($id);
    }


    header('Location: '.ROOT.'/mon_espace/vehicule');
    exit();
    
}
    public function userCanModifyVehicle($id){
        /**Cette fonction vérifie si le véhicle est modifiable
         * retourne les infos du véhicule si c'est le cas sinon redirige vers une page d'erreur
         */
        $vehicle = new Vehicle($this->getDBConnexion());
        $vehicleInfos = $vehicle->getById($id);
    
        //Vérification de l'existance du véhicule
        if(!$vehicleInfos){
    
            return $this->sendError("Le véhicule n'existe pas");
        }
        //verifier que l'utilisateur connecté soit bien propriétaire du véhicule
        if($_SESSION['user_id'] != $vehicleInfos['id_user_fk'])
        {
    
            return $this->sendError("Vous n'avez pas le droit de modifier ce véhicule");
        }
           //On vérifie que le vehicule ne soit pas associé à un trajet
        $vehicleRideCount = $vehicle->vehicleRideCount($id);

        if($vehicleRideCount > 0){
            return $this->sendError("Véhicule associé à un trajet : Vous ne pouvez pas le modifier");
            
        }
        return $vehicleInfos;

    }
public function getVehiculeFormToModify($id){
    if(!$this->isLogin()){
        return header('Location:'.ROOT.'/connexion');
    }

    $vehicleInfos = $this->userCanModifyVehicle($id);

 
    $color= new Color($this->getDBConnexion());
    $vehicleInfos['color_name'] =  $color->getColor($vehicleInfos['color']);
    $action = "modify";
     
   

    return $this->render('vehicle_form.php',compact('vehicleInfos','action'));
}

public function updateVehicle($id){
/*Contrôle le formulaire de modification d'un véhicule avant de faire une modification */

    if(!$this->isLogin()){
        return header('Location:'.ROOT.'/connexion');
    }

    $vehicleInfos = $this->userCanModifyVehicle($id);
    $dataset = $_POST;
    // On contrôle le formulaire
    $error = (new Validator($dataset,'VEHICLE'))->validate();
    $dataset =[
        'name' => $_POST['model'],
        'color' => $_POST['color'],
        'seat_number' => $_POST['seat_number']

    ];

    if ($error) {
        return $this->sendError("Une erreur est survenue lors de l'enregistrement de vos modification");
    }
    $vehicle = new Vehicle($this->getDBConnexion());
    $vehicle->update($id, $dataset);
    return $this->sendSuccess('Votre Véhicule à bien été modifié');


}


public function getVehicleListJson($userId){
    
    
        $vehicle = new Vehicle($this->getDBConnexion());
        
        $vehicleList = $vehicle->getByUser($userId);
        
        header('Content-Type: application/json');

        // Convertissez les données en JSON et retournez-les
        echo json_encode($vehicleList);
        
    

}


}