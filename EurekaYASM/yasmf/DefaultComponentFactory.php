<?php
namespace yasmf;

use services\UserService;
use services\EntrepriseService;
use services\FiliereService;
use services\EtudiantService;
use services\AdminService;
use controllers;

class DefaultComponentFactory
{
    function __construct() {
    }

    /**
     * @param string $controller_name the name of the controller to instanciate
     * @return mixed the controller
     * @throws Exception when controller is not found
     */
    public function buildControllerByName(string $controller_name, string $qualified_name ) {
        if($controller_name == "Home") {
            return new $qualified_name(new UserService);
        } else if($controller_name == "Entreprise"){
            return new $qualified_name(new EntrepriseService,new FiliereService, new UserService);
        } else if($controller_name == "Etudiant" && isset($_SESSION['role']) && $_SESSION['role'] != "Etudiant"){
            return new $qualified_name(new EtudiantService, new FiliereService);
        }else if($controller_name == "Souhait" && isset($_SESSION['role']) && $_SESSION['role'] == "Etudiant"){
            return new $qualified_name(new EntrepriseService, new UserService);
        }else if($controller_name == "Admin" && isset($_SESSION['role']) && $_SESSION['role'] != "Etudiant"){
            return new $qualified_name(new AdminService);
        } else {
            header('Location: index.php');
            exit();;
        }
    }
    
}