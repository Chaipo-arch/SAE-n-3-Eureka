<?php
namespace controllers;

use yasmf\View;
use PDO;

class EtudiantController {


    public function __construct() {
    }

    public function index($pdo) {
        
        $view = new View("SaeWeb/EurekaYASM/views/etudiant");
        $_SESSION['nomPage'] = "etudiant";
        return $view;
    }


}


