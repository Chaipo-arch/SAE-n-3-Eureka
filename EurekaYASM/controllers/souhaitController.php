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
        if ($_SESSION['role'] == "Gestionnaire" | $_SESSION['role'] == "Admin" ) {
            //On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
            header('Location: ../index.php');
            exit();
        }
        $view = new View("SaeWeb/EurekaYASM/views/souhait");
        $_SESSION['nomPage'] = "Souhait";
        $entreprises = array();
        foreach($_SESSION['souhait'] as $souhait ) {
            $liste = $this->entrepriseService->getEntreprise($pdo,$souhait);
            $entreprises[] = $liste;
        }
        $view->setVar('entreprises',$entreprises);
        return $view;

    }
}
