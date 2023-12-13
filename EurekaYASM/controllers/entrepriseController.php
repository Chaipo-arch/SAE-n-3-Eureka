<?php
namespace controllers;

use yasmf\View;
use PDO;

class EntrepriseController {


    public function __construct() {
    }

    public function index($pdo) {
        
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        return $view;
    }

    public function recherche($pdo) {
        // ajout SQL
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        return $view;
    }


}


