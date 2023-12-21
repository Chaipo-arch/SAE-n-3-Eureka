<?php
namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;
use services\UserService;
use services\ForumService;
use PDO;

/**
 * Controlleur de base du site web
 * Permet l'accés à la page connexion et a la page aprés connexion 
 */
class HomeController {

    /**
     * Constructeur de HomeControlleur on utilise une instance de UserService pour séparer les responsabilités
     */
    public function __construct(UserService $userService,ForumService $forumService) {
        $this->userService = $userService; 
        $this->forumService = $forumService;
    }

    /**
     * Méthodes de base du controlleur
     * Appel la page connexion
     */
    public function index($pdo) {
        // Cas particulier pour le forum
        if(isset($_SESSION['nomPage']) && $_SESSION['nomPage'] == "Home") {
            return $this->toForum($pdo);
        }
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
                return $this->toForum($pdo);
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

    public function toForum($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/forum");
        $_SESSION['nomPage'] = "Home";
        $caractéristiques = $this->forumService->getForumCaracteristiques($pdo);
        $view->setVar('date',$caractéristiques['date']);
        $view->setVar('dateLimite',$caractéristiques['dateLimite']);
        $view->setVar('duree',$caractéristiques['duree']);
        return $view;
            
            
            
            
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
    public function pageNTrouve($pdo) {
        $pageNTrouve = HttpHelper::getParam('pageNTrouve');
        $view = $this->toForum($pdo);
        $view->setVar('pageNTrouve', $pageNTrouve);
        return $view;
    }
}


