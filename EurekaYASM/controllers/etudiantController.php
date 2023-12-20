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
        $view = new View("SaeWeb/EurekaYASM/views/etudiant");
        $filieres = $this->getFilieres($pdo);
        $etudiants = $this->callLogique($pdo, null);
        $view->setVar('filieres',$filieres);
        $_SESSION['nomPage'] = "Etudiant";
        $view->setVar('etudiants',$etudiants);
        return $view;
    }

    /**
     * 
     * 
     */
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

    /**
     * 
     * 
     */
    private function getFilieres($pdo) {
        $filiere = $this->filiereService->getFilieres($pdo);
        if(!isset($_SESSION['filiere'])) {
            $_SESSION['filiere'] = 'Toutes';
        }
        return $filiere;
    }

    /**
     * 
     * 
     */
    private function callLogique($pdo, $saisies) {
        if ($saisies != null) {
            return $this->EtudiantService->rechercheEntreprise($pdo,$saisies,$_SESSION['filiere']);
        } else {
            return $this->EtudiantService->getStudentWithSouhaits($pdo);
        }
    }
}


