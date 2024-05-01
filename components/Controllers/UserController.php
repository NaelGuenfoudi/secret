<?php

namespace Components\Controllers;

use Models\User;
use Models\City;
use Services\Validator;
class UserController extends Controller {
    private $profilImgPath ="uploads". DIRECTORY_SEPARATOR ."profil". DIRECTORY_SEPARATOR;


    public function login()
    {
        //
        $dataset = $_POST;
        if ($this->isLogIn()) {
            header('Location: index');
        }
        // On regarde si les paramètres sont bien saisis
        $error = (new Validator($dataset, 'LOGIN'))->validate();
        if ($error) {

            return $this->render('login.php', $error);
        }

        // Récupérer l'utilisateur en fonction de l'email
        $user = (new User($this->db))->getUserByMail($dataset["mail"]);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return $this->render('login.php', ['err_mail' => "Adresse associée à aucun compte"]);
        }

        // Vérifier si le mot de passe est correct
        if (password_verify($_POST["password"], $user['password'])) {
            $_SESSION['user_id'] = $user['id_user_pk'];
            $_SESSION['mail'] = $user['mail'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            
            return $this->render('index.php',array());
        } else {    

            return $this->render('login.php', ['err_password' => "Mot de passe incorrect"]);
        }
    }

    public function getLoginForm()
    {
        //Retourne le formulaire de connexion si l'utilisateur n'est pas connecté
        if (!$this->isLogIn()) {
            return $this->render('login.php');
        }

        return $this->render('index.php',array());

    }

    public function getRegistrationForm()
    //Retourne le formulaire d'inscription si l'utilisateur n'est pas connecté
    {

        if (!$this->isLogIn()) {
            return $this->render('register.php');
        } 
        return $this->render('index.php',array());
        
        }
    

