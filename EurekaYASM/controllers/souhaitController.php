<?php
namespace controllers;

use yasmf\View;
use PDO;
use services\EntrepriseService;
use services\UserService;

class SouhaitController {


    public function __construct(EntrepriseService $entrepriseService, UserService $userService) {
        $this->entrepriseService = $entrepriseService;
        $this->userService = $userService;
    }

    public function index($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/souhait");
        $_SESSION['nomPage'] = "souhait";
        $entreprises = array();
        foreach($_SESSION['souhait'] as $souhait ) {
            $liste = $this->entrepriseService->getEntreprise($pdo,$souhait);
            $entreprises[] = $liste;
        }
        $view->setVar('entreprises',$entreprises);
        return $view;

    }
}
