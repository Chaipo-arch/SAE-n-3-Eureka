<?php
namespace controllers;

use yasmf\View;
use PDO;
use services\EtudiantService;
use services\FiliereService;

class EtudiantController {


    public function __construct(EtudiantService $EtudiantService, FiliereService $filiereService) {
        $this->EtudiantService = $EtudiantService;
        $this->filiereService = $filiereService;
    }

    public function index($pdo) {
        
        if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire') {
            $filiere = $this->filiereService->getFilieres($pdo);
            $_SESSION['filiere'] = 'Toutes';
        } else {
            $filiere = $this->filiereService->getStudentFiliere($pdo,$_SESSION['IdUser']);
            $_SESSION['filiere'] = $filiere;
        }
        $entreprises = $this->entrepriseService->Entreprise($pdo,$_SESSION['filiere']);
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $_SESSION['nomPage'] = "entreprise";
        $view->setVar('filieres',$filiere);
        $view->setVar('entreprises',$entreprises);
        return $view;
    }

    public function recherche($pdo) {
        $saisies = HttpHelper::getParam('recherche');
        $entreprises = $this->entrepriseService->rechercheEntreprise($pdo,$saisies,$_SESSION['filiere']);
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $_SESSION['nomPage'] = "entreprise";
        $view->setVar('entreprises',$entreprises);
        return $view;
    }

    public function filiereChanger($pdo) {
        $_SESSION['filiere'] = HttpHelper::getParam('filiere');
        $entreprises = $this->entrepriseService->Entreprise($pdo,$_SESSION['filiere']);
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $view->setVar('entreprises',$entreprises);
        return $view;
    }


}


