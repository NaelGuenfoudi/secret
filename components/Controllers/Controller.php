<?php
namespace Components\Controllers;
use Database\DBConnexion;
class Controller{
//Cette classe permet de gérer le fonctionalités globale de tout nos controlleur. Notamment la gestion de l'affichage et la connexion à la base de donnée.
    protected $db;

    public function __construct(DBConnexion $db){
        //A l'instanciation d'un contrôleur on ouvre une connexion à la base de donnée
        $this->db = $db;
        session_start();


    }
    protected function sendError($error)
    {
        //Cette fonction stock en session une erreur attrapée par un controlleur et redirige vers la page d'erreur pour l'afficher
 
        $_SESSION['error_msg'] = $error;
        header("Location: " . ROOT.'/error');
        exit();

    }
    protected function sendSuccess($msg)
    {
        //Cette fonction stock en session une erreur attrapée par un controlleur et redirige vers la page d'erreur pour l'afficher

        $_SESSION['success_msg'] = $msg;
        header("Location: " . ROOT.'/success');
        exit();

    }
    protected function isLogIn(){
        //vérifie si l'utilisateur est identifié

        if (isset($_SESSION['mail'])) {
            return true;
        } else {
            return false;
        }
        
    }

    protected function getDBConnexion (){
        //Getter renvoyant la connexion à la  BDD
        return $this->db;
    }

    public function render($path, array $params = null)
    //fonction permettant d'enrichir les vues avec nos données.
    //path = chemin de la vue 
    //params tableau des données à insérer dans la vue
    //Params est un tableau pour pouvoir récupérer plusieurs données à afficher. bien faire attention à retourner un tableau même si vous avez qu'une donnée à afficher (utliser "compact")
    {
        //buffering pour mettre en attente le contenu avant de le retourner
        ob_start();
        
        require VIEWS . $path;
        if($params){
 
            $params = extract($params);

        }
        $content = ob_get_clean();
        require VIEWS . 'layout.php';
    }
    
    public function getData(array $params = null){
        //fonction pour retourner des données brut nottamment pour l'application mobile

        if($params){
            header("Content-Type : JON");
        }

    }

    function getTextDate($dateString) {
        // days de la semaine
        $days = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
        
        // months de l'année
        $months = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        
        // Convertir la date en timestamp
        $timestamp = strtotime($dateString);
        
        // Extraire les éléments de la date
        $dayOfTheWeek = $days[date("w", $timestamp)];
        $day = date("j", $timestamp);
        $monthNum = date("n", $timestamp);
        $year = date("Y", $timestamp);
        
        // Formater la date
        $finalDate = $dayOfTheWeek . " " . $day . " " . $months[$monthNum] . " " . $year;
        
        return $finalDate;
    }
    



}