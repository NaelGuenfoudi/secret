<?php

namespace Components\Controllers;



class AlertController extends Controller {
//Se controller traite les erreurs attrapée dans le programme et retourne une vue personalisée.

public function handleError()
    {   
        //Récupère l'erreur stockée en session et l'envoie à la vue error
        
        if(isset($_SESSION['error_msg']) && $_SESSION['error_msg'] != NULL)
        {
            //Transmet le message d'erreur à la vue error.php
            $error_msg = $_SESSION['error_msg'];
            //On set error_msg à NULL pour empecher un utilsateur d'accéder aux pages d'erreurs manuellement
            $_SESSION['error_msg'] = NULL;
            return $this->render('error.php', compact('error_msg'));

        }

        return header('Location: index');
            
    
    }

    public function handleSuccess()
    {   
        //Récupère l'erreur stockée en session et l'envoie à la vue error
        
        if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] != NULL)
        {
            //Transmet le message d'erreur à la vue error.php
            $success_msg = $_SESSION['success_msg'];
            //On set error_msg à NULL pour empecher un utilsateur d'accéder aux pages d'erreurs manuellement
            $_SESSION['success_msg'] = NULL;
            return $this->render('success.php', compact('success_msg'));

        }

        return header('Location: index');
            
    
    }
}