<?php
namespace controllers;

use yasmf\View;
use PDO;

class HomeController {


    public function __construct() {
    }

    public function index($pdo) {
       
        $view = new View("SaeWeb/EurekaYASM/views/connexion");
        return $view;
    }

    public function tentativeConnexion($pdo) {
       var_dump($_POST);
       $connexionReussi = true; // stub
       // faire la connexion


       if($connexionReussi) {
        $view = new View("SaeWeb/EurekaYASM/views/forum");
        return $view;
       } 
        $view = new View("SaeWeb/EurekaYASM/views/connexion");
        return $view;
    }
    public function deconnexion($pdo) {
        session_start();
        session_destroy();
         $view = new View("SaeWeb/EurekaYASM/views/connexion");
         return $view;
     }

}


