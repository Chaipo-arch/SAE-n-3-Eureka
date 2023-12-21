<?php
namespace controllers;

use yasmf\View;
use yasmf\HttpHelper;
use PDO;
use controllers;
use services\AdminService;
use services\UserService;
use services\EntrepriseService;
use services\FiliereService;
use services;
use services\ForumService;
class AdminController {

    /** 
     * Constructeur du controleur admin on instancie les services qui sont utilisées
    */
    public function __construct(AdminService $adminService,ForumService $forumService) {
        $this->adminService = $adminService;
        $this->forumService = $forumService;
    }

    public function index($pdo) {
        header('Location: index.php');
        exit();;
    }

    public function ajouterEntreprise($pdo) {
        // TODO utilité de créer un controller dans un controller 
        $en = new EntrepriseController(new EntrepriseService,new FiliereService, new UserService);
        return $en->index($pdo);
    }

    public function modifierEntreprise($pdo) {
        $en = new EntrepriseController(new EntrepriseService,new FiliereService, new UserService);
        return $en->index($pdo);
    }

    public function toForum($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/forumA");
        $caractéristiques = $this->forumService->getForumCaracteristiques($pdo);
        $view->setVar('date',$caractéristiques['date']);
        $view->setVar('dateLimite',$caractéristiques['dateLimite']);
        $view->setVar('duree',$caractéristiques['duree']);
        return $view;
    }
    /** 
     * Fonction qui permet à l'admin de modifier les caractéristiques du forum
    */
    public function modifierForum($pdo) {
        $edition = HttpHelper::getParam('edition') ?: false;
        $date= HttpHelper::getParam('date') ?: "";
        $dateLimite= HttpHelper::getParam('dateLimite') ?: "";
        $duree= HttpHelper::getParam('duree') ?: "";
        $view = new View("SaeWeb/EurekaYASM/views/forumA");
        if($edition) {
            $this->adminService->ModifierForumCaracteristiques($pdo,$date,$dateLimite,$duree);
        }
        
        $view->setVar('date',$date);
        $view->setVar('dateLimite',$dateLimite);
        $view->setVar('duree',$duree);
        return $view;
    }


}
