<?php
namespace controllers;

use yasmf\View;
use PDO;

class PlanningController {


    public function __construct() {
    }

    public function index($pdo) {
        
        $view = new View("SaeWeb/EurekaYASM/views/planning");
        $_SESSION['nomPage'] = "planning";
        return $view;
    }


}


