<?php
namespace controllers;

use yasmf\HttpHelper;
use yasmf\View;
use PDO;
use services\EntrepriseService;
use services\FiliereService;
use services\UserService;

class EntrepriseController {


    public function __construct(EntrepriseService $entrepriseService, FiliereService $filiereService, UserService $userService) {
        $this->entrepriseService = $entrepriseService;
        $this->filiereService = $filiereService;
        $this->userService = $userService; 
    }

    /**
     * 
     * 
     */
    public function index($pdo) {
        
        $view = new View("SaeWeb/EurekaYASM/views/entreprise");
        $filieres = $this->getFilieres($pdo);
        $entreprises = $this->callLogique($pdo, null);
        $view->setVar('filieres',$filieres);
        $_SESSION['nomPage'] = "entreprise";
        $view->setVar('entreprises',$entreprises);
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
        if($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Gestionnaire') {
            $filiere = $this->filiereService->getFilieres($pdo);
            
            if(!isset($_SESSION['filiere'])) {
                $_SESSION['filiere'] = 'Toutes';
             }
        } else {
            $id = $_SESSION['IdUser'];
            $filiere = $this->filiereService->getStudentFiliere($pdo,$id);
            $_SESSION['filiere'] = $filiere;
        }
        return $filiere;
    }

    /**
     * 
     * 
     */
    private function callLogique($pdo, $saisies) {
        if ($saisies != null) {
            return $this->entrepriseService->rechercheEntreprise($pdo,$saisies,$_SESSION['filiere']);
        } else {
            return $this->entrepriseService->Entreprise($pdo,$_SESSION['filiere']);
        }
    }

    /**
     * 
     * 
     */
    public function setSouhaitEtudiant($pdo) {
        $idE = HttpHelper::getParam('idEntreprise');
        $this->entrepriseService->createSouhait($pdo, $idE, $_SESSION['IdUser']);
        $this->userService->getSouhait($pdo, $_SESSION['IdUser']);
        return $this->index($pdo);
    }

    public function deleteSouhaitEtudiant($pdo) {
        $idE = HttpHelper::getParam('idEntreprise');
        $this->entrepriseService->deleteSouhait($pdo, $idE, $_SESSION['IdUser']);
        $this->userService->getSouhait($pdo, $_SESSION['IdUser']);
        return $this->index($pdo);
    }


}


