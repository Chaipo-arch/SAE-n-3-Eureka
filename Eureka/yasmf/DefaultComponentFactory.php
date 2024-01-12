<?php
namespace yasmf;

use services\UserService;
use services\EntrepriseService;
use services\FiliereService;
use services\EtudiantService;
use services\AdminService;
use services\ForumService;
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
            return new $qualified_name(new UserService, new ForumService);
        } else if($controller_name == "Entreprise"){
            return new $qualified_name(new EntrepriseService,new FiliereService, new UserService);
        } else if($controller_name == "Etudiant" && isset($_SESSION['role']) && $_SESSION['role'] != "Etudiant"){
            return new $qualified_name(new EtudiantService, new FiliereService, new EntrepriseService);
        }else if($controller_name == "Souhait" && isset($_SESSION['role']) && $_SESSION['role'] == "Etudiant"){
            return new $qualified_name(new EntrepriseService, new UserService);
        }else if($controller_name == "Admin" && isset($_SESSION['role']) && $_SESSION['role'] != "Etudiant"){
            return new $qualified_name(new AdminService, new ForumService);
        } else if($controller_name == "Planning" ){
            return new $qualified_name();
        } else {
            header('Location: index.php?action=pageNTrouve&pageNTrouve='.$controller_name);
            exit();
        }
    }
    
}