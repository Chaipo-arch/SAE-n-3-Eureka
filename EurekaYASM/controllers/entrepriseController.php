<?php
namespace controllers;

use yasmf\View;
use PDO;
use services\EntrepriseService;

class EntrepriseController {


    public function __construct(EntrepriseService $entrepriseService) {
        $this->entrepriseService = $entrepriseService;
    }

    public function index($pdo) {
        
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $_SESSION['nomPage'] = "entreprise";
        return $view;
    }

    public function recherche($pdo) {
        // ajout SQL
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $_SESSION['nomPage'] = "entreprise";
        return $view;
    }


}


