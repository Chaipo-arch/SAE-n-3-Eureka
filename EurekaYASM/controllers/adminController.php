<?php
namespace controllers;

use yasmf\View;
use PDO;
use controllers;
use services\AdminService;
use services\UserService;
use services\EntrepriseService;
use services\FiliereService;
use services;
class AdminController {


    public function __construct(AdminService $adminService) {
        $this->adminService = $adminService;
    }
    public function index($pdo) {
        $en = new HomeController(new UserService);
        return $en->index($pdo);
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


}
