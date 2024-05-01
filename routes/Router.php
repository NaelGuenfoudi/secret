<?php

namespace Router;

require_once 'Route.php';
class Router
{

    // Cette classe permet d'initialiser et lancer la recherche d'une correspondance entre l'URL demandé par l'utilisateur et les routes de notre projet.
    // Si une route est identifiée -> Le controlleur et sa fonction désigné dans la définition de la route est appelé. 
    public $url; //url souhaité par l'utilisateur
    public $routes = []; //la liste des routes du projet. Ce tableau sépare les requetes GET et POST dans 2 index distinct
    
    public function __construct($url)
    {
        $this->url = trim($url, '/');
    }

    public function get($path, $action)
    //Ajoute toute les routes du projet dans  le tableau $routes à l'index GET
    {
        $this->routes['GET'][] = new Route($path, $action);
    }

    public function post($path, $action)
    //Ajoute toute les routes du projet dans  le tableau $routes à l'index POST
    {
        $this->routes['POST'][] = new Route($path, $action);
    }

    public function run()
    {
        // C'est cette fonction qui va itérer sur toute les routes du projet afin de rechercher une correspondance avec l'URL de l'utilisateur.
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->matchRoute($this->url)) {
                return $route->execute();
            }
        }
        return header('HTTP/1.0 404 Not Found');
    }
    //public function show() {
    //     echo $this->url;
    //  }

}