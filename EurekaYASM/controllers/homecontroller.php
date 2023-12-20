<?php
namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;
use services\UserService;
use PDO;

/**
 * Controlleur de base du site web
 * Permet l'accés à la page connexion et a la page aprés connexion 
 */
class HomeController {

    /**
     * Constructeur de HomeControlleur on utilise une instance de UserService pour séparer les responsabilités
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService; 
    }

    /**
     * Méthodes de base du controlleur
     * Appel la page connexion
     */
    public function index($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/connexion");
        $view->setVar('problemeDonnees',false);
        $view->setVar('tentativeConnection',false);
        return $view;
    }

    /**
     * Méthodes appelé lors d'une tentative de connexion
     * Si les informations données correspondent à un utilisateur envoient vers la page de base du forum
     * Sinon vers la page connexion
     */
    public function tentativeConnexion($pdo) {
        $login = HttpHelper::getParam('identifiant');
        $pwd = HttpHelper::getParam('pwd');
        try{ // Bloc try bd injoignable 
            $connexionReussi = $this->userService->verifUtilisateur($pdo,$login,$pwd);
            
            if($connexionReussi) {
                if($_SESSION['role'] == 'Etudiant')  {
                    $this->userService->getSouhait($pdo,$_SESSION['IdUser']);
                }
                $view = new View("SaeWeb/EurekaYASM/views/forum");
                //$view->setVar('nomPage',"forum");
                $_SESSION['nomPage'] = "Home";
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
        return $this->index($pdo);
     }
    public function renvoi($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/connexion");
        return $view;
    }

}


