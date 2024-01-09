<?php
namespace controllers;

use yasmf\View;
use PDO;
use yasmf\HttpHelper;
use services\EtudiantService;
use services\FiliereService;
use services\EntrepriseService;

class EtudiantController {


    public function __construct(EtudiantService $EtudiantService, FiliereService $filiereService, EntrepriseService $entrepriseService) {
        $this->EtudiantService = $EtudiantService;
        $this->filiereService = $filiereService;
        $this->entrepriseService = $entrepriseService;
    }

    public function index($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/etudiant");
        $filieres = $this->getFilieres($pdo);
        $etudiants = $this->callLogique($pdo, null);
        var_dump($etudiants);
        /*$etudiantWithSouhaits = array();
        foreach($etudiants as $etudiant) {
            $etudiant['souhait'] = $this->EtudiantService->getSouhaitE($pdo,$etudiant['id']);
            $etudiantWithSouhaits[] = $etudiant;
            var_dump($etudiant);
        }
        $etudiants = $etudiantWithSouhaits;*/
        var_dump($etudiants);
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
        $etudiants = $this->callLogique($pdo, $saisies);
        $view = new View("SaeWeb/EurekaYASM/views/etudiant");
        $_SESSION['nomPage'] = "Etudiant";
        $view->setVar('filieres',$filieres);
        $view->setVar('etudiants',$etudiants);
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
            return $this->EtudiantService->rechercheEtudiant($pdo,$saisies,$_SESSION['filiere']);
        } else {
            return $this->EtudiantService->getStudentWithSouhaits($pdo);
        }
    }

    public function afficherSouhait($pdo) {
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $idUser = HttpHelper::getParam('idUserS');
        var_dump($_POST);
        $souhaits = $this->EtudiantService->getSouhaitE($pdo,$idUser);
        $entreprises = array();
        foreach($souhaits as $souhait) {
             $entreprises[] = $this->entrepriseService->getEntreprise($pdo,$souhait);
        }
        $view->setVar('entreprises',$entreprises);
        return $view;
    }
    
}