    public function register()
    {
        //fonction gérant l'inscription d'un utilisateur
        if (!$this->isLogIn()) {

            $dataset = $_POST;
            //Process de validation du formulaire
            $validator = new Validator($dataset, 'REGISTER');
            $result = $validator->validate();
            if (!$result) { //si aucune erreur n'est retournée
                $user = new User($this->db);
                //On vérifie que l'adresse mail n'existe pas déjà en BDD
                if ($user->exist($dataset["mail"])) {

                    return $this->render('register.php', ["err_mail" => "Cette adresse est déjà associée à un compte"]);
                }
                //Vérification si la ville existe bien en BDD - On refuse l'inscription si c'est pas le cas pour pas polluer la BDD
                $city = (new City($this->db))->exist($dataset['city']);
                if ($city) {

                    $dataset['city'] = $city;
                } else {
                    return $this->render('register.php', ["err_city" => "Nom de ville incorrect"]);
                }
                //Gestion de l'image de profil
                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
                    $dataset['profil_image'] = $this->saveImg($_FILES['profile_image'], $dataset['mail']);
                } else {

                    //une image par default si aucune n'est téléversée
                    $dataset['profil_image'] = "default_profil.png";
                }

                //Création du compte en BDD
                $dataset['password'] = password_hash($dataset['password'], PASSWORD_DEFAULT);
                unset($dataset['password2']);
                unset($dataset['changePassword']);
                $id_user = $user->insert($dataset);

                //Connexion de l'utilisateur
                $_SESSION['user_id'] = $id_user;
                $_SESSION['firstname'] = $_POST['firstname'];
                $_SESSION['lastname'] = $_POST['lastname'];
                $_SESSION['mail'] = $_POST['mail'];
                return $this->render('index.php', ["msg" => "Votre compte à été créé avec succès."]);
            }

            //Il y a une erreur, on retourne le formulaire d'inscription avec la liste des erreurs

            return $this->render('register.php', $result);
        }
    }

    public function getMainPage()
    //permet d'accéder à la page principale si l'utilisateur est connecté
    {
        if ($this->isLogIn()) {

            return $this->render('index.php');
        }
        header('Location: connexion');
        exit();
    }

    public function logout()
    {
        if ($this->isLogIn()) {
            session_destroy();
            header('Location: connexion');
        }
    }


    public function getProfilInfos(array $message = [])
    {

        if (!$this->isLogIn()) {
            header('Location: connexion');
            exit();
        }
        $userId = $_SESSION['user_id'];

        $user = new User($this->getDBConnexion());
        $user = $user->getById($userId);
        //récupération du nom de la ville
        $city = new City($this->getDBConnexion());
        $city = $city->getById($user['city']);
        $user['city_label'] = $city['name'];
        //définition d'une section active pour le menu latéral
        $section_name = "section_profil";

        return $this->render('profil/main_section.php', compact('user', 'section_name', 'message'));
    }

    public function saveProfil()
    {
        if (!$this->isLogIn()) {
            header('Location: connexion');
            exit();
        }
        $dataset = $_POST;
        $validator = new Validator($dataset, 'PROFIL');
        $errors = $validator->validate();
        if ($errors) {
            return $this->getProfilInfos($errors);
        }

        //vérifier que la ville renseignée est présente en bdd
        $city = (new City($this->db))->exist($dataset['city']);

        if ($city) {

            $dataset['city'] = $city;
        } else {
            return $this->getProfilInfos(['err_city' => 'Ville incorrecte']);
        }
        $userId = $_SESSION["user_id"];
        $user = new User($this->getDBConnexion());
        $user->update($userId, $dataset);
        $_SESSION['firstname'] = $dataset['first_name'];
        $_SESSION['lastname'] = $dataset['last_name'];
        return $this->getProfilInfos(['success' => 'Profil modifié avec succès']);
    }

    public function testParam($id, $p2)
    //test d'une fonction qui retourne des infos dans une vue
    {
        return $this->render('profil.php', compact('id', 'p2'));
    }

    public function deleteImg($fileName)
    {

        if (!$this->isLogIn()) {
            header('Location: connexion');
            exit();
        }

        if (!$fileName or $fileName == 'default_profil.png') {
            header('Location:' . ROOT . '/mon_espace/profil');
            exit();
        }

        $imagePath = RESSOURCES . $this->profilImgPath . $fileName;
        $dataset['profil_image'] = "default_profil.png";
        if (file_exists($imagePath)) {
            // Suppression du ficher du serveur
            if (!unlink($imagePath)) {
                return $this->sendError('Une erreur est survenue lors de la suppression de votre image de profil');
            }
        } else {
            return $this->sendError('L\'mage cible n\'existe pas');
        }
        //Attribution d'une image par défaut
        $userId = $_SESSION["user_id"];
        $user = new User($this->getDBConnexion());
        $user->update($userId, $dataset);
        return header("Location:" . ROOT . "/mon_espace/profil");
    }

    private function saveImg($file, $mail)
    {



        $file_tmp_name = $file['tmp_name'];
        $file_name = $file['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png');

        if (in_array($file_ext, $allowed_extensions)) {
            //standardisation du nommage du fichier (préfixe de l'email sans caractères spéciaux encodé en hexadecimal)
            $file_name = strstr($mail, '@', true);
            $file_name = preg_replace('/[^a-zA-Z0-9]/', '', $file_name);
            $file_name = md5($file_name) . '.' . $file_ext;
            //Sauvegarde du fichier dans la $destination 

            $destination = RESSOURCES.$this->profilImgPath.$file_name; 
            //var_dump($destination);
            //exit();
            move_uploaded_file($file_tmp_name, $destination);           
                    

            $destination = RESSOURCES . $this->profilImgPath . $file_name;
            move_uploaded_file($file_tmp_name, $destination);



            //On retourne le nom de l'image
            return $file_name;
        } else {
            // Gérer les erreurs d'extension invalide
            return $this->sendError("Extension de fichier non autorisée. Veuillez télécharger une image au format JPG, JPEG, PNG");
        }
    }

    public function changePassword()
    {
        if (!$this->isLogIn()) {
            header('Location: connexion');
            exit();
        }
        $dataset = $_POST;
        unset($dataset['changePassword']);
        $validator = new Validator($dataset, 'PASSWORD');
        $errors = $validator->validate();
        //cas d'erreur sur la saisie du mot de passe
        if ($errors) {
            return $this->getProfilInfos($errors);
        }
        unset($dataset['password2']);
        $dataset['password'] = password_hash($dataset['password'], PASSWORD_DEFAULT);
        $userId = $_SESSION["user_id"];
        $user = new User($this->getDBConnexion());
        $user->update($userId, $dataset);
        return $this->getProfilInfos(['success' => 'Mot de passe modifié avec succès.']);
    }

    public function changeProfilImage()
    {
        if (!$this->isLogIn()) {
            header('Location: connexion');
            exit();
        }

        if (!isset($_FILES['profile_image'])) {

            return $this->sendError('Aucune image à modifier');
        }
        $dataset['profil_image'] = $this->saveImg($_FILES['profile_image'], $_SESSION['mail']);
        $user = new User($this->getDBConnexion());
        $user->update($_SESSION['user_id'], $dataset);
        header('Location:' . ROOT . '/mon_espace/profil');
        exit();
    }

    // Partie controleur Mobile API //

    public function registerApi()
    // Permit to register a new user by API request

    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataset = $_POST;

            // Process de validation du formulaire
            $validator = new Validator($dataset, 'REGISTER');
            $valid = $validator->validate();
            if (!$valid) { // Si aucune erreur n'est retournée
                $user = new User($this->db);
                // On vérifie que l'adresse mail n'existe pas déjà en BDD
                if ($user->exist($dataset["mail"])) {
                    // Return hhtp error code 409 Conflict - User Already Exist
                    http_response_code(409);
                    header('Content-Type: application/json');
                    $error = array(
                        'error' => 'Conflict'
                    );
                    return json_encode($error);
                }
                // Vérification si la ville existe bien en BDD - On refuse l'inscription si c'est pas le cas pour pas polluer la BDD
                $city = (new City($this->db))->exist($dataset['city']);
                if ($city) {
                    $dataset['city'] = $city;
                } else {
                    // Return http error code 406 Not Acceptable - city name
                    http_response_code(406);
                    header('Content-Type: application/json');
                    $error = array(
                        'error' => 'Not Acceptable City Name'
                    );
                    return json_encode($error);
                }
                // Gestion de l'image de profil
                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
                    $dataset['profil_image'] = $this->saveImg($_FILES['profile_image'], $dataset['mail']);
                } else {
                    // une image par default si aucune n'est téléversée
                    $dataset['profil_image'] = "default_profil.png";
                }

                // Création du compte en BDD
                $dataset['password'] = password_hash($dataset['password'], PASSWORD_DEFAULT);
                unset($dataset['password2']);
                unset($dataset['changePassword']);
                $id_user = $user->insert($dataset);

                // Return http error code 201 Created
                http_response_code(201);
                header('Content-Type: application/json');
                $error = array(
                    'message' => 'Created'
                );
                return json_encode($error);
            } else {
                // Return http error code 406 Not Acceptable - Data
                http_response_code(406);
                header('Content-Type: application/json');
                $error = array(
                    'error' => 'Not Acceptable Data'
                );
                return json_encode($error);
            }
        } else {
            // Return http error code 405 Method Not Allowed
            http_response_code(405);
            header('Content-Type: application/json');
            $error = array(
                'error' => 'Method Not Allowed'
            );
            return json_encode($error); /* Error : 405 - Invalid Method */
        }
    }

    public function loginApi()
    // Permit to login a user by API request
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataset = $_POST;

            // On regarde si les paramètres sont bien saisis
            $valid = (new Validator($dataset, 'LOGIN'))->validate();
            if (!$valid) {

                // Récupérer l'utilisateur en fonction de l'email
                $user = (new User($this->db))->getUserByMail($dataset["mail"]);

                // Vérifier si l'utilisateur existe
                if (!$user) {
                    // Return hhtp error code 409 Conflict - user already exist
                    http_response_code(409);
                    header('Content-Type: application/json');
                    $error = array(
                        'error' => 'Conflict'
                    );
                    return json_encode($error);
                }

                // Vérifier si le mot de passe est correct
                if (password_verify($_POST["password"], $user['password'])) {
                    $_SESSION['user_id'] = $user['id_user_pk'];
                    $_SESSION['mail'] = $user['mail'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];

                    // Return hhtp error code 202 Accepted
                    http_response_code(202);
                    header('Content-Type: application/json');
                    $error = array(
                        'message' => 'Accepted'
                    );
                    return json_encode($error);
                } else {
                    // Return http error code 406 Not Acceptable - Data
                    http_response_code(406);
                    header('Content-Type: application/json');
                    $error = array(
                        'error' => 'Not Acceptable Data'
                    );
                    return json_encode($error);
                }
            } else {
                // Return http error code 406 Not Acceptable - Data
                http_response_code(406);
                header('Content-Type: application/json');
                $error = array(
                    'error' => 'Not Acceptable Data'
                );
                return json_encode($error);
            }
        } else {
            // Return http error code 405 Method Not Allowed
            http_response_code(405);
            header('Content-Type: application/json');
            $error = array(
                'error' => 'Method Not Allowed'
            );
            return json_encode($error); /* Error : 405 - Invalid Method */
        }
    }
}
