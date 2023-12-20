<?php
namespace yasmf;

use services\UserService;
use services\EntrepriseService;
use services\FiliereService;
use services\EtudiantService;
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
        } else if($controller_name == "Etudiant"){
            return new $qualified_name(new EtudiantService, new FiliereService);
        }else if($controller_name == "Souhait"){
            return new $qualified_name(new EntrepriseService, new UserService);
        }else {
            return new $qualified_name();
        }
    }
}