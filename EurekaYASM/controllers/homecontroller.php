<?php
namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;
use services\UserService;
use PDO;

class HomeController {


    public function __construct(UserService $userService) {
        $this->userService = $userService; 
    }

   /* public function __construct() {
        //$this->userService = $userService; 
    }*/

    public function index($pdo) {
       
        $view = new View("SaeWeb/EurekaYASM/views/connexion");
        $view->setVar('problemeDonnees',false);
        $view->setVar('tentativeConnection',false);
        return $view;
    }

    public function tentativeConnexion($pdo) {
        var_dump($_POST);
        $login = HttpHelper::getParam('identifiant');
        $pwd = HttpHelper::getParam('pwd');
        try{ // Bloc try bd injoignable 
            $connexionReussi = $this->userService->verifUtilisateur($pdo,$login,$pwd);
            var_dump($_SESSION);
            if($connexionReussi) {
                
                $view = new View("SaeWeb/EurekaYASM/views/forum");
                return $view;
            } 
            $view = new View("SaeWeb/EurekaYASM/views/connexion");
            $view->setVar('problemeDonnees',false);
            $view->setVar('tentativeConnection',true);
            $view->setVar('login',$login);
            $view->setVar('pwd',$pwd);
            return $view;
        }catch ( Exception $e ) {
                $view = new View("SaeWeb/EurekaYASM/views/connexion");
                $view->setVar('problemeDonnees',true);
                $view->setVar('tentativeConnection',true);
                $view->setVar('messageRetour',"La base de donnée n'est actuellement pas disponible");
                return $view;
            } 
            
    }
    public function deconnexion($pdo) {
        $_SESSION['connecte'] = false;
        session_destroy();
        $view = new View("SaeWeb/EurekaYASM/views/connexion");
        $view->setVar('problemeDonnees',false);
        $view->setVar('tentativeConnection',false);
        return $view;
     }
     public function retour($pdo) {
        
        $view = new View("SaeWeb/EurekaYASM/views/forum");
        return $view;
    }

}


