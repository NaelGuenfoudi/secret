<?php

namespace Router;

use Database\DBConnexion;


class Route
{
    // Cette classe modélise une route avec sont chemin d'accés (URL), son verbe (GET, POST)
    public $path;
    public $action;
    public $matchRoute;

    public function __construct($path, $action)
    {

        $this->path = trim($path, '/');
        $this->action = $action;
    }

    public function matchRoute($url)
    {

        //Cette fonction vérifie si l'URL en paramètre (celle de l'utilisateur) correpond avec le chemin de la route
        //Le preg_replace permet de transformer l'url de l'utilisateur en regex.
        //On recherche les paramètres (pattern commençant par ":" suivi de n caractère alphanumérique)
        //On les rempla
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);

        $pathToMatch = "#^$path$#";
        if (preg_match($pathToMatch, $url, $matchRoute)) {
            $this->matchRoute = $matchRoute;
            return true;
        } else {
            return false;
        }
    }

    public function execute()
    {
        //Permet d'initier le traitement défini dans une route

        $params = explode('@', $this->action); // On sépare le chemin du controlleur de sa methode
        $controller = new $params[0](new DBConnexion('db_kovoit', 'localhost', 'root', '')); //on instancie le controlleur défini dans la route.
        $method = $params[1];

        if (isset($this->matchRoute)) {
            //Cas où il y a plusieurs paramètres
            if (count($this->matchRoute) > 1) {
                return $controller->$method(...array_slice($this->matchRoute, 1)); // le array slice permet de retirer l'url pour ne garder que les paramètres
            } else {
                return $controller->$method();
            }
        }
    }
}
