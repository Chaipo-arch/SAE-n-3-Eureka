<?php
namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;
use PDO;
use services\EntrepriseService;
use services\FiliereService;

class EntrepriseController {


    public function __construct(EntrepriseService $entrepriseService, FiliereService $filiereService) {
        $this->entrepriseService = $entrepriseService;
        $this->filiereService = $filiereService;
    }

    public function index($pdo) {
        
        if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire') {
            //$filieres = $this->filiereService->getFilieres($pdo);
            $_SESSION['filiere'] = 'Toutes';
        } else {
            //$filiere = $this->filiereService->getStudentFiliere($pdo,$_SESSION['IdUser']);
            $_SESSION['filiere'] = $filiere;
        }
        $entreprises = $this->entrepriseService->toutesLesEntreprise($pdo);
        $view = new View("Sae/EurekaYASM/views/entreprise");
        $_SESSION['nomPage'] = "entreprise";
        //$view->setVar('filieres',$filiere);
        $view->setVar('entreprises',$entreprises);
        return $view;
    }

    public function recherche($pdo) {
        $saisies = HttpHelper::getParam('recherche');
        $entreprises = $this->entrepriseService->rechercheEntreprise($pdo,$saisies,$_SESSION['filiere']);
        $view = new View("Sae/EurekaYASM/views/entreprise");
        $_SESSION['nomPage'] = "entreprise";
        $view->setVar('entreprises',$entreprises);
        return $view;
    }

    public function filiereChanger($pdo) {
        $_SESSION['filiere'] = HttpHelper::getParam('filiere');
        $entreprises = $this->entrepriseService->Entreprise($pdo,$_SESSION['filiere']);
        $view = new View("Sae/EurekaYASM/views/entreprise");
        $view->setVar('entreprises',$entreprises);
        return $view;
    }



}


