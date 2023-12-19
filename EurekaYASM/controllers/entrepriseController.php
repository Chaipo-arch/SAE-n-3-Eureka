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
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $filieres = $this->getFilieres($pdo);
        
        $entreprises = $this->callLogique($pdo, null);
        $view->setVar('filieres',$filieres);
        $_SESSION['nomPage'] = "entreprise";
        $view->setVar('entreprises',$entreprises);
        return $view;
    }

    public function recherche($pdo) {
        $_SESSION['filiere'] = HttpHelper::getParam('filiere');
        $saisies = HttpHelper::getParam('recherche');
        $filieres = $this->getFilieres($pdo);
        $entreprises = $this->callLogique($pdo, $saisies);
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $_SESSION['nomPage'] = "entreprise";
        $view->setVar('filieres',$filieres);
        $view->setVar('entreprises',$entreprises);
        return $view;
    }

    private function getFilieres($pdo) {
        if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire') {
            $filiere = $this->filiereService->getFilieres($pdo);
            
            if(!isset($_SESSION['filiere'])) {
                $_SESSION['filiere'] = 'Toutes';
             }
        } else {
            $filiere = $this->filiereService->getStudentFiliere($pdo,$_SESSION['IdUser']);
            if(!isset($_SESSION['filiere'])) {
                $_SESSION['filiere'] = $filiere;
            }
            
        }
        return $filiere;
    }

    private function callLogique($pdo, $saisies) {
        if ($saisies != null) {
            return $this->entrepriseService->rechercheEntreprise($pdo,$saisies,$_SESSION['filiere']);
        } else {
            return $this->entrepriseService->Entreprise($pdo,$_SESSION['filiere']);
        }
    }


}


