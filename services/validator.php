<?php
namespace Services;
class Validator{
//Validateur de formulaire générique
    private $data;
    private $rule;
    private $errors = array();
    
    private $rules = [
    //Chaque formulaiure doit être déclaré ici avec les règles de contrôle à appliquer
    //required : le champ est requis
    //format : renseigner un regex qui sera utilisée pour vérifier le format du champ.
    //match: donner un champs qui doit être égal au champ cible (les mdp d'inscription par exemple)
    //D'autres contrôle peuvent être ajouté au besoin
    //Point d'amélioration : Faire des fonctions séparés pour chaque contrôle - Mettre en place un formatage des données ? ex: des champs en majuscules, des dates sur un format spécifique

        "REGISTER"=>[
            "firstname"=> ["required"=>true,],
            "lastname"=>["required"],
            "gender" => ["required"=>true,"format"=>"#^([MFA])$#"],
            "address" => ["required"=>true],
            "city" => ["required"=>true],
            "mail" => ["required"=>true, "format"=> "#.{0,}(cesi|viacesi).fr#"],
            "phone" => ["required"=>true,"format"=> "#^[0-9]+$#"],
            "password"=> ["required"=>true,"format"=> "#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#"],
            "password2"=> ["required"=>true,"format"=> "#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#", "match"=>"password2"]
        ],
        "PROFIL"=>[
            "first_name"=> ["required"=>true,],
            "last_name"=>["required"],
            "gender" => ["required"=>true,"format"=>"#^([MFA])$#"],
            "address" => ["required"=>true],
            "city" => ["required"=>true],
            "mail" => ["required"=>true, "format"=> "#.{0,}(cesi|viacesi).fr#"],
            "phone" => ["required"=>true,"format"=> "#^[0-9]+$#"]
        ],
        "LOGIN"=>[
            "mail"=>["required"=>true],
            "password"=>["required"=>true]

        ],
        "VEHICLE"=>[
            "model" =>["required"=>true],
            "color" =>["required"=>true],
            "seat_number"=>["required"=>true,"format"=>"#[1-9]#"]
        ],
        "ADDRESS"=>[
            "address" => ["required"=>true],
            "city" => ["required"=>true]  
        ],
        "PASSWORD"=>[
            "password"=> ["required"=>true,"format"=> "#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#"],
            "password2"=> ["required"=>true,"format"=> "#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$#", "match"=>"password2"]
            ],
        "RIDES" => [
            "start_city" => ["required" => true],
            "end_city" => ["required" => true],
            "vehicle" => ["required" => true],
            "seat_number" => ["required" => true, "format" => "#^[1-9]$|^[1-9][0-9]$#"],  // Assuming seat number is between 1 to 99
            "start_date" => ["required" => true],  // Date format validation can be added if needed
            "end_date" => ["required" => true],  // Date format validation can be added if needed
            "description" => ["required" => false]  // Optional field
        ]
    ];

    public function __construct($data,$rule){
        $this->data = $data;
        $this->rule = $rule;
        
    }
    
    private function isRequired($fieldName){
        if (!isset($this->data[$fieldName]) || empty($this->data[$fieldName])) {
            $this->errors['err_'.$fieldName] = "Le champ est requis";

            return false;
        }

        return true;
    }

    public function validate() {
        // Cette fonction vérifie si les champs renseignés dans un formulaire sont conformes aux règles de gestion déterminées dans $rules
        foreach ($this->rules[$this->rule] as $fieldName => $fieldRules) {
            // Vérifier si le champ est requis
            if (!$this->isRequired($fieldName)) {
                $this->errors['err_'.$fieldName] ="Le champ est requis";
                continue;
            }
            
            // Vérifier les règles de format
            if (isset($this->data[$fieldName]) && isset($fieldRules['format'])) {
                if (!preg_match($fieldRules['format'], $this->data[$fieldName])) {
                    $this->errors['err_'.$fieldName] ="Format incorrect";
                }
            }
    
            // Vérifier les règles de correspondance
            if (isset($this->data[$fieldName]) && isset($fieldRules['match'])) {
                if($this->data[$fieldName] != $this->data[$fieldRules['match']]){
                    $this->errors['err_'.$fieldName] ="Doit être identique";
                }
            }
        }
        return $this->errors;
    }

}
