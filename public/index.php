<?php
//Chargement de l'autoloader
require '../vendor/autoload.php';


use Router\Router;
//Une constante pour identifier le dossier racine du site
//Modifier le 30-03-2024 pour prendre un compte un virtualhost
if ($_SERVER['HTTP_HOST'] == 'kovoit') {
    define('ROOT', 'http://' . $_SERVER['HTTP_HOST']);
} else {
    define('ROOT', 'http://' . $_SERVER['HTTP_HOST'] . '/kovoit');
}
//Une constante pour identifier le dossier racine du site
//ligne pour virtualhost 'kovoit'
//define('ROOT', 'http://' . $_SERVER['HTTP_HOST'] );
//ligne localhost

//define('ROOT', 'http://' . $_SERVER['HTTP_HOST']. '/kovoit' );

//On créer une constante pour le répertoire où se trouve les vues
//DIRECTORY_SEPARATOR = variable d'environnement qui dépend du système d'exploitation
define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);

//On créer une constante également pour les scripts (CSS, JS)
define('SCRIPTS', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR);
//Dossier ressources
define('RESSOURCES', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'ressources' . DIRECTORY_SEPARATOR);
//A faire 

//Déclarer les variables de connexion à la BDD ici ?

//La réécriture d'URL (voir .htaccess) permet de retourner la requete saisie par l'utilsateur sous forme d'un paramètre 'url'. Ce paramètre est ensuite envoyé au Routeur afin de vérifier si une route correspond à la requet ede l'utilisateur
//Si une route correspond on appelle une fonction bien précise du controlleur souhaité.
$router = new Router($_GET['url']);
//Insérer les nouvelles routes ici. Que des GET pour le moment
//Les paramètres d'URL doivent être précédés de ":" pour être bien identifiable
//Pour identifier la fonction à appeler il faut renseigner en second paramètre la localisation du contrôleur et la fonction souhaité
//sous la forme 'controller@function'. La traitement en aval est plus simple en regroupant ces 2 infos en 1 (moins de paramètres $ transfèrer)

//ROUTES WEB

$router->get('/profil/:id/:p2', 'Components\Controllers\UserController@testParam');

//Page accueil d'un utilisateur connecté
$router->get('/index', 'Components\Controllers\UserController@getMainPage');
$router->get('/', 'Components\Controllers\UserController@getMainPage');

//User:Authentification

//User:Connexion
$router->get('/connexion', 'Components\Controllers\UserController@getLoginForm');
$router->post('/connexion', 'Components\Controllers\UserController@login');
$router->get('/deconnexion', 'Components\Controllers\UserController@logout');

//User:Inscription
$router->get('/inscription', 'Components\Controllers\UserController@getRegistrationForm');
$router->post('/inscription', 'Components\Controllers\UserController@register');

//Route dédié à l'affichage des messages d'erreur - Pour l'utiliser passez un code d'erreur en paramètre
//Pensez à déclarer le code d'erreur dans le tableau d'erreur du ErrorController avec un message personalisé
//23/03/2024 - Le controlleur error devient alert afin de pouvoir afficher aussi des bien des messages d'erreurs que des message de confirmation
$router->get('/error', 'Components\Controllers\AlertController@handleError');
$router->get('/success', 'Components\Controllers\AlertController@handleSuccess');

//Espace personnel
//section vehicule
$router->get('/mon_espace/vehicule', 'Components\Controllers\VehicleController@getVehicleList');
$router->get('/mon_espace/vehicule/ajouter', 'Components\Controllers\VehicleController@getAddVehicleForm');
$router->post('/mon_espace/vehicule/ajouter', 'Components\Controllers\VehicleController@addVehicle');
$router->get('/mon_espace/vehicule/supprimer/:id', 'Components\Controllers\VehicleController@deleteVehicle');
$router->get('/mon_espace/vehicule/modifier/:id', 'Components\Controllers\VehicleController@getVehiculeFormToModify');
$router->post('/mon_espace/vehicule/sauvegarder/:id', 'Components\Controllers\VehicleController@updateVehicle');

//section profil
//$router->get('/mon_espace/profil','Components\Controllers\UserController@aaa');
$router->get('/mon_espace/profil', 'Components\Controllers\UserController@getProfilInfos');
$router->post('/mon_espace/profil/sauvegarder/adresse', 'Components\Controllers\UserController@saveAddress');
$router->get('/mon_espace/profil/supprimer/img/:fileName', 'Components\Controllers\UserController@deleteImg');
$router->post('/mon_espace/profil/sauvegarder/img', 'Components\Controllers\UserController@changeProfilImage');
$router->post('/mon_espace/profil/sauvegarder', 'Components\Controllers\UserController@saveProfil');
$router->post('/mon_espace/profil/sauvegarder/mdp', 'Components\Controllers\UserController@changePassword');
 
//section reservation
$router->get('/mon_espace/reservations', 'Components\Controllers\BookingController@getBookingList');
$router->get('/mon_espace/reservations/:all', 'Components\Controllers\BookingController@getBookingList');

//section trajet
$router->get('mon_espace/trajets','Components\Controllers\RideController@renderRidesUser');
$router->get('mon_espace/trajet/ajouter','Components\Controllers\RideController@getAddRideForm');
$router->post('mon_espace/trajet/ajouter','Components\Controllers\RideController@addRide');
//Recherche de trajet

$router->post('/rechercher','Components\Controllers\RideController@validSearch');
$router->get('/rechercher','Components\Controllers\RideController@returnToSearch');
$router->get('recherche/trajet/:id/:search','Components\Controllers\RideController@consultRidePage');
//Réservation

//$router->get('/recherche/trajet/:id','Components\Controllers\BookingController@getBookingForm');
$router->post('/reserver/:id','Components\Controllers\BookingController@addBooking');
$router->post('/reserver/modifier/:bookingId','Components\Controllers\BookingController@updateBooking');
$router->get('/reservations/supprimer/:ride/:booking','Components\Controllers\BookingController@deleteBooking');
//$router->get('/reservations/modifier/:bookingId','Components\Controllers\BookingController@getUpdateBookingForm');
$router->get('/reservations/traiter/:action/:bookingId','Components\Controllers\BookingController@askToBookingRequest');
//Demande - liste des dmeande de réservation à traiter
$router->get('/mon_espace/demandes','Components\Controllers\BookingController@getBookingRequests');


//Trajet
$router->get('/trajet/:id','Components\Controllers\RideController@consultRidePage');


//Messages
$router->post('/message/envoyer', 'Components\Controllers\MessageController@handleMessage');



//ROUTES API MOBILE
$router->post('/api/user/registerUser', 'Components\Controllers\UserController@registerApi');
$router->post('/api/user/loginUser', 'Components\Controllers\UserController@loginApi');









//Launch router
$router->run();

